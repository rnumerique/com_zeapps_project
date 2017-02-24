<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Card extends ZeCtrl
{
    public function form(){
        $this->load->view('card/form');
    }

    public function modal(){
        $this->load->view('card/modal');
    }

    public function modal_detail(){
        $this->load->view('card/modal_detail');
    }



    public function get_cards($id = 0){
        $this->load->model("Zeapps_project_cards", "cards");
        $this->load->model("Zeapps_project_card_comments", "comments");
        $this->load->model("Zeapps_project_card_documents", "documents");
        $this->load->model("Zeapps_users", "user");

        if($id)
            $where = array('zeapps_project_cards.id_project' => $id);
        else
            $where = array();

        if($user = $this->user->getUserByToken($this->session->get('token'))){
            $where['zeapps_project_rights.id_user'] = $user[0]->id;
        }

        if($cards = $this->cards->order_by('sort')->all($where)) {
            foreach ($cards as $card){
                $card->comments = $this->comments->all(array('id_card' => $card->id));
                if(!$card->comments)
                    $card->comments = [];
                $card->documents = $this->documents->all(array('id_card' => $card->id));
                if(!$card->documents)
                    $card->documents = [];
            }
        }

        echo json_encode($cards);
    }

    public function get_card($id){
        $this->load->model("Zeapps_project_cards", "cards");
        $this->load->model("Zeapps_project_card_comments", "comments");
        $this->load->model("Zeapps_project_card_documents", "documents");

        if($card = $this->cards->get($id)) {
            $card->comments = $this->comments->all(array('id_card' => $card->id));
            if(!$card->comments)
                $card->comments = [];
            $card->documents = $this->documents->all(array('id_card' => $card->id));
            if(!$card->documents)
                $card->documents = [];
        }

        echo json_encode($card);
    }

    public function save_card(){
        $this->load->model("Zeapps_project_cards", "cards");

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        if(isset($data['id'])){
            $id = $this->cards->update($data, array('id'=>$data['id']));
        }
        else{
            $id = $this->cards->insert($data);
        }

        echo json_encode($id);
    }

    public function validate_idea($id){
        $this->load->model("Zeapps_project_cards", "cards");

        $this->cards->update(array('step' => 1), $id);

        echo json_encode('OK');
    }

    public function complete_card($id = null, $deadline = 'false'){

        if($id){
            if($deadline == 'true'){
                $this->load->model("Zeapps_project_deadlines", "deadlines");

                $this->deadlines->update(array('completed' => 'Y'), $id);
            }
            else{
                $this->load->model("Zeapps_project_cards", "cards");

                $this->cards->update(array('completed' => 'Y'), $id);
            }
        }

        echo json_encode('OK');
    }

    public function delete_card($id = null, $deadline = 'false'){

        if($id){
            if($deadline == 'true'){
                $this->load->model("Zeapps_project_deadlines", "deadlines");

                $this->deadlines->delete(array('id' => $id));
            }
            else{
                $this->load->model("Zeapps_project_cards", "cards");

                $this->cards->delete(array('id' => $id));
            }
        }

        echo json_encode('OK');
    }

    public function comment(){
        $this->load->model("Zeapps_project_card_comments", "comments");

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        $id = $this->comments->insert($data);

        $comment = $this->comments->get($id);

        echo json_encode($comment);
    }

    public function uploadDocuments($id_card = null){
        if($id_card) {
            $this->load->model("Zeapps_project_card_documents", "documents");

            $data = [];
            $res = [];

            $data['id_card'] = $id_card;

            $files = $_FILES['files'];

            $path = '/assets/upload/project/cards/';

            $time = time();

            $year = date('Y', $time);
            $month = date('m', $time);
            $day = date('d', $time);
            $hour = date('H', $time);

            $data['created_at'] = $year . '-' . $month . '-' . $day;

            $path .= $year . '/' . $month . '/' . $day . '/' . $hour . '/';

            recursive_mkdir(FCPATH . $path);

            for ($i = 0; $i < sizeof($files['name']); $i++) {
                $arr = explode(".", $files["name"][$i]);
                $extension = end($arr);

                $data['name'] = implode('.', array_slice($arr, 0, -1)); // entire name except the extension

                $data['path'] = $path . ltrim(str_replace(' ', '', microtime()), '0.') . "." . $extension;

                move_uploaded_file($files["tmp_name"][$i], FCPATH . $data['path']);

                $data['id'] = $this->documents->insert($data);

                $data['date'] = date('Y-m-d');

                array_push($res, $data);

                unset($data['id']);
            }

            echo json_encode($res);
        }
        else {
            echo json_encode('false');
        }
    }
}
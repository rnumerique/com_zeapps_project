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

    public function modal_select(){
        $this->load->view('card/modal_select');
    }

    public function modal_detail(){
        $this->load->view('card/modal_detail');
    }



    public function get_cards($id = 0){
        $this->load->model("Zeapps_project_cards", "cards");
        $this->load->model("Zeapps_project_card_comments", "comments");
        $this->load->model("Zeapps_project_card_documents", "documents");

        if($id)
            $where = array('zeapps_project_cards.id_project' => $id);
        else
            $where = array();

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
        $this->load->model("Zeapps_project_rights", "rights");
        $this->load->model("Zeapps_project_categories", "categories");
        $this->load->model("Zeapps_project_card_comments", "comments");
        $this->load->model("Zeapps_project_card_documents", "documents");
        $this->load->model("Zeapps_project_card_priorities", "priorities");
        $this->load->model("Zeapps_project_timers", "timers");

        if($card = $this->cards->get($id)) {
            $card = $card[0];

            if(!$project_users = $this->rights->all(array('id_project' => $card->id_project))){
                $project_users = [];
            }

            if(!$categories = $this->categories->all(array('id_project' => $card->id_project))){
                $categories = [];
            }

            $card->time_spent = $this->timers->getTimeOfCard($card->id);
        }
        else{
            $project_users = [];
        }

        if(!$comments = $this->comments->all(array('id_card' => $id)))
            $comments = [];
        if(!$documents = $this->documents->all(array('id_card' => $id)))
            $documents = [];
        if(!$timers = $this->timers->all(array('id_card' => $id)))
            $timers = [];

        if(!$priorities = $this->priorities->all()){
            $priorities = [];
        }

        echo json_encode(array(
            'card' => $card,
            'priorities' => $priorities,
            'project_users' => $project_users,
            'categories' => $categories,
            'comments' => $comments,
            'documents' => $documents,
            'timers' => $timers,
        ));
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
        $this->load->model("Zeapps_project_cards", "cards");

        if($id){
            $this->cards->update(array('completed' => 'Y'), $id);
        }

        echo json_encode('OK');
    }

    public function delete_card($id = null){

        if($id){
            $this->load->model("Zeapps_project_cards", "cards");

            $this->cards->delete(array('id' => $id));
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

        if($data['id']){
            $this->comments->update($data, $data['id']);
            $id = $data['id'];
        }
        else{
            $id = $this->comments->insert($data);
        }

        $comment = $this->comments->get($id);

        echo json_encode($comment);
    }

    public function del_comment($id){
        $this->load->model("Zeapps_project_card_comments", "comments");

        echo json_encode($this->comments->delete($id));
    }

    public function uploadDocuments($id_card = null){
        if($id_card) {
            $this->load->model("Zeapps_project_card_documents", "documents");

            $data = $_POST;
            $files = $_FILES['files'];
            if($files) {
                if($data['path']){
                    unlink($data['path']);
                }

                $data['id_card'] = $id_card;

                $path = '/assets/upload/project/cards/';

                $time = time();

                $year = date('Y', $time);
                $month = date('m', $time);
                $day = date('d', $time);
                $hour = date('H', $time);

                $data['created_at'] = $year . '-' . $month . '-' . $day;

                $path .= $year . '/' . $month . '/' . $day . '/' . $hour . '/';

                recursive_mkdir(FCPATH . $path);

                $arr = explode(".", $files["name"][0]);
                $extension = end($arr);

                $data['path'] = $path . ltrim(str_replace(' ', '', microtime()), '0.') . "." . $extension;

                move_uploaded_file($files["tmp_name"][0], FCPATH . $data['path']);

                if ($data['id']) {
                    $this->documents->update($data, $data['id']);
                } else {
                    $data['id'] = $this->documents->insert($data);
                }
                $data['date'] = date('Y-m-d H:i:s');

                echo json_encode($data);
            }
            else{
                echo json_encode(false);
            }
        }
        else {
            echo json_encode(false);
        }
    }

    public function del_document($id){
        $this->load->model("Zeapps_project_card_documents", "documents");

        if($document = $this->documents->get($id)){
            unlink($document->path);

            $this->documents->delete($id);
        }

        echo 'OK';
    }
}
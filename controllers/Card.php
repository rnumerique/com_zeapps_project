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



    public function get_cards($id = 0){
        $this->load->model("zeapps_project_cards", "cards");

        if($id)
            $where = array('zeapps_project_cards.id_project' => $id);
        else
            $where = '';

        $cards = $this->cards->get_all($where);

        echo json_encode($cards);
    }

    public function get_card($id){
        $this->load->model("zeapps_project_cards", "cards");

        $cards = $this->cards->get($id);

        echo json_encode($cards);
    }

    public function save_card(){
        $this->load->model("zeapps_project_cards", "cards");

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        if(isset($data['id'])){
            $id = $this->cards->update($data, $data['id']);
        }
        else{
            $id = $this->cards->insert($data);
        }

        echo json_encode($id);
    }

    public function validate_idea($id){
        $this->load->model("zeapps_project_cards", "cards");

        $this->cards->update(array('step' => 1), $id);

        echo json_encode('OK');
    }

    public function complete_card($id = null, $deadline = 'false'){

        if($id){
            if($deadline == 'true'){
                $this->load->model("zeapps_project_deadlines", "deadlines");

                $this->deadlines->update(array('completed' => 'Y'), $id);
            }
            else{
                $this->load->model("zeapps_project_cards", "cards");

                $this->cards->update(array('completed' => 'Y'), $id);
            }
        }

        echo json_encode('OK');
    }

    public function delete_card($id = null, $deadline = 'false'){

        if($id){
            if($deadline == 'true'){
                $this->load->model("zeapps_project_deadlines", "deadlines");

                $this->deadlines->delete(array('id' => $id));
            }
            else{
                $this->load->model("zeapps_project_cards", "cards");

                $this->cards->delete(array('id' => $id));
            }
        }

        echo json_encode('OK');
    }

}
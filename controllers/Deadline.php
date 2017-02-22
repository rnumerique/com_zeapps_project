<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deadline extends ZeCtrl
{


    public function get_deadlines($id = 0){
        $this->load->model("Zeapps_project_deadlines", "deadlines");
        $this->load->model("Zeapps_users", "user");

        if($id)
            $where = array('id_project' => $id);
        else
            $where = array();

        if($user = $this->user->getUserByToken($this->session->get('token'))){
            $where['zeapps_project_rights.id_user'] = $user[0]->id;
        }

        $deadlines = $this->deadlines->all($where);

        echo json_encode($deadlines);
    }

    public function get_deadline($id){
        $this->load->model("Zeapps_project_deadlines", "deadlines");

        $deadlines = $this->deadlines->get($id);

        echo json_encode($deadlines);
    }

    public function save_deadline(){
        $this->load->model("Zeapps_project_deadlines", "deadlines");

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        if(isset($data['id'])){
            $id = $this->deadlines->update($data, $data['id']);
        }
        else{
            $id = $this->deadlines->insert($data);
        }

        echo json_encode($id);
    }
}
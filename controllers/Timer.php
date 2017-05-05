<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Timer extends ZeCtrl
{
    public function hook(){
        $this->load->view('timer/hook');
    }
    public function directive(){
        $this->load->view('timer/directive');
    }
    public function modal(){
        $this->load->view('timer/modal');
    }
    public function form(){
        $this->load->view('timer/form');
    }

    public function get($id){
        $this->load->model("Zeapps_project_timers", "timer");

        $timer = $this->timer->get($id);

        echo json_encode($timer);
    }

    public function get_ongoing(){
        $this->load->model("Zeapps_users", "user");
        $this->load->model("Zeapps_project_timers", "timer");

        if($user = $this->user->getUserByToken($this->session->get('token'))){
            $timer = $this->timer->all(array('id_user' => $user[0]->id, 'stop_time' => null));
        }
        else{
            $timer = false;
        }

        if($timer && sizeof($timer) > 0) {
            echo json_encode($timer[0]);
        }
        else{
            echo json_encode(false);
        }
    }

    public function save(){
        $this->load->model("Zeapps_project_timers", "timer");

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        if(isset($data['id'])){
            $id = $this->timer->update($data, array('id'=>$data['id']));
        }
        else{
            $id = $this->timer->insert($data);
        }

        echo json_encode($id);
    }

    public function delete($id){
        $this->load->model("Zeapps_project_timers", "timer");

        if($this->timer->delete($id))
            echo json_encode(true);
        else
            echo json_encode(false);
    }
}
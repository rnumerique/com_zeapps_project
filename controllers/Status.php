<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Status extends ZeCtrl
{
    public function config(){
        $this->load->view('status/config');
    }


    public function get_all(){
        $this->load->model("Zeapps_project_statuses", "statuses");

        $statuses = $this->statuses->all();

        echo json_encode($statuses);
    }

    public function save() {
        $this->load->model("Zeapps_project_statuses", "statuses");

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        if (isset($data["id"])) {
            $id = $data["id"];
            $this->statuses->update($data, $data["id"]);
        } else {
            $id = $this->statuses->insert($data);
        }

        echo $id;
    }

    public function save_all(){
        $this->load->model("Zeapps_project_statuses", "statuses");

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        if($data && is_array($data)){
            foreach($data as $status){
                $this->statuses->update($status, $status['id']);
            }
            echo json_encode('OK');
        }
        else{
            echo json_encode('false');
        }
    }

    public function delete($id){
        $this->load->model("Zeapps_project_statuses", "statuses");

        $this->statuses->delete($id);

        echo json_encode('OK');
    }
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_quotes extends ZeCtrl
{
    public function save(){
        $this->load->model("Zeapps_project_quotes", "quotes");

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        if(isset($data['id'])){
            $this->quotes->update($data, $data['id']);
            $id = $data['id'];
        }
        else{
            $id = $this->quotes->insert($data);
        }

        echo json_encode($id);
    }

    public function delete($id){
        $this->load->model("Zeapps_project_quotes", "quotes");

        echo $this->quotes->delete($id);
    }
}
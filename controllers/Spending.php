<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Spending extends ZeCtrl
{
    public function modal(){
        $this->load->view('spendings/modal');
    }

    public function save(){
        $this->load->model("Zeapps_project_spendings", "spendings");

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        if(isset($data['id'])){
            $this->spendings->update($data, $data['id']);
            $id = $data['id'];
        }
        else{
            $id = $this->spendings->insert($data);
        }

        echo json_encode($id);
    }

    public function delete($id = null){
        $this->load->model("Zeapps_project_spendings", "spendings");

        if($id){
            echo $this->spendings->delete(array('id' => $id));
        }
        else {
            echo false;
        }
    }
}
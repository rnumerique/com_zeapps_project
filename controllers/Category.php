<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends ZeCtrl
{
    public function form(){
        $this->load->view('category/form');
    }


    public function get_categories($id_project = 0){
        $this->load->model("zeapps_project_categories", "categories");

        $where = array('id_project' => $id_project);

        $categories = $this->categories->all($where);

        echo json_encode($categories);
    }

    public function get_category($id){
        $this->load->model("zeapps_project_categories", "categories");

        $category = $this->categories->get($id);

        echo json_encode($category);
    }

    public function save_category(){
        $this->load->model("zeapps_project_categories", "categories");

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        if(isset($data['id'])){
            $id = $this->categories->update($data, $data['id']);
        }
        else{
            var_dump($data);
            $id = $this->categories->insert($data);
        }

        echo json_encode($id);
    }

    public function delete_category($id = null){
        $this->load->model("zeapps_project_categories", "categories");
        $this->load->model("zeapps_project_cards", "cards");
        $this->load->model("zeapps_project_deadlines", "deadlines");

        if($id){
            $this->categories->delete(array('id' => $id));

            $this->cards->update(array('id_category' => 0), array('id_category' => $id));
            $this->deadlines->update(array('id_category' => 0), array('id_category' => $id));
        }

        echo json_encode('OK');
    }
}
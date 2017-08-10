<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Todos extends ZeCtrl
{
    public function view(){
        $this->load->view('todos/view');
    }



    public function all(){
        $this->load->model("Zeapps_project_todos", "todos");
        $this->load->model("Zeapps_project_todo_categories", "categories");

        if(!$categories = $this->categories->order_by('sort')->all()){
            $categories = [];
        }

        if($categories[0]){
            $id_cat = $categories[0]->id;
        }
        else{
            $id_cat = 0;
        }

        if(!$todos = $this->todos->order_by('sort')->all(array('done' => 0, 'id_category' => $id_cat))){
            $todos = [];
        }

        if(!$history = $this->todos->order_by('updated_at', 'DESC')->limit(5)->all(array('done' => 1, 'id_category' => $id_cat))){
            $history = [];
        }

        echo json_encode(array(
            'todos' => $todos,
            'history' => $history,
            'categories' => $categories
        ));
    }

    public function get_todos($id){
        $this->load->model("Zeapps_project_todos", "todos");

        if(!$todos = $this->todos->order_by('sort')->all(array('done' => 0, 'id_category' => $id))){
            $todos = [];
        }

        if(!$history = $this->todos->order_by('updated_at', 'DESC')->limit(5)->all(array('done' => 1, 'id_category' => $id))){
            $history = [];
        }

        echo json_encode(array(
            'todos' => $todos,
            'history' => $history
        ));
    }

    public function save(){
        $this->load->model("Zeapps_project_todos", "todos");

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        if(isset($data['id'])){
            $this->todos->update($data, $data['id']);
            $id = $data['id'];
        }
        else{
            $id = $this->todos->insert($data);
        }

        echo json_encode($id);
    }

    public function save_category(){
        $this->load->model("Zeapps_project_todo_categories", "categories");

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        if(isset($data['id'])){
            $this->categories->update($data, $data['id']);
            $id = $data['id'];
        }
        else{
            $id = $this->categories->insert($data);
        }

        echo json_encode($id);
    }

    public function delete_category($id){
        $this->load->model("Zeapps_project_todo_categories", "categories");
        $this->load->model("Zeapps_project_todos", "todos");

        $this->todos->delete(array('id_category' => $id));

        echo $this->categories->delete($id);
    }

    public function validate($id){
        $this->load->model("Zeapps_project_todos", "todos");

        $ret = $this->todos->update(array('done' => 1), $id);

        echo $ret;
    }

    public function cancel($id){
        $this->load->model("Zeapps_project_todos", "todos");

        $ret = $this->todos->update(array('done' => 0), $id);

        echo $ret;
    }

    public function delete($id){
        $this->load->model("Zeapps_project_todos", "todos");

        echo $this->todos->delete($id);
    }

    public function todos_position(){
        $this->load->model("Zeapps_project_todos", "todos");

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        foreach($data as $todo){
            $this->todos->update(array('sort' => $todo['sort']), $todo['id']);
        }

        echo 'OK';
    }

    public function categories_position(){
        $this->load->model("Zeapps_project_todo_categories", "categories");

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        foreach($data as $category){
            $this->categories->update(array('sort' => $category['sort']), $category['id']);
        }

        echo 'OK';
    }
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends ZeCtrl
{
    public function index(){
        $this->load->view('project/view');
    }

    public function categories(){
        $this->load->view('project/categories');
    }

    public function form(){
        $this->load->view('project/form');
    }

    public function form_card(){
        $this->load->view('project/formCard');
    }

    public function modal_project(){
        $this->load->view('project/modalProject');
    }











    public function get_filters(){
        $this->load->model("zeapps_project_cards", "cards");
        $this->load->model("zeapps_project_deadlines", "deadlines");

        $dates_tmp = $this->cards->get_dates();
        $dates_merged = array_merge($dates_tmp, $this->deadlines->get_dates());
        $dates = [];

        foreach ($dates_merged as $date) {
            if ( ! in_array($date, $dates)) {
                $dates[] = $date;
            }
        }

        echo json_encode($dates);
    }

    public function get_project($id = 0){
        $this->load->model("zeapps_projects", "projects");

        $project = $this->projects->get($id);

        echo json_encode($project);
    }

    public function get_projects($id_parent = 0, $spaces = 'false', $filter = 'false'){
        $this->load->model("zeapps_projects", "projects");

        if($id_parent)
            $where = array('id_parent' => $id_parent);
        else
            $where = '';

        $projects = $this->projects->get_all($where, $spaces, $filter);

        echo json_encode($projects);
    }

    public function get_childs($id){
        $this->load->model("zeapps_projects", "projects");

        $childs = $this->_getChildsOf($id);

        echo json_encode($childs);
    }

    public function get_projects_tree(){
        $this->load->model("zeapps_projects", "projects");

        $projects = $this->projects->get_all();

        if ($projects == false) {
            echo json_encode(array());
        } else {
            $result = $this->_build_tree($projects);
            echo json_encode($result);
        }
    }

    public function save_project(){
        $this->load->model("zeapps_projects", "projects");

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        if(isset($data['id'])){
            $this->projects->update($data, $data['id']);
            $id = $data['id'];
        }
        else{
            $id = $this->projects->insert($data);
        }

        echo $id;
    }

    public function delete_project($id = null, $force = 'false'){
        $this->load->model("zeapps_projects", "projects");
        $this->load->model("zeapps_project_deadlines", "deadlines");
        $this->load->model("zeapps_project_cards", "cards");

        if($force == 'false'){
            if($this->projects->get_all(array('id_parent' => $id)) || $this->deadlines->get_all(array('id_project' => $id)) || $this->cards->get_all(array('id_project' => $id))){
                echo json_encode(array('hasDependencies' => true));
                return;
            }
        }

        $this->projects->delete($id);

        echo json_encode('OK');
    }

    private function _build_tree($categories, $id = 0){
        $result = array();

        foreach($categories as $category){
            if($category->id_parent == $id){

                $tmp = $category;
                $res = $this->_build_tree($categories, $category->id);
                if(!empty($res)) {
                    $tmp->branches = $res;
                }
                $tmp->open = false;
                $result[] = $tmp;
            }
        }

        return $result;
    }

    private function _getChildsOf($id){

        $arr = [];

        if($childs = $this->projects->get_all(array('id_parent' => $id))){
            foreach($childs as $child){
                $arr[] = $child;
                $ret = $this->_getChildsOf($child->id);
                $arr = array_merge($arr, $ret);
            }
        }

        return $arr;

    }
}

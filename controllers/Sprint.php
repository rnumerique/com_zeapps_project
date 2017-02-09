<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sprint extends ZeCtrl
{
    public function view()
    {
        $this->load->view('sprint/view');
    }

    public function detail(){
        $this->load->view('sprint/detail');
    }

    public function form(){
        $this->load->view('sprint/form');
    }

    public function formCard(){
        $this->load->view('sprint/formCard');
    }

    public function modal_sprint(){
        $this->load->view('sprint/modalSprint');
    }



    public function get_sprint($id){
        $this->load->model("Zeapps_project_sprints", "sprints");

        $sprint = $this->sprints->get($id);

        echo json_encode($sprint);
    }

    public function get_sprints($id_project = null){
        $this->load->model("Zeapps_project_sprints", "sprints");

        if($id_project)
            $where = array('id_project' => $id_project);
        else
            $where = array();

        $sprints = $this->sprints->order_by('completed', 'ASC')->order_by('active', 'ASC')->all($where);

        echo json_encode($sprints);
    }

    public function save_sprint(){
        $this->load->model("Zeapps_project_sprints", "sprints");

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        if(isset($data['id'])){
            $this->sprints->update($data, $data['id']);
            $id = $data['id'];
        }
        else{
            $id = $this->sprints->insert($data);
        }

        echo $id;
    }

    public function delete_sprint($id = null){
        $this->load->model("Zeapps_project_sprints", "sprints");
        $this->load->model("Zeapps_project_cards", "cards");

        $this->sprints->delete($id);

        $this->cards->update(array('step' => 1, 'id_sprint' => 0), array('id_sprint' => $id));

        echo json_encode('OK');
    }

    public function updateCardsOf($id){
        $this->load->model("Zeapps_project_cards", "cards");

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        $this->cards->updateStateOf($data, $id);

        echo json_encode('OK');
    }



    public function get_filters(){
        $this->load->model("Zeapps_projects", "projects");
        $this->load->model("Zeapps_project_cards", "cards");
        $this->load->model("Zeapps_project_deadlines", "deadlines");

        $companies = $this->projects->get_companies();
        $managers = $this->projects->get_managers();

        $dates_tmp = $this->cards->get_dates();
        $dates_merged = array_merge($dates_tmp, $this->deadlines->get_dates());
        $dates = [];

        foreach ($dates_merged as $date) {
            if ( ! in_array($date, $dates)) {
                $dates[] = $date;
            }
        }

        $assigned = $this->cards->get_assigned();

        echo json_encode(array('companies' => $companies, 'managers' => $managers, 'dates' => $dates, 'assigned' => $assigned));
    }
}
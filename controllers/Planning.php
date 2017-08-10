<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Planning extends ZeCtrl
{
    public function index(){
        $this->load->view('planning/view');
    }
    public function table(){
        $this->load->view('planning/table');
    }



    public function get_context(){
        $this->load->model("Zeapps_projects", "projects");
        $this->load->model("Zeapps_project_cards", "cards");
        $this->load->model("Zeapps_project_deadlines", "deadlines");

        $companies = $this->projects->get_companies();
        $managers = $this->projects->get_managers();
        $assigned = $this->cards->get_assigned();

        $projects = $this->projects->all();

        $cards = $this->cards->all(array(), true);

        $deadlines = $this->deadlines->all();

        $filters = array('companies' => $companies, 'managers' => $managers, 'assigned' => $assigned);

        echo json_encode(array('projects' => $projects, 'filters' => $filters, 'cards' => $cards, 'deadlines' => $deadlines));
    }

    public function get_filters(){
    }
}

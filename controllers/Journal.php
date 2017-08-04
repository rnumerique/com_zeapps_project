<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Journal extends ZeCtrl
{
    public function view(){
        $this->load->view('journal/view');
    }

    public function get_journal(){
        $this->load->model("Zeapps_projects", "projects");
        $this->load->model("Zeapps_project_cards", "cards");
        $this->load->model("Zeapps_project_timers", "timers");

        $companies = $this->projects->get_companies();
        $managers = $this->projects->get_managers();
        $assigned = $this->cards->get_assigned();

        $where = array();

        $projects = $this->projects->all($where);
        $logs = $this->timers->get_logs();

        $filters = array('projects' => $projects, 'companies' => $companies, 'managers' => $managers, 'assigned' => $assigned);

        echo json_encode(array('filters' => $filters, 'logs' => $logs));
    }
}
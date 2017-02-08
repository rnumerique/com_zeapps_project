<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sandbox extends ZeCtrl
{
    public function index()
    {
        $this->load->view('sandbox/view');
    }
    public function form()
    {
        $this->load->view('sandbox/form');
    }


    public function get_sandboxes(){
        $this->load->model("zeapps_project_cards", "cards");

        $where = array('step' => 0);

        $sandboxes = $this->cards->get_all($where);

        echo json_encode($sandboxes);
    }



    public function get_filters(){
        $this->load->model("zeapps_projects", "projects");
        $this->load->model("zeapps_project_cards", "cards");
        $this->load->model("zeapps_project_deadlines", "deadlines");

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
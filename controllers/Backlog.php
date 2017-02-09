<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backlog extends ZeCtrl
{
    public function view()
    {
        $data = array() ;

        $this->load->view('backlog/view', $data);
    }
    public function form()
    {
        $data = array() ;

        $this->load->view('backlog/form', $data);
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
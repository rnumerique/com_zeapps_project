<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Journal extends ZeCtrl
{
    public function view(){
        $this->load->view('journal/view');
    }

    public function get_journal(){
        $this->load->model("Zeapps_project_cards", "cards");
        $this->load->model("Zeapps_project_timers", "timers");

        $assigned = $this->cards->get_assigned();

        $filters = array('assigned' => $assigned);

        $logs = [];

        if($timers = $this->timers->get_logs()){
            foreach($timers as $timer){
                $date = date('Y-m-d', strtotime($timer->start_time));
                if(!isset($logs[$date])){
                    $logs[$date] = [];
                }
                if(!isset($logs[$date][$timer->id_user])){
                    $logs[$date][$timer->id_user] = array(
                        'id_user' => $timer->id_user,
                        'name_user' => $timer->name_user,
                        'time_spent' => intval($timer->time_spent),
                    );
                }
                else{
                    $logs[$date][$timer->id_user]['time_spent'] += intval($timer->time_spent);
                }
            }
        }

        $formatted_logs = [];

        foreach($logs as $date => $log){
            foreach($log as $user){
                array_push($formatted_logs, array(
                    'date' => $date,
                    'id_user' => $user['id_user'],
                    'name_user' => $user['name_user'],
                    'time_spent' => $user['time_spent']
                ));
            }
        }


        echo json_encode(array('filters' => $filters, 'logs' => $formatted_logs));
    }
}
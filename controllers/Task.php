<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task extends ZeCtrl
{

    public function get_tasks(){
        $this->load->model("Zeapps_project_card_tasks", "tasks");

        $tasks = $this->tasks->all();

        echo json_encode($tasks);
    }
}
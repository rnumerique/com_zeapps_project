<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class com_zeapps_project_get_context extends ZeCtrl
{
    public function execute($data = array()){
        $this->load->model('Zeapps_project_rights', 'rights');
        $this->load->model('Zeapps_project_statuses', 'statuses');
        $this->load->model('Zeapps_project_timers', 'timers');

        $return = [];

        if(!$return['rights'] = $this->rights->all()){
            $return['rights'] = [];
        }

        $return['currentTimer'] = $this->timers->ongoing();

        if($return['statuses'] = $this->rights->all()){
            foreach($return['statuses'] as $status){
                $status->sort = intval($status->sort);
            }
        }
        else{
            $return['statuses'] = [];
        }

        return $return;
    }
}

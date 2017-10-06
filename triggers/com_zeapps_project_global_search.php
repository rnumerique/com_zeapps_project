<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class com_zeapps_project_global_search extends ZeCtrl
{
    public function execute($data = array()){
        $this->load->model('Zeapps_projects', 'projects');
        $this->load->model('Zeapps_project_cards', 'cards');
        $this->load->model('Zeapps_project_deadlines', 'deadlines');

        $return = array(
            "Projets" => []
        );

        if($projects = $this->projects->searchFor($data)){
            $return['Projets']["Projets"] = [];
            foreach($projects as $project){
                $return['Projets']["Projets"][] = array(
                    'label' => $project->title,
                    'url' => "/ng/com_zeapps_project/project/".$project->id
                );
            }
        }

        if($cards = $this->cards->searchFor($data)){
            $return['Projets']["Tâches"] = [];
            foreach($cards as $card){
                $return['Projets']["Tâches"][] = array(
                    'label' => $card->title,
                    'url' => "/ng/com_zeapps_project/project/".$card->id_project
                );
            }
        }

        if($deadlines = $this->deadlines->searchFor($data)){
            $return['Projets']["Jalons"] = [];
            foreach($deadlines as $deadline){
                $return['Projets']["Jalons"][] = array(
                    'label' => $deadline->title,
                    'url' => "/ng/com_zeapps_project/project/".$deadline->id_project
                );
            }
        }

        return $return;
    }
}

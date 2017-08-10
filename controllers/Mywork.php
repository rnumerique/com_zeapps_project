<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mywork extends ZeCtrl
{
    public function view(){
        $this->load->view('mywork/view');
    }



    public function get_work(){
        $this->load->model("Zeapps_project_cards", "cards");
        $this->load->model("Zeapps_project_card_comments", "comments");
        $this->load->model("Zeapps_project_card_documents", "documents");
        $this->load->model("Zeapps_project_card_priorities", "priorities");

        if($actuals = $this->cards->get_actuals()){
            foreach($actuals as $actual){
                if(!$actual->comments = $this->comments->all(array('id_card' => $actual->id)))
                    $actual->comments = [];
                if(!$actual->documents = $this->documents->all(array('id_card' => $actual->id)))
                    $actual->documents = [];
            }
        }
        else{
            $actuals = [];
        }

        if($leftovers = $this->cards->get_leftovers()){
            foreach($leftovers as $leftover){
                if(!$leftover->comments = $this->comments->all(array('id_card' => $leftover->id)))
                    $leftover->comments = [];
                if(!$leftover->documents = $this->documents->all(array('id_card' => $leftover->id)))
                    $leftover->documents = [];
            }
        }
        else{
            $leftovers = [];
        }

        if($futures = $this->cards->get_futures()){
            foreach($futures as $future){
                if(!$future->comments = $this->comments->all(array('id_card' => $future->id)))
                    $future->comments = [];
                if(!$future->documents = $this->documents->all(array('id_card' => $future->id)))
                    $future->documents = [];
            }
        }
        else{
            $futures = [];
        }

        if($nodates = $this->cards->get_nodates()){
            foreach($nodates as $nodate){
                if(!$nodate->comments = $this->comments->all(array('id_card' => $nodate->id)))
                    $nodate->comments = [];
                if(!$nodate->documents = $this->documents->all(array('id_card' => $nodate->id)))
                    $nodate->documents = [];
            }
        }
        else{
            $nodates = [];
        }

        if(!$dates_tmp = $this->cards->get_dates()) {
            $dates_tmp = [];
        }
        $dates = [];

        foreach ($dates_tmp as $date) {
            if ( ! in_array($date, $dates)) {
                $dates[] = $date;
            }
        }

        $priorities = $this->priorities->all();

        echo json_encode(array(
            "actuals" => $actuals,
            "leftovers" => $leftovers,
            "futures" => $futures,
            "nodates" => $nodates,
            "priorities" => $priorities,
            "dates" => $dates
        ));
    }
}
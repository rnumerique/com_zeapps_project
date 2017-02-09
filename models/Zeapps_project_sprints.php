<?php
class Zeapps_project_sprints extends ZeModel {

    public function update($data = NULL, $where = NULL){

        if(!isset($data['title']) || $data['title'] === ''){
            $data['title'] = 'Sprint n°' . $data['numerotation'];
        }

        return parent::update($data, $where);
    }

    public function insert($data = NULL){

        if($last = $this->database()->select('numerotation')->order_by('numerotation', 'DESC')->limit(1)->where(array('id_project'=>$data['id_project']))->table('zeapps_project_sprints')->result())
            $data['numerotation'] = intval($last[0]->numerotation) + 1;
        else
            $data['numerotation'] = 1;

        if(!isset($data['title']) || $data['title'] === ''){

            $data['title'] = 'Sprint n°' . $data['numerotation'];
        }

        return parent::insert($data);
    }
}
<?php
class Zeapps_project_sprints extends ZeModel {

    public function __construct()
    {
        parent::__construct();

        $this->soft_deletes = TRUE;
    }

    public function update($data = NULL, $column_name_where = NULL, $escape = TRUE){

        if(!isset($data['title']) || $data['title'] === ''){
            $data['title'] = 'Sprint n°' . $data['numerotation'];
        }

        return parent::update($data, $column_name_where, $escape);
    }

    public function insert($data = NULL){

        if($last = $this->_database->select('numerotation')->order_by('numerotation', 'DESC')->limit(1)->where(array('id_project'=>$data['id_project']))->get('zeapps_project_sprints')->result())
            $data['numerotation'] = intval($last[0]->numerotation) + 1;
        else
            $data['numerotation'] = 1;

        if(!isset($data['title']) || $data['title'] === ''){

            $data['title'] = 'Sprint n°' . $data['numerotation'];
        }

        return parent::insert($data);
    }
}
<?php
class Zeapps_project_sprints extends ZeModel {

    public function all($where = array()){
        $where['zeapps_project_sprints.deleted_at'] = null;
        $where['zeapps_project_rights.access'] = 1;

        return $this->database()->select('*,
                                        zeapps_project_sprints.id as id')
            ->join('zeapps_project_rights', 'zeapps_project_rights.id_project = zeapps_project_sprints.id_project', 'LEFT')
            ->where($where)
            ->where_not(array('zeapps_project_sprints.id' => null))
            ->group_by('zeapps_project_sprints.id')
            ->table('zeapps_project_sprints')
            ->result();
    }

    public function insert($data = array()){

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
<?php
class Zeapps_project_deadlines extends ZeModel {

    public function all($where = array()){
        $where['zeapps_project_deadlines.deleted_at'] = null;
        $where['zeapps_project_rights.access'] = 1;

        return $this->database()->select('*, 
                                        zeapps_project_deadlines.id as id, 
                                        zeapps_project_deadlines.title as title, 
                                        zeapps_projects.title as project_title, 
                                        zeapps_project_deadlines.due_date as due_date')
            ->join('zeapps_projects', 'zeapps_project_deadlines.id_project = zeapps_projects.id', 'LEFT')
            ->join('zeapps_project_rights', 'zeapps_project_rights.id_project = zeapps_project_deadlines.id_project', 'LEFT')
            ->where($where)
            ->where_not(array('zeapps_project_deadlines.id' => null))
            ->group_by('zeapps_project_deadlines.id')
            ->table('zeapps_project_deadlines')
            ->result();
    }

    public function get_dates(){
        return $this->database()->select('due_date')->group_by('due_date')->table('zeapps_project_deadlines')->result();
    }
}
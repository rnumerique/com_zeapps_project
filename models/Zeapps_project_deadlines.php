<?php
class Zeapps_project_deadlines extends ZeModel {

    public function __construct()
    {
        parent::__construct();

        $this->soft_deletes = TRUE;
    }

    public function get_all($where = array()){
        $where['zeapps_project_deadlines.deleted_at'] = null;
        return $this->_database->select('*, zeapps_project_deadlines.id as id, zeapps_project_deadlines.title as title, zeapps_projects.title as project_title, zeapps_project_deadlines.due_date as due_date')->join('zeapps_projects', 'zeapps_project_deadlines.id_project = zeapps_projects.id', 'LEFT')->where($where)->get('zeapps_project_deadlines')->result();
    }

    public function get_dates(){
        return $this->_database->select('due_date')->group_by('due_date')->get('zeapps_project_deadlines')->result();
    }
}
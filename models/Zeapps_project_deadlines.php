<?php
class Zeapps_project_deadlines extends ZeModel {

    public function all($where = array()){
        $this->_pLoad->model('Zeapps_users', 'users');

        if($user = $this->_pLoad->ctrl->users->getUserByToken($this->_pLoad->ctrl->session->get('token'))){
            $where['zeapps_project_rights.id_user'] = $user[0]->id;
        }

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

    public function get($where = array()){

        if(!is_array($where)) {
            $where = array('zeapps_project_deadlines.id' => $where);
        }
        $where['zeapps_project_deadlines.deleted_at'] = null;

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

    public function get_nextOf($id_project){
        $where = array(
            'id_project' => $id_project,
            'deleted_at' => null
        );

        return $this->database()->select('*')->limit(1)->where($where)->table('zeapps_project_deadlines')->result();
    }
}
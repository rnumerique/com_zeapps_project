<?php
class Zeapps_project_deadlines extends ZeModel {

    public function all($where = array(), $unfinished = false){
        $this->_pLoad->model('Zeapps_users', 'users');

        if($user = $this->_pLoad->ctrl->users->getUserByToken($this->_pLoad->ctrl->session->get('token'))){
            $where['zeapps_project_rights.id_user'] = $user->id;
        }

        $where['zeapps_project_deadlines.deleted_at'] = null;
        $where['zeapps_project_rights.access'] = 1;



        if($unfinished){
            $where['zeapps_project_deadlines.end_at'] = '0000-00-00';
        }

        $this->database()->clearSql();
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
        $this->database()->clearSql();

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
        $this->database()->clearSql();
        return $this->database()->select('due_date')->group_by('due_date')->table('zeapps_project_deadlines')->result();
    }

    public function get_nextOf($id_project){
        $this->database()->clearSql();
        $where = array(
            'id_project' => $id_project,
            'deleted_at' => null,
            'end_at' => '0000-00-00'
        );

        return $this->database()->select('*')->limit(1)->where($where)->table('zeapps_project_deadlines')->result();
    }


    public function delete($where, $forceDelete = false)
    {
        $this->_pLoad->model('Zeapps_projects', 'projects');

        $idProjects = array() ;

        if (isset($where["id_project"])) {
            if (is_array($where["id_project"])) {
                foreach ($where["id_project"] as $idProject) {
                    $idProjects[] = $idProject ;
                }
            } else {
                $idProjects[] = $where["id_project"];
            }
        }

        if (isset($where["id"])) {
            if (is_array($where["id"])) {
                foreach ($where["id"] as $idDeadline) {
                    $deadline = $this->get($idDeadline);
                    if ($deadline && is_array($deadline) && count($deadline) > 0) {
                        $idProjects[] = $deadline[0]->id_project ;
                    }
                }
            } else {
                $deadline = $this->get($where["id"]);
                if ($deadline && is_array($deadline) && count($deadline) > 0) {
                    $idProjects[] = $deadline[0]->id_project ;
                }
            }
        }

        if (is_numeric($where)) {
            $deadline = $this->get($where);
            if ($deadline) {
                $idProjects[] = $deadline->id_project ;
            }
        }


        $retour = parent::delete($where, $forceDelete) ;


        foreach ($idProjects as $idProject) {
            $this->_pLoad->ctrl->projects->updateNextDueDate($idProject) ;
        }

        return $retour ;
    }

    public function insert($objData = null)
    {
        $this->_pLoad->model('Zeapps_projects', 'projects');

        $idInsert = parent::insert($objData) ;

        if (isset($objData["id_project"])) {
            $this->_pLoad->ctrl->projects->updateNextDueDate($objData["id_project"]);
        }

        return $idInsert ;
    }

    public function update($objData = null, $where = null)
    {
        $this->_pLoad->model('Zeapps_projects', 'projects');

        $idProjects = array() ;


        if (is_numeric($where)) {
            $deadline = $this->get($where);
            if ($deadline && is_array($deadline) && count($deadline)) {
                $idProjects[] = $deadline[0]->id_project ;
            }
        }

        if (isset($where["id_project"])) {
            if (is_array($where["id_project"])) {
                foreach ($where["id_project"] as $idProject) {
                    $idProjects[] = $idProject ;
                }
            } else {
                $idProjects[] = $where["id_project"];
            }
        }

        if (isset($where["id"])) {
            if (is_array($where["id"])) {
                foreach ($where["id"] as $idDeadline) {
                    $deadline = parent::get($idDeadline);
                    if ($deadline) {
                        $idProjects[] = $deadline->id_project ;
                    }
                }
            } else {
                $deadline = parent::get($where["id"]);
                if ($deadline) {
                    $idProjects[] = $deadline->id_project ;
                }
            }
        }



        $retour = parent::update($objData, $where) ;

        foreach ($idProjects as $idProject) {
            $this->_pLoad->ctrl->projects->updateNextDueDate($idProject) ;
        }

        return $retour ;
    }
}
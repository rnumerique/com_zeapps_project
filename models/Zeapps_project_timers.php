<?php
class Zeapps_project_timers extends ZeModel {

    public function insert($objData = null){
        if(!$objData['id_user']) {
            $this->_pLoad->model('zeapps_users', 'users');

            if ($user = $this->_pLoad->ctrl->users->getUserByToken($this->_pLoad->ctrl->session->get('token'))) {
                $objData['id_user'] = $user[0]->id;
                $objData['name_user'] = $user[0]->firstname . ' ' . $user[0]->lastname;
            }
        }

        return parent::insert($objData);
    }

    public function ongoing(){
        $this->_pLoad->model('zeapps_users', 'users');

        $where = [];

        $where['zeapps_project_timers.time_stop'] = null;

        if($user = $this->_pLoad->ctrl->users->getUserByToken($this->_pLoad->ctrl->session->get('token'))){
            $where['zeapps_project_timers.id_user'] = $user[0]->id;
        }

        return parent::get($where);
    }

    public function all($where = array()){
        $this->_pLoad->model('zeapps_users', 'users');

        $where['zeapps_project_timers.deleted_at'] = null;

        $where_or = [];
        $where['zeapps_project_rights.project'] = 1;

        if($user = $this->_pLoad->ctrl->users->getUserByToken($this->_pLoad->ctrl->session->get('token'))){
            $where_or['zeapps_project_rights.id_user'] = $user[0]->id;
            $where_or['zeapps_project_timers.id_user'] = $user[0]->id;
        }

        return $this->database()->select('*, 
                                        zeapps_project_timers.id as id, 
                                        zeapps_project_timers.label as label,
                                        zeapps_project_timers.id_user as id_user,
                                        zeapps_project_timers.comment as comment,
                                        zeapps_project_timers.start_time as start_time,
                                        zeapps_project_timers.stop_time as stop_time,
                                        zeapps_project_timers.time_spent as time_spent')
            ->join('zeapps_project_rights', 'zeapps_project_rights.id_project = zeapps_project_timers.id_project', 'LEFT')
            ->where($where)
            ->where_or($where_or)
            ->where_not(array('zeapps_project_timers.id' => null))
            ->group_by('zeapps_project_timers.id')
            ->table('zeapps_project_timers')
            ->result();
    }

    public function get_logs(){
        $this->_pLoad->model('zeapps_users', 'users');

        $where = [];
        $where['zeapps_project_timers.deleted_at'] = null;
        $where['zeapps_project_rights.project'] = 1;

        if($user = $this->_pLoad->ctrl->users->getUserByToken($this->_pLoad->ctrl->session->get('token'))){
            $where['zeapps_project_rights.id_user'] = $user[0]->id;
        }

        return $this->database()->select('*, 
                                        zeapps_project_timers.id as id, 
                                        zeapps_project_timers.label as label,
                                        zeapps_project_timers.id_user as id_user,
                                        zeapps_project_timers.comment as comment,
                                        zeapps_project_timers.start_time as start_time,
                                        zeapps_project_timers.stop_time as stop_time,
                                        zeapps_project_timers.time_spent as time_spent')
            ->join('zeapps_project_rights', 'zeapps_project_rights.id_project = zeapps_project_timers.id_project', 'LEFT')
            ->where($where)
            ->where_not(array('zeapps_project_timers.id' => null))
            ->group_by('zeapps_project_timers.id')
            ->table('zeapps_project_timers')
            ->result();
    }

    public function getTimeOfProject($id_project){
        $where = [
            'zeapps_project_timers.id_project' => $id_project,
            'zeapps_project_timers.deleted_at' => null
        ];

        $ret = $this->database()->select('SUM(zeapps_project_timers.time_spent) as total_time')
            ->where($where)
            ->table('zeapps_project_timers')
            ->result();

        if($ret && $ret[0]->total_time) {
            return $ret[0]->total_time;
        }
        else{
            return 0;
        }
    }

    public function getHourlyPriceOfProject($id_project){
        $where = [
            'zeapps_project_timers.id_project' => $id_project,
            'zeapps_project_rights.id_project' => $id_project,
            'zeapps_project_timers.deleted_at' => null
        ];

        $ret = $this->database()->select('SUM(zeapps_project_timers.time_spent / 60 * zeapps_project_rights.hourly_rate) as total')
            ->join('zeapps_project_rights', 'zeapps_project_timers.id_user = zeapps_project_rights.id_user', 'LEFT')
            ->where($where)
            ->table('zeapps_project_timers')
            ->result();

        if($ret && $ret[0]->total) {
            return $ret[0]->total;
        }
        else{
            return 0;
        }
    }

    public function getTimeOfCard($id_card){
        $where = [
            'zeapps_project_timers.id_card' => $id_card,
            'zeapps_project_timers.deleted_at' => null
        ];

        $ret = $this->database()->select('SUM(zeapps_project_timers.time_spent) as total_time')
            ->where($where)
            ->table('zeapps_project_timers')
            ->result();

        if($ret && $ret[0]->total_time) {
            return $ret[0]->total_time;
        }
        else{
            return 0;
        }
    }
}
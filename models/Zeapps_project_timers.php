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

    public function get($where = array()){

        if(is_array($where))
            $where['zeapps_project_timers.deleted_at'] = null;
        elseif(is_numeric($where)){
            $where = [
                'zeapps_project_timers.id' => $where,
                'zeapps_project_timers.deleted_at' => null
            ];
        }

        $ret = $this->database()->select('zeapps_project_timers.*')
            ->where($where)
            ->table('zeapps_project_timers')
            ->limit(1)
            ->result();

        if(isset($ret) && is_array($ret) && sizeof($ret) > 0) {
            return $ret[0];
        }
        else{
            return false;
        }
    }

    public function all($where = array()){
        $where['zeapps_project_timers.deleted_at'] = null;

        $ret = $this->database()->select('zeapps_project_timers.*')
            ->where($where)
            ->table('zeapps_project_timers')
            ->result();

        if(isset($ret) && is_array($ret) && sizeof($ret) > 0) {
            return $ret;
        }
        else{
            return false;
        }
    }

    public function get_logs(){
        $this->_pLoad->model('zeapps_users', 'users');

        $where = [];
        $where['zeapps_project_timers.deleted_at'] = null;
        $where['zeapps_project_rights.project'] = 1;

        return $this->database()->select('*, 
                                        zeapps_project_timers.id as id, 
                                        zeapps_project_timers.label as label,
                                        zeapps_project_timers.id_user as id_user,
                                        zeapps_project_timers.comment as comment,
                                        zeapps_project_timers.start_time as start_time,
                                        zeapps_project_timers.stop_time as stop_time,
                                        zeapps_project_timers.time_spent as time_spent,
                                        zeapps_project_card_priorities.label as priority, 
                                        zeapps_project_card_priorities.sort as priority_sort, 
                                        zeapps_project_card_priorities.color as priority_color, 
                                        zeapps_project_cards.description as description, 
                                        zeapps_project_timers.id_project as id_project,
                                        zeapps_projects.title as project_title, 
                                        zeapps_project_categories.title as category_title,
                                        zeapps_project_categories.color as color')
            ->join('zeapps_projects', 'zeapps_projects.id = zeapps_project_timers.id_project', 'LEFT')
            ->join('zeapps_project_cards', 'zeapps_project_cards.id = zeapps_project_timers.id_card', 'LEFT')
            ->join('zeapps_project_categories', 'zeapps_project_categories.id = zeapps_project_cards.id_category', 'LEFT')
            ->join('zeapps_project_rights', 'zeapps_project_rights.id_project = zeapps_project_timers.id_project', 'LEFT')
            ->join('zeapps_project_card_priorities', 'zeapps_project_card_priorities.id = zeapps_project_cards.id_priority', 'LEFT')
            ->where($where)
            ->where_not(array('zeapps_project_timers.id' => null))
            ->group_by('zeapps_project_timers.id')
            ->table('zeapps_project_timers')
            ->result();
    }

    public function getTimeOfProject($id_project){
        $where = [
            'id_project' => $id_project,
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

    public function getTimeOfCard($id_card){
        $where = [
            'id_card' => $id_card,
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
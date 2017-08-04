<?php
class Zeapps_project_cards extends ZeModel {

    public function all($where = array()){
        $this->_pLoad->model('Zeapps_users', 'users');

        if($user = $this->_pLoad->ctrl->users->getUserByToken($this->_pLoad->ctrl->session->get('token'))){
            $where['zeapps_project_rights.id_user'] = $user[0]->id;
        }

        $where['zeapps_project_cards.deleted_at'] = null;
        $where['zeapps_project_rights.access'] = 1;

        return $this->database()->select('*, 
                                        zeapps_project_cards.id as id, 
                                        zeapps_project_card_priorities.label as priority, 
                                        zeapps_project_card_priorities.sort as priority_sort, 
                                        zeapps_project_card_priorities.color as priority_color, 
                                        zeapps_project_cards.description as description, 
                                        zeapps_project_cards.due_date as due_date, 
                                        zeapps_project_cards.id_project as id_project,
                                        zeapps_project_cards.title as title, 
                                        zeapps_projects.title as project_title, 
                                        zeapps_project_categories.title as category_title,
                                        zeapps_project_categories.color as color')
            ->join('zeapps_projects', 'zeapps_project_cards.id_project = zeapps_projects.id', 'LEFT')
            ->join('zeapps_project_categories', 'zeapps_project_categories.id = zeapps_project_cards.id_category', 'LEFT')
            ->join('zeapps_project_rights', 'zeapps_project_rights.id_project = zeapps_project_cards.id_project', 'LEFT')
            ->join('zeapps_project_card_priorities', 'zeapps_project_card_priorities.id = zeapps_project_cards.id_priority', 'LEFT')
            ->where($where)
            ->where_not(array('zeapps_project_cards.id' => null))
            ->group_by('zeapps_project_cards.id')
            ->order_by('zeapps_project_cards.sort')
            ->table('zeapps_project_cards')
            ->result();
    }

    public function get($where = array()){

        if(!is_array($where)) {
            $where = array('zeapps_project_cards.id' => $where);
        }
        $where['zeapps_project_cards.deleted_at'] = null;

        $this->_pLoad->model('Zeapps_users', 'users');

        if($user = $this->_pLoad->ctrl->users->getUserByToken($this->_pLoad->ctrl->session->get('token'))){
            $where['zeapps_project_rights.id_user'] = $user[0]->id;
        }

        return $this->database()->select('*, 
                                        zeapps_project_cards.id as id, 
                                        zeapps_project_card_priorities.label as priority, 
                                        zeapps_project_card_priorities.sort as priority_sort, 
                                        zeapps_project_card_priorities.color as priority_color, 
                                        zeapps_project_cards.description as description, 
                                        zeapps_project_cards.due_date as due_date, 
                                        zeapps_project_cards.id_project as id_project,
                                        zeapps_project_cards.title as title, 
                                        zeapps_projects.title as project_title, 
                                        zeapps_project_categories.title as category_title,
                                        zeapps_project_categories.color as color')
            ->join('zeapps_projects', 'zeapps_project_cards.id_project = zeapps_projects.id', 'LEFT')
            ->join('zeapps_project_categories', 'zeapps_project_categories.id = zeapps_project_cards.id_category', 'LEFT')
            ->join('zeapps_project_rights', 'zeapps_project_rights.id_project = zeapps_project_cards.id_project', 'LEFT')
            ->join('zeapps_project_card_priorities', 'zeapps_project_card_priorities.id = zeapps_project_cards.id_priority', 'LEFT')
            ->where($where)
            ->where_not(array('zeapps_project_cards.id' => null))
            ->group_by('zeapps_project_cards.id')
            ->order_by('zeapps_project_cards.sort')
            ->table('zeapps_project_cards')
            ->result();
    }

    public function update_batch($cards = array()){
        if($cards){
            foreach($cards as $card){
                if(!parent::update($card, $card['id'])){
                    return false;
                }
            }
            return true;
        }

        return false;
    }

    public function get_dates(){
        return $this->database()->select('due_date')->group_by('due_date')->table('zeapps_project_cards')->result();
    }

    public function get_assigned(){
        return $this->database()->select('id_assigned_to as id, name_assigned_to as label')->group_by('id_assigned_to')->where_not(array('id_assigned_to'=>0))->table('zeapps_project_cards')->result();
    }

    public function get_nbSandboxOf($id_project){
        $where = array(
            'id_project' => $id_project,
            'step' => 0,
            'deleted_at' => null
        );

        $res = $this->database()->select('*')->where($where)->table('zeapps_project_cards')->result();

        if($res)
            return sizeof($res);
        else
            return 0;
    }

    public function get_nbBacklogOf($id_project){
        $where = array(
            'id_project' => $id_project,
            'step' => 1,
            'deleted_at' => null
        );

        $res = $this->database()->select('*')->where($where)->table('zeapps_project_cards')->result();

        if($res)
            return sizeof($res);
        else
            return 0;
    }

    public function get_nbOngoingOf($id_project){
        $where = array(
            'id_project' => $id_project,
            'step >' => 1,
            'step <' => 4,
            'deleted_at' => null
        );

        $res = $this->database()->select('*')->where($where)->table('zeapps_project_cards')->result();

        if($res)
            return sizeof($res);
        else
            return 0;
    }

    public function get_nbQualityOf($id_project){
        $where = array(
            'id_project' => $id_project,
            'step' => 4,
            'deleted_at' => null
        );

        $res = $this->database()->select('*')->where($where)->table('zeapps_project_cards')->result();

        if($res)
            return sizeof($res);
        else
            return 0;
    }

    public function get_nbInSprintOf($id_sprint, $id_project){
        $where = array(
            'id_project' => $id_project,
            'id_sprint' => $id_sprint,
            'deleted_at' => null
        );

        $res = $this->database()->select('*')->where($where)->table('zeapps_project_cards')->result();

        if($res)
            return sizeof($res);
        else
            return 0;
    }

    public function get_actuals(){
        $this->_pLoad->model('Zeapps_users', 'users');

        $where = [];
        $where['zeapps_project_cards.deleted_at'] = null;

        if($user = $this->_pLoad->ctrl->users->getUserByToken($this->_pLoad->ctrl->session->get('token'))){
            $where['zeapps_project_cards.id_assigned_to'] = $user[0]->id;
        }

        $where['zeapps_project_rights.access'] = 1;

        $where['zeapps_project_cards.completed'] = 'N';
        $where['zeapps_project_cards.due_date'] = 'NOW()';

        return $this->database()->select('*, 
                                        zeapps_project_cards.id as id, 
                                        zeapps_project_card_priorities.label as priority, 
                                        zeapps_project_card_priorities.sort as priority_sort, 
                                        zeapps_project_card_priorities.color as priority_color, 
                                        zeapps_project_cards.description as description, 
                                        zeapps_project_cards.due_date as due_date, 
                                        zeapps_project_cards.id_project as id_project,
                                        zeapps_project_cards.title as title, 
                                        zeapps_projects.title as project_title, 
                                        zeapps_project_categories.title as category_title,
                                        zeapps_project_categories.color as color')
            ->join('zeapps_projects', 'zeapps_project_cards.id_project = zeapps_projects.id', 'LEFT')
            ->join('zeapps_project_categories', 'zeapps_project_categories.id = zeapps_project_cards.id_category', 'LEFT')
            ->join('zeapps_project_rights', 'zeapps_project_rights.id_project = zeapps_project_cards.id_project', 'LEFT')
            ->join('zeapps_project_card_priorities', 'zeapps_project_card_priorities.id = zeapps_project_cards.id_priority', 'LEFT')
            ->where($where)
            ->where_not(array('zeapps_project_cards.id' => null, 'zeapps_project_cards.due_date' => "0000-00-00"))
            ->group_by('zeapps_project_cards.id')
            ->order_by('zeapps_project_cards.sort')
            ->table('zeapps_project_cards')
            ->result();
    }

    public function get_leftovers(){
        $this->_pLoad->model('Zeapps_users', 'users');

        $where = [];
        $where['zeapps_project_cards.deleted_at'] = null;

        if($user = $this->_pLoad->ctrl->users->getUserByToken($this->_pLoad->ctrl->session->get('token'))){
            $where['zeapps_project_cards.id_assigned_to'] = $user[0]->id;
        }

        $where['zeapps_project_rights.access'] = 1;

        $where['zeapps_project_cards.completed'] = 'N';
        $where['zeapps_project_cards.due_date <'] = 'NOW()';

        return $this->database()->select('*, 
                                        zeapps_project_cards.id as id, 
                                        zeapps_project_card_priorities.label as priority, 
                                        zeapps_project_card_priorities.sort as priority_sort, 
                                        zeapps_project_card_priorities.color as priority_color, 
                                        zeapps_project_cards.description as description, 
                                        zeapps_project_cards.due_date as due_date, 
                                        zeapps_project_cards.id_project as id_project,
                                        zeapps_project_cards.title as title, 
                                        zeapps_projects.title as project_title, 
                                        zeapps_project_categories.title as category_title,
                                        zeapps_project_categories.color as color')
            ->join('zeapps_projects', 'zeapps_project_cards.id_project = zeapps_projects.id', 'LEFT')
            ->join('zeapps_project_categories', 'zeapps_project_categories.id = zeapps_project_cards.id_category', 'LEFT')
            ->join('zeapps_project_rights', 'zeapps_project_rights.id_project = zeapps_project_cards.id_project', 'LEFT')
            ->join('zeapps_project_card_priorities', 'zeapps_project_card_priorities.id = zeapps_project_cards.id_priority', 'LEFT')
            ->where($where)
            ->where_not(array('zeapps_project_cards.id' => null, 'zeapps_project_cards.due_date' => "0000-00-00"))
            ->group_by('zeapps_project_cards.id')
            ->order_by('zeapps_project_cards.sort')
            ->table('zeapps_project_cards')
            ->result();
    }

    public function get_futures(){
        $this->_pLoad->model('Zeapps_users', 'users');

        $where = [];
        $where['zeapps_project_cards.deleted_at'] = null;

        if($user = $this->_pLoad->ctrl->users->getUserByToken($this->_pLoad->ctrl->session->get('token'))){
            $where['zeapps_project_cards.id_assigned_to'] = $user[0]->id;
        }

        $where['zeapps_project_rights.access'] = 1;

        $where['zeapps_project_cards.completed'] = 'N';
        $where['zeapps_project_cards.due_date >'] = 'NOW()';

        return $this->database()->select('*, 
                                        zeapps_project_cards.id as id, 
                                        zeapps_project_card_priorities.label as priority, 
                                        zeapps_project_card_priorities.sort as priority_sort, 
                                        zeapps_project_card_priorities.color as priority_color, 
                                        zeapps_project_cards.description as description, 
                                        zeapps_project_cards.due_date as due_date, 
                                        zeapps_project_cards.id_project as id_project,
                                        zeapps_project_cards.title as title, 
                                        zeapps_projects.title as project_title, 
                                        zeapps_project_categories.title as category_title,
                                        zeapps_project_categories.color as color')
            ->join('zeapps_projects', 'zeapps_project_cards.id_project = zeapps_projects.id', 'LEFT')
            ->join('zeapps_project_categories', 'zeapps_project_categories.id = zeapps_project_cards.id_category', 'LEFT')
            ->join('zeapps_project_rights', 'zeapps_project_rights.id_project = zeapps_project_cards.id_project', 'LEFT')
            ->join('zeapps_project_card_priorities', 'zeapps_project_card_priorities.id = zeapps_project_cards.id_priority', 'LEFT')
            ->where($where)
            ->where_not(array('zeapps_project_cards.id' => null, 'zeapps_project_cards.due_date' => "0000-00-00"))
            ->group_by('zeapps_project_cards.id')
            ->order_by('zeapps_project_cards.sort')
            ->table('zeapps_project_cards')
            ->result();
    }
}
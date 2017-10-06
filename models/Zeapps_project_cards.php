<?php
class Zeapps_project_cards extends ZeModel {

    public function all($where = array(), $unfinished = false){
        $this->_pLoad->model('Zeapps_users', 'users');

        $user = $this->_pLoad->ctrl->users->getUserByToken($this->_pLoad->ctrl->session->get('token'));

        $where['zeapps_project_cards.deleted_at'] = null;
        if(!isset($user->rights['com_zeapps_project_sudo']) || !$user->rights['com_zeapps_project_sudo']) {
            $where['zeapps_project_rights.access'] = 1;
            $where['zeapps_project_rights.id_user'] = $user->id;
        }

        $where_not = array('zeapps_project_cards.id' => null);

        if($unfinished){
            $where_not['zeapps_project_cards.step'] = '4';
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
            ->where_not($where_not)
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
            $where['zeapps_project_rights.id_user'] = $user->id;
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

    public function get_dates($where = array()){
        $where['zeapps_project_cards.deleted_at'] = null;
        $where['zeapps_project_rights.access'] = 1;

        return $this->database()->select('due_date')
            ->join('zeapps_project_rights', 'zeapps_project_rights.id_project = zeapps_project_cards.id_project', 'LEFT')
            ->where($where)
            ->group_by('due_date')
            ->table('zeapps_project_cards')
            ->result();
    }

    public function get_assigned(){
        return $this->database()->select('id_assigned_to as id, name_assigned_to as label')->group_by('id_assigned_to')->where_not(array('id_assigned_to'=>0))->table('zeapps_project_cards')->result();
    }

    public function get_nodates(){
        $this->_pLoad->model('Zeapps_users', 'users');

        $where = [];
        $where['zeapps_project_cards.deleted_at'] = null;

        if($user = $this->_pLoad->ctrl->users->getUserByToken($this->_pLoad->ctrl->session->get('token'))){
            $where['zeapps_project_cards.id_assigned_to'] = $user->id;
        }

        if(!isset($user->rights['com_zeapps_project_sudo']) || !$user->rights['com_zeapps_project_sudo']) {
            $where['zeapps_project_rights.access'] = 1;
        }
        $where['zeapps_project_cards.due_date'] = '0000-00-00';

        $where_not = array('zeapps_project_cards.id' => null, 'zeapps_project_cards.step' => '4');

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
            ->where_not($where_not)
            ->group_by('zeapps_project_cards.id')
            ->order_by('zeapps_project_cards.sort')
            ->table('zeapps_project_cards')
            ->result();
    }

    public function get_actuals(){
        $this->_pLoad->model('Zeapps_users', 'users');

        $where = [];
        $where['zeapps_project_cards.deleted_at'] = null;

        if($user = $this->_pLoad->ctrl->users->getUserByToken($this->_pLoad->ctrl->session->get('token'))){
            $where['zeapps_project_cards.id_assigned_to'] = $user->id;
        }

        if(!isset($user->rights['com_zeapps_project_sudo']) || !$user->rights['com_zeapps_project_sudo']) {
            $where['zeapps_project_rights.access'] = 1;
        }
        $where['zeapps_project_cards.due_date'] = date('Y-m-d');

        $where_not = array('zeapps_project_cards.id' => null, 'zeapps_project_cards.due_date' => "0000-00-00", 'zeapps_project_cards.step' => '4');

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
            ->where_not($where_not)
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
            $where['zeapps_project_cards.id_assigned_to'] = $user->id;
        }

        if(!isset($user->rights['com_zeapps_project_sudo']) || !$user->rights['com_zeapps_project_sudo']) {
            $where['zeapps_project_rights.access'] = 1;
        }
        $where['zeapps_project_cards.due_date <'] = date('Y-m-d');

        $where_not = array('zeapps_project_cards.id' => null, 'zeapps_project_cards.due_date' => "0000-00-00", 'zeapps_project_cards.step' => '4');

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
            ->where_not($where_not)
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
            $where['zeapps_project_cards.id_assigned_to'] = $user->id;
        }

        if(!isset($user->rights['com_zeapps_project_sudo']) || !$user->rights['com_zeapps_project_sudo']) {
            $where['zeapps_project_rights.access'] = 1;
        }
        $where['zeapps_project_cards.due_date >'] = date('Y-m-d');

        $where_not = array('zeapps_project_cards.id' => null, 'zeapps_project_cards.due_date' => "0000-00-00", 'zeapps_project_cards.step' => '4');

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
            ->where_not($where_not)
            ->group_by('zeapps_project_cards.id')
            ->order_by('zeapps_project_cards.sort')
            ->table('zeapps_project_cards')
            ->result();
    }

    public function searchFor($terms = array()){
        $query = "SELECT * FROM zeapps_project_cards WHERE (1 ";

        foreach($terms as $term){
            $query .= "AND (title LIKE '%".$term."%') ";
        }

        $query .= ") AND deleted_at IS NULL LIMIT 10";

        return $this->database()->customQuery($query)->result();
    }
}
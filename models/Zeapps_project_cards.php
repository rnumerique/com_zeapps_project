<?php
class Zeapps_project_cards extends ZeModel {

    public function __construct()
    {
        parent::__construct();

        $this->soft_deletes = TRUE;
    }

    public function get_all($where = array()){
        $where['zeapps_project_cards.deleted_at'] = null;
        return $this->_database->select('*, 
                                        zeapps_project_cards.id as id, 
                                        zeapps_project_cards.description as description, 
                                        zeapps_project_cards.due_date as due_date, 
                                        zeapps_project_cards.id_project as id_project,
                                        zeapps_project_cards.title as title, 
                                        zeapps_projects.title as project_title, 
                                        zeapps_project_categories.title as category_title')
            ->join('zeapps_projects', 'zeapps_project_cards.id_project = zeapps_projects.id', 'LEFT')
            ->join('zeapps_project_categories', 'zeapps_project_categories.id = zeapps_project_cards.id_category', 'LEFT')
            ->where($where)->get('zeapps_project_cards')->result();
    }

    public function updateStateOf($data, $id_sprint){
        return $this->_database->set(array('step' => 2, 'id_sprint' => $id_sprint))->where_in('id', $data)->update('zeapps_project_cards');
    }

    public function get_dates(){
        return $this->_database->select('due_date')->group_by('due_date')->get('zeapps_project_cards')->result();
    }

    public function get_assigned(){
        return $this->_database->select('id_assigned_to, name_assigned_to')->group_by('id_assigned_to')->get('zeapps_project_cards')->result();
    }
}
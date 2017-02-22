<?php
class Zeapps_project_cards extends ZeModel {

    public function all($where = array()){
        $where['zeapps_project_cards.deleted_at'] = null;
        $where['zeapps_project_rights.access'] = 1;

        return $this->database()->select('*, 
                                        zeapps_project_cards.id as id, 
                                        zeapps_project_cards.description as description, 
                                        zeapps_project_cards.due_date as due_date, 
                                        zeapps_project_cards.id_project as id_project,
                                        zeapps_project_cards.title as title, 
                                        zeapps_projects.title as project_title, 
                                        zeapps_project_categories.title as category_title')
            ->join('zeapps_projects', 'zeapps_project_cards.id_project = zeapps_projects.id', 'LEFT')
            ->join('zeapps_project_categories', 'zeapps_project_categories.id = zeapps_project_cards.id_category', 'LEFT')
            ->join('zeapps_project_rights', 'zeapps_project_rights.id_project = zeapps_project_cards.id_project', 'LEFT')
            ->where($where)
            ->where_not(array('zeapps_project_cards.id' => null))
            ->group_by('zeapps_project_cards.id')
            ->table('zeapps_project_cards')
            ->result();
    }

    public function updateStateOf($ids, $id_sprint){
        $data = array('step' => 2, 'id_sprint' => $id_sprint);
        return $this->update($data, array('id' => $ids));
    }

    public function get_dates(){
        return $this->database()->select('due_date')->group_by('due_date')->table('zeapps_project_cards')->result();
    }

    public function get_assigned(){
        return $this->database()->select('id_assigned_to, name_assigned_to')->group_by('id_assigned_to')->table('zeapps_project_cards')->result();
    }

    public function updateOldSort($id_sprint, $id_category, $step, $sort) {
        $this->database()->query('UPDATE zeapps_project_cards SET sort = (sort-1) WHERE id_sprint = ' . $id_sprint . ' AND id_category = ' . $id_category . ' AND step = ' . $step . ' AND sort > ' . $sort);
    }

    public function updateNewSort($id_sprint, $id_category, $step, $sort) {
        $this->database()->query('UPDATE zeapps_project_cards SET sort = (sort+1) WHERE id_sprint = ' . $id_sprint . ' AND id_category = ' . $id_category . ' AND step = ' . $step . ' AND sort >= ' . $sort);
    }
}
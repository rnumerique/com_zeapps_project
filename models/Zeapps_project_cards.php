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
            ->order_by('sort')
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
        return $this->database()->select('id_assigned_to, name_assigned_to')->group_by('id_assigned_to')->table('zeapps_project_cards')->result();
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
}
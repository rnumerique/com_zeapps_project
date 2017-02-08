<?php
class Zeapps_projects extends ZeModel {

    public function __construct()
    {
        parent::__construct();

        $this->soft_deletes = TRUE;
    }

    public function get_companies(){
        return $this->_database->select('id_company, name_company')->group_by('id_company')->get('zeapps_projects')->result();
    }

    public function get_managers(){
        return $this->_database->select('id_manager, name_manager')->group_by('id_manager')->get('zeapps_projects')->result();
    }

    public function get_all($where = null, $spaces = 'false', $filter = 'false'){

        if(isset($where['id_parent']))
            $id = $where['id_parent'];
        else
            $id = 0;


        if($filter !== 'false') {
            $haystack[] = $filter;
        }

        if($ret = parent::get_all($where)) {
            $ret = $this->_parentChild_sort($ret, $id);
            foreach ($ret as $row) {
                if ($filter !== 'false') {
                    if (in_array($row->id, $haystack) || in_array($row->id_parent, $haystack)) {
                        $haystack[] = $row->id;
                        //unset($ret[$key]);
                    }
                }
                if ($spaces !== 'false') {
                    $row->title = str_repeat('&nbsp;&nbsp;&nbsp;', intval($row->spaces)) . $row->title;
                }
            }

            if ($filter !== 'false') {
                // We are using array_values to reset the keys, so that JS doesn't turn the array into an object
                return array_values($ret);
            } else
                return $ret;
        }
    }

    public function insert($data = null){
        if(isset($data['id_parent']) && $data['id_parent'] > 0){
            if($parent = $this->get($data['id_parent'])){
                $data['spaces'] = intval($parent->spaces) + 1;
            }
            $data['breadcrumbs'] = $this->_generateBreadcrumbsOf($data['id_parent']) . $data['title'];
        }
        else
            $data['breadcrumbs'] = $data['title'];

        return parent::insert($data);
    }

    public function update($data = NULL, $column_name_where = NULL, $escape = TRUE){
        if(isset($data['id_parent']) && $data['id_parent'] > 0){
            if($parent = $this->get($data['id_parent'])){
                $data['spaces'] = intval($parent->spaces) + 1;
            }
            $data['breadcrumbs'] = $this->_generateBreadcrumbsOf($data['id_parent']) . $data['title'];
        }
        else
            $data['breadcrumbs'] = $data['title'];

        $this->_updateChildsBreadcrumbsOf($data['id'], $data['breadcrumbs']);

        return parent::update($data, $column_name_where, $escape);
    }

    // where is a primary key for delete
    public function delete($where = null){
        $this->load->model("zeapps_project_deadlines", "deadlines");
        $this->load->model("zeapps_project_cards", "cards");

        $subs = $this->get_all(array('id_parent' => $where));

        if($subs) {
            foreach ($subs as $sub) {
                $this->delete($sub->id);
            }
        }

        $now = time();

        $this->deadlines->update(array('deleted_at' => $now), array('id_project' => $where));
        $this->cards->update(array('deleted_at' => $now), array('id_project' => $where));

        return parent::delete($where);
    }



    private function _parentChild_sort(&$arr, $parent = 0){

        $res = [];

        if($arr) {
            foreach ($arr as $key => $elm) {
                if ($elm->id_parent == $parent) {
                    $res[] = $elm;
                    unset($arr[$key]);
                    $res = array_merge($res, $this->_parentChild_sort($arr, $elm->id));
                }
            }
        }

        return $res;

    }

    private function _generateBreadcrumbsOf($id){
        $project = $this->get($id);

        if($project->id_parent > 0)
            $breadcrumbs = $this->_generateBreadcrumbsOf($project->id_parent);
        else
            $breadcrumbs = '';

        $breadcrumbs .= $project->title . ' : ';

        return $breadcrumbs;
    }

    private function _updateChildsBreadcrumbsOf($id, $breadcrumbs){
        if($projects = parent::get_all(array('id_parent' => $id))){
            foreach($projects as $project){
                $project->breadcrumbs = $breadcrumbs . ' : ' . $project->title;
                parent::update($project, $project->id);
                $this->_updateChildsBreadcrumbsOf($project->id, $project->breadcrumbs);
            }
        }
    }
}
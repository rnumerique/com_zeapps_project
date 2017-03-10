<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends ZeCtrl
{
    public function index(){
        $this->load->view('project/view');
    }

    public function overview(){
        $this->load->view('project/list');
    }

    public function categories(){
        $this->load->view('project/categories');
    }

    public function rights(){
        $this->load->view('project/rights');
    }

    public function form(){
        $this->load->view('project/form');
    }

    public function form_card(){
        $this->load->view('project/formCard');
    }

    public function modal_project(){
        $this->load->view('project/modalProject');
    }











    public function get_filters(){
        $this->load->model("Zeapps_project_cards", "cards");
        $this->load->model("Zeapps_project_deadlines", "deadlines");

        $dates_tmp = $this->cards->get_dates();
        $dates_merged = array_merge($dates_tmp, $this->deadlines->get_dates());
        $dates = [];

        foreach ($dates_merged as $date) {
            if ( ! in_array($date, $dates)) {
                $dates[] = $date;
            }
        }

        echo json_encode($dates);
    }

    public function get_project($id = 0){
        $this->load->model("Zeapps_projects", "projects");
        $this->load->model("Zeapps_project_rights", "rights");
        $this->load->model("Zeapps_project_categories", "categories");
        $this->load->model("Zeapps_project_cards", "cards");
        $this->load->model("Zeapps_project_deadlines", "deadlines");
        $this->load->model("Zeapps_project_rights", "rights");
        $this->load->model("Zeapps_users", "user");

        if($id) {
            $whereP = array('zeapps_projects.id' => $id);
            $whereC = array('zeapps_project_cards.id_project' => $id);
            $whereD = array('zeapps_project_deadlines.id_project' => $id);
        }

        if($user = $this->user->getUserByToken($this->session->get('token'))){
            $whereP['zeapps_project_rights.id_user'] = $user[0]->id;
            $whereC['zeapps_project_rights.id_user'] = $user[0]->id;
            $whereD['zeapps_project_rights.id_user'] = $user[0]->id;
        }

        if($project = $this->projects->get($id)) {
            $project->users = $this->rights->all(array('id_project' => $id));
        }

        if(!$categories = $this->categories->all(array('id_project' => $id))){
            $categories = [];
        }

        if($dates_tmp = $this->cards->get_dates()) {
            $dates_merged = array_merge($dates_tmp, $this->deadlines->get_dates());
        }
        else{
            $dates_merged = $this->deadlines->get_dates();
        }
        $dates = [];

        foreach ($dates_merged as $date) {
            if ( ! in_array($date, $dates)) {
                $dates[] = $date;
            }
        }

        if(!$project_users = $this->rights->all(array('id_project' => $id))){
            $project_users = [];
        }

        if($cards = $this->cards->all($whereC)){
            if($deadlines = $this->deadlines->all($whereD)){
                foreach($cards as $card){
                    $card->deadline = 0;
                }
                foreach($deadlines as $deadline){
                    $deadline->deadline = 1;
                }
                $cards = array_merge($cards, $deadlines);
            }
        }
        else{
            if($cards = $this->deadlines->all($whereD)) {
                foreach($cards as $card){
                    $card->deadline = 1;
                }
            }
            else{
                $cards = [];
            }
        }

        echo json_encode(
            array(
                'project' => $project,
                'categories' => $categories,
                'project_users' => $project_users,
                'cards' => $cards,
                'dates' => $dates
            )
        );
    }

    public function get_projects($id_parent = 0, $spaces = 'false', $filter = 'false'){
        $this->load->model("Zeapps_projects", "projects");
        $this->load->model("Zeapps_users", "user");

        if($id_parent)
            $where = array('id_parent' => $id_parent);
        else
            $where = array();

        if($user = $this->user->getUserByToken($this->session->get('token'))){
            $where['zeapps_project_rights.id_user'] = $user[0]->id;
        }

        $projects = $this->projects->all($where, $spaces, $filter);

        echo json_encode($projects);
    }

    public function get_overview(){
        $this->load->model("Zeapps_projects", "projects");
        $this->load->model("Zeapps_project_sprints", "sprints");
        $this->load->model("Zeapps_project_cards", "cards");
        $this->load->model("Zeapps_project_deadlines", "deadlines");
        $this->load->model("Zeapps_project_statuses", "statuses");
        $this->load->model("Zeapps_users", "user");

        $where = array();

        if($user = $this->user->getUserByToken($this->session->get('token'))){
            $where['zeapps_project_rights.id_user'] = $user[0]->id;
        }

        if($projects = $this->projects->all($where, 'false', 'false')){
            foreach($projects as $project){
                    $project->nbSandbox = $this->cards->get_nbSandboxOf($project->id);
                    $project->nbBacklog = $this->cards->get_nbBacklogOf($project->id);

                if($activeSprint = $this->sprints->get(array('id_project' => $project->id, 'active' => 'Y'))) {
                    $project->nbOngoing = $this->cards->get_nbOngoingOf($project->id);
                    $project->nbQuality = $this->cards->get_nbQualityOf($project->id);
                }
                else{
                    $project->nbOngoing = 0;
                    $project->nbQuality = 0;
                }

                if($nextSprints = $this->sprints->all(array('id_project' => $project->id, 'active' => 'N', 'completed' => 'N'))){
                    $nbNext = 0;
                    foreach($nextSprints as $sprint){
                        $nbNext += $this->cards->get_nbInSprintOf($sprint->id, $project->id);
                    }
                    $project->nbNext = $nbNext;
                }
                else{
                    $project->nbNext = 0;
                }

                if($deadline = $this->deadlines->get_nextOf($project->id))
                    $project->nextDeadline = $deadline[0]->due_date;
                else
                    $project->nextDeadline = 0;
            }
        }

        echo json_encode($projects);
    }

    public function get_childs($id){
        $this->load->model("Zeapps_projects", "projects");

        $childs = $this->_getChildsOf($id);

        echo json_encode($childs);
    }

    public function get_projects_tree(){
        $this->load->model("Zeapps_projects", "projects");
        $this->load->model("Zeapps_users", "user");

        $where = [];

        if($user = $this->user->getUserByToken($this->session->get('token'))){
            $where['zeapps_project_rights.id_user'] = $user[0]->id;
        }

        $projects = $this->projects->all($where);

        if ($projects == false) {
            echo json_encode(array());
        } else {
            $result = $this->_build_tree($projects);
            echo json_encode($result);
        }
    }

    public function save_project(){
        $this->load->model("Zeapps_projects", "projects");

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        if(isset($data['id'])){
            $this->projects->update($data, $data['id']);
            $id = $data['id'];
        }
        else{
            $id = $this->projects->insert($data);
        }

        echo $id;
    }

    public function archive_project($id = null){
        $this->load->model("Zeapps_projects", "projects");

        if($id) {
            $this->projects->update(array('archived' => 1), $id);
        }

        echo json_encode('OK');
    }

    public function delete_project($id = null, $force = 'false'){
        $this->load->model("Zeapps_projects", "projects");
        $this->load->model("Zeapps_project_deadlines", "deadlines");
        $this->load->model("Zeapps_project_cards", "cards");

        if($force == 'false'){
            if($this->projects->all(array('id_parent' => $id)) || $this->deadlines->all(array('id_project' => $id)) || $this->cards->all(array('id_project' => $id))){
                echo json_encode(array('hasDependencies' => true));
                return;
            }
        }

        $this->projects->delete($id);

        echo json_encode('OK');
    }

    private function _build_tree($categories, $id = 0){
        $result = array();

        foreach($categories as $category){
            if($category->id_parent == $id){

                $tmp = $category;
                $res = $this->_build_tree($categories, $category->id);
                if(!empty($res)) {
                    $tmp->branches = $res;
                }
                $tmp->open = false;
                $result[] = $tmp;
            }
        }

        return $result;
    }

    private function _getChildsOf($id){

        $arr = [];

        if($childs = $this->projects->all(array('id_parent' => $id))){
            foreach($childs as $child){
                $arr[] = $child;
                $ret = $this->_getChildsOf($child->id);
                $arr = array_merge($arr, $ret);
            }
        }

        return $arr;

    }
}

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

    public function deadlines(){
        $this->load->view('deadline/partial');
    }

    public function taches(){
        $this->load->view('card/partial');
    }

    public function calendar(){
        $this->load->view('project/calendar');
    }

    public function categories(){
        $this->load->view('category/partial');
    }

    public function notes(){
        $this->load->view('comment/partial');
    }

    public function documents(){
        $this->load->view('document/partial');
    }

    public function timers(){
        $this->load->view('timer/partial');
    }

    public function rights(){
        $this->load->view('right/partial');
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

    public function modal_comment(){
        $this->load->view('comment/modal');
    }

    public function modal_document(){
        $this->load->view('document/modal');
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
        $this->load->model("Zeapps_project_timers", "timers");
        $this->load->model("Zeapps_project_comments", "comments");
        $this->load->model("Zeapps_project_documents", "documents");
        $this->load->model("Zeapps_project_card_comments", "card_comments");
        $this->load->model("Zeapps_project_card_documents", "card_documents");
        $this->load->model("Zeapps_project_card_priorities", "priorities");

        if($id) {
            $whereC = array('zeapps_project_cards.id_project' => $id);
            $whereD = array('zeapps_project_deadlines.id_project' => $id);
        }

        if($project = $this->projects->get($id)) {
            $project->users = $this->rights->all(array('id_project' => $id));
            $project->time_spent = $this->timers->getTimeOfProject($project->id);
        }

        if(!$timers = $this->timers->all(array('id_project' => $id))){
            $timers = [];
        }

        if(!$comments = $this->comments->all(array('id_project' => $id))){
            $comments = [];
        }

        if(!$documents = $this->documents->all(array('id_project' => $id))){
            $documents = [];
        }

        if(!$categories = $this->categories->all(array('id_project' => $id))){
            $categories = [];
        }

        if(!$priorities = $this->priorities->all()){
            $priorities = [];
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

        if(!$cards = $this->cards->all($whereC)) {
            $cards = [];
        }
        if(!$deadlines = $this->deadlines->all($whereD)) {
            $deadlines = [];
        }

        echo json_encode(
            array(
                'project' => $project,
                'categories' => $categories,
                'priorities' => $priorities,
                'comments' => $comments,
                'documents' => $documents,
                'timers' => $timers,
                'project_users' => $project_users,
                'cards' => $cards,
                'deadlines' => $deadlines,
                'dates' => $dates
            )
        );
    }

    public function get_projects($id_parent = 0, $spaces = 'false', $filter = 'false'){
        $this->load->model("Zeapps_projects", "projects");
        $this->load->model("Zeapps_project_categories", "categories");

        if($id_parent)
            $where = array('id_parent' => $id_parent);
        else
            $where = array();

        if($projects = $this->projects->all($where, $spaces, $filter)){
            foreach($projects as $project){
                $project->categories = $this->categories->all(array('id_project' => $project->id));
            }
        }

        echo json_encode($projects);
    }

    public function get_overview(){
        $this->load->model("Zeapps_projects", "projects");
        $this->load->model("Zeapps_project_cards", "cards");
        $this->load->model("Zeapps_project_deadlines", "deadlines");
        $this->load->model("Zeapps_project_statuses", "statuses");
        $this->load->model("Zeapps_project_timers", "timers");

        $where = array();

        if($projects = $this->projects->all($where, 'false', 'false')){
            foreach($projects as $project){
                if($deadline = $this->deadlines->get_nextOf($project->id))
                    $project->nextDeadline = $deadline[0]->due_date;
                else
                    $project->nextDeadline = 0;

                $project->time_spent = $this->timers->getTimeOfProject($project->id);
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

        $where = [];

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

    public function comment(){
        $this->load->model("Zeapps_project_comments", "comments");

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        if($data['id']){
            $this->comments->update($data, $data['id']);
            $id = $data['id'];
        }
        else{
            $id = $this->comments->insert($data);
        }

        $comment = $this->comments->get($id);

        echo json_encode($comment);
    }

    public function del_comment($id){
        $this->load->model("Zeapps_project_comments", "comments");

        echo json_encode($this->comments->delete($id));
    }

    public function uploadDocuments($id_project = null){
        if($id_project) {
            $this->load->model("Zeapps_project_documents", "documents");

            $data = $_POST;
            $files = $_FILES['files'];
            if($files) {
                if($data['path']){
                    unlink($data['path']);
                }

                $data['id_project'] = $id_project;

                $path = '/assets/upload/project/projects/';

                $time = time();

                $year = date('Y', $time);
                $month = date('m', $time);
                $day = date('d', $time);
                $hour = date('H', $time);

                $data['created_at'] = $year . '-' . $month . '-' . $day;

                $path .= $year . '/' . $month . '/' . $day . '/' . $hour . '/';

                recursive_mkdir(FCPATH . $path);

                $arr = explode(".", $files["name"][0]);
                $extension = end($arr);

                $data['path'] = $path . ltrim(str_replace(' ', '', microtime()), '0.') . "." . $extension;

                move_uploaded_file($files["tmp_name"][0], FCPATH . $data['path']);

                if ($data['id']) {
                    $this->documents->update($data, $data['id']);
                } else {
                    $data['id'] = $this->documents->insert($data);
                }
                $data['date'] = date('Y-m-d H:i:s');

                echo json_encode($data);
            }
            else{
                if ($data['id']) {
                    $this->documents->update($data, $data['id']);

                    $data['date'] = date('Y-m-d H:i:s');

                    echo json_encode($data);
                }
                else {
                    echo json_encode(false);
                }
            }
        }
        else {
            echo json_encode(false);
        }
    }

    public function del_document($id){
        $this->load->model("Zeapps_project_documents", "documents");

        if($document = $this->documents->get($id)){
            unlink($document->path);

            $this->documents->delete($id);
        }

        echo 'OK';
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
                $arr[] = $child->id;
                $ret = $this->_getChildsOf($child->id);
                $arr = array_merge($arr, $ret);
            }
        }

        return array_unique($arr);

    }
}

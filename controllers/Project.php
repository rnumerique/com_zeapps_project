<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends ZeCtrl
{
    public function index(){
        $this->load->view('project/view');
    }

    public function partial(){
        $this->load->view('project/partial');
    }

    public function overview(){
        $this->load->view('project/list');
    }

    public function archives(){
        $this->load->view('project/archives');
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

    public function quotes(){
        $this->load->view('quotes/partial');
    }

    public function invoices(){
        $this->load->view('invoices/partial');
    }

    public function timers(){
        $this->load->view('timer/partial');
    }

    public function spendings(){
        $this->load->view('spendings/partial');
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
        $this->load->model("Zeapps_project_quotes", "quotes");
        $this->load->model("Zeapps_project_invoices", "invoices");
        $this->load->model("Zeapps_project_spendings", "spendings");

        if($id) {
            $whereD = array('zeapps_project_deadlines.id_project' => $id);
        }

        $project = $this->projects->get($id);

        if(!$timers = $this->timers->all(array('zeapps_project_timers.id_project' => $id))){
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

        if(!$quotes = $this->quotes->all(array('id_project' => $id))){
            $quotes = [];
        }

        if(!$spendings = $this->spendings->all(array('id_project' => $id))){
            $spendings = [];
        }

        if(!$invoices = $this->invoices->all(array('id_project' => $id))){
            $invoices = [];
        }

        if(!$priorities = $this->priorities->all()){
            $priorities = [];
        }

        if(!$project_users = $this->rights->all(array('id_project' => $id))){
            $project_users = [];
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
                'deadlines' => $deadlines,
                'quotes' => $quotes,
                'invoices' => $invoices,
                'spendings' => $spendings
            )
        );
    }

    public function get_calendar($id){
        $this->load->model("Zeapps_project_cards", "cards");
        $this->load->model("Zeapps_project_deadlines", "deadlines");

        if($id) {
            $whereC = array('zeapps_project_cards.id_project' => $id);
            $whereD = array('zeapps_project_deadlines.id_project' => $id);
        }

        if(!$cards = $this->cards->all($whereC, true)) {
            $cards = [];
        }
        if(!$deadlines = $this->deadlines->all($whereD)) {
            $deadlines = [];
        }

        echo json_encode(
            array(
                'cards' => $cards,
                'deadlines' => $deadlines
            )
        );
    }

    public function update_project($id = 0){
        $this->load->model("Zeapps_projects", "projects");
        $this->load->model("Zeapps_project_timers", "timers");
        $this->load->model("Zeapps_project_spendings", "spendings");

        $time_spent = $this->timers->getTimeOfProject($id);
        $total_hourly = $this->timers->getHourlyPriceOfProject($id);
        $spendings = $this->spendings->getTotalProject($id);

        $this->projects->update(array(
            'total_time_spent' => $time_spent,
            'total_spendings' => floatval($total_hourly) + floatval($spendings)
        ), $id);

        if(!$timers = $this->timers->all(array('zeapps_project_timers.id_project' => $id))){
            $timers = [];
        }

        echo json_encode(
            array(
                'spendings' => $spendings,
                'total_hourly' => $total_hourly,
                'time_spent' => $time_spent,
                'timers' => $timers
            )
        );
    }

    public function get_projects(){
        $this->load->model("Zeapps_projects", "projects");
        $this->load->model("Zeapps_project_categories", "categories");
        if($projects = $this->projects->all()){
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

        if($projects = $this->projects->all()){
            foreach($projects as $project){
                /*if($deadline = $this->deadlines->get_nextOf($project->id)) {
                    if ($project->due_date && $project->due_date != '0000-00-00') {
                        if (strtotime($project->due_date) < strtotime($deadline[0]->due_date)) {
                            $project->nextDeadline = $project->due_date;
                        } else {
                            $project->nextDeadline = $deadline[0]->due_date;
                        }
                    } else {
                        $project->nextDeadline = $deadline[0]->due_date;
                    }

                } else {
                    if ($project->due_date && $project->due_date != '0000-00-00') {
                        $project->nextDeadline = $project->due_date;
                    } else {
                        $project->nextDeadline = 0;
                    }
                }*/

                if ($project->next_due_date > 0) {
                    $project->nextDeadline = date("Y-m-d", $project->next_due_date);
                } else {
                    $project->nextDeadline = 0 ;
                }
            }
        }

        echo json_encode($projects);
    }

    public function make_excel($status = null, $details = null){
        $this->load->model("Zeapps_projects", "projects");

        $writer = new XLSXWriter();

        if($details){
            $header = array(
                "#",
                "Client",
                "Projet",
                "Responsable",
                "Statut",
                "Montant",
                "Commission",
                "Marge",
                "Déjà facturé",
                "Reste dû",
                "Total dépenses",
                "Temps passé",
                "Prochaine échéance",
                "Note"
            );
        }
        else{
            $header = array(
                "#",
                "Client",
                "Projet",
                "Responsable",
                "Statut",
                "Temps passé",
                "Prochaine échéance",
                "Note"
            );
        }
        $writer->writeSheetRow('Projets', $header);

        $where = [];
        if($status && $status !== "all"){
            $where['id_status'] = $status;
        }

        if ($projects = $this->projects->all($where)) {
            foreach($projects as &$project) {
                $data = array(
                    $project->id,
                    $project->name_company ? $project->name_company : $project->name_contact,
                    $project->title,
                    $project->name_manager,
                    $project->label_status
                );

                if($details){
                    array_push($data, $project->due);
                    array_push($data, $project->commission);
                    array_push($data, $project->due - $project->commission);
                    array_push($data, $project->payed);
                    array_push($data, $project->due - $project->payed);
                    array_push($data, $project->total_spendings);
                }

                var_dump($project);

                if($project->total_time_spent) {
                    $time_spent_formatted = intval($project->total_time_spent / 60).'h '.intval($project->total_time_spent % 60);
                }
                else{
                    $time_spent_formatted = '0h';
                }

                array_push($data, $time_spent_formatted);
                array_push($data, $project->next_due_date ? date('d/m/Y',$project->next_due_date) : "");
                array_push($data, $project->annotations);

                $writer->writeSheetRow('Projets', $data);
            }
        }

        recursive_mkdir(FCPATH . 'tmp/com_zeapps_project/projects/');
        $writer->writeToFile(FCPATH . 'tmp/com_zeapps_project/projects/projects.xlsx');

        echo json_encode(true);
    }

    public function get_excel(){
        $file_url = FCPATH . 'tmp/com_zeapps_project/projects/projects.xlsx';

        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");
        readfile($file_url);
    }

    public function get_archives(){
        $this->load->model("Zeapps_projects", "projects");
        $this->load->model("Zeapps_project_cards", "cards");
        $this->load->model("Zeapps_project_deadlines", "deadlines");
        $this->load->model("Zeapps_project_statuses", "statuses");
        $this->load->model("Zeapps_project_timers", "timers");

        if($projects = $this->projects->all_archived()){
            foreach($projects as $project){
                $project->nextDeadline = 0;
            }
        }

        echo json_encode($projects);
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
            if($this->deadlines->all(array('id_project' => $id)) || $this->cards->all(array('id_project' => $id))){
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
}

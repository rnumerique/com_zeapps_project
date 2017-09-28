<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Card extends ZeCtrl
{
    private $pdf_path = "tmp/com_zeapps_project/cards/";

    public function form(){
        $this->load->view('card/form');
    }

    public function modal(){
        $this->load->view('card/modal');
    }

    public function modal_select(){
        $this->load->view('card/modal_select');
    }

    public function modal_detail(){
        $this->load->view('card/modal_detail');
    }



    public function get_cards($id = 0, $step = null){
        $this->load->model("Zeapps_project_cards", "cards");
        $this->load->model("Zeapps_project_card_comments", "comments");
        $this->load->model("Zeapps_project_card_documents", "documents");

        if($id)
            $where = array('zeapps_project_cards.id_project' => $id);
        else
            $where = array();

        if($step){
            $where['zeapps_project_cards.step'] = $step;

            if(!$cards = $this->cards->all($where)) {
                $cards = [];
            }
        }
        elseif($step === "0"){
            if(!$cards = $this->cards->all($where, true)) {
                $cards = [];
            }
        }
        else{
            if(!$cards = $this->cards->all($where)) {
                $cards = [];
            }
        }

        $dates = [];
        if($dates_tmp = $this->cards->get_dates($where)) {
            foreach ($dates_tmp as $date) {
                if ( ! in_array($date, $dates)) {
                    $dates[] = $date;
                }
            }
        }

        echo json_encode(array(
            "cards" => $cards,
            "dates" => $dates
        ));
    }

    public function get_card($id){
        $this->load->model("Zeapps_project_cards", "cards");
        $this->load->model("Zeapps_project_rights", "rights");
        $this->load->model("Zeapps_project_categories", "categories");
        $this->load->model("Zeapps_project_card_comments", "comments");
        $this->load->model("Zeapps_project_card_documents", "documents");
        $this->load->model("Zeapps_project_card_priorities", "priorities");
        $this->load->model("Zeapps_project_timers", "timers");

        if($card = $this->cards->get($id)) {
            $card = $card[0];

            if(!$project_users = $this->rights->all(array('id_project' => $card->id_project))){
                $project_users = [];
            }

            if(!$categories = $this->categories->all(array('id_project' => $card->id_project))){
                $categories = [];
            }

            $card->total_time_spent = $this->timers->getTimeOfCard($card->id);
        }
        else{
            $project_users = [];
        }

        if(!$comments = $this->comments->all(array('id_card' => $id)))
            $comments = [];
        if(!$documents = $this->documents->all(array('id_card' => $id)))
            $documents = [];
        if(!$timers = $this->timers->all(array('id_card' => $id)))
            $timers = [];

        if(!$priorities = $this->priorities->all()){
            $priorities = [];
        }

        echo json_encode(array(
            'card' => $card,
            'priorities' => $priorities,
            'project_users' => $project_users,
            'categories' => $categories,
            'comments' => $comments,
            'documents' => $documents,
            'timers' => $timers,
        ));
    }

    public function save_card(){
        $this->load->model("Zeapps_projects", "projects");
        $this->load->model("Zeapps_project_cards", "cards");
        $this->load->model("Zeapps_users", "users");
        $this->load->model("Zeapps_notifications", "notifications");

        $notification = array(
            'module' => 'Projects',
            'color' => 'rgba(0,128,0,1)',
            'status' => 'info'
        );

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        if(isset($data['id'])){
            $id = $this->cards->update($data, array('id'=>$data['id']));
            if($user = $this->users->getUserByToken($this->session->get('token'))){
                if($project = $this->projects->get($data['id_project'])){
                    if($user->id !== $project->id_manager){
                        $notification['id_user'] = $project->id_manager;
                        $notification['url'] = "/ng/com_zeapps_project/project/" . $project->id;
                        $notification['message'] = 'La tâche n°'.$data['id'].' a été mise à jour dans le projet '.$project->title;
                        $this->notifications->insert($notification);
                    }
                    if($user->id !== $data['id_assigned_to']){
                        $notification['id_user'] = $data['id_assigned_to'];
                        $notification['url'] = "/ng/com_zeapps_project/project/" . $project->id;
                        $notification['message'] = 'La tâche n°'.$data['id'].' a été mise à jour dans le projet '.$project->title;
                        $this->notifications->insert($notification);
                    }
                }
            }
        }
        else{
            $id = $this->cards->insert($data);
            if($user = $this->users->getUserByToken($this->session->get('token'))){
                if($project = $this->projects->get($data['id_project'])){
                    if($user->id !== $project->id_manager){
                        $notification['id_user'] = $project->id_manager;
                        $notification['url'] = "/ng/com_zeapps_project/project/" . $project->id;
                        $notification['message'] = 'La tâche n°'.$data['id'].' vient d\'être ajoutée dans le projet '.$project->title;
                        $this->notifications->insert($notification);
                    }
                    if($user->id !== $data['id_assigned_to']){
                        $notification['id_user'] = $data['id_assigned_to'];
                        $notification['url'] = "/ng/com_zeapps_project/project/" . $project->id;
                        $notification['message'] = 'La tâche n°'.$data['id'].' vient d\'être ajoutée dans le projet '.$project->title;
                        $this->notifications->insert($notification);
                    }
                }
            }
        }

        echo json_encode($id);
    }

    public function delete_card($id = null){

        if($id){
            $this->load->model("Zeapps_project_cards", "cards");

            $this->cards->delete(array('id' => $id));
        }

        echo json_encode('OK');
    }

    public function comment(){
        $this->load->model("Zeapps_project_card_comments", "comments");

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
        $this->load->model("Zeapps_project_card_comments", "comments");

        echo json_encode($this->comments->delete($id));
    }

    public function uploadDocuments($id_card = null){
        if($id_card) {
            $this->load->model("Zeapps_project_card_documents", "documents");

            $data = $_POST;
            $files = $_FILES['files'];
            if($files) {
                if($data['path']){
                    unlink($data['path']);
                }

                $data['id_card'] = $id_card;

                $path = '/assets/upload/project/cards/';

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
        $this->load->model("Zeapps_project_card_documents", "documents");

        if($document = $this->documents->get($id)){
            unlink($document->path);

            $this->documents->delete($id);
        }

        echo 'OK';
    }

    public function makePDF($id_project, $description = false, $step = null, $echo = true){
        $this->load->model("Zeapps_projects", "projects");
        $this->load->model("Zeapps_project_cards", "cards");

        $data = [];

        $data['project'] = $this->projects->get($id_project);

        $data['description'] = $description;

        $data['dates'] = $this->cards->get_dates();
        $data['cardsByDate'] = [];

        $where = array('zeapps_project_cards.id_project'=>$id_project);

        if($step){
            $where['zeapps_project_cards.step'] = $step;
            $cards = $this->cards->all($where);
        }
        else{
            $cards = $this->cards->all($where, true);
        }

        if($cards){
            foreach($cards as $card){
                if(!is_array($data['cardsByDate'][$card->due_date])){
                    $data['cardsByDate'][$card->due_date] = [];
                }
                array_push($data['cardsByDate'][$card->due_date], $card);
            }
        }

        //load the view and saved it into $html variable
        $html = $this->load->view('card/PDF', $data, true);

        $nomPDF = $data['project']->title;
        $nomPDF = preg_replace('/\W+/', '_', $nomPDF);
        $nomPDF = trim($nomPDF, '_');

        recursive_mkdir(FCPATH . $this->pdf_path);

        //this the the PDF filename that user will get to download
        $pdfFilePath = FCPATH . $this->pdf_path.$nomPDF.'.pdf';

        //set the PDF header
        $this->M_pdf->pdf->SetHeader('#'.$data['project']->id.'|'.$data['project']->title.'|Imprimé le {DATE d/m/Y}');

        //set the PDF footer
        $this->M_pdf->pdf->SetFooter('{PAGENO}/{nb}');

        //generate the PDF from the given html
        $this->M_pdf->pdf->WriteHTML($html);

        //download it.
        $this->M_pdf->pdf->Output($pdfFilePath, "F");

        if($echo)
            echo json_encode($nomPDF);

        return $nomPDF;
    }

    public function getPDF($nomPDF){
        $file_url = FCPATH . $this->pdf_path.$nomPDF.'.pdf';
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");
        readfile($file_url);
        unlink($file_url);
    }
}
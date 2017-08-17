<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Timer extends ZeCtrl
{
    private $pdf_path = "tmp/com_zeapps_project/timers/";

    public function hook(){
        $this->load->view('timer/hook');
    }
    public function directive(){
        $this->load->view('timer/directive');
    }
    public function modal(){
        $this->load->view('timer/modal');
    }





    public function get($id){
        $this->load->model("Zeapps_project_timers", "timer");

        $timer = $this->timer->get($id);

        echo json_encode($timer);
    }

    public function get_all($id_project = false){
        $this->load->model("Zeapps_project_timers", "timer");

        $where = [];
        if($id_project){
            $where['id_project'] = $id_project;
        }

        $timers = $this->timer->all($where);

        echo json_encode($timers);
    }

    public function get_ongoing(){
        $this->load->model("Zeapps_project_timers", "timer");

        $timer = $this->timer->ongoing();

        echo json_encode($timer);
    }

    public function save(){
        $this->load->model("Zeapps_project_timers", "timer");

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        if(isset($data['id'])){
            $this->timer->update($data, array('id'=>$data['id']));
            $id = $data['id'];
        }
        else{
            $id = $this->timer->insert($data);
        }

        echo $id;
    }

    public function delete($id){
        $this->load->model("Zeapps_project_timers", "timer");

        if($this->timer->delete($id))
            echo json_encode(true);
        else
            echo json_encode(false);
    }

    public function makePDF($id_project, $echo = true){
        $this->load->model("Zeapps_projects", "projects");
        $this->load->model("Zeapps_project_timers", "timers");

        $data = [];

        $data['project'] = $this->projects->get($id_project);
        $data['project']->total_time_spent = 0;

        $data['costs'] = [];
        if($data['timers'] = $this->timers->all(array('zeapps_project_timers.id_project' => $id_project))){
            foreach($data['timers'] as $timer){
                $data['project']->total_time_spent += intval($timer->time_spent);

                $timer->time_spent_formatted = intval($timer->time_spent / 60) . 'h ' . str_pad(($timer->time_spent % 60) ? : '', 2, '0', STR_PAD_LEFT);
                if(!isset($data['costs'][$timer->id_user])){
                    $data['costs'][$timer->id_user] = [];
                    $data['costs'][$timer->id_user]['time_spent'] = 0;
                    $data['costs'][$timer->id_user]['name_user'] = $timer->name_user;
                    $data['costs'][$timer->id_user]['hourly_rate'] = floatval($timer->hourly_rate);
                }
                $data['costs'][$timer->id_user]['time_spent'] += intval($timer->time_spent);
            }
        }

        $data['project']->total_time_spent_formatted = intval($data['project']->total_time_spent / 60) . 'h ' . str_pad(($data['project']->total_time_spent % 60) ? : '', 2, '0', STR_PAD_LEFT);
        $data['project']->total_cost = 0;

        foreach($data['costs'] as &$cost){
            $cost['total'] = $cost['time_spent'] / 60 * $cost['hourly_rate'];
            $data['project']->total_cost += $cost['total'];
            $cost['time_spent_formatted'] = intval($cost['time_spent'] / 60) . 'h ' . str_pad(($cost['time_spent'] % 60) ? : '', 2, '0', STR_PAD_LEFT);
        }

        //load the view and saved it into $html variable
        $html = $this->load->view('timer/PDF', $data, true);

        $nomPDF = $data['project']->title;
        $nomPDF = preg_replace('/\W+/', '_', $nomPDF);
        $nomPDF = trim($nomPDF, '_');

        recursive_mkdir(FCPATH . $this->pdf_path);

        //this the the PDF filename that user will get to download
        $pdfFilePath = FCPATH . $this->pdf_path.$nomPDF.'.pdf';

        $id = $data['project']->id;
        $client = $data['project']->name_company ?: $data['project']->name_contact;
        $title = $data['project']->title;

        //set the PDF header
        $this->M_pdf->pdf->SetHeader('#'.$id.'|'.$client.' - '.$title.'|Imprimé le {DATE d/m/Y}');

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
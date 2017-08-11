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

    public function makePDF($id_project, $description = false, $echo = true){
        $this->load->model("Zeapps_projects", "projects");
        $this->load->model("Zeapps_project_timers", "timers");

        $data = [];

        $data['project'] = $this->projects->get($id_project);

        $data['description'] = $description;

        if($data['timers'] = $this->timers->all(array('id_project' => $id_project))){
            foreach($data['timers'] as $timer){
                $timer->time_spent_formatted = intval($timer->time_spent / 60) . 'h ' . (($timer->time_spent % 60) ? : '');
            }
        }

        //load the view and saved it into $html variable
        $html = $this->load->view('timer/PDF', $data, true);

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
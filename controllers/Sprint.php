<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sprint extends ZeCtrl
{
    public function view()
    {
        $this->load->view('sprint/view');
    }

    public function detail(){
        $this->load->view('sprint/detail');
    }

    public function form(){
        $this->load->view('sprint/form');
    }

    public function formCard(){
        $this->load->view('sprint/formCard');
    }

    public function modal_sprint(){
        $this->load->view('sprint/modalSprint');
    }



    public function get_sprint($id){
        $this->load->model("Zeapps_project_sprints", "sprints");

        $sprint = $this->sprints->get($id);

        echo json_encode($sprint);
    }

    public function get_sprints($id_project = null){
        $this->load->model("Zeapps_project_sprints", "sprints");
        $this->load->model("Zeapps_users", "user");

        if($id_project)
            $where = array('id_project' => $id_project);
        else
            $where = array();

        if($user = $this->user->getUserByToken($this->session->get('token'))){
            $where['zeapps_project_rights.id_user'] = $user[0]->id;
        }

        $sprints = $this->sprints->order_by('completed', 'ASC')->order_by('active', 'ASC')->all($where);

        echo json_encode($sprints);
    }

    public function save_sprint(){
        $this->load->model("Zeapps_project_sprints", "sprints");

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        if(isset($data['id'])){
            $this->sprints->update($data, $data['id']);
            $id = $data['id'];
        }
        else{
            $id = $this->sprints->insert($data);
        }

        echo $id;
    }

    public function delete_sprint($id = null){
        $this->load->model("Zeapps_project_sprints", "sprints");
        $this->load->model("Zeapps_project_cards", "cards");

        $this->sprints->delete($id);

        $this->cards->update(array('step' => 1, 'id_sprint' => 0), array('id_sprint' => $id));

        echo json_encode('OK');
    }

    public function updateCardsOf($id){
        $this->load->model("Zeapps_project_cards", "cards");

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        $this->cards->updateStateOf($data, $id);

        echo json_encode('OK');
    }

    public function finalize_sprint($id, $next){
        $this->load->model("Zeapps_project_sprints", "sprints");
        $this->load->model("Zeapps_project_cards", "cards");

        $sprint = $this->sprints->get($id);

        $this->sprints->update(array('active' => 'N', 'completed' => 'Y'), $sprint->id);
        $this->cards->update(array('completed' => 'Y'), array('id_sprint' => $sprint->id, 'step' => 5));

        if($next === 'true') {

            if ($new_sprint = $this->sprints->all(array('id_project' => $sprint->id_project, 'numerotation >' => $sprint->numerotation))) {
                $new_sprint = $new_sprint[0];
                $this->sprints->update(array('active' => 'Y'), $new_sprint->id);
            } else {
                $due_date_prev = new DateTime($sprint->due_date);
                $due_date_prev->modify('+1 day');
                $start_date = $due_date_prev->format('Y-m-d');
                $due_date_prev->modify('+15 day');
                $due_date = $due_date_prev->format('Y-m-d');
                $new_sprint = array(
                    'id_project' => $sprint->id_project,
                    'active' => 'Y',
                    'completed' => 'N',
                    'start_date' => $start_date,
                    'due_date' => $due_date
                );
                $id = $this->sprints->insert($new_sprint);
                $new_sprint = $this->sprints->get($id);
            }

            $this->cards->update(array('step' => 2, 'id_sprint' => $new_sprint->id), array('id_sprint' => $sprint->id, 'step <' => 5));

            echo $new_sprint->id;
        }
        else{
            echo "OK";
        }
    }



    public function get_filters(){
        $this->load->model("Zeapps_projects", "projects");
        $this->load->model("Zeapps_project_cards", "cards");
        $this->load->model("Zeapps_project_deadlines", "deadlines");

        $companies = $this->projects->get_companies();
        $managers = $this->projects->get_managers();

        $dates_tmp = $this->cards->get_dates();
        $dates_merged = array_merge($dates_tmp, $this->deadlines->get_dates());
        $dates = [];

        foreach ($dates_merged as $date) {
            if ( ! in_array($date, $dates)) {
                $dates[] = $date;
            }
        }

        $assigned = $this->cards->get_assigned();

        echo json_encode(array('companies' => $companies, 'managers' => $managers, 'dates' => $dates, 'assigned' => $assigned));
    }
}
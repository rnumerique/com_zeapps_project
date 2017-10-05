<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Right extends ZeCtrl
{
    public function get_rights($id_project = 0){
        $this->load->model("Zeapps_project_rights", "rights");

        $where = array('id_project' => $id_project);

        $rights = $this->rights->all($where);

        echo json_encode($rights);
    }

    public function get_right($id_user){
        $this->load->model("Zeapps_project_rights", "rights");

        $right = $this->rights->get(array('id_user' => $id_user));

        echo json_encode($right);
    }

    public function get_connected(){
        $this->load->model("Zeapps_project_rights", "rights");
        $this->load->model("Zeapps_users", "user");

        $user = $this->user->getUserByToken($this->session->get('token'));
        $rows = $this->rights->all(array('id_user' => $user->id));

        $rights = array();

        foreach($rows as $row){
            $rights[$row->id_project] = $row;
        }

        echo json_encode($rights);
    }

    public function save_right(){
        $this->load->model("Zeapps_project_rights", "rights");
        $this->load->model("Zeapps_project_hourly_rates", "hourly_rates");

        // constitution du tableau
        $data = array() ;

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $data = json_decode(file_get_contents('php://input'), true);
        }

        if(isset($data['id'])) {
            $this->rights->update($data, $data['id']);
            $id = $data['id'];
        }
        else{
            $id = $this->rights->insert($data);
            if(!$user = $this->hourly_rates->get(array('id_project' => $data['id_project'], 'id_user' => $data['id_user']))) {
                $this->hourly_rates->insert(array(
                    'id_project' => $data['id_project'],
                    'id_user' => $data['id_user']
                ));
            }
        }

        echo $id;
    }

    public function delete_right($id = null){
        $this->load->model("Zeapps_project_rights", "rights");

        echo json_encode($this->rights->delete($id));
    }
}
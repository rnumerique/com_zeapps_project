<?php
class Zeapps_project_card_comments extends ZeModel {

    public function insert($objData = null){
        $this->load->model('Zeapps_users', 'users');

        if($user = $this->load->ctrl->users->getUserByToken($this->load->ctrl->session->get('token'))){
            $objData['id_user'] = $user[0]->id;
            $objData['name_user'] = $user[0]->firstname[0] . '. ' . $user[0]->lastname;
        }

        $objData['date'] = date('Y-m-d');

        return parent::insert($objData);
    }

}
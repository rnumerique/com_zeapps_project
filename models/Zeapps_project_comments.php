<?php
class Zeapps_project_comments extends ZeModel {

    public function insert($objData = null){
        $this->_pLoad->model('Zeapps_users', 'users');

        if($user = $this->_pLoad->ctrl->users->getUserByToken($this->_pLoad->ctrl->session->get('token'))){
            $objData['id_user'] = $user[0]->id;
            $objData['name_user'] = $user[0]->firstname[0] . '. ' . $user[0]->lastname;
        }

        $objData['date'] = date('Y-m-d H:i:s');

        return parent::insert($objData);
    }

    public function update($objData = null, $where = null){
        $objData['date'] = date('Y-m-d H:i:s');

        return parent::update($objData, $where);
    }

}
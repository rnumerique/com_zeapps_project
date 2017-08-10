<?php
class Zeapps_project_todos extends ZeModel {
    public function all($where = array()){
        $this->_pLoad->model('Zeapps_users', 'users');

        if($user = $this->_pLoad->ctrl->users->getUserByToken($this->_pLoad->ctrl->session->get('token'))){
            $where['zeapps_project_todos.id_user'] = $user->id;
        }

        return parent::all($where);
    }

    public function insert($data = array()){
        $this->_pLoad->model('Zeapps_users', 'users');

        if($user = $this->_pLoad->ctrl->users->getUserByToken($this->_pLoad->ctrl->session->get('token'))){
            $data['id_user'] = $user->id;
        }

        return parent::insert($data);
    }
}
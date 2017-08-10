<?php
class Zeapps_project_rights extends ZeModel {
    public function insert($data = null){
        $this->_pLoad->model('Zeapps_users', 'users');

        if($data['id_user']) {
            if ($user = $this->_pLoad->ctrl->users->get($data['id_user'])) {
                $data['hourly_rate'] = $user->hourly_rate;
            }
        }

        return parent::insert($data);
    }
}
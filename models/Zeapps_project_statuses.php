<?php
class Zeapps_project_statuses extends ZeModel {
    public function delete($where, $forceDelete = false)
    {
        $this->_pLoad->model("Zeapps_projects", "projects");

        if(is_numeric($where)){
            $id = $where;
        }
        else{
            $id = $where['id'];
        }

        if($projects = $this->_pLoad->ctrl->projects->all(array('id_status' => $id))){
            foreach($projects as $project){
                $this->_pLoad->ctrl->projects->update(array('id_status' => 0), $project->id);
            }
        }

        return parent::delete($where, $forceDelete);
    }
}
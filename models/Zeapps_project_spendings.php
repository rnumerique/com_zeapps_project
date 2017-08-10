<?php
class Zeapps_project_spendings extends ZeModel {
    public function getTotalProject($id_project){
        $where = [
            'id_project' => $id_project,
            'zeapps_project_spendings.deleted_at' => null
        ];

        $ret = $this->database()->select('SUM(total) as total_spendings')
            ->where($where)
            ->table('zeapps_project_spendings')
            ->result();

        if($ret && $ret[0]->total_spendings) {
            return $ret[0]->total_spendings;
        }
        else{
            return 0;
        }
    }
}
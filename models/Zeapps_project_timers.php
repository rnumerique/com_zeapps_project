<?php
class Zeapps_project_timers extends ZeModel {

    public function get($where = array()){

        if(is_array($where))
            $where['Zeapps_project_timers.deleted_at'] = null;
        elseif(is_numeric($where)){
            $where = [
                'Zeapps_project_timers.id' => $where,
                'Zeapps_project_timers.deleted_at' => null
            ];
        }

        $ret = $this->database()->select('Zeapps_project_timers.*, TIMESTAMPDIFF(MINUTE,Zeapps_project_timers.start_time,Zeapps_project_timers.stop_time) as duration')
            ->where($where)
            ->table('Zeapps_project_timers')
            ->limit(1)
            ->result();

        if(isset($ret) && is_array($ret) && sizeof($ret) > 0) {
            return $ret[0];
        }
        else{
            return false;
        }
    }

    public function all($where = array()){
        $where['Zeapps_project_timers.deleted_at'] = null;

        $ret = $this->database()->select('Zeapps_project_timers.*, TIMESTAMPDIFF(MINUTE,Zeapps_project_timers.start_time,Zeapps_project_timers.stop_time) as duration')
            ->where($where)
            ->table('Zeapps_project_timers')
            ->result();

        if(isset($ret) && is_array($ret) && sizeof($ret) > 0) {
            return $ret;
        }
        else{
            return false;
        }
    }

    public function getTimeOf($id_project){
        $where = [
            'id_project' => $id_project,
            'deleted_at' => null
        ];

        $ret = $this->database()->select('SUM(TIMESTAMPDIFF(MINUTE,Zeapps_project_timers.start_time,Zeapps_project_timers.stop_time)) as total_time')
            ->where($where)
            ->table('Zeapps_project_timers')
            ->result();

        if($ret) {
            return $ret[0]->total_time;
        }
        else{
            return 0;
        }
    }
}
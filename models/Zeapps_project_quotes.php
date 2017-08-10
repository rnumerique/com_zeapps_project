<?php
class Zeapps_project_quotes extends ZeModel {

    public function all($where = array()){
        $where['zeapps_project_quotes.deleted_at'] = null;

        return $this->database()->select('zeapps_quotes.*,
                                        zeapps_project_quotes.id as id, 
                                        zeapps_project_quotes.id_quote as id_quote')
            ->join('zeapps_quotes', 'zeapps_project_quotes.id_quote = zeapps_quotes.id', 'LEFT')
            ->where($where)
            ->table('zeapps_project_quotes')
            ->result();
    }

    public function get($where = array()){
        $where['zeapps_project_quotes.deleted_at'] = null;

        return $this->database()->select('zeapps_quotes.*,
                                        zeapps_project_quotes.id as id, 
                                        zeapps_project_quotes.id_quote as id_quote')
            ->join('zeapps_quotes', 'zeapps_project_quotes.id_quote = zeapps_quotes.id', 'LEFT')
            ->where($where)
            ->table('zeapps_project_quotes')
            ->result();
    }

}
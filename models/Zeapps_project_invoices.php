<?php
class Zeapps_project_invoices extends ZeModel {

    public function all($where = array()){
        $where['zeapps_project_invoices.deleted_at'] = null;

        return $this->database()->select('zeapps_invoices.*,
                                        zeapps_project_invoices.id as id, 
                                        zeapps_project_invoices.id_invoice as id_invoice')
            ->join('zeapps_invoices', 'zeapps_project_invoices.id_invoice = zeapps_invoices.id', 'LEFT')
            ->where($where)
            ->where_not(array('zeapps_project_invoices.id' => null))
            ->group_by('zeapps_project_invoices.id')
            ->table('zeapps_project_invoices')
            ->result();
    }

    public function get($where = array()){
        $where['zeapps_project_invoices.deleted_at'] = null;

        return $this->database()->select('zeapps_invoices.*,
                                        zeapps_project_invoices.id as id, 
                                        zeapps_project_invoices.id_invoice as id_invoice')
            ->join('zeapps_invoices', 'zeapps_project_invoices.id_invoice = zeapps_invoices.id', 'LEFT')
            ->where($where)
            ->where_not(array('zeapps_project_invoices.id' => null))
            ->group_by('zeapps_project_invoices.id')
            ->table('zeapps_project_invoices')
            ->result();
    }

}
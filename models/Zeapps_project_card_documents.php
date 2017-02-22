<?php
class Zeapps_project_card_documents extends ZeModel {

    public function insert($objData = null){
        $objData['date'] = date('Y-m-d');

        return parent::insert($objData);
    }

}
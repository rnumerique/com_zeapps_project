<?php
class Zeapps_project_categories extends ZeModel {

    public function __construct()
    {
        parent::__construct();

        $this->soft_deletes = TRUE;
    }
}
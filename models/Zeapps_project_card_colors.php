<?php
class Zeapps_project_card_colors extends ZeModel {

    public function __construct()
    {
        parent::__construct();

        $this->soft_deletes = TRUE;
    }
}
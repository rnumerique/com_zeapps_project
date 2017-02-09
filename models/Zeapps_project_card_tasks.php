<?php
class Zeapps_project_card_tasks extends ZeModel {

    public function get_all($where = array()){
        $where['deleted_at'] = null;
        $tasks = $this->database()->select('*, zeapps_project_card_tasks.id as id, zeapps_project_categories.label as category')
            ->join('zeapps_project_cards', 'zeapps_project_card_tasks.id_card = zeapps_project_cards.id', 'LEFT')
            ->join('zeapps_project_categories', 'zeapps_project_categories.id = zeapps_project_card_tasks.id_category', 'LEFT')
            ->table('zeapps_project_card_tasks')->result();

        $ids = [];

        foreach($tasks as $task){
            array_push($ids, $task->id);
        }

        $colors = $this->database()->select('id_card, label, value')
            ->join('zeapps_config_colors', 'zeapps_config_colors.id = zeapps_project_card_colors.id_color', 'LEFT')
            ->where(array('id_card' => $ids))
            ->table('zeapps_project_card_colors')->result();

        foreach($tasks as $task){
            foreach($colors as $key => $color){
                if($task->id === $color->id_card){
                    unset($color->id_card);
                    $task->color = $color;
                    unset($colors[$key]);
                }
            }
        }

        return $tasks;
    }
}
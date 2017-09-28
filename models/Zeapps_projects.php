<?php

class Zeapps_projects extends ZeModel
{

    public function get_companies()
    {
        return $this->database()->select('id_company as id, name_company as label')->group_by('id_company')->where_not(array('id_company' => 0))->table('zeapps_projects')->result();
    }

    public function get_managers()
    {
        return $this->database()->select('id_manager as id, name_manager as label')->group_by('id_manager')->where_not(array('id_manager' => 0))->table('zeapps_projects')->result();
    }

    public function all($where = array())
    {
        $this->_pLoad->model('Zeapps_users', 'users');

        $user = $this->_pLoad->ctrl->users->getUserByToken($this->_pLoad->ctrl->session->get('token'));

        $where['zeapps_projects.archived'] = null;
        $where['zeapps_projects.deleted_at'] = null;

        if(!isset($user->rights['com_zeapps_project_sudo']) || !$user->rights['com_zeapps_project_sudo']) {
            $where['zeapps_project_rights.access'] = 1;
            $where['zeapps_project_rights.id_user'] = $user->id;
        }

        return $this->database()->select('*,
                                        zeapps_projects.id as id,
                                        zeapps_projects.breadcrumbs as label,
                                        zeapps_projects.breadcrumbs as breadcrumbs,
                                        zeapps_project_statuses.label as label_status')
            ->join('zeapps_project_rights', 'zeapps_project_rights.id_project = zeapps_projects.id', 'LEFT')
            ->join('zeapps_project_statuses', 'zeapps_project_statuses.id = zeapps_projects.id_status', 'LEFT')
            ->where($where)
            ->order_by('zeapps_projects.title')
            ->table('zeapps_projects')
            ->result();
    }

    public function all_archived($where = array())
    {
        $this->_pLoad->model('Zeapps_users', 'users');

        $user = $this->_pLoad->ctrl->users->getUserByToken($this->_pLoad->ctrl->session->get('token'));

        $where['zeapps_projects.deleted_at'] = null;
        if(!isset($user->rights['com_zeapps_project_sudo']) || !$user->rights['com_zeapps_project_sudo']) {
            $where['zeapps_project_rights.id_user'] = $user->id;
            $where['zeapps_project_rights.access'] = 1;
        }

        return $this->database()->select('*,
                                        zeapps_projects.id as id,
                                        zeapps_projects.breadcrumbs as label,
                                        zeapps_projects.breadcrumbs as breadcrumbs,
                                        zeapps_project_statuses.label as label_status')
            ->join('zeapps_project_rights', 'zeapps_project_rights.id_project = zeapps_projects.id', 'LEFT')
            ->join('zeapps_project_statuses', 'zeapps_project_statuses.id = zeapps_projects.id_status', 'LEFT')
            ->where($where)
            ->where_not(array('zeapps_projects.archived' => null))
            ->order_by('zeapps_projects.title')
            ->table('zeapps_projects')
            ->result();
    }

    public function insert($data = array())
    {
        $this->_pLoad->model("Zeapps_project_rights", "rights");

        if (isset($data['id_parent']) && $data['id_parent'] > 0) {
            if ($parent = $this->get($data['id_parent'])) {
                $data['spaces'] = intval($parent->spaces) + 1;
            }
            $data['breadcrumbs'] = $this->_generateBreadcrumbsOf($data['id_parent']) . $data['title'];
        } else
            $data['breadcrumbs'] = $data['title'];

        $id = parent::insert($data);

        $this->updateNextDueDate($id);

        if ($data['id_parent']) {
            if ($users = $this->_pLoad->ctrl->rights->all(array('id_project' => $data['id_parent']))) {
                foreach ($users as $user) {
                    unset($user->id);
                    $user->id_project = $id;
                    $this->_pLoad->ctrl->rights->insert($user);
                }
            }
        }

        if ($data['id_manager']) {
            if ($manager = $this->_pLoad->ctrl->rights->get(array('id_project' => $id, 'id_user' => $data['id_manager']))) {
                $this->_pLoad->ctrl->rights->update(array('access' => 1, 'card' => 1, 'accounting' => 1, 'project' => 1), $manager->id);
            } else {
                $this->_pLoad->ctrl->rights->insert(array('id_project' => $id, 'id_user' => $data['id_manager'], 'name' => $data['name_manager'], 'access' => 1, 'card' => 1, 'accounting' => 1, 'project' => 1, 'hourly_rate' => $manager->hourly_rate));
            }
        }

        return $id;
    }

    public function update($data = array(), $where = array())
    {
        $this->_pLoad->model("Zeapps_project_rights", "rights");

        /*if (isset($data['id_parent']) && $data['id_parent'] > 0) {
            if ($parent = parent::get($data['id_parent'])) {
                $data['spaces'] = intval($parent->spaces) + 1;
            }
            $data['breadcrumbs'] = $this->_generateBreadcrumbsOf($data['id_parent']) . $data['title'];
        } else {
            $data['breadcrumbs'] = $data['title'];
        }*/

        //$this->_updateChildsBreadcrumbsOf($data['id'], $data['breadcrumbs']);

        if ($data['id_manager']) {
            if ($manager = $this->_pLoad->ctrl->rights->get(array('id_project' => $data['id'], 'id_user' => $data['id_manager']))) {
                $this->_pLoad->ctrl->rights->update(array('access' => 1, 'card' => 1, 'accounting' => 1, 'project' => 1), $manager->id);
            } else {
                $this->_pLoad->ctrl->rights->insert(array('id_project' => $data['id'], 'id_user' => $data['id_manager'], 'name' => $data['name_manager'], 'access' => 1, 'card' => 1, 'accounting' => 1, 'project' => 1, 'hourly_rate' => $manager->hourly_rate));
            }
        }

        $retourProjectUpdate = parent::update($data, $where);

        // pour mettre à jour la date de prochaine livraison
        $this->updateNextDueDate($data['id']);

        return $retourProjectUpdate;
    }


    public function updateNextDueDate($id_project)
    {
        $this->_pLoad->model("zeapps_project_deadlines", "deadlines");

        $nextDeadLine = 0 ;

        $where = array(
            'id_project' => $id_project,
            'deleted_at' => null
        );



        $deadline = $this->_pLoad->ctrl->deadlines->get_nextOf($id_project);

        if ($deadline && is_array($deadline) && count($deadline) >= 1) {
            if ($deadline[0]->due_date && $deadline[0]->due_date != "0000-00-00") {
                $nextDeadLine = strtotime($deadline[0]->due_date) ;
            }
        }



        // charge le projet
        $projet = $this->get($id_project) ;
        if ($projet) {
            if ($projet->due_date && $projet->due_date != "0000-00-00") {
                if ((strtotime($projet->due_date) < $nextDeadLine) || $nextDeadLine == 0) {
                    $nextDeadLine = strtotime($projet->due_date) ;
                }
            }

            $this->update(array("next_due_date"=>$nextDeadLine) , $projet->id) ;
        }


    }

    public function delete($where, $forceDelete = false)
    {
        $this->_pLoad->model("zeapps_project_deadlines", "deadlines");
        $this->_pLoad->model("zeapps_project_cards", "cards");
        $this->_pLoad->model("Zeapps_project_rights", "rights");

        $subs = $this->all(array('id_parent' => $where), $forceDelete);

        if ($subs) {
            foreach ($subs as $sub) {
                $this->delete($sub->id);
            }
        }

        $now = time();

        $this->_pLoad->ctrl->deadlines->delete(array('id_project' => $where));
        $this->_pLoad->ctrl->cards->delete(array('id_project' => $where));
        $this->_pLoad->ctrl->rights->delete(array('id_project' => $where));

        return parent::delete($where);
    }

    private function _parentChild_sort(&$arr, $parent = 0)
    {

        $res = [];

        if ($arr) {
            foreach ($arr as $key => $elm) {
                if ($elm->id_parent == $parent) {
                    $res[] = $elm;
                    unset($arr[$key]);
                    $res = array_merge($res, $this->_parentChild_sort($arr, $elm->id));
                }
            }
        }

        return $res;

    }

    private function _generateBreadcrumbsOf($id)
    {
        $project = $this->get($id);

        if ($project->id_parent > 0)
            $breadcrumbs = $this->_generateBreadcrumbsOf($project->id_parent);
        else
            $breadcrumbs = '';

        $breadcrumbs .= $project->title . ' : ';

        return $breadcrumbs;
    }

    private function _updateChildsBreadcrumbsOf($id, $breadcrumbs)
    {
        if ($projects = parent::all(array('id_parent' => $id))) {
            foreach ($projects as $project) {
                $project->breadcrumbs = $breadcrumbs . ' : ' . $project->title;
                parent::update($project, $project->id);
                $this->_updateChildsBreadcrumbsOf($project->id, $project->breadcrumbs);
            }
        }
    }
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<table class="table table-condensed table-stripped">
    <thead>
    <tr>
        <th>
            <button type="button" class='btn btn-xs btn-success' ng-click="addProjectUser()">
                <span class='fa fa-plus' aria-hidden='true'></span> Utilisateur
            </button>
        </th>
        <th class="text-center">
            Lecture
        </th>
        <th class="text-center">
            Cartes / Deadlines
        </th>
        <th class="text-center">
            Gestion financière
        </th>
        <th class="text-center">
            Projet
        </th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <tr ng-repeat="user in project_users">
        <td>{{ user.name }}</td>
        <td class="text-center">
            <input type="checkbox" ng-model="user.access" ng-change="changeRights(user, 'access')" ng-disabled="$root.user.id === user.id_user">
        </td>
    </tr>
    </tbody>
</table>
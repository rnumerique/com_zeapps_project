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
        <th class="text-center">
            Taux horaire (€)
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
        <td class="text-center">
            <input type="checkbox" ng-model="user.card" ng-change="changeRights(user, 'card')" ng-disabled="$root.user.id === user.id_user">
        </td>
        <td class="text-center">
            <input type="checkbox" ng-model="user.accounting" ng-change="changeRights(user, 'accounting')" ng-disabled="$root.user.id === user.id_user">
        </td>
        <td class="text-center">
            <input type="checkbox" ng-model="user.project" ng-change="changeRights(user, 'project')" ng-disabled="$root.user.id === user.id_user">
        </td>
        <td class="text-center">
            <input type="number" ng-model="user.hourly_rate" ng-change="saveRightsOf(user)" class="form-control input-sm">
        </td>
        <td class="text-right">
            <button type="button" class="btn btn-xs btn-danger" ng-click="deleteRightsOf(user)" ng-if="$root.user.id !== user.id_user" ze-confirmation>
                <i class="fa fa-fw fa-trash"></i>
            </button>
        </td>
    </tr>
    </tbody>
</table>
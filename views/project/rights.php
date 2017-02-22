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
            Bac à Sable
        </th>
        <th class="text-center">
            Cartes / Deadlines
        </th>
        <th class="text-center">
            Sprint
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
        <td class="text-center">
            <input type="checkbox" ng-model="user.sandbox" ng-change="changeRights(user, 'sandbox')" ng-disabled="$root.user.id === user.id_user">
        </td>
        <td class="text-center">
            <input type="checkbox" ng-model="user.card" ng-change="changeRights(user, 'card')" ng-disabled="$root.user.id === user.id_user">
        </td>
        <td class="text-center">
            <input type="checkbox" ng-model="user.sprint" ng-change="changeRights(user, 'sprint')" ng-disabled="$root.user.id === user.id_user">
        </td>
        <td class="text-center">
            <input type="checkbox" ng-model="user.project" ng-change="changeRights(user, 'project')" ng-disabled="$root.user.id === user.id_user">
        </td>
        <td class="text-right">
            <button type="button" class="btn btn-xs btn-danger" ng-click="deleteRightsOf(user)" ng-if="$root.user.id !== user.id_user">
                <i class="fa fa-fw fa-trash"></i>
            </button>
        </td>
    </tr>
    </tbody>
</table>
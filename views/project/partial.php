<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="text-right">
    <div class="form-group">
        <label for="details">
            <input type="checkbox" id="details" name="details" ng-model="details" value="1">
            Afficher le détail
        </label>
    </div>
</div>
<table class="table table-striped table-hover table-condensed">
    <thead>
    <tr>
        <th>#</th>
        <th>Client</th>
        <th>Projet</th>
        <th>Responsable</th>
        <th>Statut</th>
        <th ng-if="details== 1" class="text-center">Montant</th>
        <th ng-if="details== 1" class="text-center">Commission</th>
        <th ng-if="details== 1" class="text-center">Marge</th>
        <th ng-if="details== 1" class="text-center">Déjà facturé</th>
        <th ng-if="details== 1" class="text-center">Reste dû</th>
        <th ng-if="details== 1" class="text-center">Total dépenses</th>
        <th class="text-center">Temps passé</th>
        <th class="text-right">Deadline</th>
        <th class="text-right"></th>
    </tr>
    </thead>
    <tbody>
    <tr ng-repeat="project in projects | orderBy:['name_company','breadcrumbs'] | projectFilter:filters" ng-click="goTo(project.id)">
        <td>{{ project.id }}</td>
        <td>{{ project.name_company ? project.name_company : project.name_contact }}</td>
        <td>{{ project.breadcrumbs }}</td>
        <td>{{ project.name_manager }}</td>
        <td>{{ project.label_status }}</td>
        <td ng-if="details== 1" class="text-center"><span project-auth="{id_project : project.id, right : 'accounting'}">{{ project.due || '-' | currency }}</span></td>
        <td ng-if="details== 1" class="text-center"><span project-auth="{id_project : project.id, right : 'accounting'}">{{ project.commission || '-' | currency }}</span></td>
        <td ng-if="details== 1" class="text-center"><span project-auth="{id_project : project.id, right : 'accounting'}">{{ (project.due - project.commission) || '-' | currency }}</span></td>
        <td ng-if="details== 1" class="text-center"><span project-auth="{id_project : project.id, right : 'accounting'}">{{ project.payed || '-' | currency }}</span></td>
        <td ng-if="details== 1" class="text-center"><span project-auth="{id_project : project.id, right : 'accounting'}">{{ (project.due - project.payed) || '-' | currency }}</span></td>
        <td ng-if="details== 1" class="text-center"><span project-auth="{id_project : project.id, right : 'accounting'}">{{ project.total_spendings | currency }}</span></td>
        <td class="text-center">
                        <span project-auth="{id_project : project.id, right : 'project'}">
                            {{ project.time_spent_formatted || "0h" }}
                            (<span ng-style="{color: project.timer_color}">{{(project.timer_ratio || "0")+ "%" }}</span>)
                        </span>
        </td>
        <td class="text-right">
            {{ project.nextDeadline || '-' | date:'dd/MM/yyyy' }}
        </td>
        <td class="text-right">
            <button type="button" class="btn btn-info btn-xs" ng-click="edit(project.id, $event)">
                <i class="fa fa-fw fa-pencil" ></i>
            </button>
            <button type="button" class="btn btn-danger btn-xs" ng-click="delete_project(project)" ze-confirmation>
                <i class="fa fa-fw fa-trash" ></i>
            </button>
        </td>
    </tr>
    </tbody>
</table>
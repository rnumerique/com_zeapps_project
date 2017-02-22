<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="breadcrumb">
    Backlog
</div>

<div id="content">
    <div class="row">
        <div class="col-md-11">
            <div class="form-group">
                <select class="form-control" ng-model="options.projectId">
                    <option value="all">
                        Tous
                    </option>
                    <option ng-repeat="project in projects" value="{{project.id}}" ng-bind-html="project.title">
                        {{ company.name_company || 'Aucun' }}
                    </option>
                </select>
            </div>
        </div>
        <div class="text-center col-md-1">
                <span class="pointer" ng-click="shownFilter = !shownFilter">
                    <i class="fa fa-filter"></i> Filtres <i class="fa" ng-class="shownFilter ? 'fa-caret-up' :  'fa-caret-down'"></i>
                </span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="well" ng-if="shownFilter">
                <div class="form-group">
                    <label>Tache :</label>
                    <input type="text" class="form-control" ng-model="options.title">
                </div>
                <div class="form-group">
                    <label>Demandeur :</label>
                    <select class="form-control" ng-model="options.id_company">
                        <option value="all">
                            Tous
                        </option>
                        <option ng-repeat="company in companies" value="{{company.id_company}}">
                            {{ company.name_company || 'Aucun' }}
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Responsable :</label>
                    <select class="form-control" ng-model="options.id_user_project_manager">
                        <option value="all">
                            Tous
                        </option>
                        <option ng-repeat="manager in managers" value="{{manager.id_manager}}">
                            {{ manager.name_manager || 'Aucun' }}
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Assigné à :</label>
                    <select class="form-control" ng-model="options.id_assigned_to">
                        <option value="all">
                            Tous
                        </option>
                        <option ng-repeat="worker in assigned" value="{{worker.id_assigned_to}}">
                            {{ worker.name_assigned_to || 'Aucun' }}
                        </option>
                    </select>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" ng-model="options.completed">
                        Inclure les tâches déjà validées
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-stripped table-condensed">
                <thead>
                <tr>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Auteur</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr class="info" ng-repeat-start="project in projects" ng-if="(cardsByProject[project.id] | backlogFilter:options).length > 0">
                    <th colspan="4">
                        {{ project.breadcrumbs }}{{ project.name_company ? ' (' + project.name_company + ')' : '' }}
                        <a class="pull-right text-success pointer" ng-href="/ng/com_zeapps_project/backlog/new/{{project.id}}" ze-auth="{id_project : project.id, right : 'card'}">
                            <i class="fa fa-plus"></i>
                            Nouvelle carte
                        </a>
                    </th>
                </tr>
                <tr ng-repeat-end ng-repeat="card in cardsByProject[project.id] | backlogFilter:options | orderBy:['title']">
                    <td><i class="fa fa-lg fa-check text-success" ng-if="task.completed === 'Y'"></i> {{ card.title }}</td>
                    <td ng-class="'text-' + compareDates(task.due_date)">{{ card.description }}</td>
                    <td>{{ card.name_author }}</td>
                    <td class="text-right no-wrap">
                        <div ze-auth="{id_project : project.id, right : 'card'}">
                            <button type="button" class="btn btn-info btn-xs" ng-click="edit(card)">
                                <i class="fa fa-pencil" ></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-xs" ng-click="delete(card)">
                                <i class="fa fa-trash" ></i>
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

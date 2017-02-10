<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="breadcrumb">
    Bac à sable
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
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-stripped table-condensed">
                <thead>
                <tr>
                    <th>Idée</th>
                    <th>Description</th>
                    <th>Auteur</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr class="info" ng-repeat-start="project in projects">
                    <th colspan="4">
                        {{ project.breadcrumbs }}{{ project.name_company ? ' (' + project.name_company + ')' : '' }}
                        <a class="pull-right text-success pointer" ng-href="/ng/com_zeapps_project/sandbox/new/{{project.id}}">
                            <i class="fa fa-plus"></i>
                            Nouvelle idée
                        </a>
                    </th>
                </tr>
                <tr ng-repeat-end ng-repeat="sandbox in sandboxesByProject[project.id] | sandboxFilter:options | orderBy:['title']">
                    <td><i class="fa fa-lg fa-check text-success" ng-if="task.completed === 'Y'"></i> {{ sandbox.title }}</td>
                    <td ng-class="'text-' + compareDates(task.due_date)">{{ sandbox.description }}</td>
                    <td>{{ sandbox.name_author }}</td>
                    <td class="text-right no-wrap">
                        <button type="button" class="btn btn-success btn-xs" ng-click="validate(sandbox)">
                            <i class="fa fa-share" ></i>
                        </button>
                        <button type="button" class="btn btn-info btn-xs" ng-click="edit(sandbox)">
                            <i class="fa fa-pencil" ></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-xs" ng-click="delete(sandbox)">
                            <i class="fa fa-trash" ></i>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


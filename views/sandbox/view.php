<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="breadcrumb">
    Bac à sable
</div>

<div id="content">
    <div class="row">
        <div class="col-md-12">
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
                <tr class="info" ng-repeat-start="project in projects | sandboxFilter:options">
                    <th colspan="4">
                        {{ project.breadcrumbs }}{{ project.name_company ? ' (' + project.name_company + ')' : '' }}
                        <a class="pull-right text-success pointer" ng-href="/ng/com_zeapps_project/sandbox/new/{{project.id}}" ze-auth="{id_project : project.id, right : 'sandbox'}">
                            <i class="fa fa-plus"></i>
                            Nouvelle idée
                        </a>
                    </th>
                </tr>
                <tr ng-repeat-end ng-repeat="sandbox in sandboxesByProject[project.id] | filter:{step : 0} | orderBy:['title']">
                    <td><i class="fa fa-lg fa-check text-success" ng-if="task.completed === 'Y'"></i> {{ sandbox.title }}</td>
                    <td ng-class="'text-' + compareDates(task.due_date)">{{ sandbox.description }}</td>
                    <td>{{ sandbox.name_author }}</td>
                    <td class="text-right no-wrap">
                        <div>
                            <button type="button" class="btn btn-info btn-xs" ng-click="detailCard(sandbox)">
                                <i class="fa fa-fw fa-eye" ></i>
                            </button>
                            <span ze-auth="{id_project : project.id, right : 'card'}">
                                <button type="button" class="btn btn-success btn-xs" ng-click="validate(sandbox)">
                                    <i class="fa fa-share" ></i>
                                </button>
                            </span>
                            <span ze-auth="{id_project : project.id, right : 'sandbox'}">
                                <button type="button" class="btn btn-info btn-xs" ng-click="edit(sandbox)">
                                    <i class="fa fa-pencil" ></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-xs" ng-click="delete(sandbox)">
                                    <i class="fa fa-trash" ></i>
                                </button>
                            </span>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


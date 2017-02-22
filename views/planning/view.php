<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="breadcrumb">
    Planning
</div>

<div id="content">
    <div class="row">
        <div class="col-md-5">
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
        <div class="col-md-6 form-horizontal">
            <div class="form-group">
                <label class="col-md-2 control-label">Assigné à :</label>
                <div class="col-md-10">
                    <select class="form-control" ng-model="options.id_assigned_to">
                        <option value="all">
                            Tous
                        </option>
                        <option ng-repeat="worker in assigned" value="{{worker.id_assigned_to}}">
                            {{ worker.name_assigned_to || 'Aucun' }}
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <div class="text-center col-md-1">
            <span class="pointer" ng-click="shownFilter = !shownFilter">
                <i class="fa fa-filter"></i> Filtres <i class="fa" ng-class="shownFilter ? 'fa-caret-up' :  'fa-caret-down'"></i>
            </span>
        </div>
    </div>
    <div class="row" ng-if="shownFilter">
        <div class="col-md-12">
            <div class="well">
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
                <div class="checkbox">
                    <label>
                        <input type="checkbox" ng-model="options.completed">
                        Inclure les tâches déjà terminées
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12" ng-include="'/com_zeapps_project/planning/table'">
        </div>
    </div>
</div>


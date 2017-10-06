<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div ng-controller="ComZeAppsPlanningTableCtrl">

    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <a class='btn btn-xs btn-primary' ng-click="printCards()" project-auth="{id_project : project.id, right : 'project'}">
                    <span class='fa fa-fw fa-print' aria-hidden='true'></span>
                </a>
                <a class='btn btn-xs btn-primary' ng-click="printCards(true)" project-auth="{id_project : project.id, right : 'project'}">
                    <span class='fa fa-fw fa-print' aria-hidden='true'></span> avec description
                </a>
                <a class='btn btn-xs btn-success' ng-href='/ng/com_zeapps_project/project/card/create/card/{{:: project.id }}' project-auth="{id_project : project.id, right : 'card'}">
                    <span class='fa fa-fw fa-plus' aria-hidden='true'></span> Carte
                </a>
            </div>

            <div class="form-group form-inline">
                <label>Statut</label>
                <select class="form-control" ng-model="currentStep" ng-change="fetchCards()">
                    <option value="0">A faire</option>
                    <option value="1">Nouveau</option>
                    <option value="2">En cours</option>
                    <option value="3">Contrôle qualité</option>
                    <option value="4">Terminé</option>
                </select>
            </div>
        </div>
    </div>

    <table class="table table-striped table-condensed">
        <thead>
        <tr>
            <th>#</th>
            <th>Catégorie</th>
            <th>Priorité</th>
            <th>To-Do</th>
            <th project-auth="{id_project : project.id, right : 'card'}">Statut</th>
            <th class="text-center">Demandeur</th>
            <th class="text-center">Assigné à</th>
            <th class="text-right"></th>
        </tr>
        </thead>
        <tbody>
        <tr ng-class="compareDates(date.due_date)"
            ng-repeat-start="date in dates | orderBy:'due_date'"
            ng-if="cardsByDate[date.due_date] && (cardsByDate[date.due_date] | planningFilter:options).length != 0"
            ng-click="showDate[date.due_date] = !showDate[date.due_date]"
        >
            <td colspan="8">
                {{:: (date.due_date != '0000-00-00' ? date.due_date : 'Sans date attribuée') | date:'dd MMMM yyyy' }} ({{::(cardsByDate[date.due_date] | planningFilter:options).length}})
                <i class="fa fa-fw" ng-class="showDate[date.due_date] ? 'fa-caret-down' : 'fa-caret-up'"></i>
            </td>
        </tr>
        <tr ng-repeat-end ng-repeat="card in cardsByDate[date.due_date] | planningFilter:options | orderBy:['id_priority', 'step']"
            ng-class="{'text-danger':card.deadline}"
            ng-if="!showDate[date.due_date]"
        >
            <td>
                {{:: card.deadline ? '' : card.id }}
            </td>
            <td ng-style="{'background-color': card.color}">
                {{:: card.category_title }}
            </td>
            <td ng-style="{'background-color': card.priority_color}">
                {{:: card.priority }}
            </td>
            <td>
                <i class="fa fa-flag-checkered fa-lg fa-fw" aria-hidden="true" ng-if="card.deadline" ng-class="card.step === '4' ? 'text-success' : ''"></i>
                {{:: card.title }}
            </td>
            <td class="text-right" project-auth="{id_project : card.id_project, right : 'card'}">
                <select class="form-control input-sm" ng-model="card.step" ng-change="changeStep(card)">
                    <option value="1">Nouveau</option>
                    <option value="2">En cours</option>
                    <option value="3">Contrôle qualité</option>
                    <option value="4">Terminé</option>
                </select>
            </td>
            <td class="text-center">
                {{:: card.name_manager }}
            </td>
            <td class="text-center">
                {{:: card.name_assigned_to }}
            </td>
            <td class="text-right no-wrap">
                <div>
                    <button type="button" class="btn btn-info btn-xs" ng-click="detailCard(card)">
                        <i class="fa fa-fw fa-eye" ></i>
                    </button>
                    <span project-auth="{id_project : card.id_project, right : 'card'}">
                        <button type="button" class="btn btn-info btn-xs" ng-click="edit(card)">
                            <i class="fa fa-fw fa-pencil" ></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-xs" ng-click="delete(card)" ze-confirmation>
                            <i class="fa fa-fw fa-trash" ></i>
                        </button>
                    </span>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div ng-controller="ComZeAppsPlanningTableCtrl">
    <table class="table table-stripped">
        <thead>
        <tr>
            <th class="col-xs-2">Projet</th>
            <th class="col-xs-2">Catégorie</th>
            <th class="col-xs-3">To-Do</th>
            <th class="col-xs-2 text-right">Demandeur</th>
            <th class="col-xs-1 text-right">Responsable</th>
            <th class="col-xs-1 text-right">Assigné à</th>
            <th class="col-xs-1 text-right"></th>
        </tr>
        </thead>
    </table>
    <table class="table table-condensed table-stripped" ng-repeat="date in dates | orderBy:'due_date'" ng-if="cardsByDate[date.due_date] && (cardsByDate[date.due_date] | planningFilter:options).length != 0">
        <thead>
        <tr ng-class="compareDates(date.due_date)">
            <th colspan="7">{{ (date.due_date != '0000-00-00' ? date.due_date : 'Sans date attribuée') | date:'dd MMMM yyyy' }}</th>
        </tr>
        </thead>
        <tbody>
        <tr ng-repeat="card in cardsByDate[date.due_date] | planningFilter:options | orderBy:['name_company', 'project_title', 'category_title', '-deadline']" ng-class="{'text-danger':card.deadline}">
            <td class="col-xs-2">
                {{ card.breadcrumbs }}
            </td>
            <td class="col-xs-2">
                {{ card.category_title }}
            </td>
            <td class="col-xs-3">
                <i class="fa fa-flag-checkered fa-lg fa-fw" aria-hidden="true" ng-if="card.deadline" ng-class="card.completed === 'Y' ? 'text-success' : ''"></i>
                <i class="fa fa-check-circle-o fa-lg fa-fw text-success" aria-hidden="true" ng-if="card.completed === 'Y' && !card.deadline"></i>
                <i class="fa fa-lg fa-fw" aria-hidden="true" ng-if="card.completed === 'N' && card.id_sprint !== '0' && !card.deadline" ng-class="stepOf(card)"></i>
                {{ card.title }}
            </td>
            <td class="col-xs-2 text-right">
                {{ card.name_company }}
            </td>
            <td class="col-xs-1 text-right">
                {{ card.name_manager }}
            </td>
            <td class="col-xs-1 text-right">
                {{ card.name_assigned_to }}
            </td>
            <td class="text-right">
                <button type="button" class="btn btn-success btn-xs" ng-click="complete(card)" ng-if="card.completed === 'N'">
                    <i class="fa fa-fw fa-check" ></i>
                </button>
                <button type="button" class="btn btn-info btn-xs" ng-click="edit(card)">
                    <i class="fa fa-fw fa-pencil" ></i>
                </button>
                <button type="button" class="btn btn-danger btn-xs" ng-click="delete(card)">
                    <i class="fa fa-fw fa-trash" ></i>
                </button>
            </td>
        </tr>
        </tbody>
    </table>
</div>
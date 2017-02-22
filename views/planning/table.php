<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div ng-controller="ComZeAppsPlanningTableCtrl">
    <table class="table table-striped table-condensed">
        <thead>
        <tr>
            <th>Projet</th>
            <th>Catégorie</th>
            <th>To-Do</th>
            <th class="text-right">Demandeur</th>
            <th class="text-right">Responsable</th>
            <th class="text-right">Assigné à</th>
            <th class="text-right"></th>
        </tr>
        </thead>
        <tbody>
        <tr ng-class="compareDates(date.due_date)"
            ng-repeat-start="date in dates | orderBy:'due_date'"
            ng-if="cardsByDate[date.due_date] && (cardsByDate[date.due_date] | planningFilter:options).length != 0"
            ng-click="showDate[date.due_date] = !showDate[date.due_date]"
        >
            <th colspan="7">
                {{ (date.due_date != '0000-00-00' ? date.due_date : 'Sans date attribuée') | date:'dd MMMM yyyy' }} ({{(cardsByDate[date.due_date] | planningFilter:options).length}})
                <i class="fa fa-fw" ng-class="showDate[date.due_date] ? 'fa-caret-down' : 'fa-caret-up'"></i>
            </th>
        </tr>
        <tr ng-repeat-end ng-repeat="card in cardsByDate[date.due_date] | planningFilter:options | orderBy:['name_company', 'project_title', 'category_title', '-deadline']"
            ng-class="{'text-danger':card.deadline}"
            ng-if="!showDate[date.due_date]"
        >
            <td>
                {{ card.breadcrumbs }}
            </td>
            <td>
                {{ card.category_title }}
            </td>
            <td>
                <i class="fa fa-flag-checkered fa-lg fa-fw" aria-hidden="true" ng-if="card.deadline" ng-class="card.completed === 'Y' ? 'text-success' : ''"></i>
                <i class="fa fa-check-circle-o fa-lg fa-fw text-success" aria-hidden="true" ng-if="card.completed === 'Y' && !card.deadline"></i>
                <a ng-href="/ng/com_zeapps_project/sprint/{{ card.id_project }}/{{ card.id_sprint }}" ng-if="card.completed === 'N' && card.id_sprint !== '0' && !card.deadline"><i class="fa fa-lg fa-fw" aria-hidden="true" ng-class="stepOf(card)"></i></a>
                {{ card.title }}
            </td>
            <td class="text-right">
                {{ card.name_company }}
            </td>
            <td class="text-right">
                {{ card.name_manager }}
            </td>
            <td class="text-right">
                {{ card.name_assigned_to }}
            </td>
            <td class="text-right no-wrap">
                <div ze-auth="{id_project : card.id_project, right : 'card'}">
                    <button type="button" class="btn btn-success btn-xs" ng-click="complete(card)" ng-if="card.completed === 'N'">
                        <i class="fa fa-fw fa-check" ></i>
                    </button>
                    <button type="button" class="btn btn-info btn-xs" ng-click="edit(card)">
                        <i class="fa fa-fw fa-pencil" ></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-xs" ng-click="delete(card)">
                        <i class="fa fa-fw fa-trash" ></i>
                    </button>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
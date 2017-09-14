<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div ng-controller="ComZeAppsPlanningTableCtrl">

    <div class="pull-right">
        <a class='btn btn-xs btn-success' ng-href='/ng/com_zeapps_project/project/card/create/deadline/{{ project.id }}' project-auth="{id_project : project.id, right : 'card'}">
            <span class='fa fa-fw fa-plus' aria-hidden='true'></span> Jalon
        </a>
    </div>

    <table class="table table-condensed table-responsive">
        <thead>
        <tr>
            <td>#</td>
            <!--<td>Projet</td>-->
            <td>Catégorie</td>
            <td>Date</td>
            <td>Date</td>
            <td></td>
        </tr>
        </thead>
        <tbody>
        <tr ng-repeat="deadline in deadlines | planningFilter:options | orderBy:'-due_date'">
            <td>{{deadline.id}}</td>
            <!--<td>{{deadline.breadcrumbs}}</td>-->
            <td>{{deadline.category_title}}</td>
            <td>
                {{deadline.title}}
            </td>
            <td>{{ (deadline.due_date != '0000-00-00' ? deadline.due_date : 'Sans date attribuée') | date:'dd MMMM yyyy' }}</td>
            <td class="text-right no-wrap">
                <div>
                    <span project-auth="{id_project : deadline.id_project, right : 'card'}">
                        <button type="button" class="btn btn-info btn-xs" ng-click="edit_deadline(deadline)">
                            <i class="fa fa-fw fa-pencil" ></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-xs" ng-click="delete_deadline(deadline)" ze-confirmation>
                            <i class="fa fa-fw fa-trash" ></i>
                        </button>
                    </span>
                </div>
            </td>
        </tr>
        </tbody>
    </table>

</div>
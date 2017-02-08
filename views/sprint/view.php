<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="breadcrumb">
    Scrum
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
                    </option>
                </select>
            </div>
        </div>
        <div class="col-md-1 text-center">
            <a class='text-success' ng-href='/ng/com_zeapps_project/sprint/create/'><span class='fa fa-plus' aria-hidden='true'></span> Sprint</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="sprints clearfix" ng-repeat="project in projects | sprintFilter:options" ng-if="sprintsByProject[project.id] && sprintsByProject[project.id].length > 0">
                <div class="sprints_project">
                    <strong>{{ project.breadcrumbs }}</strong>{{ project.name_company ? ' (' + project.name_company + ')' : '' }}
                </div>
                <div class="sprint pointer text-center" ng-repeat-start="sprint in sprintsByProject[project.id] | orderBy:['-completed','-active','due_date']" ng-class="sprint.active === 'Y' ? 'active' : ''" ng-click="goTo(sprint)">
                    <h5>
                        {{ sprint.title }}
                    </h5>
                    <div class="sprint_content">
                        {{ sprint.start_date | date:'dd/MM/yyyy' }}<br>
                        au<br>
                        {{ sprint.due_date | date:'dd/MM/yyyy' }}
                    </div>
                </div>
                <div class="sprint_separator" ng-repeat-end ng-hide="$last">
                    <i class="fa fa-long-arrow-right fa-3x fa-fw"></i>
                </div>
            </div>
        </div>
    </div>
</div>

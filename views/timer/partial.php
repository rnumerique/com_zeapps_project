<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<small><strong>Utilisation du temps :</strong></small>
<div class="progress">
    <div class="progress-bar" role="progressbar" aria-valuenow="{{timer_ratio}}" aria-valuemin="0" aria-valuemax="100" style="min-width: 40px" ng-style="{width: timer_ratio+'%', 'background-color': timer_color}">
        {{timer_ratio}}%
    </div>
</div>

<div class="text-right">
    <button type="button" class="btn btn-xs btn-success" ng-click="newTimer()">
        <i class="fa fa-fw fa-plus"></i> temps
    </button>
</div>

<table class="table table-condensed table-stripped">
    <thead>
    <tr>
        <th>Tâche</th>
        <th>Utilisateur</th>
        <th>Durée</th>
        <th>Commentaires</th>
        <th>Début</th>
        <th>Fin</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <tr ng-repeat="timer in timers | orderBy:id">
        <td>{{ timer.label }}</td>
        <td>{{ timer.name_user }}</td>
        <td>{{ timer.time_spent_formatted }}</td>
        <td>{{ timer.comment }}</td>
        <td>{{ timer.start_time | date:'dd/MM/yyyy HH:mm' }}</td>
        <td>{{ timer.stop_time | date:'dd/MM/yyyy HH:mm' }}</td>
        <td class="text-right">
            <button type="button" class="btn btn-xs btn-info" ng-click="editTimer(timer)">
                <i class="fa fa-fw fa-pencil"></i>
            </button>
            <button type="button" class="btn btn-xs btn-danger" ng-click="deleteTimer(timer)">
                <i class="fa fa-fw fa-trash"></i>
            </button>
        </td>
    </tr>
    </tbody>
</table>
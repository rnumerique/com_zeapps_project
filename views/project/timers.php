<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
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
    <tr ng-repeat="timer in project.timers | orderBy:id">
        <td>{{ timer.label }}</td>
        <td>{{ timer.name_user }}</td>
        <td>{{ timer.duration }}</td>
        <td>{{ timer.comment }}</td>
        <td>{{ timer.start_time | date:'hh:mm - dd/MM/yyyy' }}</td>
        <td>{{ timer.stop_time | date:'hh:mm - dd/MM/yyyy' }}</td>
        <td class="text-right">
            <a class="btn btn-xs btn-info" href="/ng/com_zeapps_project/project/timer/edit/{{timer.id}}">
                <i class="fa fa-fw fa-pencil"></i>
            </a>
            <button class="btn btn-xs btn-danger" ng-click="deleteTimer(timer)">
                <i class="fa fa-fw fa-trash"></i>
            </button>
        </td>
    </tr>
    </tbody>
</table>
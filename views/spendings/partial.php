<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="text-right">
    <button type="button" class="btn btn-xs btn-success" ng-click="newSpending()">
        <i class="fa fa-fw fa-plus"></i> Dépense
    </button>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped table-condensed table-responsive" ng-show="spendings.length">
            <thead>
            <tr>
                <th>#</th>
                <th>Libelle</th>
                <th>Description</th>
                <th>Montant (€)</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="spending in spendings">
                <td>{{spending.id}}</td>
                <td>{{spending.label}}</td>
                <td>{{spending.description}}</td>
                <td>{{spending.total | currency}}</td>
                <td class="text-right no-wrap">
                    <button type="button" class="btn btn-xs btn-info" ng-click="editSpending(spending)">
                        <i class="fa fa-pencil fa-fw"></i>
                    </button>
                    <button type="button" class="btn btn-xs btn-danger" ng-click="deleteSpending(spending)" ze-confirmation>
                        <i class="fa fa-trash fa-fw"></i>
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
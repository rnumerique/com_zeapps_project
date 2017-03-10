<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="breadcrumb">Config > Statuts de projets</div>
<div id="content">
    <div class="row">
        <div class="col-md-12">
            <h3>Statuts de projets</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form>
                <table class="table table-responsive table-condensed table-stripped">
                    <thead>
                    <tr>
                        <th>
                            Libellé
                        </th>
                        <th>
                            Ordre
                        </th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th>
                            <input type="text" ng-model="newLine.label" class="form-control">
                        </th>
                        <th>
                            <input type="number" ng-model="newLine.sort" class="form-control">
                        </th>
                        <td class="text-right">
                            <button type="button" class="btn btn-success btn-xs" ng-click="createLine()">
                                <i class="fa fa-fw fa-plus"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-xs" ng-click="cancelLine()">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </td>
                    </tr>
                    <tr ng-repeat="status in form.statuses">
                        <td>
                            <input type="text" ng-model="status.label" class="form-control">
                        </td>
                        <td>
                            <input type="number" ng-model="status.sort" class="form-control">
                        </td>
                        <td class="text-right">
                            <button type="button" class="btn btn-danger btn-xs" ng-click="delete($index)">
                                <i class="fa fa-fw fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <form-buttons></form-buttons>
            </form>
        </div>
    </div>
</div>
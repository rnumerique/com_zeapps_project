<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="breadcrumb">Statuts de projets</div>
<div id="content">
    <div class="row">
        <div class="col-md-12">
            <ze-btn fa="plus" color="success" hint="Statut" always-on="true"
                    ze-modalform="add"
                    data-template="templateForm"
                    data-title="Ajouter un nouveau statut de projet"></ze-btn>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-responsive table-condensed table-hover">
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
                <tr ng-repeat="status in statuses">
                    <td>
                        {{status.label}}
                    </td>
                    <td>
                        {{status.sort}}
                    </td>
                    <td class="text-right">
                        <ze-btn fa="pencil" color="info" hint="Editer" direction="left"
                                ze-modalform="edit"
                                data-edit="status"
                                data-template="templateForm"
                                data-title="Modifier le statut de projet"></ze-btn>
                        <ze-btn fa="trash" color="danger" hint="Supprimer" direction="left" ng-click="delete(status)" ze-confirmation></ze-btn>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
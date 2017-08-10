<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="text-right">
    <button type="button" class="btn btn-xs btn-success" ng-click="addDocument()" ng-if="!progress">
        <i class="fa fa-fw fa-plus"></i> document
    </button>
    <span ng-if="progress">
        <i class="fa fa-spinner fa-pulse fa-lg fa-fw text-info"></i>
    </span>
</div>


<div class="row" ng-if="documents.length > 0">
    <div class="col-md-12">
        <div class="card_document" ng-repeat="document in documents | orderBy:['-date','-id']">
            <div class="card_document-head clearfix">
                <div class="pull-right">
                    <button type="button" class="btn btn-xs btn-info" ng-click="editDocument(document)" project-auth="{id_project : project.id, right : 'project'}">
                        <span class="hover-hint">editer</span> <i class="fa fa-fw fa-pencil"></i>
                    </button>
                    <button type="button" class="btn btn-xs btn-danger" ng-click="deleteDocument(document)" project-auth="{id_project : project.id, right : 'project'}" ze-confirmation>
                        <span class="hover-hint">supprimer</span> <i class="fa fa-fw fa-times"></i>
                    </button>
                </div>
                <i class="fa fa-fw fa-file"></i>
                <a ng-href="{{ document.path }}" class="text-primary" target="_blank">
                    {{ document.label }}
                </a>
            </div>
            <div class="card_document-body" ng-if="document.description">
                {{ document.description }}
            </div>
            <div class="card_document-footer text-muted">
                Envoyé par <strong>{{ document.name_user }}</strong> le <strong>{{ document.date | date:'dd/MM/yyyy' }}</strong> à <strong>{{ document.date | date:'HH:mm' }}</strong>
            </div>
        </div>
    </div>
</div>

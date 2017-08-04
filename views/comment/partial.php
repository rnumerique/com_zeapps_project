<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="text-right">
    <button type="button" class="btn btn-xs btn-success" ng-click="addComment()">
        <i class="fa fa-fw fa-plus"></i> note
    </button>
</div>
<div class="card_comment clearfix" ng-repeat="comment in comments | orderBy:['-date','-id']">
    <div class="pull-right">
        <button type="button" class="btn btn-xs btn-info" ng-click="editComment(comment)" ze-auth="{id_project : project.id, right : 'project'}">
            <span class="hover-hint">editer</span> <i class="fa fa-fw fa-pencil"></i>
        </button>
        <button type="button" class="btn btn-xs btn-danger" ng-click="deleteComment(comment)" ze-auth="{id_project : project.id, right : 'project'}">
            <span class="hover-hint">supprimer</span> <i class="fa fa-fw fa-times"></i>
        </button>
    </div>
    <div class="card_comment-body">{{ comment.comment }}</div>
    <div class="card_comment-footer text-muted">
        Envoyé par <strong>{{ comment.name_user }}</strong> le <strong>{{ comment.date | date:'dd/MM/yyyy' }}</strong> à <strong>{{ comment.date | date:'HH:mm' }}</strong>
    </div>
</div>


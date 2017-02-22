<div class="modal-header">
    <strong class="pull-right">
        {{ '#' + card.id }}
    </strong>
    <h3 class="modal-title">
        {{card.title}}
    </h3>
</div>


<div class="modal-body table">
    <div class="clearfix">
        <h4 class="pull-right" ng-if="card.name_assigned_to">Attribué à : <i>{{ card.name_assigned_to }}</i></h4>
        <h4 ng-if="card.due_date != '0000-00-00'">Echéance : <i>{{ card.due_date | date:'dd/MM/yyyy' }}</i></h4>
    </div>
    <h4 ng-if="card.description">Description :</h4>
    <div class="card_description" ng-if="card.description">{{ card.description }}</div>
    <br>
    <div class="text-right">
        <button type="button" class="btn btn-xs btn-success" ngf-select="upload($files)" multiple ng-if="!progress">
            <i class="fa fa-fw fa-plus"></i> document
        </button>
        <span ng-if="progress">
            <i class="fa fa-spinner fa-pulse fa-lg fa-fw text-info"></i>
        </span>
    </div>
    <h4 ng-if="card.documents.length > 0">Documents</h4>
    <div class="row" ng-if="card.documents.length > 0">
        <div class="col-md-4 card_document" ng-repeat="document in card.documents">
            <i class="fa fa-fw fa-file"></i>
            <a ng-href="{{ document.path }}" target="_blank">
                {{ document.name }}
            </a>
            <br>
            <i class="small">Ajouté le : {{ document.date | date:'dd/MM/yyyy' }}</i>
        </div>
    </div>
    <br>
    <div class="text-right">
        <button type="button" class="btn btn-xs btn-success" ng-click="showCardDetailForm = !showCardDetailForm" ng-show="!showCardDetailForm">
            <i class="fa fa-fw fa-plus"></i> commentaire
        </button>
    </div>
    <div ng-show="showCardDetailForm">
        <div class="form-group">
            <textarea class="form-control" ng-model="form.comment" rows="2"></textarea>
        </div>
        <div class="text-center">
            <button type="button" class="btn btn-xs btn-default" ng-click="showCardDetailForm = !showCardDetailForm">annuler</button>
            <button type="button" class="btn btn-xs btn-success" ng-click="saveComment()">enregistrer</button>
        </div>
    </div>
    <div class="card_comment" ng-repeat="comment in card.comments | orderBy:['-date','-id']">{{ comment.comment }}<br><small>{{ comment.name_user }} - {{ comment.date | date:'dd/MM/yyyy' }}</small></div>
</div>

<div class="modal-footer">
    <span ze-auth="{id_project : card.id_project, right : 'card'}">
        <button class="btn btn-info" type="button" ng-click="edit()" i8n="Modifier"></button>
    </span>
    <button class="btn btn-default" type="button" ng-click="close()" i8n="Fermer"></button>
</div>
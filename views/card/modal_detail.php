<div class="modal-header">
    <div>
        <div class="pull-right">
            <button type="button" class="btn btn-xs btn-info" ng-click="edit()" project-auth="{id_project : card.id_project, right : 'card'}">
                <i class="fa fa-fw fa-pencil"></i> <span class="hover-hint">Editer</span>
            </button>
            <button type="button" class="btn btn-xs btn-success" ng-click="startTimer()" project-auth="{id_project : card.id_project, right : 'card'}">
                <i class="fa fa-fw fa-play"></i> <span class="hover-hint">Timer</span>
            </button>
        </div>
        <strong>
            {{:: '#' + card.id }}
        </strong>
    </div>
    <h3 class="modal-title">
        {{:: card.title}}
    </h3>
</div>


<div class="modal-body table">
    <div class="clearfix">
        <h4 class="pull-right" ng-if="card.name_assigned_to">Attribué à : <i>{{ ::card.name_assigned_to }}</i></h4>
        <h4 ng-if="card.due_date != '0000-00-00'">Echéance : <i>{{:: card.due_date | date:'dd/MM/yyyy' }}</i></h4>
    </div>
    <h4 ng-if="card.description">Description :</h4>
    <div class="card_description" ng-if="card.description">{{:: card.description }}</div>

    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" ng-click="goToTabCard('notes')" ng-class="isActiveCard('notes') ? 'active' : ''">
                    <a href="#">Notes</a>
                </li>
                <li role="presentation" ng-click="goToTabCard('documents')" ng-class="isActiveCard('documents') ? 'active' : ''">
                    <a href="#">Documents</a>
                </li>
                <li role="presentation" ng-click="goToTabCard('timers')" ng-class="isActiveCard('timers') ? 'active' : ''" project-auth="{id_project : card.id_project, right : 'card'}">
                    <a href="#">Temps</a>
                </li>
            </ul>
        </div>
    </div>

    <div ng-include="view" ng-if="card.id_project"></div>
</div>

<div class="modal-footer">
    <button class="btn btn-default" type="button" ng-click="close()">Fermer</button>
</div>
<div class="modal-header">
    <h3 class="modal-title">{{card.title}}</h3>
</div>


<div class="modal-body table">
    <div class="clearfix">
        <h5 class="pull-right" ng-if="card.name_assigned_to">Attribué à : <i>{{ card.name_assigned_to }}</i></h5>
        <h5 ng-if="card.due_date != '0000-00-00'">Echéance : <i>{{ card.due_date | date:'dd/MM/yyyy'}}</i></h5>
    </div>
    <p>
        <h5>Description :</h5>
        {{ card.description || 'Pas de description' }}
    </p>
</div>

<div class="modal-footer">
    <button class="btn btn-info" type="button" ng-click="close()" i8n="Fermer"></button>
</div>
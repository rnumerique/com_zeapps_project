<div class="modal-header">
    <h3 class="modal-title">{{titre}}</h3>
</div>


<div class="modal-body table">
    <div class="row table-row">
        <div class="table_cell scrum_card pointer" ng-repeat="card in cards | cardmodalFilter:option" ng-click="toggle(card)">
            <i class="fa fa-fw" ng-class="isSelected(card) ? 'text-success fa-check' : 'fa-circle-o'"></i>
            <b>{{ card.title }}</b>
            <div>
                {{ card.description }}
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button class="btn btn-danger" type="button" ng-click="cancel()" i8n="Annuler"></button>
    <button class="btn btn-success" type="button" ng-click="loadCards()" i8n="Valider"></button>
</div>
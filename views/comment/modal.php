<div class="modal-header">
    <h3 class="modal-title">{{titre}}</h3>
</div>


<div class="modal-body">
    <div class="form-group">
        <textarea class="form-control" ng-model="form.comment" rows="10" placeholder="Votre message..."></textarea>
    </div>
</div>

<div class="modal-footer">
    <button class="btn btn-danger" type="button" ng-click="cancel()" i8n="Annuler"></button>
    <button class="btn btn-success" type="button" ng-click="save()" i8n="Sauvegarder"></button>
</div>
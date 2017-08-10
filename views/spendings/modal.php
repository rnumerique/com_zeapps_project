<div class="modal-header">
    <h3 class="modal-title">{{titre}}</h3>
</div>


<div class="modal-body">
    <div class="row">
        <div class="col-md-7">
            <div class="form-group">
                <label>Libellé</label>
                <input type="text" class="form-control" ng-model="form.label">
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label>Montant (€)</label>
                <input type="number" class="form-control" ng-model="form.total">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Description</label>
                <textarea class="form-control" rows="5" ng-model="form.description"></textarea>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button class="btn btn-danger" type="button" ng-click="cancel()" i8n="Annuler"></button>
    <button class="btn btn-success" type="button" ng-click="save()" i8n="Sauvegarder"></button>
</div>
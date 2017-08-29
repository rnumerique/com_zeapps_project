<div class="modal-header">
    <h3 class="modal-title">{{titre}}</h3>
</div>


<div class="modal-body table">
    <div class="table-row">
        <div class="row">
            <div class="col-md-12" project-auth="{id_project : form.id_project, right : 'project'}">
                <div class="form-group">
                    <label>Utilisateur</label>
                    <div class="input-group">
                        <input type="text" ng-model="form.name_user" class="form-control" disabled ng-required="true">

                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" ng-click="removeUser()"
                                    ng-show="form.id_user != 0 && form.id_user != undefined">x
                            </button>
                            <button class="btn btn-default" type="button" ng-click="loadUser()">...</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Tâche :</label>
                    <div class="input-group">
                        <input type="text" ng-model="form.label" class="form-control">

                        <span class="input-group-btn">
                        <button class="btn btn-default" type="button" ng-click="removeCard()"
                                ng-show="form.id_card != 0">x
                        </button>
                        <button class="btn btn-default" type="button" ng-click="loadCard()">...</button>
                    </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" class="form-control input-sm" ng-model="form.date">
                </div>
            </div>
            <div class="col-md-6 form-inline">
                <div class="form-group">
                    <label>Temps passé (en heures, au format 1:30 ou 1.5)</label><br>
                    <input type="text" class="form-control input-sm" ng-model="form.time_spent_form" ng-change="update('time_spent')" ng-model-options="{debounce: 250}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-inline">
                <div class="form-group">
                    <label>Heure de début</label><br>
                    <input type="number" class="form-control input-sm" ng-model="form.start_time_h" ng-change="update('start_time')" ng-model-options="{debounce: 250}"> h
                    <input type="number" class="form-control input-sm" ng-model="form.start_time_m" ng-change="update('start_time')" ng-model-options="{debounce: 250}">
                </div>
            </div>
            <div class="col-md-6 form-inline">
                <div class="form-group">
                    <label>Heure de fin</label><br>
                    <input type="number" class="form-control input-sm" ng-model="form.stop_time_h" ng-change="update('stop_time')" ng-model-options="{debounce: 250}"> h
                    <input type="number" class="form-control input-sm" ng-model="form.stop_time_m" ng-change="update('stop_time')" ng-model-options="{debounce: 250}">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Commentaires</label>
                <textarea class="form-control" ng-model="form.comment" rows="6"></textarea>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button class="btn btn-danger" type="button" ng-click="cancel()">Annuler</button>
    <button class="btn btn-success" type="button" ng-click="save()">Valider</button>
</div>
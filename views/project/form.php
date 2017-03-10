<div id="breadcrumb">
    Gestion des projets
</div>

<div id="content">
    <form>
        <div class="well">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Intitulé du projet</label>
                        <input class="form-control" type='text' ng-model="form.title" ng-required="true">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Contexte</label>
                        <input class="form-control" type='text' ng-model="form.context">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Statut</label>
                        <select class="form-control" ng-model="form.id_status">
                            <option value="0">
                                Sans statut
                            </option>
                            <option ng-repeat="status in statuses | orderBy:'sort'" value="{{ status.id }}">
                                {{ status.label }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Projet parent</label>
                        <div class="input-group">
                            <input type="text" ng-model="form.title_parent" class="form-control" disabled>

                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" ng-click="removeProject()"
                                        ng-show="form.id_parent != 0 && form.id_parent != undefined">x
                                </button>
                                <button class="btn btn-default" type="button" ng-click="loadProject()">...</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Entreprise</label>
                        <div class="input-group">
                            <input type="text" ng-model="form.name_company" class="form-control" disabled  ng-required="!form.id_contact">

                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" ng-click="removeCompany()"
                                        ng-show="form.id_company != 0 && form.id_company != undefined">x
                                </button>
                                <button class="btn btn-default" type="button" ng-click="loadCompany()">...</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Contact</label>
                        <div class="input-group">
                            <input type="text" ng-model="form.name_contact" class="form-control" disabled  ng-required="!form.id_company">

                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" ng-click="removeContact()"
                                        ng-show="form.id_contact != 0 && form.id_contact != undefined">x
                                </button>
                                <button class="btn btn-default" type="button" ng-click="loadContact()">...</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Manager</label>
                        <div class="input-group">
                            <input type="text" ng-model="form.name_manager" class="form-control" disabled ng-required="true">

                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" ng-click="removeManager()"
                                        ng-show="form.id_manager != 0 && form.id_manager != undefined">x
                                </button>
                                <button class="btn btn-default" type="button" ng-click="loadManager()">...</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Date de début</label>
                        <input class="form-control" type='date' ng-model="form.start_date">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Date limite</label>
                        <input class="form-control" type='date' ng-model="form.due_date">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Temps estimé (h)</label>
                        <input class="form-control" type='number' ng-model="form.estimated_time">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Temps passé (h)</label>
                        <input class="form-control" type='number' ng-model="form.time_spent">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Montant</label>
                        <input class="form-control" type='number' ng-model="form.due">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Commision</label>
                        <input class="form-control" type='number' ng-model="form.commission">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Déjà facturé</label>
                        <input class="form-control" type='number' ng-model="form.payed">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" ng-model="form.description" row="3"></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Annotations</label>
                        <textarea class="form-control" ng-model="form.annotations" row="3"></textarea>
                    </div>
                </div>
            </div>

            <form-buttons></form-buttons>
        </div>
    </form>
</div>
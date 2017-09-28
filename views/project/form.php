<div id="breadcrumb">
    Gestion des projets
</div>

<div id="content">
    <form>
        <div class="well">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Intitulé du projet</label>
                        <input class="form-control" type='text' ng-model="form.title" ng-required="true">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Contexte</label>
                        <input class="form-control" type='text' ng-model="form.context">
                    </div>
                </div>
                <div class="col-md-4">
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
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Entreprise</label>
                        <span   ze-modalsearch="loadCompany"
                                data-http="companyHttp"
                                data-model="form.name_company"
                                data-fields="companyFields"
                                data-template-new="companyTplNew"
                                data-title="Choisir une entreprise"></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Contact</label>
                        <span   ze-modalsearch="loadContact"
                                data-http="contactHttp"
                                data-model="form.name_contact"
                                data-fields="contactFields"
                                data-template-new="contactTplNew"
                                data-title="Choisir un contact"></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Manager</label>
                        <span   ze-modalsearch="loadManager"
                                data-http="managerHttp"
                                data-model="form.name_manager"
                                data-fields="managerFields"
                                data-title="Choisir une entreprise"></span>
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
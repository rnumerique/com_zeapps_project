<div id="breadcrumb">
    Gestion des projets
</div>

<div id="content">
    <form>
        <div class="well">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Intitulé</label>
                        <input class="form-control" type='text' ng-model="form.title" ng-required="true">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Projet</label>
                        <div class="input-group">
                            <input type="text" ng-model="form.project_title" class="form-control" disabled ng-required="true">

                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" ng-click="removeProject()"
                                        ng-show="form.id_project != 0 && form.id_project != undefined">x
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
                        <label>Catégorie</label>
                        <select ng-model="form.id_category" class="form-control">
                            <option value="0">
                                Sans catégorie
                            </option>
                            <option ng-repeat="category in categories" value="{{::category.id}}">
                                {{ ::category.title }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4" ng-if="type == 'card'">
                    <div class="form-group">
                        <label>Assigné à</label>
                        <span   ze-modalsearch="loadAssigned"
                                data-http="accountManagerHttp"
                                data-model="form.name_assigned_to"
                                data-fields="accountManagerFields"
                                data-title="Choisir un utilisateur"></span>
                    </div>
                </div>

                <div class="col-md-4" ng-if="type == 'card'">
                    <div class="form-group">
                        <label>Statut</label>
                        <select class="form-control" ng-model="form.step">
                            <option value="1">Nouveau</option>
                            <option value="2">En cours</option>
                            <option value="3">Contrôle qualité</option>
                            <option value="4">Terminé</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4" ng-if="type == 'card'">
                    <div class="form-group">
                        <label>Priorité</label>
                        <select class="form-control" ng-model="form.id_priority">
                            <option ng-repeat="priority in priorities | orderBy:'order'" value="{{::priority.id}}">
                                {{ ::priority.label }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Date <span ng-if="type == 'card'">de fin</span></label>
                        <input class="form-control" type='date' ng-model="form.due_date">
                    </div>
                </div>
                <div class="col-md-6" ng-if="type == 'card'">
                    <div class="form-group">
                        <label>Temps estimé (h)</label>
                        <input class="form-control" type='number' ng-model="form.estimated_time">
                    </div>
                </div>


                <div class="col-md-6" ng-if="type == 'deadline'">
                    <div class="form-group">
                        <label>Date de clôture</label>
                        <input class="form-control" type='date' ng-model="form.end_at">
                    </div>
                </div>
            </div>

            <div class="row" ng-if="type == 'card'">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" ng-model="form.description" rows="15"></textarea>
                    </div>
                </div>
            </div>

            <form-buttons></form-buttons>
        </div>
    </form>
</div>
<div id="breadcrumb">
    Gestion des projets
</div>

<div id="content">
    <form>
        <div class="well">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Intitulé du sprint</label>
                        <input class="form-control" type='text' ng-model="form.title">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Projet</label>
                        <div class="input-group">
                            <input type="text" ng-model="form.title_project" class="form-control" disabled>

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

            </div>
            <div class="row">

                <div class="col-md-3">
                    <div class="form-group">
                        <label>
                            <input type="checkbox" ng-model="form.active">
                            Sprint actif
                        </label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>
                            <input type="checkbox" ng-model="form.completed">
                            Sprint terminé
                        </label>
                    </div>
                </div>

                <form-buttons></form-buttons>
            </div>
        </div>
    </form>
</div>
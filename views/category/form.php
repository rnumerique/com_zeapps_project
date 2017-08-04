<div id="breadcrumb">
    Gestion des projets
</div>

<div id="content">
    <form>
        <div class="well">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Intitulé de la catégorie</label>
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
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Couleur de la catégorie</label>
                        <div class="input-group">
                            <span class="input-group-addon" ng-style="{'background-color':form.color}"></span>
                            <input class="form-control" type='text' ng-model="form.color">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form-buttons></form-buttons>
    </form>
</div>
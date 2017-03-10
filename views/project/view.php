<div id="breadcrumb">
    Gestion des projets
</div>

<div id="content">
    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <a class='btn btn-xs btn-info' ng-href="/ng/com_zeapps_project/project">
                    <span class='fa fa-fw fa-arrow-left' aria-hidden='true'></span> retour a la vue d'ensemble
                </a>
                <a class='btn btn-xs btn-success' ng-href='/ng/com_zeapps_project/project/create/{{ project.id }}' ze-auth="{id_project : project.id, right : 'project'}">
                    <span class='fa fa-fw fa-plus' aria-hidden='true'></span> Sous Projet
                </a>
                <a class='btn btn-xs btn-success' ng-href='/ng/com_zeapps_project/project/card/create/card/{{ project.id }}' ze-auth="{id_project : project.id, right : 'card'}">
                    <span class='fa fa-fw fa-plus' aria-hidden='true'></span> Carte
                </a>
                <a class='btn btn-xs btn-success' ng-href='/ng/com_zeapps_project/project/card/create/deadline/{{ project.id }}' ze-auth="{id_project : project.id, right : 'card'}">
                    <span class='fa fa-fw fa-plus' aria-hidden='true'></span> Deadline
                </a>
            </div>

            <h3>
                {{ project.title }}
                <span ze-auth="{id_project : project.id, right : 'project'}">
                    <a type="button" class="btn btn-info btn-xs" ng-href="/ng/com_zeapps_project/project/edit/{{ project.id }}">
                        <i class="fa fa-fw fa-pencil" ></i>
                    </a>
                    <button type="button" class="btn btn-warning btn-xs" ng-click="archive_project(project.id)">
                        <i class="fa fa-fw fa-archive" ></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-xs" ng-click="delete_project(project.id)">
                        <i class="fa fa-fw fa-trash" ></i>
                    </button>
                </span>
            </h3>
            <h4>
                Demandeur : {{ project.name_company }}
            </h4>
            <h5>
                Manager : {{ project.name_manager }}
            </h5>


            <table class="col-xs-12 text-center postits">
                <tr>
                    <td>
                        <div class="postit">
                            <h3>
                                {{ project.due | currency }}
                            </h3>
                            <h5>Montant</h5>
                        </div>
                    </td>
                    <td>
                        <div class="postit">
                            <h3>
                                {{ project.commission | currency }}
                            </h3>
                            <h5>Commission</h5>
                        </div>
                    </td>
                    <td>
                        <div class="postit">
                            <h3>
                                {{ project.due - project.due | currency }}
                            </h3>
                            <h5>Marge</h5>
                        </div>
                    </td>
                    <td>
                        <div class="postit">
                            <h3>
                                {{ project.payed | currency }}
                            </h3>
                            <h5>Deja Facturé</h5>
                        </div>
                    </td>
                    <td>
                        <div class="postit">
                            <h3>
                                {{ project.due - project.payed | currency }}
                            </h3>
                            <h5>Reste dû</h5>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="text-right" ze-auth="{id_project : project.id, right : 'project'}">
                <span ng-click="showPlanning()" ng-class="isActive('planning') ? 'text-muted' : 'text-primary'">Planning</span>
                /
                <span ng-click="showCategories()" ng-class="isActive('categories') ? 'text-muted' : 'text-primary'">Gestion des catégories</span>
                /
                <span ng-click="showRights()" ng-class="isActive('rights') ? 'text-muted' : 'text-primary'">Gestion des droits</span>
            </div>
            <div class="checkbox" ng-if="isActive('planning')">
                <label>
                    <input type="checkbox" ng-model="options.completed">
                    Inclure les tâches déjà terminées
                </label>
            </div>

            <div ng-include="view" ng-if="project.id"></div>
        </div>
    </div>
</div>
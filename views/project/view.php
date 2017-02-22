<div id="breadcrumb">
    Gestion des projets
</div>

<div id="content">
    <div class="row">
        <div class="col-md-3">
            <div class="root">
                <project-tree
                    data-tree="tree.branches"
                    data-active-branch="activeCategory"
                </project-tree>
            </div>
        </div>
        <div class="col-md-9">
            <div class="pull-right">
                <a class='btn btn-xs btn-success' ng-href='/ng/com_zeapps_project/project/create/'>
                    <span class='fa fa-fw fa-plus' aria-hidden='true'></span> Projet
                </a>
                <a class='btn btn-xs btn-success' ng-href='/ng/com_zeapps_project/project/create/{{ activeCategory.data.id }}' ng-if="activeCategory.data.id" ze-auth="{id_project : activeCategory.data.id, right : 'project'}">
                    <span class='fa fa-fw fa-plus' aria-hidden='true'></span> Sous Projet
                </a>
                <a class='btn btn-xs btn-success' ng-href='/ng/com_zeapps_project/project/card/create/card/{{ activeCategory.data.id }}' ng-if="activeCategory.data.id" ze-auth="{id_project : activeCategory.data.id, right : 'card'}">
                    <span class='fa fa-fw fa-plus' aria-hidden='true'></span> Carte
                </a>
                <a class='btn btn-xs btn-success' ng-href='/ng/com_zeapps_project/project/card/create/deadline/{{ activeCategory.data.id }}' ng-if="activeCategory.data.id" ze-auth="{id_project : activeCategory.data.id, right : 'card'}">
                    <span class='fa fa-fw fa-plus' aria-hidden='true'></span> Deadline
                </a>
            </div>
            <div ng-if="activeCategory.data.id">
                <h3>
                    {{ activeCategory.data.title }}
                    <span ze-auth="{id_project : activeCategory.data.id, right : 'project'}">
                        <a type="button" class="btn btn-info btn-xs" ng-href="/ng/com_zeapps_project/project/edit/{{ activeCategory.data.id }}">
                            <i class="fa fa-fw fa-pencil" ></i>
                        </a>
                        <button type="button" class="btn btn-warning btn-xs" ng-click="archive_project(activeCategory.data.id)">
                            <i class="fa fa-fw fa-archive" ></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-xs" ng-click="delete_project(activeCategory.data.id)">
                            <i class="fa fa-fw fa-trash" ></i>
                        </button>
                    </span>
                </h3>
                <h4>
                    Demandeur : {{ activeCategory.data.name_company }}
                </h4>
                <h5>
                    Manager : {{ activeCategory.data.name_manager }}
                </h5>
                <div class="text-right" ze-auth="{id_project : activeCategory.data.id, right : 'project'}">
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
            </div>

            <div ng-include="view" ng-if="activeCategory.data.id"></div>
        </div>
    </div>
</div>
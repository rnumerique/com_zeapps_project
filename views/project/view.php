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
            </div>

            <h3>
                {{ project.title }}
                <span project-auth="{id_project : project.id, right : 'project'}">
                    <a type="button" class="btn btn-info btn-xs" ng-href="/ng/com_zeapps_project/project/edit/{{ project.id }}">
                        <i class="fa fa-fw fa-pencil" ></i>
                    </a>
                    <ze-btn fa="archive" hint="Archiver" color="warning" ng-click="archive_project(project.id)" ze-confirmation="Souhaitez-vous archiver ce projet ?"></ze-btn>
                    <ze-btn fa="trash" hint="Supprimer" color="danger" ng-click="delete_project(project.id)" ze-confirmation></ze-btn>
                </span>
                <ze-btn fa="play" hint="Timer" color="success" ng-click="startTimerProject()" project-auth="{id_project : project.id, right : 'card'}"></ze-btn>
            </h3>
            <h4>
                Demandeur : {{ project.name_company }}
            </h4>
            <h5>
                Manager : {{ project.name_manager }}
            </h5>


            <div project-auth="{id_project : project.id, right : 'accounting'}">
                <ze-postits postits="postits"></ze-postits>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" ng-click="goToTab('taches')" ng-class="isActive('taches') ? 'active' : ''">
                            <a href="#">
                                Tâches
                            </a>
                        </li>
                        <li role="presentation" ng-click="goToTab('deadlines')" ng-class="isActive('deadlines') ? 'active' : ''">
                            <a href="#">
                                Deadlines ({{ deadlines.length }})
                            </a>
                        </li>
                        <li role="presentation" ng-click="goToTab('calendar')" ng-class="isActive('calendar') ? 'active' : ''">
                            <a href="#">
                                Calendrier</a>
                        </li>
                        <li role="presentation" ng-click="goToTab('notes')" ng-class="isActive('notes') ? 'active' : ''">
                            <a href="#">
                                Notes ({{ comments.length }})
                            </a>
                        </li>
                        <li role="presentation" ng-click="goToTab('documents')" ng-class="isActive('documents') ? 'active' : ''">
                            <a href="#">
                                Documents ({{ documents.length }})
                            </a>
                        </li>
                        <li role="presentation" ng-click="goToTab('quotes')" ng-class="isActive('quotes') ? 'active' : ''" project-auth="{id_project : project.id, right : 'accounting'}">
                            <a href="#">
                                Devis ({{ quotes.length }})
                            </a>
                        </li>
                        <li role="presentation" ng-click="goToTab('invoices')" ng-class="isActive('invoices') ? 'active' : ''" project-auth="{id_project : project.id, right : 'accounting'}">
                            <a href="#">
                                Factures ({{ invoices.length }})
                            </a>
                        </li>
                        <li role="presentation" ng-click="goToTab('timers')" ng-class="isActive('timers') ? 'active' : ''" project-auth="{id_project : project.id, right : 'card'}">
                            <a href="#">
                                Temps ({{ timers.length }})
                            </a>
                        </li>
                        <li role="presentation" ng-click="goToTab('spendings')" ng-class="isActive('spendings') ? 'active' : ''" project-auth="{id_project : project.id, right : 'accounting'}">
                            <a href="#">
                                Dépenses ({{ spendings.length }})
                            </a>
                        </li>
                        <li role="presentation" ng-click="goToTab('categories')" ng-class="isActive('categories') ? 'active' : ''" project-auth="{id_project : project.id, right : 'project'}">
                            <a href="#">
                                Gestion des catégories ({{ categories.length }})
                            </a>
                        </li>
                        <li role="presentation" ng-click="goToTab('rights')" ng-class="isActive('rights') ? 'active' : ''" project-auth="{id_project : project.id, right : 'project'}">
                            <a href="#">
                                Gestion des utilisateurs ({{ project_users.length }})
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div ng-include="view" ng-if="project.id"></div>
        </div>
    </div>
</div>
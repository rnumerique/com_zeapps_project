<div id="breadcrumb">
    Vue d'ensemble des projets
</div>

<div id="content">
    <div class="row">
        <div class="col-md-4 col-md-push-8 form-inline text-right">
            <a class='btn btn-xs btn-success' ng-href='/ng/com_zeapps_project/project/create/'>
                <span class='fa fa-fw fa-plus' aria-hidden='true'></span> Projet
            </a>
        </div>
        <div class="col-md-8 col-md-pull-4">
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-fw fa-search"></i>
                </div>
                <input class="form-control" ng-model="filters.search" placeholder="Rechercher...">
            </div>
        </div>
    </div>

    <ze-postits postits="postits"></ze-postits>

    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" ng-click="goToTab()" ng-class="projectTab === '' ? 'active' : ''">
                    <a href="#">Tous ({{ (projects | projectFilter:{search:filters.search}).length }})</a>
                </li>
                <li role="presentation" ng-repeat="status in statuses | orderBy:'sort'" ng-click="goToTab(status.id)" ng-class="projectTab == status.id ? 'active' : ''">
                    <a href="#">{{ status.label }} ({{ (projects | projectFilter:{id_status:status.id,search:filters.search}).length }})</a>
                </li>
                <li role="presentation" ng-click="goToTab(0)" ng-class="projectTab === 0 ? 'active' : ''">
                    <a href="#">Sans statut ({{ (projects | projectFilter:{id_status:0,search:filters.search}).length }})</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="text-right">
                <div class="form-group">
                    <label for="details">
                        <input type="checkbox" id="details" name="details" ng-model="details" value="1">
                        Afficher le détail
                    </label>
                </div>
            </div>
            <table class="table table-striped table-hover table-condensed">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Projet</th>
                    <th>Responsable</th>
                    <th>Statut</th>
                    <th ng-if="details== 1" class="text-center">Montant</th>
                    <th ng-if="details== 1" class="text-center">Commission</th>
                    <th ng-if="details== 1" class="text-center">Marge</th>
                    <th ng-if="details== 1" class="text-center">Déjà facturé</th>
                    <th ng-if="details== 1" class="text-center">Reste dû</th>
                    <th class="text-center">Temps passé</th>
                    <th class="text-right">Deadline</th>
                    <th class="text-right"></th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="project in projects | orderBy:['name_company','breadcrumbs'] | projectFilter:filters" ng-click="goTo(project.id)">
                    <td>{{ project.id }}</td>
                    <td>{{ project.name_company ? project.name_company : project.name_contact }}</td>
                    <td>{{ project.breadcrumbs }}</td>
                    <td>{{ project.name_manager }}</td>
                    <td>{{ project.label_status }}</td>
                    <td ng-if="details== 1" class="text-center"><span ze-auth="{id_project : project.id, right : 'accounting'}">{{ project.due || '-' | currency }}</span></td>
                    <td ng-if="details== 1" class="text-center"><span ze-auth="{id_project : project.id, right : 'accounting'}">{{ project.commission || '-' | currency }}</span></td>
                    <td ng-if="details== 1" class="text-center"><span ze-auth="{id_project : project.id, right : 'accounting'}">{{ (project.due - project.commission) || '-' | currency }}</span></td>
                    <td ng-if="details== 1" class="text-center"><span ze-auth="{id_project : project.id, right : 'accounting'}">{{ project.payed || '-' | currency }}</span></td>
                    <td ng-if="details== 1" class="text-center"><span ze-auth="{id_project : project.id, right : 'accounting'}">{{ (project.due - project.payed) || '-' | currency }}</span></td>
                    <td class="text-center"><span ze-auth="{id_project : project.id, right : 'project'}">
                            {{ project.time_spent_formatted || "0h" }}
                            (<span ng-style="{color: project.timer_color}">{{(project.timer_ratio || "0")+ "%" }}</span>)
                    </span></td>
                    <td class="text-right">
                        {{ project.nextDeadline || '-' | date:'dd/MM/yyyy' }}
                    </td>
                    <td class="text-right">
                        <button type="button" class="btn btn-info btn-xs" ng-click="edit(project.id, $event)">
                            <i class="fa fa-fw fa-pencil" ></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-xs" ng-click="delete_project(project.id, $event)">
                            <i class="fa fa-fw fa-trash" ></i>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
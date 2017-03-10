<div id="breadcrumb">
    Vue d'ensemble des projets
</div>

<div id="content">
    <div class="row">
        <div class="col-md-4 col-md-push-8 form-inline text-right">
            <select class="form-control input-sm" ng-model="view">
                <option value="0">Affichage Commercial</option>
                <option value="1">Affichage Scrum</option>
            </select>
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
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" ng-click="goToTab()" ng-class="tab === '' ? 'active' : ''">
                    <a href="#">Tous ({{ projects.length }})</a>
                </li>
                <li role="presentation" ng-repeat="status in statuses | orderBy:'sort'" ng-click="goToTab(status.id)" ng-class="tab == status.id ? 'active' : ''">
                    <a href="#">{{ status.label }} ({{ (projects | filter:{id_status:status.id}).length }})</a>
                </li>
                <li role="presentation" ng-click="goToTab(0)" ng-class="tab === 0 ? 'active' : ''">
                    <a href="#">Sans statut ({{ (projects | filter:{id_status:0}).length }})</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <table class="col-xs-12 text-center postits">
            <tr ng-if="view === '0'">
                <td>
                    <div class="postit">
                        <h3>
                            {{ totals.due | currency }}
                        </h3>
                        <h5>Total montant</h5>
                    </div>
                </td>
                <td>
                    <div class="postit">
                        <h3>
                            {{ totals.commission | currency }}
                        </h3>
                        <h5>Total commission</h5>
                    </div>
                </td>
                <td>
                    <div class="postit">
                        <h3>
                            {{ totals.benefit | currency }}
                        </h3>
                        <h5>Total marge</h5>
                    </div>
                </td>
                <td>
                    <div class="postit">
                        <h3>
                            {{ totals.payed | currency }}
                        </h3>
                        <h5>Total deja facturé</h5>
                    </div>
                </td>
                <td>
                    <div class="postit">
                        <h3>
                            {{ totals.leftToPay | currency }}
                        </h3>
                        <h5>Total reste dû</h5>
                    </div>
                </td>
            </tr>
            <tr ng-if="view === '1'">
                <td>
                    <div class="postit">
                        <h3>
                            {{ totals.nbSandbox }}
                        </h3>
                        <h5>Total bac à sable</h5>
                    </div>
                </td>
                <td>
                    <div class="postit">
                        <h3>
                            {{ totals.nbBacklog }}
                        </h3>
                        <h5>Total backlog</h5>
                    </div>
                </td>
                <td>
                    <div class="postit">
                        <h3>
                            {{ totals.nbOngoing }}
                        </h3>
                        <h5>Total actives</h5>
                    </div>
                </td>
                <td>
                    <div class="postit">
                        <h3>
                            {{ totals.nbQuality }}
                        </h3>
                        <h5>Total qualité</h5>
                    </div>
                </td>
                <td>
                    <div class="postit">
                        <h3>
                            {{ totals.nbNext }}
                        </h3>
                        <h5>Total futurs sprints</h5>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-hover table-condensed">
                <thead>
                <tr>
                    <th>Client</th>
                    <th>Projet</th>
                    <th>Responsable</th>
                    <th>Statut</th>
                    <th class="text-center">Montant</th>
                    <th ng-if="view === '0'" class="text-center">Commission</th>
                    <th ng-if="view === '0'" class="text-center">Marge</th>
                    <th ng-if="view === '0'" class="text-center">Déjà facturé</th>
                    <th ng-if="view === '0'" class="text-center">Reste dû</th>
                    <th ng-if="view === '1'" class="text-center">Bac à sable</th>
                    <th ng-if="view === '1'" class="text-center">Backlog</th>
                    <th ng-if="view === '1'" class="text-center">Actives</th>
                    <th ng-if="view === '1'" class="text-center">Qualité</th>
                    <th ng-if="view === '1'" class="text-center">Futurs sprints</th>
                    <th class="text-right">Deadline</th>
                    <th class="text-right"></th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="project in projects | orderBy:['name_company','breadcrumbs'] | projectFilter:filters" ng-click="goTo(project.id)">
                    <td>{{ project.name_company ? project.name_company : project.name_contact }}</td>
                    <td>{{ project.breadcrumbs }}</td>
                    <td>{{ project.name_manager }}</td>
                    <td>{{ project.label_status }}</td>
                    <td class="text-center">{{ project.due || '-' | currency }}</td>
                    <td ng-if="view === '0'" class="text-center">{{ project.commission || '-' | currency }}</td>
                    <td ng-if="view === '0'" class="text-center">{{ (project.due - project.commission) || '-' | currency }}</td>
                    <td ng-if="view === '0'" class="text-center">{{ project.payed || '-' | currency }}</td>
                    <td ng-if="view === '0'" class="text-center">{{ (project.due - project.payed) || '-' | currency }}</td>
                    <td ng-if="view === '1'" class="text-center">{{ project.nbSandbox || '' }}</td>
                    <td ng-if="view === '1'" class="text-center">{{ project.nbBacklog || '' }}</td>
                    <td ng-if="view === '1'" class="text-center">{{ project.nbOngoing || '' }}</td>
                    <td ng-if="view === '1'" class="text-center">{{ project.nbQuality || '' }}</td>
                    <td ng-if="view === '1'" class="text-center">{{ project.nbNext || '' }}</td>
                    <td class="text-right">
                        {{ project.nextDeadline || '-' | date:'dd/MM/yyyy' }}
                    </td>
                    <td class="text-right">
                        <a type="button" class="btn btn-info btn-xs" ng-href="/ng/com_zeapps_project/project/edit/{{ project.id }}">
                            <i class="fa fa-fw fa-pencil" ></i>
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
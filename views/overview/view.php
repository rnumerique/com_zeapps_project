<div id="breadcrumb">
    Vue d'ensemble des projets
</div>

<div id="content">
    <div class="row">
        <div class="col-md-12 text-right">
            <a class='btn btn-xs btn-success' ng-href='/ng/com_zeapps_project/project/create/'>
                <span class='fa fa-fw fa-plus' aria-hidden='true'></span> Projet
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" ng-click="goToTab()" ng-class="tab == 0 ? 'active' : ''">
                    <a href="#">Tous</a>
                </li>
                <li role="presentation" ng-repeat="status in statuses" ng-click="goToTab(status.id)" ng-class="tab == status.id ? 'active' : ''">
                    <a href="#">{{ status.label }}</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-hover table-condensed">
                <thead>
                <tr>
                    <th>Client</th>
                    <th>Projet</th>
                    <th>Status</th>
                    <th class="text-center">Bac à sable</th>
                    <th class="text-center">Backlog</th>
                    <th class="text-center">Actives</th>
                    <th class="text-center">Qualité</th>
                    <th class="text-center">Futurs sprints</th>
                    <th class="text-right">Prochaine deadline</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="project in projects | orderBy:['name_company','breadcrumbs'] | filter:tabFilter" ng-click="goTo(project.id)">
                    <td>{{ project.name_company ? project.name_company : project.name_contact }}</td>
                    <td>{{ project.breadcrumbs }}</td>
                    <td>{{ project.label_status }}</td>
                    <td class="text-center">{{ project.nbSandbox }}</td>
                    <td class="text-center">{{ project.nbBacklog }}</td>
                    <td class="text-center">{{ project.nbOngoing }}</td>
                    <td class="text-center">{{ project.nbQuality }}</td>
                    <td class="text-center">{{ project.nbNext }}</td>
                    <td class="text-right">
                        {{ project.nextDeadline || '-' | date:'dd MMMM yyyy' }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
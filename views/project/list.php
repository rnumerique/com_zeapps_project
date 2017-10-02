<div id="breadcrumb">
    Vue d'ensemble des projets
</div>

<div id="content">

    <div ze-auth="com_zeapps_project_financial">
        <ze-postits postits="postits"></ze-postits>
    </div>

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

            <div class="row">
                <div class="col-md-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-fw fa-search"></i>
                        </div>
                        <input class="form-control" ng-model="filters.search" placeholder="Rechercher...">
                    </div>
                </div>
                <div class="col-md-4 form-inline text-right" ze-auth="com_zeapps_project_management">
                    <a class='btn btn-xs btn-success' ng-href='/ng/com_zeapps_project/project/create/'>
                        <span class='fa fa-fw fa-plus' aria-hidden='true'></span> Projet
                    </a>
                    <ze-btn fa="download" color="info" hint="Télécharger" direction="left" ng-click="print()"></ze-btn>
                </div>
            </div>

            <div ng-include="'/com_zeapps_project/project/partial/'"></div>

        </div>
    </div>
</div>
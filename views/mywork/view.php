<div id="breadcrumb">
    Mon travail
</div>

<div id="content" class="mywork">
    <ze-postits postits="postits"></ze-postits>

    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" ng-click="goToTab('')" ng-class="myworkTab === '' ? 'active' : ''">
                    <a href="#">Tous</a>
                </li>
                <li role="presentation" ng-click="goToTab('currents')" ng-class="myworkTab === 'currents' ? 'active' : ''">
                    <a href="#">Aujourd'hui + retards</a>
                </li>
                <li role="presentation" ng-click="goToTab('actuals')" ng-class="myworkTab === 'actuals' ? 'active' : ''">
                    <a href="#">Aujourd'hui</a>
                </li>
                <li role="presentation" ng-click="goToTab('leftovers')" ng-class="myworkTab === 'leftovers' ? 'active' : ''">
                    <a href="#">Retards</a>
                </li>
                <li role="presentation" ng-click="goToTab('futures')" ng-class="myworkTab === 'futures' ? 'active' : ''">
                    <a href="#">A venir</a>
                </li>
                <li role="presentation" ng-click="goToTab('nodates')" ng-class="myworkTab === 'nodates' ? 'active' : ''">
                    <a href="#">Sans échéance</a>
                </li>
                <li role="presentation" ng-click="goToTab(priority.id)" ng-class="myworkTab === priority.id ? 'active' : ''" ng-repeat="priority in priorities" ng-style="{'background-color': priority.color}">
                    <a href="#">{{priority.label}}</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-condensed table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Projet</th>
                    <th>Tâche</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat-start="date in dates | orderBy:'due_date'" ng-if="cardsByDate[date.due_date] && cardsByDate[date.due_date].length != 0" class="date-cell">
                    <td colspan="6">
                        {{ (date.due_date != '0000-00-00' ? date.due_date : 'Sans échéance') | date:'dd MMMM yyyy' }}
                    </td>
                </tr>
                <tr ng-repeat-end ng-repeat="card in cardsByDate[date.due_date] | orderBy:['due_date', 'id_priority', 'project_title']" ng-style="{'background-color': card.priority_color}">
                    <td>{{card.id}}</td>
                    <td>{{card.name_company ? card.name_company : card.name_contact}}</td>
                    <td>
                        <a href="/ng/com_zeapps_project/project/{{card.id_project}}">{{card.project_title}}</a>
                    </td>
                    <td>{{card.title}}</td>
                    <td class="text-right no-wrap">
                        <ze-btn fa="eye" hint="Détails" color="info" direction="left" ng-click="detailCard(card)"></ze-btn>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
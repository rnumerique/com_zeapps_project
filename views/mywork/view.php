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
                <li role="presentation" ng-click="goToTab('leftovers')" ng-class="myworkTab === 'leftovers' ? 'active' : ''">
                    <a href="#">Retards</a>
                </li>
                <li role="presentation" ng-click="goToTab('actuals')" ng-class="myworkTab === 'actuals' ? 'active' : ''">
                    <a href="#">Du jour</a>
                </li>
                <li role="presentation" ng-click="goToTab('futures')" ng-class="myworkTab === 'futures' ? 'active' : ''">
                    <a href="#">Futures</a>
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
                    <th>Date limite</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="card in cards | orderBy:['due_date', 'id_priority', 'project_title']" ng-style="{'background-color': card.priority_color}">
                    <td>{{card.id}}</td>
                    <td>{{card.name_company ? card.name_company : card.name_contact}}</td>
                    <td>{{card.project_title}}</td>
                    <td>{{card.title}}</td>
                    <td ng-class="'text-' + compareDates(card.due_date)">{{(card.due_date || '-') | date:'dd/MM/yyyy'}}</td>
                    <td class="text-right no-wrap">
                        <button type="button" class="btn btn-info btn-xs" ng-click="detailCard(card)">
                            <i class="fa fa-fw fa-eye" ></i> <span class="hover-hint">détails</span>
                        </button>
                        <button type="button" class="btn btn-success btn-xs" ng-click="complete(card)">
                            <i class="fa fa-fw fa-check" ></i> <span class="hover-hint">valider</span>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div id="breadcrumb">
    Scrum
</div>

<div id="content">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <select class="form-control" ng-model="options.projectId" ng-change="findActiveSprint()"
                        ng-options="projects.indexOf(project) as project.title for project in projects">
                    <option value="none">
                        Aucun projet selectionné
                    </option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <select class="form-control" ng-model="options.sprintId" ng-change="selectSprint()"
                        ng-options="projects[options.projectId].sprints.indexOf(sprint) as sprint.title for sprint in projects[options.projectId].sprints">
                </select>
            </div>
        </div>
        <div class="col-md-2 text-center">
            <button type="button" class="btn btn-success btn-xs" ng-click="new()" ze-auth="{id_project : projects[options.projectId].id, right : 'sprint'}">
                <i class="fa fa-fw fa-plus" ></i> Sprint
            </button>
            <button type="button" class="btn btn-primary btn-xs" ng-click="prev()" ng-disabled="!hasPrev()">
                <i class="fa fa-fw fa-chevron-left" ></i>
            </button>
            <button type="button" class="btn btn-primary btn-xs" ng-click="next()" ng-disabled="!hasNext()">
                <i class="fa fa-fw fa-chevron-right" ></i>
            </button>
        </div>
    </div>

    <div class="row" ng-if="options.projectId !== 'none' && !current" ze-auth="{id_project : projects[options.projectId].id, right : 'sprint'}">
        <div class="col-md-12">
            <h1 class="text-center pointer" ng-click="new()">
                <i class="fa fa-fw fa-plus" ></i> Créer un sprint pour ce projet
            </h1>
        </div>
    </div>

    <div class="row" ng-show="current !== undefined">
        <div class="col-md-12">
            <h3 class="text-center">
                {{ current.title }} {{ current.active === 'Y' ? '- En cours' : '' }}
                <span ze-auth="{id_project : projects[options.projectId].id, right : 'sprint'}">
                    <button type="button" class="btn btn-info btn-xs" ng-click="edit()">
                        <i class="fa fa-fw fa-pencil" ></i>
                    </button>
                    <button type="button" class="btn btn-success btn-xs" ng-click="finalize()" ng-if="current.completed === 'N' && current.active === 'Y'">
                        <i class="fa fa-fw fa-check" ></i> Clôturer
                    </button>
                    <button type="button" class="btn btn-danger btn-xs" ng-click="delete()" ng-if="current.completed === 'N' && current.active === 'N'">
                        <i class="fa fa-fw fa-trash" ></i>
                    </button>
                </span>
            </h3>
            <div class="text-center">
                {{ current.start_date | date:'dd/MM/yyyy' }}
                au
                {{ current.due_date | date:'dd/MM/yyyy' }}
            </div>
            <div class="text-right" ze-auth="{id_project : projects[options.projectId].id, right : 'sprint'}">
                <button type="button" class="btn btn-warning btn-xs" ng-click="addCards()" ng-if="current.completed === 'N'">
                    Ajouter des cartes
                </button>
                <a class="btn btn-success btn-xs" ng-href="/ng/com_zeapps_project/sprint/create/card/{{current.id_project}}/{{current.id}}" ng-if="current.completed === 'N'">
                    <i class="fa fa-fw fa-plus"></i> Carte
                </a>
            </div>
        </div>
    </div>

    <div class="row" ng-if="current">
        <div class="col-md-12">
            <div class="scrum table clearfix">
                <div class="table-row">
                    <div class="table-cell"></div>
                    <div class="table-cell scrum_step" ng-repeat="step_name in steps">
                        <h5 class="text-center">{{ step_name }}</h5>
                    </div>
                </div>
                <div class="table-row scrum_cards" ng-repeat="category in projects[options.projectId].categories">
                    <div class="table-cell scrum_category">
                        <div class="scrum_category_inner">
                            {{ category.title }}
                        </div>
                    </div>
                    <div ui-sortable="sortable" class="sortableContainer table-cell scrum_step" ng-repeat="(step, step_name) in steps" ng-model="current.cards[category.id][step]" data-step="{{ step }}" data-category="{{ category.id }}">
                        <div class="scrum_card card_{{ card.id }}" ng-repeat="card in current.cards[category.id][step]" data-id="{{ card.id }}" ng-click="detailCard(card)">
                            <div>
                                {{ '#' + card.id }}
                                <div class="pull-right">
                                    <i class="fa fa-fw fa-commenting-o" ng-if="card.comments.length > 0"></i>
                                    <i class="fa fa-fw fa-pencil" ng-click="editCard(card, $event)" ze-auth="{id_project : projects[options.projectId].id, right : 'card'}"></i>
                                </div>
                            </div>
                            <h5 class="text-center">
                                {{ card.title }}
                            </h5>
                            <div class="clearfix">
                                <span ng-if="card.due_date != '0000-00-00'"><i class="fa fa-fw fa-clock-o"></i>{{ card.due_date | date:'dd/MM/yyyy'}}</span>
                                <span class="pull-right" ng-if="card.name_assigned_to"><i class="fa fa-fw fa-user"></i><i>{{ card.name_assigned_to }}</i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
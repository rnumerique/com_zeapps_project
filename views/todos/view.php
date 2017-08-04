<div id="breadcrumb">
    Mes To-Dos
</div>

<div id="content" class="project_todos">
    <div class="row">
        <div class="col-md-3 todo_categories">
            <h4>Catégories</h4>

            <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control" ng-model="formCategory.label" placeholder="Nouvelle catégorie" ng-keypress="keyEventCreateCategory($event)">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-success" ng-click="createCategory()">
                            <i class="fa fa-fw fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="todo_category" ng-repeat="category in categories" ng-class="isSelected(category.id) ? 'active' : ''" ng-click="loadCategory(category.id)">
                {{ category.label }}
            </div>
            <div class="todo_category" ng-class="isSelected(0) ? 'active' : ''" ng-click="loadCategory(0)">
                Sans catégorie
            </div>
        </div>

        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" ng-model="form.label" placeholder="Votre To-Do..." ng-keypress="keyEventCreateTodo($event)">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-success" ng-click="createTodo()">
                                    <i class="fa fa-fw fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table todos">
                <div class="table-row" ng-repeat="todo in todos">
                    <div class="text-center table-cell table-min" ng-click="validate(todo)">
                        <i class="fa fa-fw fa-circle-o"></i>
                        <i class="fa fa-fw fa-check onhover"></i>
                    </div>
                    <div class="table-cell">
                        <div ng-if="!todo.edit">
                            {{ todo.label }}
                        </div>
                        <div ng-if="todo.edit">
                            <input type="text" class="form-control input-sm" ng-model="todo.label" ng-blur="editTodo(todo)" ng-keypress="keyEventEditTodo($event, todo)" ze-focus="todo.edit">
                        </div>
                    </div>
                    <div class="text-center text-info table-cell table-min" ng-click="todo.edit = !todo.edit">
                        <i class="fa fa-fw fa-pencil"></i>
                    </div>
                    <div class="text-center text-danger table-cell table-min" ng-click="deleteTodo(todo, 'todos')">
                        <i class="fa fa-fw fa-trash"></i>
                    </div>
                </div>
                <div class="row" ng-if="todos.length < 1">
                    <div class="col-md-12">
                        Vous n'avez aucun To-Do actuellement.
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="text-muted" ng-show="history.length > 0" ng-click="showHistory = !showHistory">
                        Historique
                        <i class="fa fa-fw" ng-class="showHistory ? 'fa-caret-up' : 'fa-caret-down'"></i>
                    </div>
                </div>
            </div>

            <div class="table history" ng-show="showHistory">
                <div class="table-row" ng-repeat="todo in history">
                    <div class="text-center table-cell table-min" ng-click="cancel(todo)">
                        <i class="fa fa-fw fa-undo"></i>
                    </div>
                    <div class="table-cell">{{ todo.label }}</div>
                    <div class="text-center text-danger table-cell table-min" ng-click="deleteTodo(todo, 'history')">
                        <i class="fa fa-fw fa-trash"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
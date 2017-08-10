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

            <div ui-sortable="sortableCategories" class="sortableContainerCategories table" ng-model="categories">
                <div class="todo_category table-row" ng-repeat="category in categories" ng-class="isSelected(category.id) ? 'active' : ''" ng-click="loadCategory(category.id)">
                    <div class="table-cell" ng-class="category.edit ? '' : 'handleCategories'">
                        <div ng-if="!category.edit">
                            {{ category.label }}
                        </div>
                        <div ng-if="category.edit">
                            <input type="text" class="form-control input-sm" ng-model="category.label" ng-blur="editCategory(category)" ng-keypress="keyEventEditCategory($event, category)" ze-focus="category.edit">
                        </div>
                    </div>
                    <div class="text-center text-info table-cell table-min" ng-click="category.edit = !category.edit">
                        <i class="fa fa-fw fa-pencil"></i>
                    </div>
                    <div class="text-center text-danger table-cell table-min" ng-click="deleteCategory(category)" ze-confirmation="Souhaitez-vous supprimer la catégorie ainsi que tous les todos associés ?">
                        <i class="fa fa-fw fa-trash"></i>
                    </div>
                </div>
            </div>
            <div class="todo_category defaut_category" ng-class="isSelected(0) ? 'active' : ''" ng-click="loadCategory(0)">
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

            <div class="table todos" ui-sortable="sortableTodos" class="sortableContainerTodos" ng-model="todos">
                <div class="table-row" ng-repeat="todo in todos">
                    <div class="text-center table-cell table-min" ng-click="validate(todo)">
                        <i class="fa fa-fw fa-circle-o"></i>
                        <i class="fa fa-fw fa-check onhover"></i>
                    </div>
                    <div class="table-cell" ng-class="todo.edit ? '' : 'handleTodos'">
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
                    <div class="text-center text-danger table-cell table-min" ng-click="deleteTodo(todo, 'todos')" ze-confirmation>
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
                    <div class="text-center text-danger table-cell table-min" ng-click="deleteTodo(todo, 'history')" ze-confirmation>
                        <i class="fa fa-fw fa-trash"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
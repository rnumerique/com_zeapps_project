app.controller("ComZeappsProjectTodosCtrl", ["$scope", "$route", "$routeParams", "$location", "$rootScope", "zeHttp", "$timeout",
	function ($scope, $route, $routeParams, $location, $rootScope, zhttp, $timeout) {

        $scope.$parent.loadMenu("com_ze_apps_project", "com_zeapps_projects_todos");

        $scope.form = {
        	label : ""
		};
        $scope.formCategory = {
            label : ""
        };
        $scope.categories = [];
        $scope.todos = [];
        $scope.history = [];
        $scope.categories = [];
        $scope.showHistory = false;

        $scope.sortableTodos = {
            connectWith: ".sortableContainerTodos",
            disabled: false,
            axis: "y",
			handle: '.handleTodos',
            opacity: 1,
            delay: 200,
            stop: sortableStopTodos
        };

        $scope.sortableCategories = {
            connectWith: ".sortableContainerCategories",
            disabled: false,
            axis: "y",
            handle: '.handleCategories',
            opacity: 1,
            delay: 200,
            stop: sortableStopCategories
        };

        var currentCategory = 0;

        $scope.focus = focus;

        $scope.createTodo = createTodo;
        $scope.keyEventCreateTodo = keyEventCreateTodo;
        $scope.editTodo = editTodo;
        $scope.keyEventEditTodo = keyEventEditTodo;
        $scope.validate = validate;
        $scope.cancel = cancel;
        $scope.deleteTodo = deleteTodo;

        $scope.isSelected = isSelected;
        $scope.loadCategory = loadCategory;
        $scope.createCategory = createCategory;
        $scope.keyEventCreateCategory = keyEventCreateCategory;
        $scope.editCategory = editCategory;
        $scope.keyEventEditCategory = keyEventEditCategory;
        $scope.deleteCategory = deleteCategory;

		zhttp.project.todos.all().then(function(response){
        	if(response.data && response.data != "false"){
				$scope.todos = response.data.todos;
				$scope.history = response.data.history;
				$scope.categories = response.data.categories;

				if($scope.categories[0]){
					currentCategory = $scope.categories[0].id;
				}
			}
		});

		function focus(type, target){
		    target.edit = true;
            $timeout(function(){angular.element("#" + type + target.id).focus();});
        }

		function createTodo(){
			$scope.form.sort = $scope.todos.length;
			$scope.form.id_category = currentCategory;

			var formatted_data = angular.toJson($scope.form);

			zhttp.project.todos.post(formatted_data).then(function(response){
				if(response.data && response.data != "false"){
					$scope.form.id = response.data;
					$scope.todos.push(Object.create($scope.form));

					$scope.form = {
						label : ""
					};
				}
			});
		}

		function editTodo(todo){
			var formatted_data = angular.toJson(todo);
            zhttp.project.todos.post(formatted_data).then(function(response){
            	if(response.data && response.data != 'false'){
            		todo.edit = false;
				}
			});
		}

		function validate(todo){
			zhttp.project.todos.validate(todo.id).then(function(response){
				if(response.data && response.data != "false"){
					todo.done = 1;
					$scope.todos.splice($scope.todos.indexOf(todo), 1);
					$scope.history.unshift(Object.create(todo));
					if($scope.history.length > 5) {
                        $scope.history.splice(-1, 1);
                    }
				}
			});
		}

		function cancel(todo){
            zhttp.project.todos.cancel(todo.id).then(function(response){
                if(response.data && response.data != "false"){
                    todo.done = 0;
                    $scope.history.splice($scope.history.indexOf(todo), 1);
                    todo.sort = $scope.todos.length;
                    $scope.todos.push(todo);
                }
            });
		}

		function deleteTodo(todo, src){
			zhttp.project.todos.del(todo.id).then(function(response){
				if(response.data && response.data != "false"){
					$scope[src].splice($scope[src].indexOf(todo), 1);
				}
			});
		}

		function keyEventCreateTodo($event){
			if($event.which === 13){
                createTodo();
			}
		}

		function keyEventEditTodo($event, todo){
			if($event.which === 13){
                editTodo(todo);
			}
		}

		function createCategory(){
            $scope.formCategory.sort = $scope.categories.length;

            var formatted_data = angular.toJson($scope.formCategory);
			zhttp.project.todos.postCategory(formatted_data).then(function(response){
				if(response.data && response.data != "false"){
					$scope.formCategory.id = response.data;
					$scope.categories.push(Object.create($scope.formCategory));

					$scope.formCategory = {
						label : ''
					};
				}
			});
		}

        function editCategory(category){
            var formatted_data = angular.toJson(category);
            zhttp.project.todos.postCategory(formatted_data).then(function(response){
                if(response.data && response.data != 'false'){
                    category.edit = false;
                }
            });
        }

        function deleteCategory(category){
            zhttp.project.todos.delCategory(category.id).then(function(response){
                if(response.data && response.data != "false"){
                    $scope.categories.splice($scope.categories.indexOf(category), 1);
                    loadCategory(0);
                }
            });
        }

        function keyEventCreateCategory($event){
            if($event.which === 13){
                createCategory();
            }
        }

        function keyEventEditCategory($event, category){
            if($event.which === 13){
                editCategory(category);
            }
        }

        function loadCategory(id){
            currentCategory = id;
            zhttp.project.todos.get_todos(id).then(function(response){
            	if(response.data && response.data != 'false'){
            		$scope.todos = response.data.todos;
            		$scope.history = response.data.history;
				}
			});
		}

        function isSelected(id){
            return currentCategory === id;
        }



        function sortableStopTodos() {
            angular.forEach($scope.todos, function(todo, pos){
				todo.sort = pos;
			});

            var formatted_data = angular.toJson($scope.todos);
            zhttp.project.todos.todos_position(formatted_data);
        }

        function sortableStopCategories() {
            angular.forEach($scope.categories, function(category, pos){
                category.sort = pos;
            });

            var formatted_data = angular.toJson($scope.categories);
            zhttp.project.todos.categories_position(formatted_data);
        }

	}]);
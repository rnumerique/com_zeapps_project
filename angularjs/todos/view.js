app.controller("ComZeappsProjectTodosCtrl", ["$scope", "$route", "$routeParams", "$location", "$rootScope", "zeHttp", "zeapps_modal", "$uibModal",
	function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal) {

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

        var currentCategory = 0;

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

        function keyEventCreateCategory($event){
            if($event.which === 13){
                createCategory();
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

	}]);
app.controller("ComZeappsProjectFormCategoriesCtrl", ["$scope", "$route", "$routeParams", "$location", "$rootScope", "zeHttp", "zeapps_modal", "menu",
	function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, menu) {

        menu("com_ze_apps_project", "com_zeapps_projects_management");

		$scope.form = {};

		$scope.loadProject = loadProject;
		$scope.removeProject = removeProject;
		$scope.success = success;
		$scope.cancel = cancel;

		if($routeParams.id){ // Edit
			zhttp.project.category.get($routeParams.id).then(function(response){
				if(response.data && response.data != "false"){
					$scope.form = response.data;
					zhttp.project.project.get($scope.form.id_project).then(function(response){
						if(response.data && response.data != "false"){
							$scope.form.title_project = response.data.project.breadcrumbs;
						}
					});
				}
			});
		}
		else if($routeParams.id_project){ // Project
			zhttp.project.project.get($routeParams.id_project).then(function(response){
				if(response.data && response.data != "false"){
					$scope.form.id_project = response.data.project.id;
					$scope.form.title_project = response.data.project.breadcrumbs;

					var r = (Math.round(Math.random()* 127) + 127).toString(16);
					var g = (Math.round(Math.random()* 127) + 127).toString(16);
					var b = (Math.round(Math.random()* 127) + 127).toString(16);

					$scope.form.color = "#" + r + g + b;
				}
			});
		}

		function loadProject() {
			zeapps_modal.loadModule("com_zeapps_project", "search_project", {}, function(objReturn) {
				if (objReturn) {
					$scope.form.id_project = objReturn.id;
					$scope.form.title_project = objReturn.breadcrumbs;
				} else {
					$scope.form.id_project = 0;
					$scope.form.title_project = "";
				}
			});
		}

		function removeProject() {
			$scope.form.id_project = 0;
			$scope.form.title_project = "";
		}

		function success(){

			delete $scope.form.title_project;

			var formatted_data = angular.toJson($scope.form);

			zhttp.project.category.post(formatted_data).then(function(response){
				if(response.data && response.data != "false"){
					$location.url("/ng/com_zeapps_project/project/" + $scope.form.id_project);
				}
			});
		}

		function cancel(){
			var id = $scope.form.id_project || "";
			$location.url("/ng/com_zeapps_project/project/" + id);
		}

	}]);
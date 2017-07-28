app.controller("ComZeappsSprintViewCtrl", ["$scope", "$route", "$routeParams", "$location", "$rootScope", "zeHttp", "zeapps_modal", "$uibModal",
	function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal) {

		$scope.$parent.loadMenu("com_ze_apps_project", "com_zeapps_projects_sprint");

		$scope.options = {
			"projectId": "all"
		};

		$scope.filterProjects = filterProjects;
		$scope.goTo = goTo;

		zhttp.project.filter.get_all("sprint").then(function(response){
			if(response.data && response.data != "false"){
				$scope.companies = response.data.companies;
				$scope.managers = response.data.managers;
				$scope.dates = response.data.dates;
				$scope.assigned = response.data.assigned;
			}
		});

		zhttp.project.sprint.get_all().then(function(response){
			if(response.data && response.data != "false") {
				$scope.projects = response.data;
			}
		});

		function filterProjects(){
			if($scope.options.projectId === "all"){
				delete $scope.options.id;
			}
			else {
				$scope.options.id = [];
				$scope.options.id.push($scope.options.projectId);
				zhttp.project.project.get_childs($scope.options.projectId).then(function (response) {
					if (response.data && response.data != "false") {
						var subProjects = response.data;
						if(subProjects.length === 0){
							$location.url("/ng/com_zeapps_project/sprint/" + $scope.options.projectId);
						}
						angular.forEach(subProjects, function (subProject) {
							$scope.options.id.push(subProject.id);
						});
					}
				});
			}
		}

		function goTo(sprint){
			$location.url("/ng/com_zeapps_project/sprint/" + sprint.id_project + "/" + sprint.id);
		}
	}]);
app.controller("ComZeappsProjectFormTimersCtrl", ["$scope", "$route", "$routeParams", "$location", "$rootScope", "zeHttp", "zeapps_modal", "$uibModal",
	function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal) {

		$scope.$parent.loadMenu("com_ze_apps_project", "com_zeapps_projects_management");

		$scope.form = {};

		$scope.success = success;
		$scope.cancel = cancel;

		if($routeParams.id){ // Edit
			zhttp.project.timer.get($routeParams.id).then(function(response){
				if(response.data && response.data != "false"){
					$scope.form = response.data;
					$scope.start = {
						h : moment($scope.form.start_time).hour(),
						m : moment($scope.form.start_time).minute(),
						d : moment($scope.form.start_time).date(),
						M : "" + moment($scope.form.start_time).month(),
						y : moment($scope.form.start_time).year()
					};
					$scope.end = {
						h : moment($scope.form.stop_time).hour(),
						m : moment($scope.form.stop_time).minute(),
						d : moment($scope.form.stop_time).date(),
						M : "" + moment($scope.form.stop_time).month(),
						y : moment($scope.form.stop_time).year()
					};
				}
			});
		}

		function success(){
			$scope.form.start_time = $scope.start.y + "-" + (parseInt($scope.start.M)+1) + "-" + $scope.start.d + " " + $scope.start.h + ":" + $scope.start.m + ":00";
			$scope.form.stop_time = $scope.end.y + "-" + (parseInt($scope.end.M)+1) + "-" + $scope.end.d + " " + $scope.end.h + ":" + $scope.end.m + ":00";

			var formatted_data = angular.toJson($scope.form);

			zhttp.project.timer.post(formatted_data).then(function(response){
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
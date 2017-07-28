app.controller("ComZeappsProjectStatusConfigCtrl", ["$scope", "$route", "$routeParams", "$location", "$rootScope", "zeHttp", "zeapps_modal", "$uibModal",
	function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal) {

		$scope.$parent.loadMenu("com_ze_apps_config", "com_ze_apps_project_statuses");

		$scope.form = {};
		$scope.newLine = {};

		$scope.createLine = createLine;
		$scope.cancelLine = cancelLine;
		$scope.delete = del;
		$scope.cancel = cancel;
		$scope.success = success;

		zhttp.project.status.get_all().then(function(response){
			if(response.data && response.data != "false"){
				angular.forEach(response.data, function(status){
					status.sort = parseInt(status.sort);
				});
				$rootScope.statuses = response.data;
				$scope.form.statuses = angular.fromJson(angular.toJson(response.data));
			}
		});

		function createLine(){
			var formatted_data = angular.toJson($scope.newLine);
			zhttp.project.status.save(formatted_data).then(function(response){
				if(response.data && response.data != "false"){
					$scope.newLine.id = response.data;
					$scope.form.statuses.push(angular.fromJson(angular.toJson($scope.newLine)));
					$rootScope.statuses.push($scope.newLine);
					$scope.newLine = {};
				}
			});
		}

		function cancelLine(){
			$scope.newLine = {};
		}

		function del(index){
			var id = $scope.form.statuses[index].id;
			zhttp.project.status.del(id).then(function(response){
				if(response.data && response.data != "false"){
					$scope.form.statuses.splice(index, 1);
					$rootScope.statuses.splice(index, 1);
				}
			});
		}

		function cancel(){
			$scope.form.statuses = angular.fromJson(angular.toJson($rootScope.statuses));
		}

		function success(){
			var formatted_data = angular.toJson($scope.form.statuses);
			zhttp.project.status.save_all(formatted_data).then(function(response){
				if(response.data && response.data != "false"){
					$rootScope.statuses = angular.fromJson(angular.toJson($scope.form.statuses));
				}
			});
		}
	}]);
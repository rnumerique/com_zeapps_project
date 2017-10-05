app.controller("ComZeappsProjectStatusConfigCtrl", ["$scope", "$rootScope", "zeHttp", "menu",
	function ($scope, $rootScope, zhttp, menu) {

        menu("com_ze_apps_config", "com_ze_apps_project_statuses");

        $scope.templateForm = "/com_zeapps_project/status/form_modal";

		$scope.add = add;
		$scope.edit = edit;
		$scope.delete = del;

		function add(status){
			var formatted_data = angular.toJson(status);
			zhttp.project.status.save(formatted_data).then(function(response){
				if(response.data && response.data != "false"){
					status.id = response.data;
					$rootScope.statuses.push(status);
				}
			});
		}

        function edit(status){
            var formatted_data = angular.toJson(status);
            zhttp.project.status.save(formatted_data);
        }

		function del(status){
			zhttp.project.status.del(status.id).then(function(response){
				if(response.data && response.data != "false"){
					$rootScope.statuses.splice($rootScope.statuses.indexOf(status), 1);
				}
			});
		}
	}]);
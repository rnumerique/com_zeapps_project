app.controller("ComZeappsProjectFormCardCtrl", ["$scope", "$route", "$routeParams", "$location", "$rootScope", "zeHttp", "zeapps_modal", "$uibModal",
	function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal) {

		$scope.$parent.loadMenu("com_ze_apps_project", "com_zeapps_projects_management");

		$scope.$on("triggerFormCard", function(event, data){
			$scope.$broadcast("dataFormCard",
				{
					url : "/ng/com_zeapps_project/project/" + data.id_project,
					step : 1
				}
			);
		});

	}]);
app.controller("ComZeappsSprintFormCardCtrl", ["$scope", "$route", "$routeParams", "$location", "$rootScope", "zeHttp", "zeapps_modal", "$uibModal",
	function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal) {

		$scope.$parent.loadMenu("com_ze_apps_project", "com_zeapps_projects_sprint");

		$scope.$on("triggerFormCard", function(){
			$scope.$broadcast("dataFormCard",
				{
					url : "/ng/com_zeapps_project/sprint/" + $routeParams.id_project + "/" + $routeParams.id_sprint,
					step : 2
				}
			);
		});

	}]);
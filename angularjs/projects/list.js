app.controller("ComZeappsProjectOverviewCtrl", ["$scope", "$route", "$routeParams", "$location", "$rootScope", "zeHttp", "zeapps_modal", "$uibModal", "$filter",
	function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal, $filter) {

		$scope.$parent.loadMenu("com_ze_apps_project", "com_zeapps_projects_management");

		$scope.projects = [];
		if($rootScope.projectTab){
			$scope.filters = {id_status : $rootScope.projectTab};
		}
		else
		{
			$rootScope.projectTab = "";
			$scope.filters = {};
		}
		$scope.totals = {};
		$scope.view = "0";

		$scope.goTo = goTo;
		$scope.goToTab = goToTab;

		zhttp.project.project.get_overview().then(function(response){
			if(response.data && response.data != "false"){
				$scope.projects = response.data;
				angular.forEach($scope.projects, function(project){
					project.due = parseFloat(project.due);
					project.commission = parseFloat(project.commission);
					project.payed = parseFloat(project.payed);
				});
				calcTotals();
			}
		});

		function goTo(id_project){
			$location.url("/ng/com_zeapps_project/project/" + id_project);
		}

		function goToTab(id_status){
			if(id_status !== undefined){
				$rootScope.projectTab =  id_status;
				$scope.filters.id_status = id_status;
			}
			else{
				$rootScope.projectTab = "";
				delete $scope.filters.id_status;
			}
			calcTotals();
		}

		function calcTotals(){
			$scope.totals = {
				due : 0,
				commission : 0,
				benefit : 0,
				payed : 0,
				leftToPay : 0,
				nbSandbox : 0,
				nbBacklog : 0,
				nbOngoing : 0,
				nbQuality : 0,
				nbNext : 0
			};

			var projects = $filter("filter")($scope.projects, $scope.filters);

			angular.forEach(projects, function(project){
				$scope.totals.due += parseFloat(project.due);
				$scope.totals.commission += parseFloat(project.commission);
				$scope.totals.payed += parseFloat(project.payed);
				$scope.totals.nbSandbox += parseInt(project.nbSandbox);
				$scope.totals.nbBacklog += parseInt(project.nbBacklog);
				$scope.totals.nbOngoing += parseInt(project.nbOngoing);
				$scope.totals.nbQuality += parseInt(project.nbQuality);
				$scope.totals.nbNext += parseInt(project.nbNext);
			});

			$scope.totals.benefit = $scope.totals.due - $scope.totals.commission;
			$scope.totals.leftToPay = $scope.totals.due - $scope.totals.payed;
		}
	}]);
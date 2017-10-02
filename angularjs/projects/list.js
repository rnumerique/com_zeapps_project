app.controller("ComZeappsProjectOverviewCtrl", ["$scope", "$route", "$routeParams", "$location", "$rootScope", "zeHttp", "zeapps_modal", "$uibModal", "$filter", "zeProject", "menu",
	function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal, $filter, zeProject, menu) {

        menu("com_ze_apps_project", "com_zeapps_projects_management");

		$scope.projects = [];
		if($rootScope.projectTab){
			$scope.filters = {id_status : $rootScope.projectTab};
		}
		else
		{
			$rootScope.projectTab = "";
			$scope.filters = {};
		}
		$scope.postits = {};
		$scope.details = 0;

		$scope.goTo = goTo;
		$scope.goToTab = goToTab;
		$scope.edit = edit;
		$scope.delete_project = delete_project;
		$scope.force_delete_project = force_delete_project;

		zhttp.project.project.get_overview().then(function(response){
			if(response.data && response.data != "false"){
				$scope.projects = response.data;
				angular.forEach($scope.projects, function(project){
					project.due = parseFloat(project.due);
					project.commission = parseFloat(project.commission);
					project.payed = parseFloat(project.payed);

                    var ret = zeProject.get.ratioOf(project);
					project.time_spent_formatted = ret.time_spent_formatted;
					project.timer_color = ret.timer_color;
					project.timer_ratio = ret.timer_ratio;
				});
				calcTotals();
			}
		});

		function goTo(id_project){
			$location.url("/ng/com_zeapps_project/project/" + id_project);
		}

		function edit(id_project, $event){
			$event.stopPropagation();
			$location.url("/ng/com_zeapps_project/project/edit/" + id_project);
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
			var totals = {
				due : 0,
				commission : 0,
				benefit : 0,
				payed : 0,
				leftToPay : 0
			};

			var projects = $filter("filter")($scope.projects, $scope.filters);

			angular.forEach(projects, function(project){
				totals.due += parseFloat(project.due);
				totals.commission += parseFloat(project.commission);
				totals.payed += parseFloat(project.payed);
			});

			totals.benefit = totals.due - totals.commission;
			totals.leftToPay = totals.due - totals.payed;

			$scope.postits = [
				{
					value: totals.due,
					legend: 'Total montant',
					filter: 'currency'
				},
				{
					value: totals.commission,
					legend: 'Total commission',
                    filter: 'currency'
				},
				{
					value: totals.benefit,
					legend: 'Total marge',
                    filter: 'currency'
				},
				{
					value: totals.payed,
					legend: 'Total deja facturé',
                    filter: 'currency'
				},
				{
					value: totals.leftToPay,
					legend: 'Total reste dû',
                    filter: 'currency'
				}
			]
		}

		function delete_project(project) {
            zhttp.project.project.del(project.id).then(function (response) {
                if (response.data && response.data != "false") {
                    if (response.data.hasDependencies) {
                        $scope.force_delete_project(project);
                    }
                    else {
                    	$scope.projects.splice($scope.projects.indexOf(project), 1);
                        calcTotals();
                    }
                }
            });
		}

		function force_delete_project(project) {
			var modalInstance = $uibModal.open({
				animation: true,
				templateUrl: "/assets/angular/popupModalDeBase.html",
				controller: "ZeAppsPopupModalDeBaseCtrl",
				size: "lg",
				resolve: {
					titre: function () {
						return "Attention";
					},
					msg: function () {
						return "Ce projet contient des sous-projets ou cartes/deadlines, ceux-ci seront également supprimés.";
					},
					action_danger: function () {
						return "Annuler";
					},
					action_primary: function () {
						return false;
					},
					action_success: function () {
						return "Confirmer la suppression";
					}
				}
			});

			modalInstance.result.then(function (selectedItem) {
				if (selectedItem.action == "danger") {

				} else if (selectedItem.action == "success") {
					zhttp.project.project.del(project.id, true).then(function (response) {
						if (response.status == 200) {
                            $scope.projects.splice($scope.projects.indexOf(project), 1);
                            calcTotals();
						}
					});
				}

			}, function () {
				//console.log("rien");
			});

		}
	}]);
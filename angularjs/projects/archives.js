app.controller("ComZeappsProjectArchivesCtrl", ["$scope", "$route", "$routeParams", "$location", "$rootScope", "zeHttp", "zeapps_modal", "$uibModal", "$filter", "zeProject", "menu",
	function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal, $filter, zeProject, menu) {

        menu("com_ze_apps_project", "com_zeapps_projects_archives");

		$scope.projects = [];

		$scope.goTo = goTo;
		$scope.edit = edit;
		$scope.delete_project = delete_project;
		$scope.force_delete_project = force_delete_project;

		zhttp.project.project.get_archives().then(function(response){
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
			}
		});

		function goTo(id_project){
			$location.url("/ng/com_zeapps_project/project/" + id_project);
		}

		function edit(id_project, $event){
			$event.stopPropagation();
			$location.url("/ng/com_zeapps_project/project/edit/" + id_project);
		}

		function delete_project(project) {
            zhttp.project.project.del(project.id).then(function (response) {
                if (response.data && response.data != "false") {
                    if (response.data.hasDependencies) {
                        $scope.force_delete_project(project);
                    }
                    else {
                    	$scope.projects.splice($scope.projects.indexOf(project), 1);
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
						}
					});
				}

			}, function () {
				//console.log("rien");
			});

		}
	}]);
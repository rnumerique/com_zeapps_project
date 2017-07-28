app.controller("ComZeappsBacklogViewCtrl", ["$scope", "$route", "$routeParams", "$location", "$rootScope", "zeHttp", "zeapps_modal", "$uibModal",
	function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal) {

		$scope.$parent.loadMenu("com_ze_apps_project", "com_zeapps_projects_backlog");

		$scope.options = {
			"projectId": "all",
			"id_company": "all",
			"id_manager": "all",
			"id_assigned_to":  $rootScope.user.id,
			"completed": false,
			"step" : "1"
		};

		$scope.edit = edit;
		$scope.detailCard = detailCard;
		$scope.complete = complete;
		$scope.delete = del;

		zhttp.project.project.get_all(0, true).then(function(response){
			if(response.data && response.data != "false")
				$scope.projects = response.data;
		});

		zhttp.project.filter.get_all("backlog").then(function(response){
			if(response.data && response.data != "false"){
				$scope.companies = response.data.companies;
				$scope.managers = response.data.managers;
				$scope.dates = response.data.dates;
				$scope.assigned = response.data.assigned;
			}
		});

		if($routeParams.id_project){ // Project
			$scope.options.projectId = $routeParams.id_project;
		}

		zhttp.project.card.get_all().then(function(response){
			if(response.data && response.data != "false") {
				var cards = response.data;
				if(!$scope.cardsByDate)
					$scope.cardsByDate = [];
				angular.forEach(cards, function (card) {
					if(card.due_date != 0) {
						if (!$scope.cardsByDate[card.due_date])
							$scope.cardsByDate[card.due_date] = [];
						$scope.cardsByDate[card.due_date].push(card);
					}
				});
				if(!$scope.cardsByProject)
					$scope.cardsByProject = [];
				angular.forEach(cards, function (card) {
					if (!$scope.cardsByProject[card.id_project])
						$scope.cardsByProject[card.id_project] = [];
					$scope.cardsByProject[card.id_project].push(card);
				});
			}
		});

		$scope.$watch("options.projectId", function(id, oldId, scope){
			if(id != undefined && id != oldId) {
				if(id === "all"){
					delete scope.options.id;
					delete scope.subProjects;
				}
				else {
					scope.options.id = [];
					scope.options.id.push(id);
					zhttp.project.project.get_childs(id).then(function (response) {
						if (response.data && response.data != "false") {
							scope.subProjects = response.data;
							angular.forEach(scope.subProjects, function (subProject) {
								scope.options.id.push(subProject.id);
							});
						}
						else
							delete scope.subProjects;
					});
				}
			}
		});

		function edit(card){
			$location.url("/ng/com_zeapps_project/backlog/edit/"+card.id);
		}

		function detailCard(card){
			zeapps_modal.loadModule("com_zeapps_project", "detail_card", {card : card});
		}

		function complete(card){
			zhttp.project.card.complete(card.id, "card").then(function(response){
				if (response.status == 200) {
					card.completed = "Y";
				}
			});
		}

		function del(card) {
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
						return "Souhaitez-vous supprimer définitivement cette idée ?";
					},
					action_danger: function () {
						return "Annuler";
					},
					action_primary: function () {
						return false;
					},
					action_success: function () {
						return "Confirmer";
					}
				}
			});

			modalInstance.result.then(function (selectedItem) {
				if (selectedItem.action == "danger") {

				} else if (selectedItem.action == "success") {
					zhttp.project.card.del(card.id).then(function (response) {
						if (response.status == 200) {
							$scope.cardsByProject[card.id_project].splice($scope.cardsByProject[card.id_project].indexOf(card), 1);
						}
					});
				}

			}, function () {
			});
		}
	}]);
app.controller("ComZeAppsPlanningTableCtrl", ["$scope", "$route", "$routeParams", "$location", "$rootScope", "zeHttp", "zeapps_modal", "$uibModal",
	function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal) {

		$scope.showDate = [];

		$scope.stepOf = stepOf;
		$scope.complete = complete;
		$scope.detailCard = detailCard;
		$scope.edit = edit;
		$scope.edit_deadline = edit_deadline;
		$scope.delete = del;
		$scope.delete_deadline = del_deadline;

		function stepOf(card){
			if(card.step === "2")
				return "fa-calendar-o text-muted";
			if(card.step === "3")
				return "fa-calendar-o text-info";
			if(card.step === "4")
				return "fa-calendar-o text-warning";
			if(card.step === "5")
				return "fa-calendar-check-o text-success";
		}

		function complete(card){
			zhttp.project.card.complete(card.id, card.deadline).then(function(response){
				if (response.status == 200) {
					card.completed = "Y";
				}
			});
		}

		function detailCard(card){
			zeapps_modal.loadModule("com_zeapps_project", "detail_card", {card : card});
		}

		function edit(card){
			$location.url("/ng/com_zeapps_project/project/card/edit/card/"+card.id);
		}

		function edit_deadline(deadline){
			$location.url("/ng/com_zeapps_project/project/card/edit/deadline/"+deadline.id);
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
						return "Souhaitez-vous supprimer définitivement cette carte ?";
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
							$scope.cardsByDate[card.due_date].splice($scope.cardsByDate[card.due_date].indexOf(card), 1);
						}
					});
				}

			}, function () {
			});

		}

		function del_deadline(deadline) {
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
						return "Souhaitez-vous supprimer définitivement cette deadline ?";
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
					zhttp.project.deadline.del(deadline.id).then(function (response) {
						if (response.status == 200) {
							$scope.deadlines.splice($scope.deadlines.indexOf(deadline), 1);
						}
					});
				}

			}, function () {
			});

		}

	}]);
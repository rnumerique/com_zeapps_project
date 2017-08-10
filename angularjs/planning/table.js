app.controller("ComZeAppsPlanningTableCtrl", ["$scope", "$route", "$routeParams", "$location", "$rootScope", "zeHttp", "zeapps_modal", "$uibModal",
	function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal) {

		$scope.showDate = [];
		$scope.currentStep = 1;

		$scope.fetchCards = fetchCards;
		$scope.showingStep = showingStep;
		$scope.changeStep = changeStep;
		$scope.edit = edit;
		$scope.edit_deadline = edit_deadline;
		$scope.delete = del;
		$scope.delete_deadline = del_deadline;

		zhttp.project.card.get_all($scope.project.id, 1).then(function(response){
			if(response.data && response.data != false){
                $scope.dates = response.data.dates;
                var cards = response.data.cards;

                $scope.cardsByDate = [];
                angular.forEach(cards, function (card) {
					if (!$scope.cardsByDate[card.due_date])
						$scope.cardsByDate[card.due_date] = [];
					$scope.cardsByDate[card.due_date].push(card);
                });
			}
		});

		function fetchCards(step){
            $scope.currentStep = step;

            zhttp.project.card.get_all($scope.project.id, step).then(function(response){
                if(response.data && response.data != false){
                    $scope.dates = response.data.dates;
                    var cards = response.data.cards;

                    $scope.cardsByDate = [];
                    angular.forEach(cards, function (card) {
						if (!$scope.cardsByDate[card.due_date])
							$scope.cardsByDate[card.due_date] = [];
						$scope.cardsByDate[card.due_date].push(card);
                    });
                }
            });
		}

		function showingStep(step){
			return step === $scope.currentStep;
		}

		function changeStep(card){
			var formatted_data = angular.toJson(card);

			zhttp.project.card.post(formatted_data).then(function(response){
				if(response.data && response.data != "false"){
                    $scope.cardsByDate[card.due_date].splice($scope.cardsByDate[card.due_date].indexOf(card), 1);
				}
			});
		}

		function edit(card){
			$location.url("/ng/com_zeapps_project/project/card/edit/card/"+card.id);
		}

		function edit_deadline(deadline){
			$location.url("/ng/com_zeapps_project/project/card/edit/deadline/"+deadline.id);
		}

		function del(card) {
            zhttp.project.card.del(card.id).then(function (response) {
                if (response.status == 200) {
                    $scope.cardsByDate[card.due_date].splice($scope.cardsByDate[card.due_date].indexOf(card), 1);
                }
            });
		}

		function del_deadline(deadline) {
            zhttp.project.deadline.del(deadline.id).then(function (response) {
                if (response.status == 200) {
                    $scope.deadlines.splice($scope.deadlines.indexOf(deadline), 1);
                }
            });
		}

	}]);
app.controller("ComZeappsProjectMyWorkCtrl", ["$scope", "$route", "$routeParams", "$location", "$rootScope", "zeHttp", "zeapps_modal", "$uibModal",
	function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal) {

        $scope.$parent.loadMenu("com_ze_apps_project", "com_zeapps_projects_mywork");

        $scope.cards = [];
        var actuals = [];
        var leftovers = [];
        var futures = [];
        var nbLeftovers = 0;
        $scope.postits = [];
        $scope.priorities = [];

        $scope.myworkTab = "";
        var priorityFilterValue;

        $scope.goToTab = goToTab;
        $scope.complete = complete;
        $scope.detailCard = detailCard;
        $scope.compareDates = function(date){ return zhttp.project.compareDate(date); };

		zhttp.project.mywork.get_work().then(function(response){
        	if(response.data && response.data != "false"){
				actuals = response.data.actuals;
				leftovers = response.data.leftovers;
                futures = response.data.futures;

                $scope.priorities = response.data.priorities;

        		$scope.cards = actuals.concat(leftovers).concat(futures);

                generatePostits();
			}
		});

		function generatePostits(){
            nbLeftovers = leftovers.length;

            var cards = actuals.concat(leftovers).concat(futures);

            $scope.postits = [
                {
                    legend : 'En retard',
                    value : nbLeftovers
                }
            ];

            angular.forEach($scope.priorities, function(priority){
                priorityFilterValue = priority.id;

                var nbCards = cards.filter(findPriority).length;

                $scope.postits.push({
                    legend : priority.label,
                    value : nbCards
                })
            });
		}

        function complete(card){
            zhttp.project.card.complete(card.id, card.deadline).then(function(response){
                if (response.status == 200) {
                	$scope.cards.splice($scope.cards.indexOf(card), 1);

                	angular.forEach(actuals, function(actual, key){
                		if(card.id === actual.id){
                			actuals.splice(key, 1);
						}
					});

                    angular.forEach(leftovers, function(leftover, key){
                        if(card.id === leftover.id){
                            leftovers.splice(key, 1);
                        }
                    });

                    angular.forEach(futures, function(future, key){
                        if(card.id === future.id){
                            futures.splice(key, 1);
                        }
                    });

                    generatePostits();
                }
            });
        }

        function detailCard(card){
            zeapps_modal.loadModule("com_zeapps_project", "detail_card", {card : card});
        }

		function goToTab(tab){
            $scope.myworkTab = tab;
			if(tab === "leftovers"){
				$scope.cards = leftovers;
			}
			else if(tab === "futures"){
				$scope.cards = futures;
			}
			else if(tab === "actuals"){
				$scope.cards = actuals;
			}
			else if(tab === ''){
                $scope.cards = actuals.concat(leftovers).concat(futures);
            }
			else{
                priorityFilterValue = tab;
                $scope.cards = actuals.concat(leftovers).concat(futures).filter(findPriority);
            }

		}

		function findPriority(element){
            return element['id_priority'] === priorityFilterValue;
		}

	}]);
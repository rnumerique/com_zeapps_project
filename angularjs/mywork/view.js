app.controller("ComZeappsProjectMyWorkCtrl", ["$scope", "$route", "$routeParams", "$location", "$rootScope", "zeHttp", "zeapps_modal", "menu",
	function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, menu) {

        menu("com_ze_apps_project", "com_zeapps_projects_mywork");

        $scope.dates = [];
        var cards = [];
        $scope.cardsByDate = [];
        var actuals = [];
        var leftovers = [];
        var futures = [];
        var nodates = [];
        var nbLeftovers = 0;
        $scope.postits = [];
        $scope.priorities = [];

        $scope.myworkTab = "currents";
        var priorityFilterValue;

        $scope.goToTab = goToTab;
        $scope.detailCard = detailCard;
        $scope.compareDates = function(date){ return zhttp.project.compareDate(date); };

		zhttp.project.mywork.get_work().then(function(response){
        	if(response.data && response.data != "false"){
				actuals = response.data.actuals;
				leftovers = response.data.leftovers;
                futures = response.data.futures;
                nodates = response.data.nodates;
                $scope.dates = response.data.dates;

                $scope.priorities = response.data.priorities;

        		cards = actuals.concat(leftovers);

                generatePostits();
                sortCards();
			}
		});

		function generatePostits(){
            nbLeftovers = leftovers.length;

            var total = actuals.concat(leftovers).concat(futures);

            $scope.postits = [
                {
                    legend : 'En retard',
                    value : nbLeftovers
                }
            ];

            angular.forEach($scope.priorities, function(priority){
                priorityFilterValue = priority.id;

                var nbCards = total.filter(findPriority).length;

                $scope.postits.push({
                    legend : priority.label,
                    value : nbCards
                })
            });
		}

        function detailCard(card){
            zeapps_modal.loadModule("com_zeapps_project", "detail_card", {card : card});
        }

		function goToTab(tab){
            $scope.myworkTab = tab;
			if(tab === "currents"){
				cards = actuals.concat(leftovers);
			}
			else if(tab === "leftovers"){
				cards = leftovers;
			}
			else if(tab === "futures"){
				cards = futures;
			}
			else if(tab === "actuals"){
				cards = actuals;
			}
			else if(tab === "nodates"){
				cards = nodates;
			}
			else if(tab === ''){
                cards = actuals.concat(leftovers).concat(futures).concat(nodates);
            }
			else{
                priorityFilterValue = tab;
                cards = actuals.concat(leftovers).concat(futures).filter(findPriority);
            }

            sortCards();
		}

		function findPriority(element){
            return element['id_priority'] === priorityFilterValue;
		}

		function sortCards(){
            $scope.cardsByDate = [];
            angular.forEach(cards, function (card) {
                if(card.due_date != 0) {
                    if (!$scope.cardsByDate[card.due_date])
                        $scope.cardsByDate[card.due_date] = [];
                    $scope.cardsByDate[card.due_date].push(card);
                }
            });
        }
	}]);
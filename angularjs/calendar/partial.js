app.controller("ComZeAppsProjectCalendarCtrl", ["$scope", "$route", "$routeParams", "$location", "$rootScope", "zeHttp", "zeapps_modal", "$uibModal",
	function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal) {

		if($routeParams.id){
			zhttp.project.project.get_calendar($routeParams.id).then(function(response){
				if(response.data && response.data != "false"){
                    $scope.cards = response.data.cards;
                    $scope.deadlines = response.data.deadlines;

                    var events = [];

                    angular.forEach($scope.cards, function (card) {
                        if(card.due_date != 0) {
                            var event = {
                                allDay: true,
                                title: card.title + (card.name_assigned_to ? " - assigné à " + card.name_assigned_to : ''),
                                start: card.due_date,
                                textColor: card.color ? "#333" : "#fff",
                                color: card.color || "#393939",
                                order: 3,
                                card: card,
                                id: card.id
                            };

                            events.push(event);
                        }
                    });

                    angular.forEach($scope.deadlines, function (card) {
                        if(card.due_date != 0) {
                            var event = {
                                allDay: true,
                                title: card.title,
                                start: card.due_date,
                                textColor: "#fff",
                                color: "#a94442",
                                order: 2,
                                id: card.id
                            };

                            events.push(event);
                        }
                    });

                    $scope.calendarModel.events = events;
				}
			})
		}

	}]);
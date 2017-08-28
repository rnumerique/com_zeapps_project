app.controller("ComZeAppsProjectCalendarCtrl", ["$scope", "$route", "$routeParams", "$location", "$rootScope", "zeHttp", "zeCalendar",
	function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeCalendar) {

		if($routeParams.id){

            var calendarModel = {
                eventLimit: 6,
                eventLimitClick: "day",
                views: {
                    basic: {
                        "eventLimit": false
                    }
                },
                step: 1,
                completed: false,
                events: [],
                eventClick: function(calEvent){
                    if(calEvent.card) {
                        $scope.$parent.detailCard(calEvent.card);
                    }
                },
                editable: true,
                eventDrop: function(event) {
                    var data = {};

                    data.id = event.id;
                    data.due_date = event.start.format();

                    var formatted_data = angular.toJson(data);

                    if(event.order === 1){
                        zhttp.project.project.post(formatted_data);
                    }else if(event.order === 2){
                        zhttp.project.deadline.post(formatted_data);
                    }else if(event.order === 3){
                        zhttp.project.card.post(formatted_data);
                    }
                }
            };
            zeCalendar.init(calendarModel);

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

                    zeCalendar.fill(events);
				}
			})
		}
	}]);
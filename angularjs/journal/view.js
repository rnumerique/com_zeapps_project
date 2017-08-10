app.controller("ComZeappsProjectJournalCtrl", ["$scope", "$route", "$routeParams", "$location", "$rootScope", "zeHttp", "$filter",
	function ($scope, $route, $routeParams, $location, $rootScope, zhttp, $filter) {

		$scope.$parent.loadMenu("com_ze_apps_project", "com_zeapps_projects_journal");

		var logs = [];
		var journalFilter = $filter('journalFilter');

		$scope.filter = {
			model: {},
			options: {
				main: [
                    {
                        format: 'select',
                        field: 'id',
                        label: 'Utilisateur',
                        options: []
                    }
				],
				secondaries: []
			}
		};
		$scope.calendarModel = {
			defaultView: "listWeek",
            header: {
                left: "prev,next today",
                center: "title",
                right: "listWeek,listDay"
            },
			eventLimit: 6,
			eventLimitClick: "day",
			views: {
				basic: {
					"eventLimit": false
				}
			},
			clickDayView: "listDay",
			step: 1,
			completed: false,
			events: []
		};

		zhttp.project.journal.get_journal().then(function(response){
			if(response.data && response.data != "false"){
				$scope.filter.options.main[0].options = response.data.filters.assigned;

                logs = response.data.logs;

                angular.forEach(logs, function (log) {
                	var formatted_date = new Date(log.date);
					var time_spent_formatted = parseInt(log.time_spent/60) + "h " + (log.time_spent % 60 || '');
					var event = {
						allDay: true,
						title: log.name_user + " : " + time_spent_formatted,
						start: formatted_date,
						textColor: "#fff",
						color: "#393939"
					};

					$scope.calendarModel.events.push(event);
                });
			}
		});

		$scope.$watch("filter", function(filter, oldFilter){
			if(filter && filter != oldFilter){
				var events = [];

				angular.forEach(journalFilter(logs, $scope.filter.model), function (log) {
                    var formatted_date = new Date(log.date);
					var time_spent_formatted = parseInt(log.time_spent/60) + "h " + (log.time_spent % 60 || '');
					var event = {
						allDay: true,
						title: log.name_user + " : " + time_spent_formatted,
						start: formatted_date,
						textColor: "#fff",
						color: "#393939"
					};

					events.push(event);
				});
				console.log(events);
                $scope.calendarModel.events = events;
			}
		}, true)
	}]);
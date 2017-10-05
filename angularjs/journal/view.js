app.controller("ComZeappsProjectJournalCtrl", ["$scope", "zeHttp", "$filter", "zeCalendar", "menu",
	function ($scope, zhttp, $filter, zeCalendar, menu) {

        menu("com_ze_apps_project", "com_zeapps_projects_journal");

		var logs = [];
		var journalFilter = $filter('journalFilter');

		$scope.filters = {
            main: [
                {
                    format: 'select',
                    field: 'id',
                    label: 'Utilisateur',
                    options: []
                }
            ],
            secondaries: []
		};
        $scope.filter_model = {};
		var calendarModel = {
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

		zeCalendar.init(calendarModel);

        $scope.applyFilters = applyFilters;

		zhttp.project.journal.get_journal().then(function(response){
			if(response.data && response.data != "false"){
				$scope.filters.main[0].options = response.data.filters.assigned;

                logs = response.data.logs;

                applyFilters();
			}
		});

        function applyFilters(){
            var events = [];

            angular.forEach(journalFilter(logs, $scope.filter_model), function (log) {
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

            zeCalendar.fill(events);
		}
	}]);
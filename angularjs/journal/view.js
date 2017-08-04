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
                    },
					{
						format: 'select',
						field: 'id_project',
						label: 'Projet',
						options: []
					},
					{
						format: 'select',
						field: 'id_company',
						label: 'Demandeur',
						options: []
					},
					{
						format: 'select',
						field: 'id_manager',
						label: 'Responsable',
						options: []
					}
				],
				secondaries: []
			}
		};
		$scope.calendarModel = {
            header: {
                left: "prev,next today",
                center: "title",
                right: "month,agendaWeek,agendaDay"
            },
			eventLimit: 6,
			eventLimitClick: "day",
			views: {
				basic: {
					"eventLimit": false
				}
			},
			clickDayView: "agendaDay",
			step: 1,
			completed: false,
			events: []
		};

		zhttp.project.journal.get_journal().then(function(response){
			if(response.data && response.data != "false"){
				$scope.filter.options.main[0].options = response.data.filters.assigned;
				$scope.filter.options.main[1].options = response.data.filters.projects;
				$scope.filter.options.main[2].options = response.data.filters.companies;
				$scope.filter.options.main[3].options = response.data.filters.managers;

                logs = response.data.logs;

                angular.forEach(logs, function (log) {
                	var date = new Date(log.start_time);
					var event = {
						allDay: !(date.getHours() || date.getMinutes()),
						title: log.name_user + " (" + log.name_company + " - " + log.project_title + ") : " + log.label + (log.id_card !== '0' ? ' - tâche #'+log.id_card : ''),
						start: log.start_time,
						end: log.stop_time,
						textColor: log.color ? "#333" : "#fff",
						color: log.color || "#393939",
						url: "/ng/com_zeapps_project/project/" + log.id_project
					};

					$scope.calendarModel.events.push(event);
                });
			}
		});

		$scope.$watch("filter", function(filter, oldFilter){
			if(filter && filter != oldFilter){
				var events = [];

				angular.forEach(journalFilter(logs, $scope.filter.model), function (log) {
                    var date = new Date(log.start_time);
                    var event = {
                        allDay: !(date.getHours() || date.getMinutes()),
                        title: log.name_user + " (" + log.name_company + " - " + log.project_title + ") : " + log.label + (log.id_card !== '0' ? ' - tâche #'+log.id_card : ''),
                        start: log.start_time,
                        end: log.stop_time,
                        textColor: log.color ? "#333" : "#fff",
                        color: log.color || "#393939",
                        url: "/ng/com_zeapps_project/project/" + log.id_project
                    };

					events.push(event);
				});
				console.log(events);
                $scope.calendarModel.events = events;
			}
		}, true)
	}]);
app.controller("ComZeappsPlanningViewCtrl", ["$scope", "$route", "$routeParams", "$location", "$rootScope", "zeHttp", "$filter",
	function ($scope, $route, $routeParams, $location, $rootScope, zhttp, $filter) {

		$scope.$parent.loadMenu("com_ze_apps_project", "com_zeapps_projects_planning");

		var cards = [];
		var deadlines = [];
		var projects = [];
		var planningFilter = $filter('planningFilter');

		$scope.filter = {
			model: {},
			options: {
				main: [
					{
						format: 'checkbox',
						field: 'displayCards',
						label: 'Afficher les tâches',
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
					},
					{
						format: 'select',
						field: 'id_assigned_to',
						label: 'Assigné à',
						options: []
					}
				],
				secondaries: []
			}
		};
		$scope.calendarModel = {
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
			editable: true,
            eventDrop: function(event) {
				var data = {};
				var formatted_data = "";

                data.id = event.id;
                data.due_date = event.start.format();

                formatted_data = angular.toJson(data);

				if(event.order === 1){
					zhttp.project.project.post(formatted_data);
				}else if(event.order === 2){
                    zhttp.project.deadline.post(formatted_data);
				}else if(event.order === 3){
                    zhttp.project.card.post(formatted_data);
            	}
            }
		};

		zhttp.project.planning.get_context().then(function(response){
			if(response.data && response.data != "false"){
				$scope.filter.options.main[2].options = response.data.filters.companies;
				$scope.filter.options.main[3].options = response.data.filters.managers;
				$scope.filter.options.main[4].options = response.data.filters.assigned;

                $scope.filter.options.main[1].options = response.data.projects;

				projects = response.data.projects;
				angular.forEach(projects, function (card) {
					if(card.due_date != 0) {
						var event = {
							allDay: true,
                            title: card.name_company + " ( " + card.label + " ) ",
							start: card.due_date,
							color: "#760692",
							order: 1,
							id: card.id,
							url: "/ng/com_zeapps_project/project/" + card.id_project
						};

						$scope.calendarModel.events.push(event);
					}
				});

				deadlines = response.data.deadlines;
				angular.forEach(deadlines, function (card) {
					if(card.due_date != 0) {
						var event = {
							allDay: true,
							title: card.name_company + " ( " + card.project_title + " ) : " + card.title,
							start: card.due_date,
							color: "#a94442",
							order: 2,
                            id: card.id,
                            url: "/ng/com_zeapps_project/project/" + card.id_project
						};

						$scope.calendarModel.events.push(event);
					}
				});

                cards = response.data.cards;
			}
		});

		$scope.$watch("filter", function(filter, oldFilter){
			if(filter && filter != oldFilter){
				var events = [];
				angular.forEach(planningFilter(projects, $scope.filter.model), function (card) {
					if(card.due_date != 0) {
						var event = {
							allDay: true,
							title: card.name_company + " ( " + card.label + " ) ",
							start: card.due_date,
							color: "#760692",
							order: 1,
                            id: card.id,
                            url: "/ng/com_zeapps_project/project/" + card.id_project
						};

						events.push(event);
					}
				});

				angular.forEach(planningFilter(deadlines, $scope.filter.model), function (card) {
					if(card.due_date != 0) {
						var event = {
							allDay: true,
                            title: card.name_company + " ( " + card.project_title + " ) : " + card.title,
							start: card.due_date,
							color: "#a94442",
							order: 2,
                            id: card.id,
                            url: "/ng/com_zeapps_project/project/" + card.id_project
						};

						events.push(event);
					}
				});

				if($scope.filter.model.displayCards) {
                    angular.forEach(planningFilter(cards, $scope.filter.model), function (card) {
                        if (card.due_date != 0) {
                            var event = {
                                allDay: true,
                                title: card.name_company + " ( " + card.project_title + " ) : " + card.title + " " + (card.name_assigned_to ? " - assigné à " + card.name_assigned_to : ''),
                                start: card.due_date,
                                textColor: card.color ? "#333" : "#fff",
                                color: card.color || "#393939",
                                order: 3,
                                id: card.id,
                                url: "/ng/com_zeapps_project/project/" + card.id_project
                            };

                            events.push(event);
                        }
                    });
                }

				$scope.calendarModel.events = events;
			}
		}, true)
	}]);
app.controller("ComZeappsProjectCardFormCtrl", ["$scope", "$routeParams", "$location", "zeHttp", "zeapps_modal", "menu",
	function ($scope, $routeParams, $location, zhttp, zeapps_modal, menu) {

        menu("com_ze_apps_project", "com_zeapps_projects_management");

		var step;
		var whitelist_ids = [];

		$scope.form = {
			id_category: "0",
            id_priority: "3",
			step: "1"
		};
		$scope.categories = [];
		$scope.priorities = [];
		$scope.type = $routeParams.type || "card";

        $scope.accountManagerHttp = zhttp.app.user;
        $scope.accountManagerFields = [
            {label:'Prénom',key:'firstname'},
            {label:'Nom',key:'lastname'}
        ];

		$scope.loadProject = loadProject;
		$scope.removeProject = removeProject;
		$scope.loadAssigned = loadAssigned;
		$scope.success = success;
		$scope.cancel = cancel;

		if($routeParams.id){ // Edit
			if($routeParams.type == "deadline"){
				zhttp.project.deadline.get($routeParams.id).then(function (response) {
					if (response.data && response.data != "false") {
						$scope.form = response.data.deadline;
						$scope.form.due_date = new Date($scope.form.due_date);
						if ($scope.form.end_at) {
                            $scope.form.end_at = new Date($scope.form.end_at);
                        }

                        whitelist_ids = [];
                        angular.forEach(response.data.project_users, function(user){
                            whitelist_ids.push(user.id_user);
                        });

                        $scope.priorities = response.data.priorities;
                        $scope.categories = response.data.categories;
					}
				});
			} else {
				zhttp.project.card.get($routeParams.id).then(function (response) {
					if (response.data && response.data != "false") {
						$scope.form = response.data.card;
						$scope.form.due_date = new Date($scope.form.due_date);

						if ($scope.form.estimated_time)
							$scope.form.estimated_time = parseFloat($scope.form.estimated_time);

                        whitelist_ids = [];
                        angular.forEach(response.data.project_users, function(user){
                            whitelist_ids.push(user.id_user);
                        });

                        $scope.priorities = response.data.priorities;
                        $scope.categories = response.data.categories;
					}
				});
			}
		} else if($routeParams.id_project){ // Project
			zhttp.project.project.get($routeParams.id_project).then(function(response){
				if(response.data && response.data != "false"){
					$scope.form.id_project = response.data.project.id;
					$scope.form.project_title = response.data.project.title;

					whitelist_ids = [];
					angular.forEach(response.data.project_users, function(user){
						whitelist_ids.push(user.id_user);
					});

                    $scope.categories = response.data.categories;
                    $scope.priorities = response.data.priorities;
				}
			});
		} else {

		}

		function loadProject() {
			zeapps_modal.loadModule("com_zeapps_project", "search_project", {}, function(objReturn) {
				if (objReturn) {
					$scope.form.id_project = objReturn.id;
					$scope.form.project_title = objReturn.breadcrumbs;
					whitelist_ids = [];
					angular.forEach(response.data.users, function(user){
						whitelist_ids.push(user.id_user);
					});
					$scope.form.id_sprint = 0;
					$scope.form.title_sprint = "";
					$scope.form.step = 1;
					$scope.categories = objReturn.categories;
				} else {
					$scope.form.id_project = 0;
					$scope.form.project_title = "";
				}
			});
		}

		function removeProject() {
			$scope.form.id_project = 0;
			$scope.form.project_title = "";
		}

        function loadAssigned(user) {
            if (user) {
                $scope.form.id_assigned_to = user.id;
                $scope.form.name_assigned_to = user.firstname + " " + user.lastname;
            } else {
                $scope.form.id_assigned_to = "0";
                $scope.form.name_assigned_to = "";
            }
        }

		function success(){
			var formatted_data;

			delete $scope.form.project_title;
			delete $scope.form.title_sprint;

			if($scope.form.due_date) {
				var y2 = $scope.form.due_date.getFullYear();
				var M2 = $scope.form.due_date.getMonth();
				var d2 = $scope.form.due_date.getDate();

				$scope.form.due_date = new Date(Date.UTC(y2, M2, d2));
			}
			else{
				$scope.form.due_date = 0;
			}

            if($scope.form.end_at) {
                var y2 = $scope.form.end_at.getFullYear();
                var M2 = $scope.form.end_at.getMonth();
                var d2 = $scope.form.end_at.getDate();

                $scope.form.end_at = new Date(Date.UTC(y2, M2, d2));
            }
            else{
                $scope.form.end_at = 0;
            }



			if($scope.type == "card") {

				$scope.form.step = $scope.form.step || step;

				formatted_data = angular.toJson($scope.form);

				zhttp.project.card.post(formatted_data).then(function (response) {
					if (response.data && response.data != "false") {
						$location.url("/ng/com_zeapps_project/project/" + ($scope.form.id_project || ''));
					}
				});
			} else{
				var data = {};

				if($scope.form.id)
					data["id"] = $scope.form.id;

				data["id_project"] = $scope.form.id_project;
				data["title"] = $scope.form.title;
				data["due_date"] = $scope.form.due_date;
				data["end_at"] = $scope.form.end_at;

				formatted_data = angular.toJson(data);

				zhttp.project.deadline.post(formatted_data).then(function (response) {
					if (response.data && response.data != "false") {
						$location.url("/ng/com_zeapps_project/project/" + ($scope.form.id_project || ''));
					}
				});
			}
		}

		function cancel(){
			$location.url("/ng/com_zeapps_project/project/" + ($scope.form.id_project || ''));
		}
	}]);
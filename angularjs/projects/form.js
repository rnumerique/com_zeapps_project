app.controller("ComZeappsProjectFormCtrl", ["$scope", "$route", "$routeParams", "$location", "$rootScope", "zeHttp", "zeapps_modal", "$uibModal",
	function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal) {

		$scope.$parent.loadMenu("com_ze_apps_project", "com_zeapps_projects_management");

		$scope.form = {};

		$scope.loadCompany = loadCompany;
		$scope.removeCompany = removeCompany;
		$scope.loadContact = loadContact;
		$scope.removeContact = removeContact;
		$scope.loadManager = loadManager;
		$scope.removeManager = removeManager;
		$scope.success = success;
		$scope.cancel = cancel;

		if($routeParams.id){ // Edit
			zhttp.project.project.get($routeParams.id).then(function(response){
				if(response.data && response.data != "false"){
					$scope.form = response.data.project;
					$scope.form.start_date = new Date($scope.form.start_date);
					$scope.form.due_date = new Date($scope.form.due_date);
					$scope.form.estimated_time = parseFloat($scope.form.estimated_time);
					$scope.form.due = parseFloat($scope.form.due);
					$scope.form.commission = parseFloat($scope.form.commission);
					$scope.form.payed = parseFloat($scope.form.payed);
					zhttp.project.project.get($scope.form.id_parent).then(function(response){
						if(response.data && response.data != "false"){
							$scope.form.title_parent = response.data.project.breadcrumbs;
						}
					});
				}
			});
		}
		else{ // New Project
			$scope.form.id_status = "0";
			$scope.form.id_manager = $rootScope.user.id;
			$scope.form.name_manager = $rootScope.user.firstname ? $rootScope.user.firstname[0] + ". " + $rootScope.user.lastname : $rootScope.user.lastname;
		}

		function loadCompany() {
			zeapps_modal.loadModule("com_zeapps_contact", "search_company", {}, function(objReturn) {
				if (objReturn) {
					$scope.form.id_company = objReturn.id;
					$scope.form.name_company = objReturn.company_name;
				} else {
					$scope.form.id_company = 0;
					$scope.form.name_company = "";
				}
			});
		}

		function removeCompany() {
			$scope.form.id_company = 0;
			$scope.form.name_company = "";
		}

		function loadContact() {
			zeapps_modal.loadModule("com_zeapps_contact", "search_contact", {}, function(objReturn) {
				if (objReturn) {
					$scope.form.id_contact = objReturn.id;
					$scope.form.name_contact = objReturn.first_name ? objReturn.first_name[0] + ". " + objReturn.last_name : objReturn.last_name;
				} else {
					$scope.form.id_contact = 0;
					$scope.form.name_contact = "";
				}
			});
		}

		function removeContact() {
			$scope.form.id_contact = 0;
			$scope.form.name_contact = "";
		}

		function loadManager() {
			zeapps_modal.loadModule("com_zeapps_core", "search_user", {}, function(objReturn) {
				if (objReturn) {
					$scope.form.id_manager = objReturn.id;
					$scope.form.name_manager = objReturn.firstname ? objReturn.firstname[0]  + ". " + objReturn.lastname : objReturn.lastname;
				} else {
					$scope.form.id_manager = 0;
					$scope.form.name_manager = "";
				}
			});
		}

		function removeManager() {
			$scope.form.id_manager = 0;
			$scope.form.name_manager = "";
		}

		function success(){

			delete $scope.form.title_parent;

			if($scope.form.start_date) {
				var y = $scope.form.start_date.getFullYear();
				var M = $scope.form.start_date.getMonth();
				var d = $scope.form.start_date.getDate();

				var date = new Date(Date.UTC(y, M, d));

				$scope.form.start_date = date;
			}

			if($scope.form.due_date) {
				var y2 = $scope.form.due_date.getFullYear();
				var M2 = $scope.form.due_date.getMonth();
				var d2 = $scope.form.due_date.getDate();

				var date2 = new Date(Date.UTC(y2, M2, d2));

				$scope.form.due_date = date2;
			}

			var formatted_data = angular.toJson($scope.form);

			zhttp.project.project.post(formatted_data).then(function(response){
				if(response.data && response.data != "false"){
					if(!$scope.form.id){ // new project, let's assign the rights so we can manage it w/o reloading the app
						$rootScope.project_rights[response.data] = {
							access : "1",
							sandbox : "1",
							card : "1",
							sprint : "1",
							project : "1"
						};
					}
					$location.url("/ng/com_zeapps_project/project/" + response.data);
				}
			});
		}

		function cancel(){
			var id = $scope.form.id || "";
			$location.url("/ng/com_zeapps_project/project/" + id);
		}

	}]);
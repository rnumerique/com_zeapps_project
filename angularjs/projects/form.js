app.controller("ComZeappsProjectFormCtrl", ["$scope", "$routeParams", "$location", "$rootScope", "zeHttp", "menu",
	function ($scope, $routeParams, $location, $rootScope, zhttp, menu) {

        menu("com_ze_apps_project", "com_zeapps_projects_management");

		$scope.form = {};

        $scope.managerHttp = zhttp.app.user;
        $scope.managerFields = [
            {label:'Prénom',key:'firstname'},
            {label:'Nom',key:'lastname'}
        ];

        $scope.companyHttp = zhttp.contact.company;
        $scope.companyTplNew = '/com_zeapps_contact/companies/form_modal/';
        $scope.companyFields = [
            {label:'Nom',key:'company_name'},
            {label:'Téléphone',key:'phone'},
            {label:'Ville',key:'billing_city'},
            {label:'Gestionnaire du compte',key:'name_user_account_manager'}
        ];

        $scope.contactHttp = zhttp.contact.contact;
        $scope.contactTplNew = '/com_zeapps_contact/contacts/form_modal/';
        $scope.contactFields = [
            {label:'Nom',key:'last_name'},
            {label:'Prénom',key:'first_name'},
            {label:'Entreprise',key:'name_company'},
            {label:'Téléphone',key:'phone'},
            {label:'Ville',key:'city'},
            {label:'Gestionnaire du compte',key:'name_user_account_manager'}
        ];

		$scope.loadCompany = loadCompany;
		$scope.loadContact = loadContact;
		$scope.loadManager = loadManager;
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
				}
			});
		}
		else{ // New Project
			$scope.form.id_status = $rootScope.statuses[0].id || "0";
			$scope.form.id_manager = $rootScope.user.id;
			$scope.form.name_manager = $rootScope.user.firstname ? $rootScope.user.firstname[0] + ". " + $rootScope.user.lastname : $rootScope.user.lastname;
		}

		function loadCompany(company) {
            if (company) {
                $scope.form.id_company = company.id;
                $scope.form.name_company = company.company_name;
            } else {
                $scope.form.id_company = 0;
                $scope.form.name_company = "";
            }
		}

		function loadContact(contact) {
            if (contact) {
                $scope.form.id_contact = contact.id;
                $scope.form.name_contact = contact.first_name ? contact.first_name[0] + ". " + contact.last_name : contact.last_name;
            } else {
                $scope.form.id_contact = 0;
                $scope.form.name_contact = "";
            }
		}

        function loadManager(user) {
            if (user) {
                $scope.form.id_manager = user.id;
                $scope.form.name_manager = user.firstname + " " + user.lastname;
            } else {
                $scope.form.id_manager = "0";
                $scope.form.name_manager = "";
            }
        }

		function success(){

			delete $scope.form.title_parent;

			if($scope.form.start_date) {
				var y = $scope.form.start_date.getFullYear();
				var M = $scope.form.start_date.getMonth();
				var d = $scope.form.start_date.getDate();

				var date = new Date(Date.UTC(y, M, d));

				$scope.form.start_date = date;
			} else {
                $scope.form.start_date = 0;
			}

			if($scope.form.due_date) {
				var y2 = $scope.form.due_date.getFullYear();
				var M2 = $scope.form.due_date.getMonth();
				var d2 = $scope.form.due_date.getDate();

				var date2 = new Date(Date.UTC(y2, M2, d2));

				$scope.form.due_date = date2;
			} else {
                $scope.form.due_date = 0;
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
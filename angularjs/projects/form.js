app.controller('ComZeappsProjectFormCtrl', ['$scope', '$route', '$routeParams', '$location', '$rootScope', 'zeHttp', 'zeapps_modal', '$uibModal',
    function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal) {

        $scope.$parent.loadMenu("com_ze_apps_project", "com_zeapps_projects_management");

        $scope.form = {};

        if($routeParams.id){ // Edit
            zhttp.project.project.get($routeParams.id).then(function(response){
                if(response.data && response.data != 'false'){
                    $scope.form = response.data;
                    $scope.form.start_date = new Date($scope.form.start_date);
                    $scope.form.due_date = new Date($scope.form.due_date);
                    $scope.form.estimated_time = parseFloat($scope.form.estimated_time);
                    $scope.form.time_spent = parseFloat($scope.form.time_spent);
                    zhttp.project.project.get($scope.form.id_parent).then(function(response){
                        if(response.data && response.data != 'false'){
                            $scope.form.title_parent = response.data.breadcrumbs;
                        }
                    })
                }
            })
        }
        else if($routeParams.id_parent){ // Sub Project
            zhttp.project.project.get($routeParams.id_parent).then(function(response){
                if(response.data && response.data != 'false'){
                    $scope.form.id_parent = response.data.id;
                    $scope.form.title_parent = response.data.breadcrumbs;
                    $scope.form.id_company = response.data.id_company;
                    $scope.form.name_company = response.data.name_company;
                    $scope.form.id_contact = response.data.id_contact;
                    $scope.form.name_contact = response.data.name_contact;
                    $scope.form.id_manager = response.data.id_manager;
                    $scope.form.name_manager = response.data.name_manager;
                }
            })
        }
        else{ // New Project
            $scope.form.id_manager = $rootScope.user.id;
            $scope.form.name_manager = $rootScope.user.firstname ? $rootScope.user.firstname[0] + '. ' + $rootScope.user.lastname : $rootScope.user.lastname;
        }

        $scope.loadProject = function () {
            zeapps_modal.loadModule("com_zeapps_project", "search_project", {id:$scope.form.id}, function(objReturn) {
                if (objReturn) {
                    $scope.form.id_parent = objReturn.id;
                    $scope.form.title_parent = objReturn.breadcrumbs;
                } else {
                    $scope.form.id_parent = 0;
                    $scope.form.title_parent = '';
                }
            });
        };

        $scope.removeProject = function() {
            $scope.form.id_parent = 0;
            $scope.form.title_parent = '';
        };

        $scope.loadCompany = function () {
            zeapps_modal.loadModule("com_zeapps_contact", "search_company", {}, function(objReturn) {
                if (objReturn) {
                    $scope.form.id_company = objReturn.id;
                    $scope.form.name_company = objReturn.company_name;
                } else {
                    $scope.form.id_company = 0;
                    $scope.form.name_company = '';
                }
            });
        };

        $scope.removeCompany = function() {
            $scope.form.id_company = 0;
            $scope.form.name_company = '';
        };

        $scope.loadContact = function () {
            zeapps_modal.loadModule("com_zeapps_contact", "search_contact", {}, function(objReturn) {
                if (objReturn) {
                    $scope.form.id_contact = objReturn.id;
                    $scope.form.name_contact = objReturn.first_name ? objReturn.first_name[0] + '. ' + objReturn.last_name : objReturn.last_name;
                } else {
                    $scope.form.id_contact = 0;
                    $scope.form.name_contact = '';
                }
            });
        };

        $scope.removeContact = function() {
            $scope.form.id_contact = 0;
            $scope.form.name_contact = '';
        };

        $scope.loadManager = function () {
            zeapps_modal.loadModule("com_zeapps_core", "search_user", {}, function(objReturn) {
                if (objReturn) {
                    $scope.form.id_manager = objReturn.id;
                    $scope.form.name_manager = objReturn.firstname ? objReturn.firstname[0]  + '. ' + objReturn.lastname : objReturn.lastname;
                } else {
                    $scope.form.id_manager = 0;
                    $scope.form.name_manager = '';
                }
            });
        };

        $scope.removeManager = function() {
            $scope.form.id_manager = 0;
            $scope.form.name_manager = '';
        };

        $scope.success = function(){

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
                if(response.data && response.data != 'false'){
                    $location.url('/ng/com_zeapps_project/project/' + response.data);
                }
            })
        };

        $scope.cancel = function(){
            var id = $scope.form.id || '';
            $location.url('/ng/com_zeapps_project/project/' + id)
        };

    }]);
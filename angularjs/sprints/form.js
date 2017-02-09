app.controller('ComZeappsSprintFormCtrl', ['$scope', '$route', '$routeParams', '$location', '$rootScope', 'zeHttp', 'zeapps_modal', '$uibModal',
    function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal) {

        $scope.$parent.loadMenu("com_ze_apps_project", "com_zeapps_projects_sprint");

        $scope.form = {};

        if($routeParams.id){ // Edit
            zhttp.project.sprint.get($routeParams.id).then(function(response){
                if(response.data && response.data != 'false'){
                    $scope.form = response.data;
                    $scope.form.start_date = new Date($scope.form.start_date);
                    $scope.form.due_date = new Date($scope.form.due_date);
                    $scope.form.completed = $scope.form.completed === 'Y';
                    $scope.form.active = $scope.form.active === 'Y';
                    zhttp.project.project.get($scope.form.id_parent).then(function(response){
                        if(response.data && response.data != 'false'){
                            $scope.form.title_project = response.data.breadcrumbs;
                        }
                    })
                }
            })
        }
        else if($routeParams.id_project){ // Creation
            zhttp.project.project.get($routeParams.id_project).then(function(response){
                if(response.data && response.data != 'false'){
                    $scope.form.id_project = response.data.id;
                    $scope.form.title_project = response.data.breadcrumbs;
                }
            })
        }

        $scope.loadProject = function () {
            zeapps_modal.loadModule("com_zeapps_project", "search_project", {id:$scope.form.id}, function(objReturn) {
                if (objReturn) {
                    $scope.form.id_project = objReturn.id;
                    $scope.form.title_project = objReturn.breadcrumbs;
                } else {
                    $scope.form.id_project = 0;
                    $scope.form.title_project = '';
                }
            });
        };

        $scope.removeProject = function() {
            $scope.form.id_project = 0;
            $scope.form.title_project = '';
        };

        $scope.success = function(){
            delete $scope.form.title_project;

            if($scope.form.start_date) {
                var y = $scope.form.start_date.getFullYear();
                var M = $scope.form.start_date.getMonth();
                var d = $scope.form.start_date.getDate();

                $scope.form.start_date = new Date(Date.UTC(y, M, d));
            }

            if($scope.form.due_date) {
                var y2 = $scope.form.due_date.getFullYear();
                var M2 = $scope.form.due_date.getMonth();
                var d2 = $scope.form.due_date.getDate();

                $scope.form.due_date = new Date(Date.UTC(y2, M2, d2));
            }

            $scope.form.active = $scope.form.active ? 'Y' : 'N';
            $scope.form.completed = $scope.form.completed ? 'Y' : 'N';

            var formatted_data = angular.toJson($scope.form);

            zhttp.project.sprint.post(formatted_data).then(function(response){
                if(response.data && response.data != 'false'){
                    $location.url('/ng/com_zeapps_project/sprint/' + $scope.form.id_project + '/' + response.data);
                }
            })
        };

        $scope.cancel = function(){
            $location.url('/ng/com_zeapps_project/sprint/' + $scope.form.id_project);
        };
    }]);
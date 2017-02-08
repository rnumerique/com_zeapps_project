app.controller('ComZeappsProjectCardFormCtrl', ['$scope', '$route', '$routeParams', '$location', '$rootScope', 'zeHttp', 'zeapps_modal', '$uibModal', '$timeout',
    function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal, $timeout) {

        $scope.form = {};
        var step;
        var url;

        var getContext = function(id_project, id_sprint){
            $scope.$emit('triggerFormCard', {id_project : id_project, id_sprint: id_sprint});
        };

        $scope.$on('dataFormCard', function(event, data){
            step = data.step;
            url = data.url;
        });

        $scope.type = $routeParams.type || 'card';

        if($routeParams.id){ // Edit
            if($routeParams.type == 'deadline'){
                zhttp.project.deadline.get($routeParams.id).then(function (response) {
                    if (response.data && response.data != 'false') {
                        $scope.form = response.data;
                        $scope.form.due_date = new Date($scope.form.due_date);
                        zhttp.project.project.get($scope.form.id_project).then(function (response) {
                            if (response.data && response.data != 'false') {
                                $scope.form.title_project = response.data.breadcrumbs;
                            }
                        });
                        getContext($scope.form.id_project);
                    }
                })
            }
            else {
                zhttp.project.card.get($routeParams.id).then(function (response) {
                    if (response.data && response.data != 'false') {
                        $scope.form = response.data;
                        $scope.form.due_date = new Date($scope.form.due_date);
                        if ($scope.form.estimated_time)
                            $scope.form.estimated_time = parseFloat($scope.form.estimated_time);
                        zhttp.project.project.get($scope.form.id_project).then(function (response) {
                            if (response.data && response.data != 'false') {
                                $scope.form.title_project = response.data.breadcrumbs;
                            }
                        });
                        if($scope.form.id_sprint && $scope.form.id_sprint > 0) {
                            zhttp.project.sprint.get($scope.form.id_sprint).then(function (response) {
                                if (response.data && response.data != 'false') {
                                    $scope.form.title_sprint = response.data.title;
                                }
                            });
                        }
                        getContext($scope.form.id_project);
                    }
                })
            }
        }
        if($routeParams.id_sprint){ // Sprint
            zhttp.project.sprint.get($routeParams.id_sprint).then(function(response){
                if(response.data && response.data != 'false'){
                    $scope.form.id_sprint = response.data.id;
                    $scope.form.title_sprint = response.data.title;
                }
            });
        }
        if($routeParams.id_project){ // Project
            zhttp.project.project.get($routeParams.id_project).then(function(response){
                if(response.data && response.data != 'false'){
                    $scope.form.id_project = response.data.id;
                    $scope.form.title_project = response.data.breadcrumbs;
                    getContext($scope.form.id_project);
                }
            });
        }

        $scope.loadProject = function () {
            zeapps_modal.loadModule("com_zeapps_project", "search_project", {}, function(objReturn) {
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

        $scope.loadSprint = function () {
            zeapps_modal.loadModule("com_zeapps_project", "search_sprint", {id_project:$scope.form.id_project}, function(objReturn) {
                if (objReturn) {
                    $scope.form.id_sprint = objReturn.id;
                    $scope.form.title_sprint = objReturn.title || ('Sprint n° ' + objReturn.numerotation);
                    $scope.form.step = 2;
                } else {
                    $scope.form.id_sprint = 0;
                    $scope.form.title_sprint = '';
                    $scope.form.step = 1;
                }
            });
        };

        $scope.removeSprint = function() {
            $scope.form.id_sprint = 0;
            $scope.form.title_sprint = '';
            $scope.form.step = 1;
        };

        $scope.loadAssigned = function () {
            zeapps_modal.loadModule("com_zeapps_core", "search_user", {}, function(objReturn) {
                if (objReturn) {
                    $scope.form.id_assigned_to = objReturn.id;
                    $scope.form.name_assigned_to = objReturn.firstname + ' ' + objReturn.lastname;
                } else {
                    $scope.form.id_assigned_to = 0;
                    $scope.form.name_assigned_to = '';
                }
            });
        };

        $scope.removeAssigned = function() {
            $scope.form.id_assigned_to = 0;
            $scope.form.name_assigned_to = '';
        };

        $scope.success = function(){
            var formatted_data;

            delete $scope.form.title_project;
            delete $scope.form.title_sprint;

            if($scope.form.start_date) {
                var y = $scope.form.start_date.getFullYear();
                var M = $scope.form.start_date.getMonth();
                var d = $scope.form.start_date.getDate();

                $scope.form.start_date = new Date(Date.UTC(y, M, d));;
            }

            if($scope.form.due_date) {
                var y2 = $scope.form.due_date.getFullYear();
                var M2 = $scope.form.due_date.getMonth();
                var d2 = $scope.form.due_date.getDate();

                $scope.form.due_date = new Date(Date.UTC(y2, M2, d2));;
            }

            if($scope.type == 'card') {

                $scope.form.step = $scope.form.step || step;

                formatted_data = angular.toJson($scope.form);

                zhttp.project.card.post(formatted_data).then(function (response) {
                    if (response.data && response.data != 'false') {
                        $location.url(url);
                    }
                })
            }
            else{
                var data = {};

                if($scope.form.id)
                    data['id'] = $scope.form.id;

                data['id_project'] = $scope.form.id_project;
                data['title'] = $scope.form.title;
                data['due_date'] = $scope.form.due_date;

                formatted_data = angular.toJson(data);

                zhttp.project.deadline.post(formatted_data).then(function (response) {
                    if (response.data && response.data != 'false') {
                        $location.url(url);
                    }
                })
            }
        };

        $scope.cancel = function(){
            $location.url(url)
        };

    }]);
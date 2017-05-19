app.controller('ComZeappsProjectCardFormCtrl', ['$scope', '$route', '$routeParams', '$location', '$rootScope', 'zeHttp', 'zeapps_modal', '$uibModal', '$timeout',
    function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal, $timeout) {

        var step;
        var url;
        var whitelist_ids = [];

        $scope.form = {};
        $scope.categories = [];
        $scope.type = $routeParams.type || 'card';

        $scope.loadProject = loadProject;
        $scope.removeProject = removeProject;
        $scope.loadSprint = loadSprint;
        $scope.removeSprint = removeSprint;
        $scope.loadAssigned = loadAssigned;
        $scope.removeAssigned = removeAssigned;
        $scope.success = success;
        $scope.cancel = cancel;

        $scope.$on('dataFormCard', function(event, data){
            step = data.step;
            url = data.url;
        });

        if($routeParams.id){ // Edit
            if($routeParams.type == 'deadline'){
                zhttp.project.deadline.get($routeParams.id).then(function (response) {
                    if (response.data && response.data != 'false') {
                        $scope.form = response.data;
                        $scope.form.due_date = new Date($scope.form.due_date);
                        zhttp.project.project.get($scope.form.id_project).then(function (response) {
                            if (response.data && response.data != 'false') {
                                $scope.form.title_project = response.data.project.breadcrumbs;
                            }
                        });
                        getContext($scope.form.id_project);
                        getCategoriesOf($scope.form.id_project);
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
                                $scope.form.title_project = response.data.project.breadcrumbs;
                                whitelist_ids = [];
                                angular.forEach(response.data.project.users, function(user){
                                     whitelist_ids.push(user.id_user);
                                });
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
                        getCategoriesOf($scope.form.id_project);
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
                    $scope.form.id_project = response.data.project.id;
                    $scope.form.title_project = response.data.project.breadcrumbs;
                    whitelist_ids = [];
                    angular.forEach(response.data.project.users, function(user){
                        whitelist_ids.push(user.id_user);
                    });
                    getContext($scope.form.id_project);
                    getCategoriesOf($scope.form.id_project);
                }
            });
        }

        function loadProject() {
            zeapps_modal.loadModule("com_zeapps_project", "search_project", {}, function(objReturn) {
                if (objReturn) {
                    $scope.form.id_project = objReturn.id;
                    $scope.form.title_project = objReturn.breadcrumbs;
                    whitelist_ids = [];
                    angular.forEach(response.data.users, function(user){
                        whitelist_ids.push(user.id_user);
                    });
                    $scope.form.id_sprint = 0;
                    $scope.form.title_sprint = '';
                    $scope.form.step = 1;
                    getCategoriesOf($scope.form.id_project);
                } else {
                    $scope.form.id_project = 0;
                    $scope.form.title_project = '';
                }
            });
        }

        function removeProject() {
            $scope.form.id_project = 0;
            $scope.form.title_project = '';
        }

        function loadSprint() {
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
        }

        function removeSprint() {
            $scope.form.id_sprint = 0;
            $scope.form.title_sprint = '';
            $scope.form.step = 1;
        }

        function loadAssigned() {
            zeapps_modal.loadModule("com_zeapps_core", "search_user", {whitelist_ids : whitelist_ids}, function(objReturn) {
                if (objReturn) {
                    $scope.form.id_assigned_to = objReturn.id;
                    $scope.form.name_assigned_to = objReturn.firstname ? objReturn.firstname[0]  + '. ' + objReturn.lastname : objReturn.lastname;
                } else {
                    $scope.form.id_assigned_to = 0;
                    $scope.form.name_assigned_to = '';
                }
            });
        }

        function removeAssigned() {
            $scope.form.id_assigned_to = 0;
            $scope.form.name_assigned_to = '';
        }

        function success(){
            var formatted_data;

            delete $scope.form.title_project;
            delete $scope.form.title_sprint;

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
        }

        function cancel(){
            $location.url(url)
        }

        function getContext(id_project, id_sprint){
            $scope.$emit('triggerFormCard', {id_project : id_project, id_sprint: id_sprint});
        }

        function getCategoriesOf(id_project){
            zhttp.project.category.get_all(id_project).then(function(response){
                if(response.data && response.data != 'false'){
                    $scope.categories = response.data;
                }
            });
        }

    }]);
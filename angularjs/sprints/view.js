app.controller('ComZeappsSprintViewCtrl', ['$scope', '$route', '$routeParams', '$location', '$rootScope', 'zeHttp', 'zeapps_modal', '$uibModal',
    function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal) {

        $scope.$parent.loadMenu("com_ze_apps_project", "com_zeapps_projects_sprint");

        $scope.options = {
            'projectId': 'all'
        };

        zhttp.project.project.get_all(0, true).then(function(response){
            if(response.data && response.data != "false")
                $scope.projects = response.data;
        });

        zhttp.project.filter.get_all('sprint').then(function(response){
            if(response.data && response.data != 'false'){
                $scope.companies = response.data.companies;
                $scope.managers = response.data.managers;
                $scope.dates = response.data.dates;
                $scope.assigned = response.data.assigned;
            }
        });

        zhttp.project.sprint.get_all().then(function(response){
            if(response.data && response.data != "false") {
                var sprints = response.data;
                if(!$scope.sprintsByProject)
                    $scope.sprintsByProject = [];
                angular.forEach(sprints, function (sprint) {
                    if (!$scope.sprintsByProject[sprint.id_project])
                        $scope.sprintsByProject[sprint.id_project] = [];
                    $scope.sprintsByProject[sprint.id_project].push(sprint);
                });
            }
        });

        $scope.$watch("options.projectId", function(id, oldId, scope){
            if(id != undefined && id != oldId) {
                if(id === 'all'){
                    delete scope.options.id;
                }
                else {
                    scope.options.id = [];
                    scope.options.id.push(id);
                    zhttp.project.project.get_childs(id).then(function (response) {
                        if (response.data && response.data != "false") {
                            var subProjects = response.data;
                            if(subProjects.length === 0){
                                $location.url('/ng/com_zeapps_project/sprint/' + id);
                            }
                            angular.forEach(subProjects, function (subProject) {
                                scope.options.id.push(subProject.id);
                            });
                        }
                    })
                }
            }
        });

        $scope.goTo = function(sprint){
            $location.url('/ng/com_zeapps_project/sprint/' + sprint.id_project + '/' + sprint.id);
        }
    }]);
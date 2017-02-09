app.controller('ComZeappsPlanningViewCtrl', ['$scope', '$route', '$routeParams', '$location', '$rootScope', 'zeHttp', 'zeapps_modal', '$uibModal',
    function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal) {

        $scope.$parent.loadMenu("com_ze_apps_project", "com_zeapps_projects_planning");

        $scope.options = {
            'projectId': 'all',
            'id_company': 'all',
            'id_manager': 'all',
            'id_assigned_to': 'all',
            'step' : 1,
            'completed': false
        };

        $scope.select = function(project){
            $scope.options.projectId = project.id;
        };

        $scope.compareDates = function(date){
            return zhttp.project.compareDate(date);
        };

        zhttp.project.project.get_all(0, true).then(function(response){
            if(response.data && response.data != "false")
                $scope.projects = response.data;
        });

        zhttp.project.filter.get_all('planning').then(function(response){
            if(response.data && response.data != 'false'){
                $scope.companies = response.data.companies;
                $scope.managers = response.data.managers;
                $scope.dates = response.data.dates;
                $scope.assigned = response.data.assigned;
            }
        });

        zhttp.project.card.get_all().then(function(response){
            if(response.data && response.data != "false") {
                var cards = response.data;
                if(!$scope.cardsByDate)
                    $scope.cardsByDate = [];
                angular.forEach(cards, function (card) {
                    if(card.due_date != 0) {
                        if (!$scope.cardsByDate[card.due_date])
                            $scope.cardsByDate[card.due_date] = [];
                        $scope.cardsByDate[card.due_date].push(card);
                    }
                });
                if(!$scope.cardsByProject)
                    $scope.cardsByProject = [];
                angular.forEach(cards, function (card) {
                    if (!$scope.cardsByProject[card.id_project])
                        $scope.cardsByProject[card.id_project] = [];
                    $scope.cardsByProject[card.id_project].push(card);
                });
            }
        });

        zhttp.project.deadline.get_all().then(function(response){
           if(response.data && response.data != "false"){
               var deadlines = response.data;
               if(!$scope.cardsByDate)
                   $scope.cardsByDate = [];
               angular.forEach(deadlines, function (deadline) {
                   if (!$scope.cardsByDate[deadline.due_date])
                       $scope.cardsByDate[deadline.due_date] = [];
                   deadline.deadline = true;
                   $scope.cardsByDate[deadline.due_date].push(deadline);
               });
           }
        });

        $scope.tab = "planning";
        $scope.tabTemplate = '/com_zeapps_project/scrum/planning';

        $scope.display = function(tab){
            $scope.tab = tab;
            $scope.tabTemplate = '/com_zeapps_project/scrum/' + tab;
        };

        $scope.$watch("options.projectId", function(id, oldId, scope){
            if(id != undefined && id != oldId) {
                if(id === 'all'){
                    delete scope.options.id_project;
                }
                else {
                    scope.options.id_project = [];
                    scope.options.id_project.push(id);
                    zhttp.project.project.get_childs(id).then(function (response) {
                        if (response.data && response.data != "false") {
                            var subProjects = response.data;
                            angular.forEach(subProjects, function (subProject) {
                                scope.options.id_project.push(subProject.id);
                            });
                        }
                    })
                }
            }
        });

    }]);
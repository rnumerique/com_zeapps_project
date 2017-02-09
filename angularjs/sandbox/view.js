app.controller('ComZeappsSandboxViewCtrl', ['$scope', '$route', '$routeParams', '$location', '$rootScope', 'zeHttp', 'zeapps_modal', '$uibModal',
    function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal) {

        $scope.$parent.loadMenu("com_ze_apps_project", "com_zeapps_projects_sandbox");

        $scope.options = {
            'projectId': 'all',
            'subProjectId': 'all',
            'id_company': 'all',
            'id_manager': 'all',
            'id_assigned_to': 'all',
            'completed': false,
            'step' : '0'
        };

        if($routeParams.id)
            $scope.options.projectId = $routeParams.id;

        zhttp.project.project.get_all(0, true).then(function(response){
            if(response.data && response.data != "false")
                $scope.projects = response.data;
        });

        zhttp.project.filter.get_all('sandbox').then(function(response){
            if(response.data && response.data != 'false'){
                $scope.companies = response.data.companies;
                $scope.managers = response.data.managers;
                $scope.dates = response.data.dates;
                $scope.assigned = response.data.assigned;
            }
        });

        zhttp.project.sandbox.get_all().then(function(response){
            if(response.data && response.data != "false") {
                var sandboxes = response.data;
                $scope.sandboxesByProject = [];
                angular.forEach(sandboxes, function (sandbox) {
                    if (!$scope.sandboxesByProject[sandbox.id_project])
                        $scope.sandboxesByProject[sandbox.id_project] = [];
                    $scope.sandboxesByProject[sandbox.id_project].push(sandbox);
                });
                console.log($scope.sandboxesByProject);
            }
        });

        $scope.$watch("options.projectId", function(id, oldId, scope){
            if(id != undefined) {
                if(id === 'all'){
                    delete scope.options.id;
                }
                else {
                    scope.options.id = [];
                    scope.options.id.push(id);
                    zhttp.project.project.get_childs(id).then(function (response) {
                        if (response.data && response.data != "false") {
                            var subProjects = response.data;
                            angular.forEach(subProjects, function (subProject) {
                                scope.options.id.push(subProject.id);
                            });
                        }
                    })
                }
            }
        });

        $scope.validate = function(sandbox){
            zhttp.project.card.validate(sandbox.id).then(function(response){
                if(response.data && response.data != 'false'){
                    $scope.sandboxesByProject[sandbox.id_project].splice($scope.sandboxesByProject[sandbox.id_project].indexOf(sandbox), 1);
                }
            })
        };

        $scope.edit = function(sandbox){
            $location.url('/ng/com_zeapps_project/sandbox/edit/'+sandbox.id);
        };

        $scope.delete = function (sandbox) {
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: '/assets/angular/popupModalDeBase.html',
                controller: 'ZeAppsPopupModalDeBaseCtrl',
                size: 'lg',
                resolve: {
                    titre: function () {
                        return 'Attention';
                    },
                    msg: function () {
                        return 'Souhaitez-vous supprimer définitivement cette idée ?';
                    },
                    action_danger: function () {
                        return 'Annuler';
                    },
                    action_primary: function () {
                        return false;
                    },
                    action_success: function () {
                        return 'Confirmer';
                    }
                }
            });

            modalInstance.result.then(function (selectedItem) {
                if (selectedItem.action == 'danger') {

                } else if (selectedItem.action == 'success') {
                    zhttp.project.card.del(sandbox.id).then(function (response) {
                        if (response.status == 200) {
                            $scope.sandboxesByProject[sandbox.id_project].splice($scope.sandboxesByProject[sandbox.id_project].indexOf(sandbox), 1);
                        }
                    });
                }

            }, function () {
                //console.log("rien");
            });

        };

    }]);
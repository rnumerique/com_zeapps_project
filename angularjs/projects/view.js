app.controller('ComZeappsProjectViewCtrl', ['$scope', '$route', '$routeParams', '$location', '$rootScope', 'zeHttp', 'zeapps_modal', '$uibModal',
    function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal) {

        $scope.$parent.loadMenu("com_ze_apps_project", "com_zeapps_projects_management");

        $scope.tree = {
            branches: []
        };

        $scope.options = {
            'step' : 1,
            'completed' : false
        };

        $scope.activeCategory = {
            data: ''
        };

        $scope.view = '/com_zeapps_project/planning/table';

        $scope.compareDates = function(date){
            return zhttp.project.compareDate(date);
        };

        zhttp.project.filter.get_all('project').then(function(response){
            if(response.data && response.data != 'false'){
                $scope.dates = response.data;
            }
        });

        var getTree = function() {
            zhttp.project.project.tree().then(function (response) {
                if (response.status == 200) {
                    $scope.tree.branches = response.data;
                    var id = $routeParams.id || 0;
                    if (id)
                        $scope.activeCategory.data = zhttp.project.openTree($scope.tree, id);
                }
            });
        };
        getTree();

        $scope.isActive = function(tab){
            if(tab === 'planning' && $scope.view === '/com_zeapps_project/planning/table')
                return true;
            else if(tab === 'categories' && $scope.view === '/com_zeapps_project/project/categories')
                return true;
            else
                return false;
        };

        $scope.showPlanning = function(){
            console.log('test');
            $scope.view = '/com_zeapps_project/planning/table';
        };

        $scope.showCategories = function(){
            $scope.view = '/com_zeapps_project/project/categories';
        };

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

        var getCategories = function(id_project){
            zhttp.project.category.get_all(id_project).then(function(response){
                if(response.data && response.data != 'false'){
                    $scope.categories = response.data;
                }
                else
                    $scope.categories = [];
            })
        };

        $scope.editCategory = function(category){
            $location.url('/ng/com_zeapps_project/project/categories/edit/' + category.id);
        };

        $scope.deleteCategory = function(category){
            zhttp.project.category.del(category.id).then(function(response){
                if(response.data && response.data != 'false'){
                    $scope.categories.splice($scope.categories.indexOf(category), 1);
                }
            });
        };

        $scope.$watch('activeCategory.data', function(value, old, scope){
            if(typeof(value.id) !== 'undefined' && value.id != old.id){
                scope.options.id_project = [];
                scope.options.id_project.push(value.id);
                zhttp.project.project.get_childs(value.id).then(function (response) {
                    if (response.data && response.data != "false") {
                        var subProjects = response.data;
                        angular.forEach(subProjects, function (subProject) {
                            scope.options.id_project.push(subProject.id);
                        });
                    }
                });
                getCategories(value.id);
            }
        });

        $scope.delete_project = function (id) {
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
                        return 'Souhaitez-vous supprimer définitivement ce projet ?';
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
                    zhttp.project.project.del(id).then(function (response) {
                        if (response.data && response.data != 'false') {
                            if (response.data.hasDependencies) {
                                $scope.force_delete_project(id);
                            }
                            else {
                                $scope.activeCategory.data = '';
                                getTree();
                            }
                        }
                    });
                }

            }, function () {
                //console.log("rien");
            });

        };

        $scope.force_delete_project = function (id) {
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
                        return 'Ce projet contient des sous-projets ou cartes/deadlines, ceux-ci seront également supprimés.';
                    },
                    action_danger: function () {
                        return 'Annuler';
                    },
                    action_primary: function () {
                        return false;
                    },
                    action_success: function () {
                        return 'Confirmer la suppression';
                    }
                }
            });

            modalInstance.result.then(function (selectedItem) {
                if (selectedItem.action == 'danger') {

                } else if (selectedItem.action == 'success') {
                    zhttp.project.project.del(id, true).then(function (response) {
                        if (response.status == 200) {
                            $scope.activeCategory.data = response.data;
                            getTree();
                        }
                    });
                }

            }, function () {
                //console.log("rien");
            });

        };

    }]);
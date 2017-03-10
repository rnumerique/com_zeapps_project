app.controller('ComZeappsProjectViewCtrl', ['$scope', '$route', '$routeParams', '$location', '$rootScope', 'zeHttp', 'zeapps_modal', '$uibModal',
    function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal) {

        $scope.$parent.loadMenu("com_ze_apps_project", "com_zeapps_projects_management");

        var project_users_ids = [];

        $scope.tree = {
            branches: []
        };
        $scope.options = {
            'step' : 1,
            'completed' : false
        };
        $scope.project = {};

        $scope.view = '/com_zeapps_project/planning/table';
        $scope.showPlanning     = function(){ $scope.view = '/com_zeapps_project/planning/table'; };
        $scope.showCategories   = function(){ $scope.view = '/com_zeapps_project/project/categories'; };
        $scope.showRights       = function(){ $scope.view = '/com_zeapps_project/project/rights'; };

        $scope.compareDates = function(date){ return zhttp.project.compareDate(date); };

        $scope.isActive = function(tab){
            if(tab === 'planning' && $scope.view === '/com_zeapps_project/planning/table')
                return true;
            else if(tab === 'categories' && $scope.view === '/com_zeapps_project/project/categories')
                return true;
            else if(tab === 'rights' && $scope.view === '/com_zeapps_project/project/rights')
                return true;
            else
                return false;
        };

        if($routeParams.id){
            zhttp.project.project.get($routeParams.id).then(function(response){
                if(response.data && response.data != 'false'){
                    $scope.project = response.data.project;
                    $scope.dates = response.data.dates;
                    $scope.categories = response.data.categories;

                    var cards = response.data.cards;
                    $scope.cardsByDate = [];
                    angular.forEach(cards, function (card) {
                        if(card.due_date != 0) {
                            if (!$scope.cardsByDate[card.due_date])
                                $scope.cardsByDate[card.due_date] = [];
                            $scope.cardsByDate[card.due_date].push(card);
                        }
                    });


                    $scope.project_users = response.data.project_users;
                    project_users_ids = [];
                    angular.forEach($scope.project_users, function(user){
                        project_users_ids.push(user.id_user);
                        user.access = !!parseInt(user.access);
                        user.sandbox = !!parseInt(user.sandbox);
                        user.card = !!parseInt(user.card);
                        user.sprint = !!parseInt(user.sprint);
                        user.project = !!parseInt(user.project);
                    });
                }
            });
        }

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

        $scope.addProjectUser = function(){
            zeapps_modal.loadModule("com_zeapps_core", "search_user", {banned_ids : project_users_ids}, function(objReturn) {
                if (objReturn) {
                    var user = {};

                    user.id_user = objReturn.id;
                    user.id_project = $scope.project.id;
                    user.name = objReturn.firstname ? objReturn.firstname[0]  + '. ' + objReturn.lastname : objReturn.lastname;
                    user.access = 1;

                    var formatted_data = angular.toJson(user);
                    zhttp.project.right.post(formatted_data).then(function(response){
                        if(response.data && response.data != 'false'){
                            user.id = response.data;
                            user.access = true;
                            $scope.project_users.push(Object.create(user));
                            project_users_ids.push(user.id_user);
                        }
                    });
                } else {
                }
            });
        };

        $scope.deleteRightsOf = function(user){
            zhttp.project.right.del(user.id).then(function(response){
                if(response.data && response.data != 'false'){
                    $scope.project_users.splice($scope.project_users.indexOf(user), 1);
                    project_users_ids.splice(project_users_ids.indexOf(user.id_user), 1);
                }
            })
        };

        $scope.changeRights = function(user, right){
            if(!user[right]) {
                switch(right){
                    case 'access' :
                        user['sandbox'] = false;
                    case 'sandbox' :
                        user['card'] = false;
                    case 'card' :
                        user['sprint'] = false;
                    case 'sprint' :
                        user['project'] = false;
                    default :
                        break;
                }
            }
            else{
                switch(right){
                    case 'project' :
                        user['sprint'] = true;
                    case 'sprint' :
                        user['card'] = true;
                    case 'card' :
                        user['sandbox'] = true;
                    case 'sandox' :
                        user['access'] = true;
                    default :
                        break;
                }
            }
            console.log(user);
            saveRightsOf(user);
        };

        $scope.archive_project = function (id) {
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
                        return 'Souhaitez-vous archiver ce projet ?';
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
                    zhttp.project.project.archive(id);
                }

            }, function () {
                //console.log("rien");
            });

        };

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
                                $location.url('/ng/com_zeapps_project/project');
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
                            $location.url('/ng/com_zeapps_project/project');
                        }
                    });
                }

            }, function () {
                //console.log("rien");
            });

        };

        function saveRightsOf(user){

            var data = {};

            if(user.id){
                data.id = user.id;
            }

            data.access = user.access ? 1 : 0;
            data.sandbox = user.sandbox ? 1 : 0;
            data.card = user.card ? 1 : 0;
            data.sprint = user.sprint ? 1 : 0;
            data.project = user.project ? 1 : 0;

            data.id_user = user.id_user;
            data.id_project = user.id_project;

            var formatted_data = angular.toJson(data);
            zhttp.project.right.post(formatted_data);
        }

    }]);
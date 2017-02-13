app.controller('ComZeappsSprintDetailCtrl', ['$scope', '$route', '$routeParams', '$location', '$rootScope', 'zeHttp', 'zeapps_modal', '$uibModal',
    function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal) {

        $scope.$parent.loadMenu("com_ze_apps_project", "com_zeapps_projects_sprint");

        $scope.options = {
            'projectId': 'none',
            'sprintId' : 0,
            'sprint' : {}
        };

        $scope.steps = {
            '2': 'A faire',
            '3': 'En cours',
            '4': 'Qualité',
            '5': 'Terminé'
        };

        var defaultCategory = {
            id: 0,
            title: ""
        };

        var cards = [];

        $scope.sprintsByProject = [];
        $scope.current = undefined;

        zhttp.project.project.get_all(0, true).then(function(response){
            if(response.data && response.data != "false") {
                $scope.projects = response.data;
                angular.forEach($scope.projects, function (project) {
                    if (!$scope.sprintsByProject[project.id])
                        $scope.sprintsByProject[project.id] = [];
                });
            }
        });

        zhttp.project.sprint.get_all().then(function(response){
            if(response.data && response.data != "false") {
                var sprints = response.data;
                angular.forEach(sprints, function (sprint) {
                    if (!$scope.sprintsByProject[sprint.id_project])
                        $scope.sprintsByProject[sprint.id_project] = [];
                    $scope.sprintsByProject[sprint.id_project].push(sprint);
                    if($scope.options.sprintId && sprint.id === $scope.options.sprintId)
                        $scope.current = sprint;
                });
            }
        });

        var get_categories = function(id_project) {
            zhttp.project.category.get_all(id_project).then(function (response) {
                if (response.data && response.data != "false") {
                    $scope.categories = response.data;
                }
                else{
                    $scope.categories = [];
                }
                $scope.categories.unshift(defaultCategory);
                initCards();
            });
        };

        var initCards = function(){
            $scope.cards = [];
            angular.forEach($scope.categories, function(category){
                if(!$scope.cards[category.id])
                    $scope.cards[category.id] = [];
                angular.forEach($scope.steps, function(step, id){
                    if(!$scope.cards[category.id][id])
                        $scope.cards[category.id][id] = [];
                });
            });
            angular.forEach(cards, function(card){
                if(card.id_sprint === $scope.options.sprintId) {
                    if (!$scope.cards[card.id_category])
                        $scope.cards[card.id_category] = [];
                    if (!$scope.cards[card.id_category][card.step])
                        $scope.cards[card.id_category][card.step] = [];
                    $scope.cards[card.id_category][card.step].push(card);
                }
            });
        };

        var get_cards = function(id_project){
            zhttp.project.card.get_all(id_project).then(function(response){
                if(response.data && response.data != "false") {
                    cards = response.data;
                    initCards();
                }
            });
        };

        if($routeParams.id){
            $scope.options.sprintId = $routeParams.id;
        }

        if($routeParams.id_project){
            $scope.options.projectId = $routeParams.id_project;
            get_categories($routeParams.id_project);
            get_cards($routeParams.id_project);
        }

        $scope.$watch("options.projectId", function(id, oldId, scope){
            if(id != undefined && id != oldId) {
                scope.options.sprintId = 0;
                get_categories(id);
                angular.forEach(scope.sprintsByProject[id], function (sprint) {
                    if(sprint.active === 'Y')
                        scope.options.sprintId = sprint.id;
                });
            }
        });

        $scope.$watch("options.sprintId", function(id, oldId, scope){
            if(id != undefined && id != oldId) {
                scope.current = undefined;
                for(var i = 0; i < scope.sprintsByProject[scope.options.projectId].length; i++){
                    if(scope.sprintsByProject[scope.options.projectId][i].id === id) {
                        scope.current = scope.sprintsByProject[scope.options.projectId][i];
                        scope.sortable.disabled = scope.current.completed === 'Y';
                        break;
                    }
                }
                get_cards(scope.options.projectId);
            }
        });

        $scope.addCards = function(){
            zeapps_modal.loadModule("com_zeapps_project", "search_card", {id_project:$scope.options.projectId, id_sprint:$scope.options.sprintId}, function(objReturn) {
                if (objReturn) {
                    zhttp.project.sprint.updateCards($scope.options.sprintId, objReturn).then(function(response){
                        if(response.data && response.data != 'false'){
                            get_cards($scope.options.projectId);
                        }
                    })
                }
            });
        };

        $scope.detailCard = function(card){
            zeapps_modal.loadModule("com_zeapps_project", "detail_card", {id : card.id});
        };

        $scope.editCard = function(card, event){
            event.stopPropagation();
            $location.url('/ng/com_zeapps_project/sprint/edit/card/' + card.id_project + '/' + card.id_sprint + '/' + card.id);
        };

        $scope.new = function(){
            if($scope.options.projectId !== 'none')
                $location.url('/ng/com_zeapps_project/sprint/create/' + $scope.options.projectId);
            else
                $location.url('/ng/com_zeapps_project/sprint/create/');
        };

        $scope.edit = function(){
            $location.url('/ng/com_zeapps_project/sprint/edit/' + $scope.options.sprintId);
        };

        $scope.delete = function(){
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
                        return 'Souhaitez-vous supprimer définitivement ce sprint ?';
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
                    zhttp.project.sprint.del($scope.current.id).then(function (response) {
                        if (response.status == 200) {
                            $location.url('/ng/com_zeapps_project/sprint/');
                        }
                    });
                }

            }, function () {
                //console.log("rien");
            });
        };

        $scope.hasPrev = function(){
            if($scope.sprintsByProject[$scope.options.projectId] && $scope.sprintsByProject[$scope.options.projectId].length > 0)
                return $scope.sprintsByProject[$scope.options.projectId][0].id !== $scope.options.sprintId;
            else
                return false;
        };

        $scope.prev = function(){
            var last = $scope.options.sprintId;
            for(var i = 0; i < $scope.sprintsByProject[$scope.options.projectId].length; i++){
                if($scope.sprintsByProject[$scope.options.projectId][i].id === $scope.options.sprintId) {
                    $scope.options.sprintId = last;
                    break;
                }
                last = $scope.sprintsByProject[$scope.options.projectId][i].id;
            }
        };

        $scope.hasNext = function(){
            if($scope.sprintsByProject[$scope.options.projectId] && $scope.sprintsByProject[$scope.options.projectId].length > 0)
                return $scope.sprintsByProject[$scope.options.projectId][$scope.sprintsByProject[$scope.options.projectId].length - 1].id !== $scope.options.sprintId;
            else
                return false;
        };

        $scope.next = function(){
            var next = false;
            for(var i = 0; i < $scope.sprintsByProject[$scope.options.projectId].length; i++){
                if(next) {
                    $scope.options.sprintId = $scope.sprintsByProject[$scope.options.projectId][i].id;
                    break;
                }
                next = $scope.sprintsByProject[$scope.options.projectId][i].id === $scope.options.sprintId;
            }
        };

        $scope.sortable = {
            connectWith: ".sortableContainer",
            placeholder: "app",
            disabled: false,
            delay: 200,
            stop: function( event, ui ) {

                var idObj = $(ui.item[0]).attr("data-id") ;
                var ligneSelectionnee = $(".card_" + idObj) ;
                var step = ligneSelectionnee.parent().attr("data-step") ;
                var category = ligneSelectionnee.parent().attr("data-category") ;

                var data = {} ;
                data.id = idObj;
                data.step = step ;
                data.id_category = category ;

                var formatted_data = angular.toJson(data);

                zhttp.project.card.post(formatted_data);
            }
        };
    }]);
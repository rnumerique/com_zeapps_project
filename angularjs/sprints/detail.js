app.controller('ComZeappsSprintDetailCtrl', ['$scope', '$route', '$routeParams', '$location', '$rootScope', 'zeHttp', 'zeapps_modal', '$uibModal',
    function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal) {

        $scope.$parent.loadMenu("com_ze_apps_project", "com_zeapps_projects_sprint");

        $scope.options = {
            'projectId': 'none',
            'sprintId' : undefined,
            'sprint' : {}
        };
        $scope.steps = {
            '2': 'A faire',
            '3': 'En cours',
            '4': 'Qualité',
            '5': 'Terminé'
        };
        $scope.sortable = {
            connectWith: ".sortableContainer",
            placeholder: "sprint_placeholder",
            disabled: true,
            delay: 200,
            stop: sortableStop
        };
        $scope.current = undefined;

        $scope.findActiveSprint = findActiveSprint;
        $scope.selectSprint = selectSprint;
        $scope.addCards = addCards;
        $scope.detailCard = detailCard;
        $scope.editCard = editCard;
        $scope.new = newSprint;
        $scope.edit = edit;
        $scope.finalize = finalize;
        $scope.delete = del;
        $scope.hasPrev = hasPrev;
        $scope.prev = prev;
        $scope.hasNext = hasNext;
        $scope.next = next;

        zhttp.project.sprint.get_all().then(function(response){
            if(response.data && response.data != "false") {
                $scope.projects = response.data;

                if($routeParams.id_project){
                    for(var i = 0; i < $scope.projects.length; i++){
                        if($scope.projects[i].id == $routeParams.id_project){
                            $scope.options.projectId = i;
                            $scope.sortable.disabled = !$rootScope.project_rights || $rootScope.project_rights[$scope.projects[i].id]['card'] == '0';

                            if($routeParams.id) {
                                for (var j = 0; j < $scope.projects[i].sprints.length; j++) {
                                    if ($scope.projects[i].sprints[j].id == $routeParams.id) {
                                        $scope.options.sprintId = j;
                                    }
                                }
                                $scope.selectSprint();
                            }
                            else{
                                $scope.findActiveSprint();
                            }
                        }
                    }
                }
            }
        });





        function findActiveSprint(){
            $scope.options.sprintId = undefined;
            if($scope.options.projectId !== 'none') {
                $scope.sortable.disabled = $rootScope.project_rights[$scope.projects[$scope.options.projectId].id]['card'] == '0';

                for(var i=0; i < $scope.projects[$scope.options.projectId].sprints.length; i++){
                    if ($scope.projects[$scope.options.projectId].sprints[i].active === 'Y') {
                        $scope.options.sprintId = i;
                        break;
                    }
                }
            }

            $scope.selectSprint();
        }

        function selectSprint(){
            if($scope.options.sprintId !== undefined){
                $scope.current = $scope.projects[$scope.options.projectId].sprints[$scope.options.sprintId];
            }
            else{
                $scope.current = undefined;
            }
        }

        function addCards(){
            zeapps_modal.loadModule("com_zeapps_project", "search_card", {id_project:$scope.projects[$scope.options.projectId].id, id_sprint:$scope.current.id}, function(objReturn) {
                if (objReturn) {
                    for(var i=0; i < objReturn.length; i++) {
                        objReturn[i].id_sprint = $scope.current.id;
                        objReturn[i].step = 2;
                        objReturn[i].sort = $scope.current.cards[objReturn[i].id_category][objReturn[i].step].length;

                        $scope.current.cards[objReturn[i].id_category][objReturn[i].step].push(objReturn[i]);
                    }
                    zhttp.project.sprint.updateCards(objReturn);
                }
            });
        }

        function detailCard(card){
            zeapps_modal.loadModule("com_zeapps_project", "detail_card", {card : card});
        }

        function editCard(card, event){
            event.stopPropagation();
            $location.url('/ng/com_zeapps_project/sprint/edit/card/' + card.id_project + '/' + card.id_sprint + '/' + card.id);
        }

        function newSprint(){
            if($scope.options.projectId !== 'none')
                $location.url('/ng/com_zeapps_project/sprint/create/' + $scope.projects[$scope.options.projectId].id);
            else
                $location.url('/ng/com_zeapps_project/sprint/create/');
        }

        function edit(){
            $location.url('/ng/com_zeapps_project/sprint/edit/' + $scope.current.id);
        }

        function finalize(){
            if(hasCardsNotFinished()) {
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
                            return 'Ce sprint a des cartes non terminées. Souhaitez-vous les transferer vers le sprint suivant ?';
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
                        zhttp.project.sprint.finalize($scope.current.id, true).then(function (response) {
                            if (response.status == 200) {
                                $location.url('/ng/com_zeapps_project/sprint/' + $scope.current.id_project + '/' + response.data);
                            }
                        });
                    }

                }, function () {
                    //console.log("rien");
                });
            }
            else{
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
                            return 'Souhaitez-vous clôturer ce sprint ?';
                        },
                        action_danger: function () {
                            return 'Annuler';
                        },
                        action_primary: function () {
                            return 'Clôturer';
                        },
                        action_success: function () {
                            return 'Clôturer et ouvrir le suivant';
                        }
                    }
                });

                modalInstance.result.then(function (selectedItem) {
                    if (selectedItem.action == 'danger') {

                    } else if (selectedItem.action == 'primary') {
                        zhttp.project.sprint.finalize($scope.current.id, false).then(function (response) {
                            if (response.status == 200) {
                                $location.url('/ng/com_zeapps_project/sprint/' + $scope.current.id_project);
                            }
                        });
                    } else if (selectedItem.action == 'success') {
                        zhttp.project.sprint.finalize($scope.current.id, true).then(function (response) {
                            if (response.status == 200) {
                                $location.url('/ng/com_zeapps_project/sprint/' + $scope.current.id_project + '/' + response.data);
                            }
                        });
                    }

                }, function () {
                    //console.log("rien");
                });
            }
        }

        function del(){
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
        }

        function hasPrev(){
            if($scope.projects && $scope.projects[$scope.options.projectId] && $scope.projects[$scope.options.projectId].sprints && $scope.projects[$scope.options.projectId].sprints.length > 0)
                return $scope.projects[$scope.options.projectId].sprints[0].id !== $scope.current.id;
            else
                return false;
        }

        function prev(){
            var last = $scope.options.sprintId;
            for(var i = 0; i < $scope.projects[$scope.options.projectId].sprints.length; i++){
                if($scope.projects[$scope.options.projectId].sprints[i].id === $scope.current.id) {
                    $scope.options.sprintId = last;
                    break;
                }
                last = $scope.projects[$scope.options.projectId].sprints[i].id;
            }
        }

        function hasNext(){
            if($scope.projects && $scope.projects[$scope.options.projectId] && $scope.projects[$scope.options.projectId].sprints && $scope.projects[$scope.options.projectId].sprints.length > 0)
                return $scope.projects[$scope.options.projectId].sprints[$scope.projects[$scope.options.projectId].sprints.length - 1].id !== $scope.current.id;
            else
                return false;
        }

        function next(){
            var next = false;
            for(var i = 0; i < $scope.projects[$scope.options.projectId].sprints.length; i++){
                if(next) {
                    $scope.options.sprintId = $scope.projects[$scope.options.projectId].sprints[i].id;
                    break;
                }
                next = $scope.projects[$scope.options.projectId].sprints[i].id === $scope.current.id;
            }
        }

        function hasCardsNotFinished(){
            var hasCards = false;
            angular.forEach($scope.projects[$scope.options.projectId].categories, function(category){
                if(($scope.current.cards[category.id][2] && $scope.current.cards[category.id][2].length > 0) ||
                    ($scope.current.cards[category.id][3] && $scope.current.cards[category.id][3].length > 0) ||
                    ($scope.current.cards[category.id][4] && $scope.current.cards[category.id][4].length > 0)){
                    hasCards = true;
                }
            });
            return hasCards;
        }

        function sortableStop( event, ui ) {

            var idObj = $(ui.item[0]).attr("data-id") ;
            var ligneSelectionnee = $(".card_" + idObj) ;
            var step = ligneSelectionnee.parent().attr("data-step") ;
            var category = ligneSelectionnee.parent().attr("data-category") ;

            var data = {} ;
            data.id = idObj;

            data.step = step ;
            if(step == 5){
                data.completed = 'Y';
            }
            else{
                data.completed = 'N';
            }
            data.id_category = category ;

            for(var k = 0; k < $scope.current.cards[category][step].length; k++){
                if($scope.current.cards[category][step][k].id == data.id){
                    data.oldStep = $scope.current.cards[category][step][k].step;
                    data.oldCategory = $scope.current.cards[category][step][k].id_category;

                    if($scope.current.cards[category][step][k].step == 2 && step != 2 && $scope.current.cards[category][step][k].id_assigned_to == 0){
                        $scope.current.cards[category][step][k].id_assigned_to = $rootScope.user.id;
                        $scope.current.cards[category][step][k].name_assigned_to = $rootScope.user.firstname ? $rootScope.user.firstname[0]  + '. ' + $rootScope.user.lastname : $rootScope.user.lastname;
                    }

                    $scope.current.cards[category][step][k].category = category;
                    $scope.current.cards[category][step][k].step = step;
                }
                $scope.current.cards[category][step][k].sort = k;
            }

            var formatted_data = angular.toJson($scope.current.cards[category][step]);
            zhttp.project.sprint.updateCards(formatted_data);

            for(var i = 0; i < $scope.current.cards[data.oldCategory][data.oldStep].length; i++){
                $scope.current.cards[data.oldCategory][data.oldStep][i].sort = i;
            }

            var formatted_data = angular.toJson($scope.current.cards[data.oldCategory][data.oldStep]);
            zhttp.project.sprint.updateCards(formatted_data);
        }
    }]);
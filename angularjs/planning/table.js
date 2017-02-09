app.controller('ComZeAppsPlanningTableCtrl', ['$scope', '$route', '$routeParams', '$location', '$rootScope', 'zeHttp', 'zeapps_modal', '$uibModal',
    function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal) {

        $scope.stepOf = function(card){
            if(card.step === '2')
                return 'fa-calendar-o text-muted';
            if(card.step === '3')
                return 'fa-calendar-o text-info';
            if(card.step === '4')
                return 'fa-calendar-o text-warning';
            if(card.step === '5')
                return 'fa-calendar-check-o text-success';
        };

        $scope.complete = function(card){
            zhttp.project.card.complete(card.id, card.deadline).then(function(response){
                if (response.status == 200) {
                    card.completed = 'Y';
                }
            });
        };

        $scope.edit = function(card){
            var type = card.deadline ? 'deadline' : 'card';
            $location.url('/ng/com_zeapps_project/project/card/edit/'+type+'/'+card.id);
        };

        $scope.delete = function (card) {
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
                        if(card.deadline)
                            return 'Souhaitez-vous supprimer définitivement cette deadline ?';
                        else
                            return 'Souhaitez-vous supprimer définitivement cette carte ?';
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
                    zhttp.project.card.del(card.id, card.deadline).then(function (response) {
                        if (response.status == 200) {
                            $scope.cardsByDate[card.due_date].splice($scope.cardsByDate[card.due_date].indexOf(card), 1);
                        }
                    });
                }

            }, function () {
                //console.log("rien");
            });

        };

    }]);
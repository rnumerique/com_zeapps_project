// declare the modal to the app service
listModuleModalFunction.push({
    module_name:'com_zeapps_project',
    function_name:'detail_card',
    templateUrl:'/com_zeapps_project/card/modal_detail',
    controller:'ZeAppsProjectsModalDetailCardCtrl',
    size:'lg',
    resolve: {}
});


app.controller('ZeAppsProjectsModalDetailCardCtrl', function($scope, $uibModalInstance, zeHttp, option) {
    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };


    zeHttp.project.card.get(option.id).then(function (response) {
        if (response.status == 200) {
            $scope.card = response.data ;
        }
    });

    $scope.close = function () {
        $uibModalInstance.close($scope.selectedCards);
    }

}) ;
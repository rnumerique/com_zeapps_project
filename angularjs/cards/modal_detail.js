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

    $scope.card = option.card;

    $scope.close = function () {
        $uibModalInstance.close($scope.selectedCards);
    }

}) ;
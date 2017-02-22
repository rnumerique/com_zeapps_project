// declare the modal to the app service
listModuleModalFunction.push({
    module_name:'com_zeapps_project',
    function_name:'detail_card',
    templateUrl:'/com_zeapps_project/card/modal_detail',
    controller:'ZeAppsProjectsModalDetailCardCtrl',
    size:'lg',
    resolve: {}
});


app.controller('ZeAppsProjectsModalDetailCardCtrl', function($scope, $uibModalInstance, zeHttp, option, $location) {
    $scope.card = option.card;

    $scope.edit = function(){
        $location.url('/ng/com_zeapps_project/sprint/edit/card/' + $scope.card.id_project + '/' + $scope.card.id_sprint + '/' + $scope.card.id);
        $uibModalInstance.dismiss();
    };

    $scope.close = function () {
        $uibModalInstance.dismiss();
    };

}) ;
// declare the modal to the app service
listModuleModalFunction.push({
    module_name:'com_zeapps_project',
    function_name:'timer_end',
    templateUrl:'/com_zeapps_project/timer/modal',
    controller:'ZeAppsProjectsModalTimerCtrl',
    size:'lg',
    resolve:{
        titre: function () {
            return 'Fin du timer';
        }
    }
});


app.controller('ZeAppsProjectsModalTimerCtrl', function($scope, $rootScope, $uibModalInstance, zeHttp, titre) {
    $scope.titre = titre ;

    $scope.start = {
        h : moment($rootScope.currentTask.start_time).hour(),
        m : moment($rootScope.currentTask.start_time).minute(),
        d : moment($rootScope.currentTask.start_time).date(),
        M : '' + moment($rootScope.currentTask.start_time).month(),
        y : moment($rootScope.currentTask.start_time).year()
    };
    $scope.end = {
        h : moment($rootScope.currentTask.stop_time).hour(),
        m : moment($rootScope.currentTask.stop_time).minute(),
        d : moment($rootScope.currentTask.stop_time).date(),
        M : '' + moment($rootScope.currentTask.stop_time).month(),
        y : moment($rootScope.currentTask.stop_time).year()
    };

    $scope.cancel = function () {
        $uibModalInstance.close(false);
    };

    $scope.save = function () {
        $rootScope.currentTask.start_time = $scope.start.y + '-' + (parseInt($scope.start.M)+1) + '-' + $scope.start.d + ' ' + $scope.start.h + ':' + $scope.start.m + ':00';
        $rootScope.currentTask.stop_time = $scope.end.y + '-' + (parseInt($scope.end.M)+1) + '-' + $scope.end.d + ' ' + $scope.end.h + ':' + $scope.end.m + ':00';

        $uibModalInstance.close(true);
    }

}) ;
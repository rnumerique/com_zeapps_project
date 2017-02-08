// declare the modal to the app service
listModuleModalFunction.push({
    module_name:'com_zeapps_project',
    function_name:'search_sprint',
    templateUrl:'/com_zeapps_project/sprint/modal_sprint',
    controller:'ZeAppsProjectsModalSprintCtrl',
    size:'lg',
    resolve:{
        titre: function () {
            return 'Recherche d\'un sprint';
        }
    }
});


app.controller('ZeAppsProjectsModalSprintCtrl', function($scope, $uibModalInstance, zeHttp, titre, option) {
    $scope.titre = titre ;

    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };



    var loadList = function () {
        zeHttp.project.sprint.get_all(option.id_project).then(function (response) {
            if (response.status == 200 && response.data != 'false') {
                $scope.sprints = response.data ;
            }
        });
    };
    loadList() ;



    $scope.loadSprint = function (id) {


        // search the company
        var sprint = false ;
        for (var i = 0 ; i < $scope.sprints.length ; i++) {
            if ($scope.sprints[i].id == id) {
                sprint = $scope.sprints[i] ;
                break;
            }
        }

        $uibModalInstance.close(sprint);
    }

}) ;
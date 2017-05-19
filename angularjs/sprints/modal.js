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

    $scope.formCtrl = {};
    $scope.form = {
        id_project : option.id_project
    };
    $scope.showForm = false;

    $scope.addSprint = addSprint;
    $scope.updateDueDate = updateDueDate;
    $scope.cancel = cancel;

    loadList() ;

    function loadList() {
        zeHttp.project.sprint.get_all(option.id_project).then(function (response) {
            if (response.status == 200 && response.data != 'false') {
                $scope.sprints = response.data ;
            }
        });
    }

    function addSprint(){
        var formatted_data = angular.toJson($scope.form);

        zeHttp.project.sprint.post(formatted_data).then(function(response){
            if(response.data && response.data != 'false'){
                zeHttp.project.sprint.get(response.data).then(function(response){
                    if(response.data && response.data != 'false'){
                        $uibModalInstance.close(response.data);
                    }
                });
            }
        })
    }

    function updateDueDate(){
        $scope.form.due_date = new Date($scope.form.start_date);
        $scope.form.due_date.setDate($scope.form.due_date.getDate() + 15);
    }

    function cancel() {
        $uibModalInstance.dismiss('cancel');
    }






    $scope.loadSprint = function (sprint) {
        sprint = sprint || false;
        $uibModalInstance.close(sprint);
    }

}) ;
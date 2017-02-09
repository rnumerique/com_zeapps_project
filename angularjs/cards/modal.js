// declare the modal to the app service
listModuleModalFunction.push({
    module_name:'com_zeapps_project',
    function_name:'search_card',
    templateUrl:'/com_zeapps_project/card/modal',
    controller:'ZeAppsProjectsModalCardCtrl',
    size:'lg',
    resolve:{
        titre: function () {
            return 'Selection des cartes';
        }
    }
});


app.controller('ZeAppsProjectsModalCardCtrl', function($scope, $uibModalInstance, zeHttp, titre, option) {
    $scope.titre = titre ;

    $scope.option = option;

    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };

    $scope.selectedCards = [];

    var loadList = function () {
        zeHttp.project.card.get_all(option.id_project).then(function (response) {
            if (response.status == 200) {
                $scope.cards = response.data ;
            }
        });
    };
    loadList() ;

    $scope.add = function(card){
        var i = $scope.selectedCards.indexOf(card.id);
        if(i === -1)
            $scope.selectedCards.push(card.id)
    };

    $scope.isSelected = function(card){
        return $scope.selectedCards.indexOf(card.id) > -1;
    };

    $scope.loadCards = function () {
        $uibModalInstance.close($scope.selectedCards);
    }

}) ;
app.directive('zeAuth', function($rootScope){

    return {
        restrict: 'A',
        scope: {
            zeAuth: '='
        },
        link: function(scope, elm){
            scope.$watch('zeAuth', function(value){
                evaluateRight(value.id_project, value.right, elm);
            }, true);
        }
    };

    function evaluateRight(id_project, right, elm){
        if($rootScope.project_rights[id_project] && $rootScope.project_rights[id_project][right] == '0'){
            elm.remove();
        }
    }

});
app.directive('timer', function($rootScope, $interval, zeHttp){
    return{
        restrict: 'E',
        templateUrl: '/com_zeapps_project/timer/directive',
        link: function(scope, element){
            var card = {};
            scope.play = function(){
                card = {
                    id_project : $rootScope.currentTask.id_project,
                    id_category : $rootScope.currentTask.id_category,
                    id_sprint : $rootScope.currentTask.id_sprint,
                    id : $rootScope.currentTask.id_card,
                    id_user : $rootScope.user.id,
                    name_user : $rootScope.user.firstname + ' ' + $rootScope.user.lastname,
                    title : $rootScope.currentTask.label
                };
                zeHttp.project.timer.start(card);
            };

            scope.stop = function(){
                zeHttp.project.timer.stop();
            };
        }
    }
});
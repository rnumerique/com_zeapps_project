app.directive("timer", ["$rootScope", "zeHttp", function($rootScope, zhttp){
	return{
		restrict: "E",
		templateUrl: "/com_zeapps_project/timer/directive",
		link: function(scope){
			scope.stop = stop;

			function stop(){
                zhttp.project.timer.stop().then(function(){
					$rootScope.$broadcast('projectTimerBroadcast', {done: true});
				});
			}
		}
	};
}]);
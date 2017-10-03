app.directive("timer", ["$rootScope", "$interval", "zeHttp", function($rootScope, $interval, zeHttp){
	return{
		restrict: "E",
		templateUrl: "/com_zeapps_project/timer/directive",
		link: function(scope){
			scope.stop = stop;

			function stop(){
				zeHttp.project.timer.stop().then(function(){
					$rootScope.$broadcast('projectTimerBroadcast', {done: true});
				});
			}
		}
	};
}]);
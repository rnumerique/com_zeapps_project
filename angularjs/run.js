app.run(["zeHttp", "$rootScope", "$timeout", function(zhttp, $rootScope, $timeout){
    var watch = $rootScope.$watch("contextLoaded", function(value, oldValue){
		if(value && value !== oldValue){
			console.log(value);
			$timeout(function(){
                zhttp.project.timer.start();
			},0);
			watch();
		}
	});
}]);
app.run(["zeHttp", "$rootScope", function(zhttp, $rootScope){
	$rootScope.project_rights = [];
    zhttp.project.right.get_connected().then(function(response){
		if(response.data && response.data != "false"){
			$rootScope.project_rights = response.data;
		}
	});
    zhttp.project.status.get_all().then(function(response){
		if(response.data && response.data != "false"){
			$rootScope.statuses = response.data;
			angular.forEach($rootScope.statuses, function(status){
				status.sort = parseInt(status.sort);
			});
		}
	});
    zhttp.project.timer.get_ongoing().then(function(response){
        if(response.data && response.data != "false"){
            $rootScope.currentTimer = response.data;
            zhttp.project.timer.start();
        }
    });
}]);
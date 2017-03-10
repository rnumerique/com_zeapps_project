app.run(function(zeHttp, $rootScope){
    $rootScope.project_rights = [];
    zeHttp.project.right.get_connected().then(function(response){
        if(response.data && response.data != 'false'){
            $rootScope.project_rights = response.data;
        }
    });
    zeHttp.project.status.get_all().then(function(response){
        if(response.data && response.data != 'false'){
            $rootScope.statuses = response.data;
            angular.forEach($rootScope.statuses, function(status){
                status.sort = parseInt(status.sort);
            })
        }
    });
});
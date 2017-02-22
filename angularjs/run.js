app.run(function(zeHttp, $rootScope){
    $rootScope.project_rights = [];
    zeHttp.project.right.get_connected().then(function(response){
        if(response.data && response.data != 'false'){
            $rootScope.project_rights = response.data;
        }
    });
});
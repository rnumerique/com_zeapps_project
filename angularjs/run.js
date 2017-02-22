app.run(function(zeHttp, $rootScope){
    zeHttp.project.right.get_connected().then(function(response){
        if(response.data && response.data != 'false'){
            $rootScope.project_rights = response.data;
        }
    });
});
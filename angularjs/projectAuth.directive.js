app.directive("projectAuth", function($rootScope){

	return {
		restrict: "A",
		scope: {
            projectAuth: '='
		},
		link: function(scope, elm){
            elm.hide();

            var watch1 = scope.$watch("projectAuth", function(value){
            	if(value) {
                    if(evaluateRight(value.id_project, value.right, elm)){
                        watch1();
                        watch2();
                    }
                }
			}, true);
            var watch2 = $rootScope.$watch("project_rights", function(value){
            	if(value) {
                    if(evaluateRight(scope.projectAuth.id_project, scope.projectAuth.right, elm)){
                        watch1();
                        watch2();
                    }
                }
			}, true);
		}
	};

	function evaluateRight(id_project, right, elm){
		if($rootScope.project_rights[id_project]){
			if($rootScope.project_rights[id_project][right] !== "1") {
                elm.remove();
                return true;
            }
            else{
				elm.show();
                elm.removeAttr("project-auth");
                return true;
			}
		}
        return false;
	}

});
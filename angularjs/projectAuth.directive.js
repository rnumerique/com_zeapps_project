app.directive("projectAuth", function($rootScope){

	return {
		restrict: "A",
		scope: {
            projectAuth: '='
		},
		link: function(scope, elm){
            elm.hide();

            scope.$watch("projectAuth", function(value){
            	if(value) {
                    evaluateRight(value.id_project, value.right, elm);
                }
			}, true);
            $rootScope.$watch("project_rights", function(value){
            	if(value) {
                    evaluateRight(scope.projectAuth.id_project, scope.projectAuth.right, elm);
                }
			}, true);
		}
	};

	function evaluateRight(id_project, right, elm){
		if($rootScope.project_rights[id_project]){
			if($rootScope.project_rights[id_project][right] !== "1") {
                elm.remove();
            }
            else{
				elm.show();
			}
		}
	}

});
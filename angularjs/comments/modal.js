// declare the modal to the app service
listModuleModalFunction.push({
	module_name:"com_zeapps_project",
	function_name:"form_comment",
	templateUrl:"/com_zeapps_project/project/modal_comment",
	controller:"ZeAppsProjectsModalCommentCtrl",
	size:"lg",
	resolve:{
		titre: function () {
			return "Ajouter une note";
		}
	}
});


app.controller("ZeAppsProjectsModalCommentCtrl", ["$scope", "$uibModalInstance", "titre", "option", function($scope, $uibModalInstance, titre, option) {

	$scope.titre = titre ;

	$scope.cancel = cancel;
	$scope.save = save;

	if(option.comment){
		$scope.form = option.comment;
        $scope.titre = "Modifier une note";
	}
	else{
		$scope.form = {};
	}

	function cancel() {
		$uibModalInstance.dismiss("cancel");
	}

	function save() {
		$uibModalInstance.close($scope.form);
	}

}]) ;
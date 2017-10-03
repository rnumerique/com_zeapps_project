// declare the modal to the app service
listModuleModalFunction.push({
	module_name:"com_zeapps_project",
	function_name:"form_spending",
	templateUrl:"/com_zeapps_project/spending/modal",
	controller:"ZeAppsProjectsModalSpendingCtrl",
	size:"lg",
	resolve:{
		titre: function () {
			return "Ajouter une dépense";
		}
	}
});


app.controller("ZeAppsProjectsModalSpendingCtrl", ["$scope", "$uibModalInstance", "zeHttp", "titre", "option", function($scope, $uibModalInstance, zeHttp, titre, option) {

	$scope.titre = titre ;

	$scope.cancel = cancel;
	$scope.save = save;

	if(option.spending){
		$scope.form = option.spending;
		$scope.form.total = parseFloat($scope.form.total);
        $scope.titre = "Modifier une dépense";
	}
	else{
		$scope.form = {
			id_project : option.id_project
		};
	}

	function cancel() {
		$uibModalInstance.dismiss("cancel");
	}

	function save() {
		$uibModalInstance.close($scope.form);
	}

}]) ;
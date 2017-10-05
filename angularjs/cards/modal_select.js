// declare the modal to the app service
listModuleModalFunction.push({
	module_name:"com_zeapps_project",
	function_name:"select_card",
	templateUrl:"/com_zeapps_project/card/modal_select",
	controller:"ZeAppsProjectsModalSelectCardCtrl",
	size:"lg",
	resolve:{
		titre: function () {
			return "Selection de la tâche";
		}
	}
});


app.controller("ZeAppsProjectsModalSelectCardCtrl", ["$scope", "$uibModalInstance", "zeHttp", "titre", "option", function($scope, $uibModalInstance, zhttp, titre, option) {

	$scope.titre = titre ;

	$scope.cancel = cancel;
	$scope.loadCard = loadCard;

	loadList() ;

	function cancel() {
		$uibModalInstance.dismiss("cancel");
	}

	function loadList() {
        zhttp.project.card.get_all(option.id_project).then(function (response) {
			if (response.status == 200) {
				$scope.cards = response.data.cards ;
				angular.forEach($scope.cards, function(card){
					card.id = parseInt(card.id);
				})
			}
		});
	}

	function loadCard(card) {
		$uibModalInstance.close(card);
	}

}]) ;
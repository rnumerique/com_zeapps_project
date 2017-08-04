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


app.controller("ZeAppsProjectsModalSelectCardCtrl", function($scope, $uibModalInstance, zeHttp, titre, option) {

	$scope.titre = titre ;

	$scope.cancel = cancel;
	$scope.loadCard = loadCard;

	loadList() ;

	function cancel() {
		$uibModalInstance.dismiss("cancel");
	}

	function loadList() {
		zeHttp.project.card.get_all(option.id_project).then(function (response) {
			if (response.status == 200) {
				$scope.cards = response.data ;
				angular.forEach($scope.cards, function(card){
					card.id = parseInt(card.id);
				})
			}
		});
	}

	function loadCard(card) {
		$uibModalInstance.close(card);
	}

}) ;
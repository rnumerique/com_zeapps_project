// declare the modal to the app service
listModuleModalFunction.push({
	module_name:"com_zeapps_project",
	function_name:"search_card",
	templateUrl:"/com_zeapps_project/card/modal",
	controller:"ZeAppsProjectsModalCardCtrl",
	size:"lg",
	resolve:{
		titre: function () {
			return "Selection des cartes";
		}
	}
});


app.controller("ZeAppsProjectsModalCardCtrl", function($scope, $uibModalInstance, zeHttp, titre, option) {

	$scope.titre = titre ;
	$scope.option = option;
	$scope.selectedCards = [];

	$scope.cancel = cancel;
	$scope.toggle = toggle;
	$scope.isSelected = isSelected;
	$scope.loadCards = loadCards;

	loadList() ;

	function cancel() {
		$uibModalInstance.dismiss("cancel");
	}

	function loadList() {
		zeHttp.project.card.get_all(option.id_project).then(function (response) {
			if (response.status == 200) {
				$scope.cards = response.data ;
			}
		});
	}

	function toggle(card){
		var i = $scope.selectedCards.indexOf(card);
		if(i === -1) {
			$scope.selectedCards.push(card);
		}
		else {
			$scope.selectedCards.splice(i, 1);
		}
	}

	function isSelected(card){
		return $scope.selectedCards.indexOf(card) > -1;
	}

	function loadCards() {
		$uibModalInstance.close($scope.selectedCards);
	}

}) ;
// declare the modal to the app service
listModuleModalFunction.push({
	module_name:"com_zeapps_project",
	function_name:"search_project",
	templateUrl:"/com_zeapps_project/project/modal_project",
	controller:"ZeAppsProjectsModalProjectCtrl",
	size:"lg",
	resolve:{
		titre: function () {
			return "Recherche d'un projet";
		}
	}
});


app.controller("ZeAppsProjectsModalProjectCtrl", function($scope, $uibModalInstance, zeHttp, titre, option) {

	$scope.titre = titre ;

	loadList() ;

	$scope.cancel = cancel;
	$scope.loadProject = loadProject;

	function loadList() {
		zeHttp.project.project.get_all(0, true, option.id).then(function (response) {
			if (response.status == 200) {
				$scope.projects = response.data ;
			}
		});
	}

	function cancel() {
		$uibModalInstance.dismiss("cancel");
	}

	function loadProject(id) {


		// search the company
		var project = false ;
		for (var i = 0 ; i < $scope.projects.length ; i++) {
			if ($scope.projects[i].id == id) {
				project = $scope.projects[i] ;
				break;
			}
		}

		$uibModalInstance.close(project);
	}

}) ;
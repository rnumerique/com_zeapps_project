// declare the modal to the app service
listModuleModalFunction.push({
	module_name:"com_zeapps_project",
	function_name:"detail_card",
	templateUrl:"/com_zeapps_project/card/modal_detail",
	controller:"ZeAppsProjectsModalDetailCardCtrl",
	size:"lg",
	resolve: {}
});


app.controller("ZeAppsProjectsModalDetailCardCtrl", function($scope, $rootScope, $uibModalInstance, zeHttp, option, $location, Upload) {

	$scope.card = option.card;
	$scope.progress = false;
	$scope.form = {
		id_card : $scope.card.id
	};

	$scope.upload = upload;
	$scope.saveComment = saveComment;
	$scope.edit = edit;
	$scope.close = close;

	function upload(files) {

		$scope.files = files;
		$scope.progress = true;

		if (files && files.length) {
			Upload.upload({
				url: zeHttp.project.card.document() + $scope.card.id,
				data: {
					files: files
				}
			}).then(
				function(response){
					$scope.progress = false;
					if(response.data && response.data != "false"){
						for(var i = 0; i<response.data.length; i++) {
							$scope.card.documents.push(response.data[i]);
						}
						$rootScope.toasts.push({success: "Les documents ont bien été mis en ligne"});
					}
					else{
						$rootScope.toasts.push({danger: "Il y a eu une erreur lors de la mise en ligne des documents"});
					}
				}
			);
		}
	}

	function saveComment(){

		var formatted_data = angular.toJson($scope.form);

		zeHttp.project.card.comment(formatted_data).then(function(response){
			if(response.data && response.data != "false"){
				$scope.form = {
					id_card : $scope.card.id
				};
				$scope.showCardDetailForm = false;
				$scope.card.comments.push(response.data);
			}
		});
	}

	function edit(){
		$location.url("/ng/com_zeapps_project/sprint/edit/card/" + $scope.card.id_project + "/" + $scope.card.id_sprint + "/" + $scope.card.id);
		$uibModalInstance.dismiss();
	}

	function close() {
		$uibModalInstance.dismiss();
	}

}) ;
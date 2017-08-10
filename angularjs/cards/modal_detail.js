// declare the modal to the app service
listModuleModalFunction.push({
	module_name:"com_zeapps_project",
	function_name:"detail_card",
	templateUrl:"/com_zeapps_project/card/modal_detail",
	controller:"ZeAppsProjectsModalDetailCardCtrl",
	size:"lg",
	resolve: {}
});


app.controller("ZeAppsProjectsModalDetailCardCtrl", function($scope, $rootScope, $uibModalInstance, zeHttp, option, $location, Upload, zeapps_modal, zeProject) {

	$scope.card = option.card;
	$scope.progress = false;

	var edited_timers = false;

    $scope.tab = "notes";
    $scope.view = "/com_zeapps_project/project/notes";

    $scope.goToTabCard = goToTabCard;
    $scope.isActiveCard = isActiveCard;

	$scope.addDocument = addDocument;
	$scope.editDocument = editDocument;
    $scope.deleteDocument = deleteDocument;
	$scope.addComment = addComment;
	$scope.editComment = editComment;
    $scope.deleteComment = deleteComment;
	$scope.edit = edit;
	$scope.close = close;
    $scope.startTimer = startTimer;
    $scope.newTimer = newTimer;
    $scope.editTimer = editTimer;
    $scope.deleteTimer = deleteTimer;

    zeHttp.project.card.get($scope.card.id).then(function(response){
        if(response.data && response.data != "false"){
            $scope.card = response.data.card;

            $scope.comments = response.data.comments;
            $scope.documents = response.data.documents;
            $scope.timers = response.data.timers;

            angular.forEach($scope.documents, function(document){
                document.date = new Date(document.date);
            });

            angular.forEach($scope.timers, function(timer){
                timer.time_spent_formatted = parseInt(timer.time_spent/60) + "h " + (timer.time_spent % 60  || '');
                timer.start_time = new Date(timer.start_time);
                timer.stop_time = new Date(timer.stop_time);
            });

            $scope.note_form = {
                id_project : $scope.card.id_project
            };

            angular.forEach($scope.comments, function(comment){
                comment.date = new Date(comment.date);
            });

            var ret = zeProject.get.ratioOf($scope.card);
            $scope.time_spent_formatted = ret.time_spent_formatted;
            $scope.timer_color = ret.timer_color;
            $scope.timer_ratio = ret.timer_ratio;
        }
    });

    function goToTabCard(tab){
        $scope.tab = tab;
        $scope.view = "/com_zeapps_project/project/" + tab;
    }

    function isActiveCard(tab){
        return $scope.tab === tab;
    }

    function startTimer(){
        zeHttp.project.timer.start($scope.card);
    }

    function newTimer(){
        var options = {
            id_project : $scope.card.id_project,
            name_project : $scope.card.project_title,
			id_card : $scope.card.id,
			label : $scope.card.title

        };
        zeapps_modal.loadModule("com_zeapps_project", "form_timer", options, function(objReturn) {
            if (objReturn) {
                var formatted_data = angular.toJson(objReturn);
                zeHttp.project.timer.post(formatted_data).then(function(response){
                    if(response.data && response.data != "false"){
                        objReturn.id = response.data;

                        objReturn.start_time = new Date(objReturn.start_time);
                        objReturn.stop_time = new Date(objReturn.stop_time);

                        var time_spent = moment.duration(parseInt(objReturn.time_spent), 'minutes');
                        objReturn.time_spent_formatted = parseInt(time_spent.asHours()) + 'h ' + (time_spent.minutes() || "");

                        $scope.timers.push(Object.create(objReturn));

                        $scope.card.time_spent = parseInt($scope.card.time_spent) + parseInt(objReturn.time_spent);

                        var ret = zeProject.get.ratioOf($scope.card);
                        $scope.time_spent_formatted = ret.time_spent_formatted;
                        $scope.timer_color = ret.timer_color;
                        $scope.timer_ratio = ret.timer_ratio;

                        edited_timers = true;
                    }
                });
            } else {
            }
        });
    }

    function editTimer(timer){
        var options = {
            id: timer.id
        };
        zeapps_modal.loadModule("com_zeapps_project", "form_timer", options, function(objReturn) {
            if (objReturn) {
                var formatted_data = angular.toJson(objReturn);
                zeHttp.project.timer.post(formatted_data).then(function(response){
                    if(response.data && response.data != "false"){
                        timer.time_spent_formatted = parseInt(objReturn.time_spent/60) + "h " + (objReturn.time_spent % 60 || '');

                        timer.start_time = new Date(objReturn.start_time);
                        timer.stop_time = new Date(objReturn.stop_time);

                        $scope.card.time_spent = parseInt($scope.card.time_spent) - parseInt(timer.time_spent) + parseInt(objReturn.time_spent);
                        timer.time_spent = objReturn.time_spent;

                        var ret = zeProject.get.ratioOf($scope.card);
                        $scope.time_spent_formatted = ret.time_spent_formatted;
                        $scope.timer_color = ret.timer_color;
                        $scope.timer_ratio = ret.timer_ratio;

                        edited_timers = true;
                    }
                });
            } else {
            }
        });
    }

    function deleteTimer(timer){
        zeHttp.project.timer.del(timer.id).then(function(response){
            if(response.data && response.data != "false"){
                $scope.timers.splice($scope.timers.indexOf(timer), 1);

                $scope.card.time_spent = parseInt($scope.card.time_spent) - parseInt(timer.time_spent);

                var ret = zeProject.get.ratioOf($scope.card);
                $scope.time_spent_formatted = ret.time_spent_formatted;
                $scope.timer_color = ret.timer_color;
                $scope.timer_ratio = ret.timer_ratio;

                edited_timers = true;
            }
        });
    }

    function addDocument(){
        var options = {};
        zeapps_modal.loadModule("com_zeapps_project", "form_document", options, function(objReturn) {
            if (objReturn) {
                Upload.upload({
                    url: zeHttp.project.card.document() + $scope.card.id,
                    data: objReturn
                }).then(
                    function(response){
                        $scope.progress = false;
                        if(response.data && response.data != "false"){
                            response.data.date = new Date(response.data.date);
                            response.data.id_user = $rootScope.user.id;
                            response.data.name_user = $rootScope.user.firstname[0] + '. ' + $rootScope.user.lastname;
                            $scope.documents.push(response.data);
                            $rootScope.toasts.push({success: "Les documents ont bien été mis en ligne"});
                        }
                        else{
                            $rootScope.toasts.push({danger: "Il y a eu une erreur lors de la mise en ligne des documents"});
                        }
                    }
                );
            } else {
            }
        });
    }

    function editDocument(document){
        var options = {
            document: Object.create(document)
        };
        zeapps_modal.loadModule("com_zeapps_project", "form_document", options, function(objReturn) {
            if (objReturn) {
                Upload.upload({
                    url: zeHttp.project.card.document() + $scope.card.id,
                    data: objReturn
                }).then(
                    function(response){
                        $scope.progress = false;
                        if(response.data && response.data != "false"){
                            response.data.date = new Date(response.data.date);
                            $scope.documents[$scope.documents.indexOf(document)] = response.data;
                            $rootScope.toasts.push({success: "Les documents ont bien été mis à jour"});
                        }
                        else{
                            $rootScope.toasts.push({danger: "Il y a eu une erreur lors de la mise à jour des documents"});
                        }
                    }
                );
            } else {
            }
        });
    }

    function deleteDocument(document){
        zeHttp.project.card.del_document(document.id).then(function(response){
            if(response.data && response.data != 'false'){
                $scope.documents.splice($scope.documents.indexOf(document), 1);
            }
        })
    }

    function addComment(){
        var options = {};
        zeapps_modal.loadModule("com_zeapps_project", "form_comment", options, function(objReturn) {
            if (objReturn) {
                objReturn.id_card = $scope.card.id;
                var formatted_data = angular.toJson(objReturn);

                zeHttp.project.card.comment(formatted_data).then(function(response){
                    if(response.data && response.data != "false"){
                        response.data.date = new Date(response.data.date);
                        $scope.comments.push(response.data);
                    }
                });
            } else {
            }
        });
    }

    function editComment(comment){
        var options = {
            comment: angular.fromJson(angular.toJson(comment))
        };
        zeapps_modal.loadModule("com_zeapps_project", "form_comment", options, function(objReturn) {
            if (objReturn) {
                var formatted_data = angular.toJson(objReturn);

                zeHttp.project.card.comment(formatted_data).then(function(response){
                    if(response.data && response.data != "false"){
                        response.data.date = new Date(response.data.date);
                        $scope.comments[$scope.comments.indexOf(comment)] = response.data;
                    }
                });
            } else {
            }
        });
    }

    function deleteComment(comment){
        zeHttp.project.card.del_comment(comment.id).then(function(response){
            if(response.data && response.data != 'false'){
                $scope.comments.splice($scope.comments.indexOf(comment), 1);
            }
        })
    }

	function edit(){
		$location.url("/ng/com_zeapps_project/project/card/edit/card/" + $scope.card.id);
		$uibModalInstance.dismiss(edited_timers);
	}

	function close() {
		$uibModalInstance.dismiss(edited_timers);
	}

}) ;
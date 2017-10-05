// declare the modal to the app service
listModuleModalFunction.push({
	module_name:"com_zeapps_project",
	function_name:"form_timer",
	templateUrl:"/com_zeapps_project/timer/modal",
	controller:"ZeAppsProjectsModalTimerCtrl",
	size:"lg",
	resolve:{
		titre: function () {
			return "Enregistrer un temps";
		}
	}
});


app.controller("ZeAppsProjectsModalTimerCtrl", ["$scope", "$uibModalInstance", "zeHttp", "titre", "option", "zeapps_modal", "$rootScope",
    function($scope, $uibModalInstance, zhttp, titre, option, zeapps_modal, $rootScope) {

	$scope.titre = titre ;
	$scope.option = option;
	$scope.form = {
		date : new Date(),
        id_card : 0,
        time_spent_form : '',
        start_time_m : 0,
        start_time_h : 0,
        stop_time_m : 0,
        stop_time_h : 0
	};

    $scope.userHttp = zhttp.app.user;
    $scope.userFields = [
        {label:'Prénom',key:'firstname'},
        {label:'Nom',key:'lastname'}
    ];

	$scope.cancel = cancel;
	$scope.save = save;
	$scope.update = update;
	$scope.loadUser = loadUser;
	$scope.loadCard = loadCard;
	$scope.removeCard = removeCard;

	loadList() ;

	function loadList() {
		if(option.id){
            zhttp.project.timer.get(option.id).then(function(response){
				if(response.data && response.data != "false"){
					$scope.form = response.data;

					$scope.form.date = new Date($scope.form.start_time);

                    $scope.form.time_spent_form = parseInt($scope.form.time_spent / 60) + ':' + parseInt($scope.form.time_spent % 60);

					$scope.form.start_time_m = $scope.form.date.getMinutes();
					$scope.form.start_time_h = $scope.form.date.getHours();

                    $scope.form.stop_time_m = $scope.form.start_time_m + parseInt($scope.form.time_spent % 60);
                    $scope.form.stop_time_h = $scope.form.start_time_h + parseInt($scope.form.time_spent / 60);
				}
			});
		}
		else if(option.id_card){
            $scope.form.id_card = option.id_card ;
            $scope.form.label = option.label ;
            $scope.form.id_project = option.id_project ;
            $scope.form.name_project = option.name_project ;
            $scope.form.id_user = $rootScope.user.id;
            $scope.form.name_user = $rootScope.user.firstname ? $rootScope.user.firstname + " " + $rootScope.user.lastname : $rootScope.user.lastname;
		}
		else if(option.id_project){
            $scope.form.id_project = option.id_project ;
            $scope.form.name_project = option.name_project ;
            $scope.form.id_user = $rootScope.user.id;
            $scope.form.name_user = $rootScope.user.firstname ? $rootScope.user.firstname + " " + $rootScope.user.lastname : $rootScope.user.lastname;
		}
	}

    function loadUser(user) {
        if (user) {
            $scope.form.id_user = user.id;
            $scope.form.name_user = user.firstname ? user.firstname  + " " + user.lastname : user.lastname;
        } else {
            $scope.form.id_user = 0;
            $scope.form.name_user = "";
        }
    }

    function loadCard() {
        zeapps_modal.loadModule("com_zeapps_project", "select_card", {id_project:$scope.form.id_project}, function(objReturn) {
            if (objReturn) {
                $scope.form.id_card = objReturn.id;
                $scope.form.label = objReturn.title;
            } else {
                $scope.form.id_card = 0;
                $scope.form.label = "";
            }
        });
    }

    function removeCard() {
        $scope.form.id_card = 0;
        $scope.form.label = "";
    }

	function update(src){
		var minutes;
		var hours;
        var time_spent;

        var pad = "00";

        $scope.form.time_spent_form = ($scope.form.time_spent_form + "").replace(',', '.');

        $scope.form.time_spent_form = $scope.form.time_spent_form || 0;
        $scope.form.start_time_h = $scope.form.start_time_h || 0;
        $scope.form.stop_time_h = $scope.form.stop_time_h || 0;
        $scope.form.start_time_m = $scope.form.start_time_m || 0;
        $scope.form.stop_time_m = $scope.form.stop_time_m || 0;

        $scope.form.start_time_h = $scope.form.start_time_h > 23 ? 23 : ($scope.form.start_time_h < 0 ? 0 : $scope.form.start_time_h);
        $scope.form.stop_time_h = $scope.form.stop_time_h > 23 ? 23 : ($scope.form.stop_time_h < 0 ? 0 : $scope.form.stop_time_h);

        $scope.form.start_time_m = $scope.form.start_time_m > 59 ? 59 : ($scope.form.start_time_m < 0 ? 0 : $scope.form.start_time_m);
        $scope.form.stop_time_m = $scope.form.stop_time_m > 59 ? 59 : ($scope.form.stop_time_m < 0 ? 0 : $scope.form.stop_time_m);

		if(src === "time_spent"){
			if($scope.form.time_spent_form) {
				if($scope.form.start_time_m || $scope.form.start_time_h) {
                    if ($scope.form.time_spent_form.search(':') > -1) {
                        time_spent = $scope.form.time_spent_form.split(':');

                        $scope.form.stop_time_m = parseInt($scope.form.start_time_m) + parseInt(time_spent[1]);
                        $scope.form.stop_time_h = parseInt($scope.form.start_time_h) + parseInt(time_spent[0]);

                        if ($scope.form.stop_time_m >= 60) {
                            $scope.form.stop_time_m -= 60;
                            $scope.form.stop_time_h += 1;
                        }
                    }
                    else {
                        minutes = parseFloat($scope.form.time_spent_form) * 60;
                        $scope.form.stop_time_m = parseInt($scope.form.start_time_m) + parseInt(minutes % 60);
                        $scope.form.stop_time_h = parseInt($scope.form.start_time_h) + parseInt(minutes / 60);
                        if ($scope.form.stop_time_m >= 60) {
                            $scope.form.stop_time_m -= 60;
                            $scope.form.stop_time_h += 1;
                        }
                    }
                }
            }
            else{
                $scope.form.stop_time_m = parseInt($scope.form.start_time_m);
                $scope.form.stop_time_h = parseInt($scope.form.start_time_h);
			}
		}
		else if(src === "start_time"){
			if($scope.form.time_spent_form) {
				if ($scope.form.time_spent_form.search(':') > -1) {
					time_spent = $scope.form.time_spent_form.split(':');

					$scope.form.stop_time_m = parseInt($scope.form.start_time_m) + parseInt(time_spent[1]);
					$scope.form.stop_time_h = parseInt($scope.form.start_time_h) + parseInt(time_spent[0]);

					if ($scope.form.stop_time_m >= 60) {
						$scope.form.stop_time_m -= 60;
						$scope.form.stop_time_h += 1;
					}
				}
				else {
					minutes = parseFloat($scope.form.time_spent_form) * 60;
					$scope.form.stop_time_m = parseInt($scope.form.start_time_m) + parseInt(minutes % 60);
					$scope.form.stop_time_h = parseInt($scope.form.start_time_h) + parseInt(minutes / 60);

					if ($scope.form.stop_time_m >= 60) {
						$scope.form.stop_time_m -= 60;
						$scope.form.stop_time_h += 1;
					}
				}
			}
            else if($scope.form.stop_time_m || $scope.form.stop_time_h) {
                minutes = $scope.form.stop_time_m - $scope.form.start_time_m;
                minutes = minutes % 60;
                hours = $scope.form.stop_time_h - $scope.form.start_time_h;

                if (minutes < 0) {
                    hours -= 1;
                    minutes += 60;
                }

                $scope.form.time_spent_form = hours + ':' + (pad + minutes).substring((minutes + '').length);
            }
		}
		else if(src === "stop_time"){
            if($scope.form.start_time_m || $scope.form.start_time_h) {
				minutes = $scope.form.stop_time_m - $scope.form.start_time_m;
				minutes = minutes % 60;
				hours = $scope.form.stop_time_h - $scope.form.start_time_h;

				if (minutes < 0) {
					hours -= 1;
					minutes += 60;
				}

				$scope.form.time_spent_form = hours + ':' + (pad + minutes).substring((minutes + '').length);
			}
		}
	}

    function cancel() {
        $uibModalInstance.dismiss("cancel");
    }

	function save() {

		var date = moment($scope.form.date).format("YYYY-MM-DD");

		var time_spent;

		$scope.form.start_time = date + " " + ($scope.form.start_time_h || '00') + ":" + ($scope.form.start_time_m || '00') + ":00";
		$scope.form.stop_time = date + " " + ($scope.form.stop_time_h || '00') + ":" + ($scope.form.stop_time_m || '00') + ":00";
		if($scope.form.time_spent_form.search(':') > -1){
            time_spent = $scope.form.time_spent_form.split(':');
            $scope.form.time_spent = parseInt(time_spent[0]) * 60 + parseInt(time_spent[1]);
		}
		else{
            $scope.form.time_spent = parseFloat($scope.form.time_spent_form) * 60;
		}
		$uibModalInstance.close($scope.form);
	}

}]) ;
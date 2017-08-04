app.controller("ComZeappsProjectViewCtrl", ["$scope", "$route", "$routeParams", "$location", "$rootScope", "zeHttp", "zeapps_modal", "$uibModal", "Upload",
	function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal, Upload) {

		$scope.$parent.loadMenu("com_ze_apps_project", "com_zeapps_projects_management");

		var project_users_ids = [];

		$scope.tree = {
			branches: []
		};
		$scope.options = {};
		$scope.project = {};
		$scope.calendarModel = {
			eventLimit: 6,
			eventLimitClick: "day",
			views: {
				basic: {
					"eventLimit": false
				}
			},
			step: 1,
			completed: false,
			events: [],
            eventClick: function(calEvent){
                if(calEvent.card) {
                    detailCard(calEvent.card);
                }
            }
		};
		$scope.postits = [];

		$scope.tab = "taches";
		$scope.view = "/com_zeapps_project/project/taches";
		$scope.goToTab = goToTab;

		$scope.compareDates = function(date){ return zhttp.project.compareDate(date); };

		$scope.isActive = isActive;
		$scope.editCategory = editCategory;
		$scope.deleteCategory = deleteCategory;
		$scope.addProjectUser = addProjectUser;
		$scope.deleteRightsOf = deleteRightsOf;
		$scope.changeRights = changeRights;
		$scope.archive_project = archive_project;
		$scope.delete_project = delete_project;
		$scope.force_delete_project = force_delete_project;
		$scope.addDocument = addDocument;
		$scope.editDocument = editDocument;
		$scope.deleteDocument = deleteDocument;
		$scope.addComment = addComment;
		$scope.editComment = editComment;
		$scope.deleteComment = deleteComment;
		$scope.newTimer = newTimer;
		$scope.editTimer = editTimer;
        $scope.deleteTimer = deleteTimer;

		if($routeParams.id){
			zhttp.project.project.get($routeParams.id).then(function(response){
				if(response.data && response.data != "false"){
					$scope.project = response.data.project;
					$scope.dates = response.data.dates;
					$scope.categories = response.data.categories;
					$scope.documents = response.data.documents;
					$scope.timers = response.data.timers;
                    $scope.comments = response.data.comments;
                    $scope.deadlines = response.data.deadlines;
                    var cards = response.data.cards;

					angular.forEach($scope.documents, function(document){
                        document.date = new Date(document.date);
					});

					angular.forEach($scope.timers, function(timer){
                        timer.time_spent_formatted = parseInt(timer.time_spent/60) + "h " + (timer.time_spent % 60 || '');
                        timer.start_time = new Date(timer.start_time);
                        timer.stop_time = new Date(timer.stop_time);
					});

                    $scope.note_form = {
                        id_project : $scope.project.id
                    };

					angular.forEach($scope.comments, function(comment){
						comment.date = new Date(comment.date);
					});

					$scope.cardsByDate = [];
					angular.forEach(cards, function (card) {
						angular.forEach(card.documents, function(document){
                            document.date = new Date(document.date);
						});

						angular.forEach(card.comments, function(comment){
                            comment.date = new Date(comment.date);
						});


						if(card.due_date != 0) {
							if (!$scope.cardsByDate[card.due_date])
								$scope.cardsByDate[card.due_date] = [];
							$scope.cardsByDate[card.due_date].push(card);

							var event = {
								allDay: true,
								title: card.title + (card.name_assigned_to ? " - assigné à " + card.name_assigned_to : ''),
								start: card.due_date,
								textColor: card.color ? "#333" : "#fff",
								color: card.color || "#393939",
								order: 3,
								card: card
							};

							$scope.calendarModel.events.push(event);
						}
					});

					angular.forEach($scope.deadlines, function (card) {
						if(card.due_date != 0) {
							var event = {
								allDay: true,
								title: card.title,
								start: card.due_date,
								textColor: "#fff",
								color: "#a94442",
								order: 2
							};

							$scope.calendarModel.events.push(event);
						}
					});


                    var ret = zhttp.project.timer.calcSpentTimeRatio($scope.project);
					$scope.time_spent_formatted = ret.time_spent_formatted;
					$scope.timer_color = ret.timer_color;
					$scope.timer_ratio = ret.timer_ratio;
                    generatePostits();

					$scope.project_users = response.data.project_users;
					project_users_ids = [];
					angular.forEach($scope.project_users, function(user){
						project_users_ids.push(user.id_user);
						user.access = !!parseInt(user.access);
						user.card = !!parseInt(user.card);
						user.accounting = !!parseInt(user.accounting);
						user.project = !!parseInt(user.project);
					});
				}
			});
		}



        function detailCard(card){
            zeapps_modal.loadModule("com_zeapps_project", "detail_card", {card : card});
        }

        function generatePostits(){
            $scope.postits = [
                {
                    value: $scope.project.due,
                    legend: 'Montant',
                    filter: 'currency'
                },
                {
                    value: $scope.project.commission,
                    legend: 'Commission',
                    filter: 'currency'
                },
                {
                    value: $scope.project.due - $scope.project.commission,
                    legend: 'Marge',
                    filter: 'currency'
                },
                {
                    value: $scope.project.payed,
                    legend: 'Deja facturé',
                    filter: 'currency'
                },
                {
                    value: $scope.project.due - $scope.project.payed,
                    legend: 'Reste dû',
                    filter: 'currency'
                },
                {
                    value: $scope.time_spent_formatted + ' <small>/ ' + parseInt($scope.project.estimated_time) + 'h</small>',
                    legend: 'Temps passé',
					color: $scope.timer_color

                }
            ]
        }

		function goToTab(tab){
			$scope.tab = tab;
			$scope.view = "/com_zeapps_project/project/" + tab;
		}

		function isActive(tab){
			return $scope.tab === tab;
		}

		function editCategory(category){
			$location.url("/ng/com_zeapps_project/project/categories/edit/" + category.id);
		}

		function deleteCategory(category){
			zhttp.project.category.del(category.id).then(function(response){
				if(response.data && response.data != "false"){
					$scope.categories.splice($scope.categories.indexOf(category), 1);
				}
			});
		}

		function addProjectUser(){
			zeapps_modal.loadModule("com_zeapps_core", "search_user", {banned_ids : project_users_ids}, function(objReturn) {
				if (objReturn) {
					var user = {};

					user.id_user = objReturn.id;
					user.id_project = $scope.project.id;
					user.name = objReturn.firstname ? objReturn.firstname[0]  + ". " + objReturn.lastname : objReturn.lastname;
					user.access = 1;

					var formatted_data = angular.toJson(user);
					zhttp.project.right.post(formatted_data).then(function(response){
						if(response.data && response.data != "false"){
							user.id = response.data;
							user.access = true;
							$scope.project_users.push(Object.create(user));
							project_users_ids.push(user.id_user);
						}
					});
				} else {
				}
			});
		}

		function deleteRightsOf(user){
			zhttp.project.right.del(user.id).then(function(response){
				if(response.data && response.data != "false"){
					$scope.project_users.splice($scope.project_users.indexOf(user), 1);
					project_users_ids.splice(project_users_ids.indexOf(user.id_user), 1);
				}
			});
		}

		function changeRights(user, right){
			if(!user[right]) {
				switch(right){
				case "access" :
					user["card"] = false;
				case "card" :
					user["accounting"] = false;
				case "accounting" :
					user["project"] = false;
				default :
					break;
				}
			}
			else{
				switch(right){
				case "project" :
					user["accounting"] = true;
				case "accounting" :
					user["card"] = true;
				case "card" :
					user["access"] = true;
				default :
					break;
				}
			}
			saveRightsOf(user);
		}

		function archive_project(id) {
			var modalInstance = $uibModal.open({
				animation: true,
				templateUrl: "/assets/angular/popupModalDeBase.html",
				controller: "ZeAppsPopupModalDeBaseCtrl",
				size: "lg",
				resolve: {
					titre: function () {
						return "Attention";
					},
					msg: function () {
						return "Souhaitez-vous archiver ce projet ?";
					},
					action_danger: function () {
						return "Annuler";
					},
					action_primary: function () {
						return false;
					},
					action_success: function () {
						return "Confirmer";
					}
				}
			});

			modalInstance.result.then(function (selectedItem) {
				if (selectedItem.action == "danger") {

				} else if (selectedItem.action == "success") {
					zhttp.project.project.archive(id);
				}

			}, function () {
				//console.log("rien");
			});

		}

		function delete_project(id) {
			var modalInstance = $uibModal.open({
				animation: true,
				templateUrl: "/assets/angular/popupModalDeBase.html",
				controller: "ZeAppsPopupModalDeBaseCtrl",
				size: "lg",
				resolve: {
					titre: function () {
						return "Attention";
					},
					msg: function () {
						return "Souhaitez-vous supprimer définitivement ce projet ?";
					},
					action_danger: function () {
						return "Annuler";
					},
					action_primary: function () {
						return false;
					},
					action_success: function () {
						return "Confirmer";
					}
				}
			});

			modalInstance.result.then(function (selectedItem) {
				if (selectedItem.action == "danger") {

				} else if (selectedItem.action == "success") {
					zhttp.project.project.del(id).then(function (response) {
						if (response.data && response.data != "false") {
							if (response.data.hasDependencies) {
								$scope.force_delete_project(id);
							}
							else {
								$location.url("/ng/com_zeapps_project/project");
							}
						}
					});
				}

			}, function () {
				//console.log("rien");
			});

		}

		function force_delete_project(id) {
			var modalInstance = $uibModal.open({
				animation: true,
				templateUrl: "/assets/angular/popupModalDeBase.html",
				controller: "ZeAppsPopupModalDeBaseCtrl",
				size: "lg",
				resolve: {
					titre: function () {
						return "Attention";
					},
					msg: function () {
						return "Ce projet contient des sous-projets ou cartes/deadlines, ceux-ci seront également supprimés.";
					},
					action_danger: function () {
						return "Annuler";
					},
					action_primary: function () {
						return false;
					},
					action_success: function () {
						return "Confirmer la suppression";
					}
				}
			});

			modalInstance.result.then(function (selectedItem) {
				if (selectedItem.action == "danger") {

				} else if (selectedItem.action == "success") {
					zhttp.project.project.del(id, true).then(function (response) {
						if (response.status == 200) {
							$location.url("/ng/com_zeapps_project/project");
						}
					});
				}

			}, function () {
				//console.log("rien");
			});

		}

		function saveRightsOf(user){

			var data = {};

			if(user.id){
				data.id = user.id;
			}

			data.access = user.access ? 1 : 0;
			data.sandbox = user.sandbox ? 1 : 0;
			data.card = user.card ? 1 : 0;
			data.sprint = user.sprint ? 1 : 0;
			data.project = user.project ? 1 : 0;

			data.id_user = user.id_user;
			data.id_project = user.id_project;

			var formatted_data = angular.toJson(data);
			zhttp.project.right.post(formatted_data);
		}

		function addDocument(){
            var options = {};
            zeapps_modal.loadModule("com_zeapps_project", "form_document", options, function(objReturn) {
                if (objReturn) {
                    Upload.upload({
                        url: zhttp.project.project.document() + $scope.project.id,
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
                document: angular.fromJson(angular.toJson(document))
			};
            zeapps_modal.loadModule("com_zeapps_project", "form_document", options, function(objReturn) {
                if (objReturn) {
                    Upload.upload({
                        url: zhttp.project.project.document() + $scope.project.id,
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
			zhttp.project.project.del_document(document.id).then(function(response){
				if(response.data && response.data != 'false'){
					$scope.documents.splice($scope.documents.indexOf(document), 1);
				}
			})
		}

		function addComment(){
            var options = {};
            zeapps_modal.loadModule("com_zeapps_project", "form_comment", options, function(objReturn) {
                if (objReturn) {
                	objReturn.id_project = $scope.project.id;
                    var formatted_data = angular.toJson(objReturn);

                    zhttp.project.project.comment(formatted_data).then(function(response){
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

                    zhttp.project.project.comment(formatted_data).then(function(response){
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
            zhttp.project.project.del_comment(comment.id).then(function(response){
                if(response.data && response.data != 'false'){
                    $scope.comments.splice($scope.comments.indexOf(comment), 1);
                }
            })
        }

		function newTimer(){
			var options = {
            	id_project : $scope.project.id,
				name_project : $scope.project.title
			};
            zeapps_modal.loadModule("com_zeapps_project", "form_timer", options, function(objReturn) {
                if (objReturn) {
                    var formatted_data = angular.toJson(objReturn);
                    zhttp.project.timer.post(formatted_data).then(function(response){
                        if(response.data && response.data != "false"){
                            objReturn.id = response.data;

                            objReturn.start_time = new Date(objReturn.start_time);
                            objReturn.stop_time = new Date(objReturn.stop_time);

                            var time_spent = moment.duration(parseInt(objReturn.time_spent), 'minutes');
                            objReturn.time_spent_formatted = parseInt(time_spent.asHours()) + 'h ' + (time_spent.minutes() || "");

                            $scope.timers.push(Object.create(objReturn));

                            $scope.project.time_spent = parseInt($scope.project.time_spent) + parseInt(objReturn.time_spent);

                            var ret = zhttp.project.timer.calcSpentTimeRatio($scope.project);
							$scope.time_spent_formatted = ret.time_spent_formatted;
							$scope.timer_color = ret.timer_color;
							$scope.timer_ratio = ret.timer_ratio;
                            generatePostits();
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
                    zhttp.project.timer.post(formatted_data).then(function(response){
                        if(response.data && response.data != "false"){
                            timer.time_spent_formatted = parseInt(objReturn.time_spent/60) + "h " + (objReturn.time_spent % 60 || '');

                            timer.start_time = new Date(objReturn.start_time);
                            timer.stop_time = new Date(objReturn.stop_time);

                            $scope.project.time_spent = parseInt($scope.project.time_spent) - parseInt(timer.time_spent) + parseInt(objReturn.time_spent);
                            timer.time_spent = objReturn.time_spent;

                            var ret = zhttp.project.timer.calcSpentTimeRatio($scope.project);
							$scope.time_spent_formatted = ret.time_spent_formatted;
							$scope.timer_color = ret.timer_color;
							$scope.timer_ratio = ret.timer_ratio;
                            generatePostits();
                        }
                    });
                } else {
                }
            });
		}

        function deleteTimer(timer){
            zhttp.project.timer.del(timer.id).then(function(response){
                if(response.data && response.data != "false"){
                    $scope.timers.splice($scope.timers.indexOf(timer), 1);

                    $scope.project.time_spent = parseInt($scope.project.time_spent) - parseInt(timer.time_spent);

                    var ret = zhttp.project.timer.calcSpentTimeRatio($scope.project);
                    $scope.time_spent_formatted = ret.time_spent_formatted;
                    $scope.timer_color = ret.timer_color;
                    $scope.timer_ratio = ret.timer_ratio;
                    generatePostits();
                }
            });
        }

	}]);
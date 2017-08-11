app.controller("ComZeappsProjectViewCtrl", ["$scope", "$route", "$routeParams", "$location", "$rootScope", "zeHttp", "zeapps_modal", "$uibModal", "Upload", "zeProject",
	function ($scope, $route, $routeParams, $location, $rootScope, zhttp, zeapps_modal, $uibModal, Upload, zeProject) {

		$scope.$parent.loadMenu("com_ze_apps_project", "com_zeapps_projects_management");

		var project_users_ids = [];

		$scope.$on('projectTimerBroadcast', function(){
            zeProject.update().then(updateScope);
		});

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
            },
            editable: true,
            eventDrop: function(event) {
                var data = {};
                var formatted_data = "";

                data.id = event.id;
                data.due_date = event.start.format();

                formatted_data = angular.toJson(data);

                if(event.order === 1){
                    zhttp.project.project.post(formatted_data);
                }else if(event.order === 2){
                    zhttp.project.deadline.post(formatted_data);
                }else if(event.order === 3){
                    zhttp.project.card.post(formatted_data);
                }
            }
		};
		$scope.postits = [];
		$scope.zeProject = zeProject; // so we can access it from the view

		$scope.tab = "taches";
		$scope.view = "/com_zeapps_project/project/taches";
		$scope.goToTab = goToTab;

		$scope.compareDates = function(date){ return zhttp.project.compareDate(date); };

		$scope.isActive = isActive;
        $scope.detailCard = detailCard;
		$scope.editCategory = editCategory;
		$scope.deleteCategory = deleteCategory;
		$scope.addProjectUser = addProjectUser;
		$scope.deleteRightsOf = deleteRightsOf;
		$scope.changeRights = changeRights;
		$scope.saveRightsOf = saveRightsOf;
		$scope.archive_project = archive_project;
		$scope.delete_project = delete_project;
		$scope.force_delete_project = force_delete_project;
		$scope.addDocument = addDocument;
		$scope.editDocument = editDocument;
		$scope.deleteDocument = deleteDocument;
		$scope.addComment = addComment;
		$scope.editComment = editComment;
		$scope.deleteComment = deleteComment;
		$scope.startTimerProject = startTimerProject;
		$scope.newTimer = newTimer;
		$scope.editTimer = editTimer;
        $scope.deleteTimer = deleteTimer;
		$scope.newSpending = newSpending;
		$scope.editSpending = editSpending;
        $scope.deleteSpending = deleteSpending;
        $scope.linkQuote = linkQuote;
        $scope.unlinkQuote = unlinkQuote;
        $scope.linkInvoice = linkInvoice;
        $scope.unlinkInvoice = unlinkInvoice;
        $scope.printCards = printCards;
        $scope.printTimers = printTimers;

		if($routeParams.id){
			zhttp.project.project.get($routeParams.id).then(function(response){
				if(response.data && response.data != "false"){
					$scope.project = response.data.project;

                    zeProject.init(response.data.project, response.data.timers);
					updateScope();

					$scope.categories = response.data.categories;
					$scope.documents = response.data.documents;
                    $scope.comments = response.data.comments;
                    $scope.deadlines = response.data.deadlines;
                    $scope.quotes = response.data.quotes;
                    $scope.invoices = response.data.invoices;
                    $scope.spendings = response.data.spendings;


					angular.forEach($scope.documents, function(document){
                        document.date = new Date(document.date);
					});

                    $scope.note_form = {
                        id_project : $scope.project.id
                    };

					angular.forEach($scope.comments, function(comment){
						comment.date = new Date(comment.date);
					});

					$scope.project_users = response.data.project_users;
					project_users_ids = [];
					angular.forEach($scope.project_users, function(user){
						project_users_ids.push(user.id_user);
						user.access = !!parseInt(user.access);
						user.card = !!parseInt(user.card);
						user.accounting = !!parseInt(user.accounting);
						user.project = !!parseInt(user.project);
						user.hourly_rate = parseFloat(user.hourly_rate);
					});
				}
			});
		}

		function updateScope(){
			$scope.timers = zeProject.get.timers();
			$scope.time_spent_formatted = zeProject.get.time_spent_formatted();
			$scope.timer_color = zeProject.get.timer_color();
			$scope.timer_ratio = zeProject.get.timer_ratio();
			$scope.project.total_spendings = zeProject.get.total_spendings();
            generatePostits();
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
                    value: $scope.project.total_spendings,
                    legend: 'Dépenses totales',
                    filter: 'currency'

                },
                {
                    value: $scope.time_spent_formatted + ' <small>/ ' + parseInt($scope.project.estimated_time) + 'h</small>',
                    legend: 'Temps passé',
					color: $scope.timer_color

                }
            ]
        }

        function detailCard(card){
            zeapps_modal.loadModule("com_zeapps_project", "detail_card", {card : card}, null, function(objReturn){
                if(objReturn){
                    zeProject.update().then(updateScope);
                }
            });
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
					user.hourly_rate = parseFloat(objReturn.hourly_rate);

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
			zhttp.project.project.archive(id);
		}

		function delete_project(id) {
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
			data.card = user.card ? 1 : 0;
			data.accounting = user.accounting ? 1 : 0;
			data.project = user.project ? 1 : 0;

			data.id_user = user.id_user;
			data.id_project = user.id_project;
			data.hourly_rate = user.hourly_rate;

			var formatted_data = angular.toJson(data);
			zhttp.project.right.post(formatted_data).then(function(response){
				if(response.data && response.data != "false"){
                    zeProject.update().then(updateScope);
				}
			});
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

        function startTimerProject(){
        	var timer = {
                id_project : $scope.project.id,
                project_title : $scope.project.title,
                id : 0,
                title : $scope.project.title
			};

            zhttp.project.timer.start(timer);
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
                            zeProject.update().then(updateScope);
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
                            zeProject.update().then(updateScope);
                        }
                    });
                } else {
                }
            });
		}

        function deleteTimer(timer){
            zhttp.project.timer.del(timer.id).then(function(response){
                if(response.data && response.data != "false"){
                    zeProject.update().then(updateScope);
                }
            });
        }

		function newSpending(){
			var options = {
            	id_project : $scope.project.id
			};
            zeapps_modal.loadModule("com_zeapps_project", "form_spending", options, function(objReturn) {
                if (objReturn) {
                    var formatted_data = angular.toJson(objReturn);
                    zhttp.project.spendings.post(formatted_data).then(function(response){
                        if(response.data && response.data != "false"){
                        	objReturn.id = response.data;
                        	$scope.spendings.push(objReturn);
                            zeProject.update().then(updateScope);
                        }
                    });
                } else {
                }
            });
		}

		function editSpending(spending){
			var options = {
                spending: angular.fromJson(angular.toJson(spending))
			};
            zeapps_modal.loadModule("com_zeapps_project", "form_spending", options, function(objReturn) {
                if (objReturn) {
                    var formatted_data = angular.toJson(objReturn);
                    zhttp.project.spendings.post(formatted_data).then(function(response){
                        if(response.data && response.data != "false"){
							$scope.spendings[$scope.spendings.indexOf(spending)] = objReturn;
                            zeProject.update().then(updateScope);
                        }
                    });
                } else {
                }
            });
		}

        function deleteSpending(spending){
            zhttp.project.spendings.del(spending.id).then(function(response){
                if(response.data && response.data != "false"){
                	$scope.spendings.splice($scope.spendings.indexOf(spending), 1);
                    zeProject.update().then(updateScope);
                }
            });
        }

        function linkQuote(){
            zeapps_modal.loadModule("com_zeapps_crm", "search_quote", {}, function(objReturn) {
                if (objReturn) {
                	var data = {
                		id_project : $scope.project.id,
						id_quote : objReturn.id
					};
                    var formatted_data = angular.toJson(data);
                    zhttp.project.quote.post(formatted_data).then(function(response){
                        if(response.data && response.data != "false"){
                            objReturn.id = response.data;
                        	$scope.quotes.push(objReturn);
                        }
                    });
                } else {
                }
            });
        }

        function unlinkQuote(quote){
            zhttp.project.quote.del(quote.id).then(function(response){
                if(response.data && response.data != "false"){
                	$scope.quotes.splice($scope.quotes.indexOf(quote), 1);
                }
            });
        }

        function linkInvoice(){
            zeapps_modal.loadModule("com_zeapps_crm", "search_invoice", {}, function(objReturn) {
                if (objReturn) {
                    var data = {
                        id_project : $scope.project.id,
                        id_invoice : objReturn.id
                    };
                    var formatted_data = angular.toJson(data);
                    zhttp.project.invoice.post(formatted_data).then(function(response){
                        if(response.data && response.data != "false"){
                            objReturn.id = response.data;
                            $scope.invoices.push(objReturn);
                        }
                    });
                } else {
                }
            });
        }

        function unlinkInvoice(invoice){
            zhttp.project.invoice.del(invoice.id).then(function(response){
                if(response.data && response.data != "false"){
                    $scope.invoices.splice($scope.invoices.indexOf(invoice), 1);
                }
            });
        }

        function printCards(description){
        	var description = description || false;

        	zhttp.project.card.pdf.make($scope.project.id, description).then(function(response){
        		if(response.data && response.data != "false"){
                    window.document.location.href = zhttp.project.card.pdf.get() + angular.fromJson(response.data);
				}
			});
		}

        function printTimers(){
        	zhttp.project.timer.pdf.make($scope.project.id).then(function(response){
        		if(response.data && response.data != "false"){
                    window.document.location.href = zhttp.project.timer.pdf.get() + angular.fromJson(response.data);
				}
			});
		}

	}]);
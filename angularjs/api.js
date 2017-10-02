app.config(["$provide",
	function ($provide) {
		$provide.decorator("zeHttp", function($delegate, $rootScope, $interval, zeapps_modal, $q){
			var zeHttp = $delegate;

			var tday = new Date();
            
			zeHttp.project = {
				planning : {
					get_context : getContext_planning
				},
				status : {
					get_all : getAll_statuses,
					save : save_statuses,
					del : delete_statuses,
					save_all : saveAll_statuses
				},
				project : {
					tree : get_projects_tree,
					get : get_project,
					update : update_project,
					get_all : getAll_project,
					get_calendar : getCalendar_project,
					get_overview : getOverview_project,
					get_archives : getArchives_project,
					get_childs : getChilds_project,
					post : post_project,
					archive : archive_project,
					del : delete_project,
					document : documentUrl_project,
                    del_document : delDocument_project,
                    comment : comment_project,
					del_comment : delComment_project,
					excel : {
						get : getExcel_project,
						make : makeExcel_project
					}
				},
				card : {
					get_all : getAll_card,
					get : get_card,
					post : post_card,
					del : delete_card,
					validate : validate_idea,
					comment : comment_card,
                    del_comment : delComment_card,
					document : documentUrl_card,
                    del_document : delDocument_card,
					pdf : {
						make : makePDF_card,
						get : getPDF_card
					}
				},
				deadline : {
					get_all : getAll_deadline,
					get : get_deadline,
					post : post_deadline,
					del : delete_deadline
				},
				task : {
					get : get_task,
					get_all : getAll_task
				},
				category : {
					get_all : getAll_category,
					get : get_category,
					post : post_category,
					del : delete_category
				},
				right : {
					get_all : getAll_right,
					get : get_right,
					get_connected : get_connected,
					post : post_right,
					del : delete_right
				},
				filter : {
					get_all : getAll_filter
				},
				timer : {
					get : get_timer,
					get_ongoing : getOngoing_timer,
					post : post_timer,
					del : delete_timer,
					start : timer_start,
					stop : timer_stop,
					get_interval : getInterval_timer,
                    pdf : {
                        make : makePDF_timer,
                        get : getPDF_timer
                    }
				},
				mywork : {
					get_work : getWork_mywork
				},
				journal : {
					get_journal : getJournal_journal
				},
				todos : {
					all : getAll_Todos,
					get_todos : getTodos_Todos,
					post : post_Todos,
					validate : validate_Todos,
					cancel : cancel_Todos,
					del : del_Todos,
					postCategory : postCategory_Todos,
                    delCategory : delCategory_Todos,
					todos_position : todosPosition_Todos,
					categories_position : categoriesPosition_Todos
				},
				quote : {
					post : post_quote,
					del : delete_quote
				},
				invoice : {
					post : post_invoice,
					del : delete_invoice
				},
				spendings : {
					post : post_spendings,
					del : delete_spendings
				},
				openTree : recursiveOpening,
				compareDate : compareDate
			};

			return zeHttp;

			// PLANNING
			function getContext_planning(id){
				id = id || 0;
				return zeHttp.get("/com_zeapps_project/planning/get_context/" + id)
			}

			// STATUS
			function getAll_statuses(){
				return zeHttp.get("/com_zeapps_project/status/get_all/");
			}
			function save_statuses(data){
				return zeHttp.post("/com_zeapps_project/status/save/", data);
			}
			function saveAll_statuses(data){
				return zeHttp.post("/com_zeapps_project/status/save_all/", data);
			}
			function delete_statuses(id){
				return zeHttp.delete("/com_zeapps_project/status/delete/" + id);
			}

			// PROJECT
			function get_project (id){
				return zeHttp.get("/com_zeapps_project/project/get_project/" + id);
			}
			function update_project (id){
				return zeHttp.get("/com_zeapps_project/project/update_project/" + id);
			}
			function getAll_project(){
				return zeHttp.get("/com_zeapps_project/project/get_projects/");
			}
			function getCalendar_project(id){
				return zeHttp.get("/com_zeapps_project/project/get_calendar/" + id);
			}
			function getOverview_project(){
				return zeHttp.get("/com_zeapps_project/project/get_overview/");
			}
			function getArchives_project(){
				return zeHttp.get("/com_zeapps_project/project/get_archives/");
			}
			function getChilds_project(id){
				return zeHttp.get("/com_zeapps_project/project/get_childs/" + id);
			}
			function get_projects_tree(){
				return zeHttp.get("/com_zeapps_project/project/get_projects_tree/");
			}
			function post_project(data){
				return zeHttp.post("/com_zeapps_project/project/save_project/", data);
			}
			function archive_project(id){
				return zeHttp.get("/com_zeapps_project/project/archive_project/" + id);
			}
			function delete_project(id, force){
				force = !!force;
				return zeHttp.get("/com_zeapps_project/project/delete_project/" + id + "/" + force);
			}
            function comment_project(data){
                return zeHttp.post("/com_zeapps_project/project/comment/", data);
            }
            function delComment_project(id){
                return zeHttp.delete("/com_zeapps_project/project/del_comment/" + id);
            }
			function documentUrl_project(){
				return "/com_zeapps_project/project/uploadDocuments/";
			}
			function delDocument_project(id){
                return zeHttp.delete("/com_zeapps_project/project/del_document/" + id);
			}
            function makeExcel_project(id_status, details){
                id_status = id_status === undefined ? "all" : id_status;
                details = details || '';
                return zeHttp.get("/com_zeapps_project/project/make_excel/" + id_status + "/" + details);
            }
			function getExcel_project(){
                return "/com_zeapps_project/project/get_excel/"
			}


			// CARD
			function getAll_card(id, step){
				id = id || 0;
				step = step !== undefined ? step : '';
				return zeHttp.get("/com_zeapps_project/card/get_cards/" + id + "/" + step);
			}
			function get_card(id){
				return zeHttp.get("/com_zeapps_project/card/get_card/" + id);
			}
			function post_card(data){
				return zeHttp.post("/com_zeapps_project/card/save_card/", data);
			}
			function delete_card(id){
				return zeHttp.get("/com_zeapps_project/card/delete_card/" + id);
			}
			function validate_idea(id){
				return zeHttp.get("/com_zeapps_project/card/validate_idea/" + id);
			}
			function comment_card(data){
				return zeHttp.post("/com_zeapps_project/card/comment/", data);
			}
			function delComment_card(id){
                return zeHttp.delete("/com_zeapps_project/card/del_comment/" + id);
			}
            function documentUrl_card(){
                return "/com_zeapps_project/card/uploadDocuments/";
            }
			function delDocument_card(id){
				return zeHttp.delete("/com_zeapps_project/card/del_document/" + id);
			}
			function makePDF_card(id, description, step){
				return zeHttp.get("/com_zeapps_project/card/makePDF/" + id + "/" + description + "/" + step);
			}
			function getPDF_card(){
                return "/com_zeapps_project/print/getPDF/";
			}


			// DEADLINE
			function getAll_deadline(id){
				if(!id) id = 0;
				return zeHttp.get("/com_zeapps_project/deadline/get_deadlines/" + id);
			}
			function get_deadline(id){
				return zeHttp.get("/com_zeapps_project/deadline/get_deadline/" + id);
			}
			function post_deadline(data){
				return zeHttp.post("/com_zeapps_project/deadline/save_deadline/", data);
			}
			function delete_deadline(id){
				return zeHttp.get("/com_zeapps_project/deadline/delete_deadline/" + id);
			}

			// TASK
			function get_task(id){
				return zeHttp.get("/com_zeapps_project/task/get_task/" + id);
			}
			function getAll_task(id){
				if(!id) id = 0;
				return zeHttp.get("/com_zeapps_project/task/get_tasks/" + id);
			}

			// RIGHT
			function getAll_right(id_project){
				return zeHttp.get("/com_zeapps_project/right/get_rights/" + id_project);
			}
			function get_right(id_user){
				return zeHttp.get("/com_zeapps_project/right/get_right/" + id_user);
			}
			function get_connected(){
				return zeHttp.get("/com_zeapps_project/right/get_connected/");
			}
			function post_right(data){
				return zeHttp.post("/com_zeapps_project/right/save_right/", data);
			}
			function delete_right(id){
				return zeHttp.get("/com_zeapps_project/right/delete_right/" + id);
			}

			// CATEGORY
			function getAll_category(id_project){
				return zeHttp.get("/com_zeapps_project/category/get_categories/" + id_project);
			}
			function get_category(id){
				return zeHttp.get("/com_zeapps_project/category/get_category/" + id);
			}
			function post_category(data){
				return zeHttp.post("/com_zeapps_project/category/save_category/", data);
			}
			function delete_category(id){
				return zeHttp.get("/com_zeapps_project/category/delete_category/" + id);
			}

			// MY WORK
			function getWork_mywork(){
				return zeHttp.get("/com_zeapps_project/mywork/get_work");
			}

			// JOURNAL
			function getJournal_journal(){
				return zeHttp.get("/com_zeapps_project/journal/get_journal");
			}

			// TO-DOs
			function getAll_Todos(){
				return zeHttp.get("/com_zeapps_project/todos/all/");
			}
			function getTodos_Todos(id){
				return zeHttp.get("/com_zeapps_project/todos/get_todos/" + id);
			}
			function post_Todos(data){
				return zeHttp.post("/com_zeapps_project/todos/save/", data);
			}
			function validate_Todos(id){
				return zeHttp.get("/com_zeapps_project/todos/validate/" + id);
			}
			function cancel_Todos(id){
				return zeHttp.get("/com_zeapps_project/todos/cancel/" + id);
			}
			function del_Todos(id){
				return zeHttp.delete("/com_zeapps_project/todos/delete/" + id);
			}
            function postCategory_Todos(data){
                return zeHttp.post("/com_zeapps_project/todos/save_category/", data);
            }
            function delCategory_Todos(id){
                return zeHttp.delete("/com_zeapps_project/todos/delete_category/" + id);
            }
            function todosPosition_Todos(data){
                return zeHttp.post("/com_zeapps_project/todos/todos_position/", data);
            }
            function categoriesPosition_Todos(data){
                return zeHttp.post("/com_zeapps_project/todos/categories_position/", data);
            }

            // QUOTE
			function post_quote(data){
                return zeHttp.post("/com_zeapps_project/project_quotes/save/", data);
			}
			function delete_quote(id){
                return zeHttp.delete("/com_zeapps_project/project_quotes/delete/" + id);
			}

			// INVOICE
			function post_invoice(data){
                return zeHttp.post("/com_zeapps_project/project_invoices/save/", data);
			}
			function delete_invoice(id){
                return zeHttp.delete("/com_zeapps_project/project_invoices/delete/" + id);
			}

			// SPENDINGS
            function post_spendings(data){
                return zeHttp.post("/com_zeapps_project/spending/save/", data);
            }
            function delete_spendings(id){
                return zeHttp.delete("/com_zeapps_project/spending/delete/" + id);
            }

			// TIMER
			function get_timer(id){
				return zeHttp.get("/com_zeapps_project/timer/get/" + id);
			}
			function getOngoing_timer(){
				return zeHttp.get("/com_zeapps_project/timer/get_ongoing/");
			}
			function post_timer(data){
				return zeHttp.post("/com_zeapps_project/timer/save/", data);
			}
			function delete_timer(id){
				return zeHttp.get("/com_zeapps_project/timer/delete/" + id);
			}
            function makePDF_timer(id){
                return zeHttp.get("/com_zeapps_project/timer/makePDF/" + id);
            }
            function getPDF_timer(){
                return "/com_zeapps_project/timer/getPDF/";
            }
			function timer_start(src){
				if(src){
					if($rootScope.currentTimer && $rootScope.project_timer)
						timer_stop();

					$rootScope.currentTimer = {
						id_project : src.id_project,
						name_project : src.project_title,
						id_card : src.id,
						label : src.title,
						start_time : moment().format("YYYY-MM-DD HH:mm:ss")
					};

					var formatted_data = angular.toJson($rootScope.currentTimer);
					post_timer(formatted_data).then(function(response){
						if(response && response.data != "false"){
							$rootScope.currentTimer.id = response.data;
							$rootScope.project_timer = getInterval_timer();
						}
					});
				}
				else{
					$rootScope.project_timer = getInterval_timer();
				}
			}
			function timer_stop(){
                var defer = $q.defer();
                var promise;

				if($rootScope.currentTimer && $rootScope.project_timer) {

					$interval.cancel($rootScope.project_timer);
					$rootScope.currentTimer.stop_time = moment().format("YYYY-MM-DD HH:mm:ss");

					var date_start = new Date($rootScope.currentTimer.start_time);
					var date_stop = new Date($rootScope.currentTimer.stop_time);

                    var start_time_m = date_start.getMinutes();
                    var start_time_h = date_start.getHours();

                    var stop_time_m = date_stop.getMinutes();
                    var stop_time_h = date_stop.getHours();

                    var minutes = stop_time_m - start_time_m;
                    var hours = stop_time_h - start_time_h;

                    if (minutes < 0) {
                        hours -= 1;
                        minutes += 60;
                    }

                    $rootScope.currentTimer.time_spent = hours * 60 + minutes;

                    var formatted_data = angular.toJson($rootScope.currentTimer);
                    post_timer(formatted_data).then(function (response) {
                        if (response && response.data != "false") {
                            var options = {
                                id: response.data
                            };
                            $rootScope.currentTimer = false;
                            $rootScope.project_timer = null;
                            delete $rootScope.currentTimer.interval;
                            zeapps_modal.loadModule("com_zeapps_project", "form_timer", options, function(objReturn){
								if(objReturn) {
                                    var formatted_data = angular.toJson(objReturn);
                                    post_timer(formatted_data).then(function (response) {
                                        if (response.data && response.data != "false") {
                                            defer.resolve(response);
                                        }
                                    });
                                }
							}, function(){
                                defer.resolve(response);
							});
                        }
                    });
				}

                promise = defer.promise;

                return promise;
			}
			function getInterval_timer(){
				return $interval(function () {
					$rootScope.currentTimer.seconds = moment().diff(moment($rootScope.currentTimer.start_time));
					$rootScope.currentTimer.interval = moment.utc($rootScope.currentTimer.seconds).format("HH:mm:ss");
				}, 500);
			}

			// FILTER
			function getAll_filter(ctrllr){
				return zeHttp.get("/com_zeapps_project/" + ctrllr + "/get_filters/");
			}

			function recursiveOpening(branch, id){
				if(angular.isArray(branch.branches)){
					for(var i = 0; i < branch.branches.length; i++){
						var ret;
						if(ret = recursiveOpening(branch.branches[i], id)){
							branch.open = true;
							return ret;
						}
					}
				}
				return branch.id == id ? branch : false;
			}

			function compareDate(date){
				var day = 24 * 60 * 60 * 1000;
				var warning = 7 * day; // next 7 days
				var danger = 3 * day; // next 3 days
				var parsedDate = Date.parse(date);
				if(parsedDate) {
					var diff  = parsedDate - tday;
					if(diff < - day)
						return "outdated";
					if(diff < danger)
						return "danger";
					if(diff >= danger && diff <= warning)
						return "warning";
					if(diff > warning)
						return "success";
				}
				else
					return "info";
			}
		});
	}]);
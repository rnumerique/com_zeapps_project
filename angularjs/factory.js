app.config(["$provide",
	function ($provide) {
		$provide.decorator("zeHttp", function($delegate, $rootScope, $interval, zeapps_modal){
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
					get_all : getAll_project,
					get_overview : getOverview_project,
					get_childs : getChilds_project,
					post : post_project,
					archive : archive_project,
					del : delete_project,
					document : documentUrl_project,
                    del_document : delDocument_project,
                    comment : comment_project,
					del_comment : delComment_project
				},
				card : {
					get_all : getAll_card,
					get : get_card,
					post : post_card,
					del : delete_card,
					complete : complete_card,
					validate : validate_idea,
					comment : comment_card,
                    del_comment : delComment_card,
					document : documentUrl_card,
                    del_document : delDocument_card
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
                    calcSpentTimeRatio : calcSpentTimeRatio
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
					todos_position : todosPosition_Todos,
					categories_position : categoriesPosition_Todos
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
            
			function getAll_project(id, spaces, filter){
				if(!id) id = 0;
				if(!spaces) spaces = "false";
				if(!filter) filter = "false";
				return zeHttp.get("/com_zeapps_project/project/get_projects/" + id + "/" + spaces + "/" + filter);
			}
			function getOverview_project(){
				return zeHttp.get("/com_zeapps_project/project/get_overview/");
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


			// CARD
			function getAll_card(id){
				if(!id) id = 0;
				return zeHttp.get("/com_zeapps_project/card/get_cards/" + id);
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
			function complete_card(id, deadline){
				deadline = !!deadline;
				return zeHttp.get("/com_zeapps_project/card/complete_card/" + id + "/" + deadline);
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
            function todosPosition_Todos(data){
                return zeHttp.post("/com_zeapps_project/todos/todos_position/", data);
            }
            function categoriesPosition_Todos(data){
                return zeHttp.post("/com_zeapps_project/todos/categories_position/", data);
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
			function timer_start(card){
				if(card){
					if($rootScope.currentTask && $rootScope.project_timer)
						timer_stop();
					$rootScope.currentTask = {
						id_project : card.id_project,
						id_category : card.id_category,
						id_sprint : card.id_sprint,
						id_card : card.id,
						id_user : $rootScope.user.id,
						name_user : $rootScope.user.firstname + " " + $rootScope.user.lastname,
						label : card.title,
						start_time : moment().format("YYYY-MM-DD HH:mm:ss")
					};

					var formatted_data = angular.toJson($rootScope.currentTask);
					post_timer(formatted_data).then(function(response){
						if(response && response.data != "false"){
							$rootScope.currentTask.id = response.data;
							$rootScope.project_timer = getInterval_timer();
						}
					});
				}
				else{
					$rootScope.project_timer = getInterval_timer();
				}
			}
			function timer_stop(card){
				if($rootScope.currentTask && $rootScope.project_timer) {
					$interval.cancel($rootScope.project_timer);
					$rootScope.currentTask.stop_time = moment().format("YYYY-MM-DD HH:mm:ss");
					zeapps_modal.loadModule("com_zeapps_project", "timer_end", {}, function (objReturn) {
						if (objReturn) {
							$rootScope.project_timer = null;
							if (card) {
								if ($rootScope.currentTask && $rootScope.currentTask.id_card == card.id) {
									var formatted_data = angular.toJson($rootScope.currentTask);
									post_timer(formatted_data).then(function (response) {
										if (response && response.data != "false") {
											$rootScope.currentTask = false;
										}
									});
								}
								else {
									delete $rootScope.currentTask.stop_time;
								}
							}
							else if ($rootScope.currentTask) {
								var formatted_data = angular.toJson($rootScope.currentTask);
								post_timer(formatted_data).then(function (response) {
									if (response && response.data != "false") {
										delete $rootScope.currentTask.interval;
									}
								});
							}
							else {
								delete $rootScope.currentTask.stop_time;
							}
						} else {
							$rootScope.project_timer = getInterval_timer();
							delete $rootScope.currentTask.stop_time;
						}
					});
				}
			}
			function getInterval_timer(){
				return $interval(function () {
					$rootScope.currentTask.seconds = moment().diff(moment($rootScope.currentTask.start_time));
					$rootScope.currentTask.interval = moment.utc($rootScope.currentTask.seconds).format("HH:mm:ss");
				}, 500);
			}

			function calcSpentTimeRatio(src){
				if(src.time_spent && src.estimated_time) {
                    var time_spent = moment.duration(parseInt(src.time_spent), 'minutes');
                    var time_spent_formatted = parseInt(time_spent.asHours()) + 'h' + (time_spent.minutes() || '');
                    var timer_color;
                    var timer_ratio;
					var estimated_time = moment.duration(parseInt(src.estimated_time), 'hours');

					var ratio = time_spent.asSeconds() / estimated_time.asSeconds();
					if (ratio > 1) ratio = 1;
					var g = ratio < 0.5 ? 200 : parseInt(((0.5 - (ratio - 0.5)) * 2) * 200);
					var r = ratio >= 0.5 ? 200 : parseInt(((ratio) * 2) * 200);
					timer_color = '#' + ('00' + r.toString(16)).substr(-2) + ('00' + g.toString(16)).substr(-2) + '00';
					timer_ratio = (ratio * 100).toFixed(2);

                    return {
                    	time_spent_formatted : time_spent_formatted,
						timer_color : timer_color,
						timer_ratio : timer_ratio
					}
                }
                else{
                    return {
                        time_spent_formatted : "0h",
                        timer_color : '#00c800',
                        timer_ratio : 0
                    }
				}
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
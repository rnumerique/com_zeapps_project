app.config(["$provide",
	function ($provide) {
		$provide.decorator("zeHttp", function($delegate, $rootScope, $interval, zeapps_modal){
			var zeHttp = $delegate;

			var tday = new Date();
            
			zeHttp.project = {
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
					del : delete_project
				},
				card : {
					get_all : getAll_card,
					get : get_card,
					post : post_card,
					del : delete_card,
					complete : complete_card,
					validate : validate_idea,
					comment : comment,
					document : document_url
				},
				deadline : {
					get_all : getAll_deadline,
					get : get_deadline,
					post : post_deadline
				},
				task : {
					get : get_task,
					get_all : getAll_task
				},
				sandbox : {
					get_all : getAll_sandbox,
					get : get_sandbox,
					post : post_sandbox,
					del : delete_sandbox
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
				sprint : {
					get_all : getAll_sprint,
					get : get_sprint,
					post : post_sprint,
					del : delete_sprint,
					updateCards : updateCards_sprint,
					finalize : finalize_sprint
				},
				timer : {
					get : get_timer,
					get_ongoing : getOngoing_timer,
					post : post_timer,
					del : delete_timer,
					start : timer_start,
					stop : timer_stop,
					get_interval : getInterval_timer
				},
				openTree : recursiveOpening,
				compareDate : compareDate
			};

			return zeHttp;

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
			function delete_card(id, deadline){
				deadline = !!deadline;
				return zeHttp.get("/com_zeapps_project/card/delete_card/" + id + "/" + deadline);
			}
			function complete_card(id, deadline){
				deadline = !!deadline;
				return zeHttp.get("/com_zeapps_project/card/complete_card/" + id + "/" + deadline);
			}
			function validate_idea(id){
				return zeHttp.get("/com_zeapps_project/card/validate_idea/" + id);
			}
			function comment(data){
				return zeHttp.post("/com_zeapps_project/card/comment/", data);
			}
			function document_url(){
				return "/com_zeapps_project/card/uploadDocuments/";
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

			// TASK
			function get_task(id){
				return zeHttp.get("/com_zeapps_project/task/get_task/" + id);
			}
			function getAll_task(id){
				if(!id) id = 0;
				return zeHttp.get("/com_zeapps_project/task/get_tasks/" + id);
			}


			// SANDBOX
			function getAll_sandbox(){
				return zeHttp.get("/com_zeapps_project/sandbox/get_sandboxes/");
			}
			function get_sandbox(id){
				return zeHttp.get("/com_zeapps_project/sandbox/get_sandbox/" + id);
			}
			function post_sandbox(data){
				return zeHttp.post("/com_zeapps_project/sandbox/save_sandbox/", data);
			}
			function delete_sandbox(id){
				return zeHttp.get("/com_zeapps_project/sandbox/delete_sandbox/" + id);
			}

			// SPRINT
			function get_sprint(id){
				return zeHttp.get("/com_zeapps_project/sprint/get_sprint/" + id);
			}
			function getAll_sprint(){
				return zeHttp.get("/com_zeapps_project/sprint/get_sprints/");
			}
			function post_sprint(data){
				return zeHttp.post("/com_zeapps_project/sprint/save_sprint/", data);
			}
			function delete_sprint(id){
				return zeHttp.get("/com_zeapps_project/sprint/delete_sprint/" + id);
			}
			function updateCards_sprint(data){
				return zeHttp.post("/com_zeapps_project/sprint/updateCards/", data);
			}
			function finalize_sprint(id, next){
				next = next || false;
				return zeHttp.get("/com_zeapps_project/sprint/finalize_sprint/" + id + "/" + next);
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
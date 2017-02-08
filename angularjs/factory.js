app.config(['$provide',
    function ($provide) {
        $provide.decorator('zeHttp', function($delegate){
            var zeHttp = $delegate;

            // PROJECT
            var get_project = function(id){
                return zeHttp.get('/com_zeapps_project/project/get_project/' + id);
            };
            var getAll_project = function(id, spaces, filter){
                if(!id) id = 0;
                if(!spaces) spaces = 'false';
                if(!filter) filter = 'false';
                return zeHttp.get('/com_zeapps_project/project/get_projects/' + id + '/' + spaces + '/' + filter);
            };
            var getChilds_project = function(id){
                return zeHttp.get('/com_zeapps_project/project/get_childs/' + id);
            };
            var get_projects_tree = function(){
                return zeHttp.get('/com_zeapps_project/project/get_projects_tree/');
            };
            var post_project = function(data){
                return zeHttp.post('/com_zeapps_project/project/save_project/', data);
            };
            var delete_project = function(id, force){
                force = !!force;
                return zeHttp.get('/com_zeapps_project/project/delete_project/' + id + '/' + force);
            };


            // CARD
            var getAll_card = function(id){
                if(!id) id = 0;
                return zeHttp.get('/com_zeapps_project/card/get_cards/' + id);
            };
            var get_card = function(id){
                return zeHttp.get('/com_zeapps_project/card/get_card/' + id);
            };
            var post_card = function(data){
                return zeHttp.post('/com_zeapps_project/card/save_card/', data);
            };
            var delete_card = function(id, deadline){
                deadline = !!deadline;
                return zeHttp.get('/com_zeapps_project/card/delete_card/' + id + '/' + deadline);
            };
            var complete_card = function(id, deadline){
                deadline = !!deadline;
                return zeHttp.get('/com_zeapps_project/card/complete_card/' + id + '/' + deadline);
            };
            var validate_idea = function(id){
                return zeHttp.get('/com_zeapps_project/card/validate_idea/' + id);
            };


            // DEADLINE
            var getAll_deadline = function(id){
                if(!id) id = 0;
                return zeHttp.get('/com_zeapps_project/deadline/get_deadlines/' + id);
            };
            var get_deadline = function(id){
                return zeHttp.get('/com_zeapps_project/deadline/get_deadline/' + id);
            };
            var post_deadline = function(data){
                return zeHttp.post('/com_zeapps_project/deadline/save_deadline/', data);
            };

            // TASK
            var get_task = function(id){
                return zeHttp.get('/com_zeapps_project/task/get_task/' + id);
            };
            var getAll_task = function(id){
                if(!id) id = 0;
                return zeHttp.get('/com_zeapps_project/task/get_tasks/' + id);
            };


            // SANDBOX
            var getAll_sandbox = function(){
                return zeHttp.get('/com_zeapps_project/sandbox/get_sandboxes/');
            };
            var get_sandbox = function(id){
                return zeHttp.get('/com_zeapps_project/sandbox/get_sandbox/' + id);
            };
            var post_sandbox = function(data){
                return zeHttp.post('/com_zeapps_project/sandbox/save_sandbox/', data);
            };
            var delete_sandbox = function(id){
                return zeHttp.get('/com_zeapps_project/sandbox/delete_sandbox/' + id);
            };

            // SPRINT
            var get_sprint = function(id){
                return zeHttp.get('/com_zeapps_project/sprint/get_sprint/' + id);
            };
            var getAll_sprint = function(id_project){
                id_project = id_project || '';
                return zeHttp.get('/com_zeapps_project/sprint/get_sprints/' + id_project);
            };
            var post_sprint = function(data){
                return zeHttp.post('/com_zeapps_project/sprint/save_sprint/', data);
            };
            var delete_sprint = function(id){
                return zeHttp.get('/com_zeapps_project/sprint/delete_sprint/' + id);
            };
            var updateCards_sprint = function(id, data){
                return zeHttp.post('/com_zeapps_project/sprint/updateCardsOf/' + id, data);
            };

            // CATEGORY
            var getAll_category = function(id_project){
                return zeHttp.get('/com_zeapps_project/category/get_categories/' + id_project);
            };
            var get_category = function(id){
                return zeHttp.get('/com_zeapps_project/category/get_category/' + id);
            };
            var post_category = function(data){
                return zeHttp.post('/com_zeapps_project/category/save_category/', data);
            };
            var delete_category = function(id){
                return zeHttp.get('/com_zeapps_project/category/delete_category/' + id);
            };

            // FILTER
            var getAll_filter = function(ctrllr){
                return zeHttp.get('/com_zeapps_project/' + ctrllr + '/get_filters/');
            };


            var recursiveOpening = function(branch, id){
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
            };

            var tday = new Date();
            var compareDate = function(date){
                var day = 24 * 60 * 60 * 1000;
                var warning = 7 * day; // next 7 days
                var danger = 3 * day; // next 3 days
                var parsedDate = Date.parse(date);
                if(parsedDate) {
                    var diff  = parsedDate - tday;
                    if(diff < - day)
                        return 'active';
                    if(diff < danger)
                        return 'danger';
                    if(diff >= danger && diff <= warning)
                        return 'warning';
                    if(diff > warning)
                        return 'success';
                }
                else
                    return 'info';
            };

            zeHttp.project = {
                project : {
                    tree : get_projects_tree,
                    get : get_project,
                    get_all : getAll_project,
                    get_childs : getChilds_project,
                    post : post_project,
                    del : delete_project
                },
                card : {
                    get_all : getAll_card,
                    get : get_card,
                    post : post_card,
                    del : delete_card,
                    complete : complete_card,
                    validate : validate_idea
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
                filter : {
                    get_all : getAll_filter
                },
                sprint : {
                    get_all : getAll_sprint,
                    get : get_sprint,
                    post : post_sprint,
                    del : delete_sprint,
                    updateCards : updateCards_sprint
                },
                openTree : recursiveOpening,
                compareDate : compareDate
            };

            return zeHttp;
        });
    }]);
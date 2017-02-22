app.config(['$provide',
    function ($provide) {
        $provide.decorator('zeHttp', function($delegate){
            var zeHttp = $delegate;

            var tday = new Date();
            
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
                openTree : recursiveOpening,
                compareDate : compareDate
            };

            return zeHttp;

            // PROJECT
            function get_project (id){
                return zeHttp.get('/com_zeapps_project/project/get_project/' + id);
            }
            
            function getAll_project(id, spaces, filter){
                if(!id) id = 0;
                if(!spaces) spaces = 'false';
                if(!filter) filter = 'false';
                return zeHttp.get('/com_zeapps_project/project/get_projects/' + id + '/' + spaces + '/' + filter);
            }
            function getChilds_project(id){
                return zeHttp.get('/com_zeapps_project/project/get_childs/' + id);
            }
            function get_projects_tree(){
                return zeHttp.get('/com_zeapps_project/project/get_projects_tree/');
            }
            function post_project(data){
                return zeHttp.post('/com_zeapps_project/project/save_project/', data);
            }
            function delete_project(id, force){
                force = !!force;
                return zeHttp.get('/com_zeapps_project/project/delete_project/' + id + '/' + force);
            }


            // CARD
            function getAll_card(id){
                if(!id) id = 0;
                return zeHttp.get('/com_zeapps_project/card/get_cards/' + id);
            }
            function get_card(id){
                return zeHttp.get('/com_zeapps_project/card/get_card/' + id);
            }
            function post_card(data){
                return zeHttp.post('/com_zeapps_project/card/save_card/', data);
            }
            function delete_card(id, deadline){
                deadline = !!deadline;
                return zeHttp.get('/com_zeapps_project/card/delete_card/' + id + '/' + deadline);
            }
            function complete_card(id, deadline){
                deadline = !!deadline;
                return zeHttp.get('/com_zeapps_project/card/complete_card/' + id + '/' + deadline);
            }
            function validate_idea(id){
                return zeHttp.get('/com_zeapps_project/card/validate_idea/' + id);
            }


            // DEADLINE
            function getAll_deadline(id){
                if(!id) id = 0;
                return zeHttp.get('/com_zeapps_project/deadline/get_deadlines/' + id);
            }
            function get_deadline(id){
                return zeHttp.get('/com_zeapps_project/deadline/get_deadline/' + id);
            }
            function post_deadline(data){
                return zeHttp.post('/com_zeapps_project/deadline/save_deadline/', data);
            }

            // TASK
            function get_task(id){
                return zeHttp.get('/com_zeapps_project/task/get_task/' + id);
            }
            function getAll_task(id){
                if(!id) id = 0;
                return zeHttp.get('/com_zeapps_project/task/get_tasks/' + id);
            }


            // SANDBOX
            function getAll_sandbox(){
                return zeHttp.get('/com_zeapps_project/sandbox/get_sandboxes/');
            }
            function get_sandbox(id){
                return zeHttp.get('/com_zeapps_project/sandbox/get_sandbox/' + id);
            }
            function post_sandbox(data){
                return zeHttp.post('/com_zeapps_project/sandbox/save_sandbox/', data);
            }
            function delete_sandbox(id){
                return zeHttp.get('/com_zeapps_project/sandbox/delete_sandbox/' + id);
            }

            // SPRINT
            function get_sprint(id){
                return zeHttp.get('/com_zeapps_project/sprint/get_sprint/' + id);
            }
            function getAll_sprint(id_project){
                id_project = id_project || '';
                return zeHttp.get('/com_zeapps_project/sprint/get_sprints/' + id_project);
            }
            function post_sprint(data){
                return zeHttp.post('/com_zeapps_project/sprint/save_sprint/', data);
            }
            function delete_sprint(id){
                return zeHttp.get('/com_zeapps_project/sprint/delete_sprint/' + id);
            }
            function updateCards_sprint(id, data){
                return zeHttp.post('/com_zeapps_project/sprint/updateCardsOf/' + id, data);
            }
            function finalize_sprint(id, next){
                next = next || false;
                return zeHttp.get('/com_zeapps_project/sprint/finalize_sprint/' + id + '/' + next);
            }

            // RIGHT
            function getAll_right(id_project){
                return zeHttp.get('/com_zeapps_project/right/get_rights/' + id_project);
            }
            function get_right(id_user){
                return zeHttp.get('/com_zeapps_project/right/get_right/' + id_user);
            }
            function get_connected(){
                return zeHttp.get('/com_zeapps_project/right/get_connected/');
            }
            function post_right(data){
                return zeHttp.post('/com_zeapps_project/right/save_right/', data);
            }
            function delete_right(id){
                return zeHttp.get('/com_zeapps_project/right/delete_right/' + id);
            }

            // CATEGORY
            function getAll_category(id_project){
                return zeHttp.get('/com_zeapps_project/category/get_categories/' + id_project);
            }
            function get_category(id){
                return zeHttp.get('/com_zeapps_project/category/get_category/' + id);
            }
            function post_category(data){
                return zeHttp.post('/com_zeapps_project/category/save_category/', data);
            }
            function delete_category(id){
                return zeHttp.get('/com_zeapps_project/category/delete_category/' + id);
            }

            // FILTER
            function getAll_filter(ctrllr){
                return zeHttp.get('/com_zeapps_project/' + ctrllr + '/get_filters/');
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
                        return 'outdated';
                    if(diff < danger)
                        return 'danger';
                    if(diff >= danger && diff <= warning)
                        return 'warning';
                    if(diff > warning)
                        return 'success';
                }
                else
                    return 'info';
            }
        });
    }]);
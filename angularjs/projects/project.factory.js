app.factory("zeProject", function(zeHttp, $q){
    var time_spent_formatted = "0h";
    var timer_color = "#00c800";
    var timer_ratio = 0;
    var total_spendings = 0;
    var project = {};
    var timers = [];

    return {
        get : {
            time_spent_formatted : get_time_spent_formatted,
            timer_color : get_timer_color,
            timer_ratio : get_timer_ratio,
            total_spendings : get_total_spendings,
            timers : get_timers,
            ratioOf : get_ratioOf
        },
        update : update,
        init : init
    };

    function get_time_spent_formatted(){
        return time_spent_formatted;
    }

    function get_timer_color(){
        return timer_color;
    }

    function get_timer_ratio(){
        return timer_ratio;
    }

    function get_total_spendings(){
        return total_spendings;
    }

    function get_timers(){
        return timers;
    }

    function init(p, t){
        project = p;
        timers = t;

        angular.forEach(timers, function(timer){
            timer.time_spent_formatted = parseInt(timer.time_spent/60) + "h " + (timer.time_spent % 60 || '');
            timer.start_time = new Date(timer.start_time);
            timer.stop_time = new Date(timer.stop_time);
        });

        total_spendings = project.total_spendings;

        var ret = get_ratioOf(project);
        time_spent_formatted = ret.time_spent_formatted;
        timer_color = ret.timer_color;
        timer_ratio = ret.timer_ratio;
    }

    function update(){
        var defer = $q.defer();
        var promise;

        zeHttp.project.project.update(project.id).then(function(response){
            if(response.data && response.data != "false"){
                project.total_time_spent = parseInt(response.data.time_spent);
                timers = response.data.timers;

                angular.forEach(timers, function(timer){
                    timer.time_spent_formatted = parseInt(timer.time_spent/60) + "h " + (timer.time_spent % 60 || '');
                    timer.start_time = new Date(timer.start_time);
                    timer.stop_time = new Date(timer.stop_time);
                });

                project.total_hourly = response.data.total_hourly;
                project.total_spendings = response.data.spendings;
                total_spendings = parseFloat(project.total_hourly) + parseFloat(project.total_spendings);

                var ret = get_ratioOf(project);
                time_spent_formatted = ret.time_spent_formatted;
                timer_color = ret.timer_color;
                timer_ratio = ret.timer_ratio;
            }
            defer.resolve(response);
        });

        promise = defer.promise;

        return promise;
    }

    function get_ratioOf(src){
        if(src.total_time_spent !== undefined && src.estimated_time) {
            var time_spent = moment.duration(parseInt(src.total_time_spent), 'minutes');
            var time_spent_formatted = parseInt(time_spent.asHours()) + 'h' + (time_spent.minutes() || '');
            var timer_color;
            var timer_ratio;
            var ratio;
            var estimated_time = moment.duration(parseInt(src.estimated_time), 'hours');

            if(parseInt(src.estimated_time) !== 0) {
                ratio = time_spent.asSeconds() / estimated_time.asSeconds();
            }
            else{
                ratio = 0;
            }
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
});
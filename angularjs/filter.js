app.filter('planningFilter', function($filter){
    return function(list, filters){
        if(filters){
            return $filter("filter")(list, function(listItem){
                if(filters.completed != undefined) {
                    if(listItem.completed === 'Y' && !filters.completed)
                        return false;
                }
                if(filters.title != undefined && filters.title != '') {
                    var regex = new RegExp(filters.title, 'i');
                    if(listItem.title.search(regex) == -1)
                        return false;
                }
                if(filters.name_assigned_to != undefined && filters.name_assigned_to != '') {
                    var regex = new RegExp(filters.name_assigned_to, 'i');
                    if(listItem.name_assigned_to.search(regex) == -1)
                        return false;
                }
                if(filters.id_project != undefined){
                    if(filters.id_project.indexOf(listItem.id_project) === -1)
                        return false;
                }
                if(filters.id_company != undefined && filters.id_company != 'all') {
                    if(listItem.id_company != filters.id_company)
                        return false;
                }
                if(filters.id_manager != undefined && filters.id_manager != 'all') {
                    if(listItem.id_manager != filters.id_manager)
                        return false;
                }
                if(filters.id_assigned_to != undefined && filters.id_assigned_to != 'all') {
                    if(listItem.id_assigned_to != filters.id_assigned_to && listItem.id_assigned_to !== undefined)
                        return false;
                }
                if(filters.step != undefined) {
                    if(parseInt(listItem.step) < filters.step)
                        return false;
                }
                return true;
            });
        }
        return list;
    };
})
    .filter('sandboxFilter', function($filter){
    return function(list, filters){
        if(filters){
            return $filter("filter")(list, function(listItem){
                if(filters.id != undefined){
                    if(filters.id.indexOf(listItem.id) === -1)
                        return false;
                }
                return true;
            });
        }
        return list;
    };
})
    .filter('backlogFilter', function($filter){
    return function(list, filters){
        if(filters){
            return $filter("filter")(list, function(listItem){
                if(filters.id != undefined){
                    if(filters.id.indexOf(listItem.id_project) === -1)
                        return false;
                }
                if(filters.step != undefined){
                    if(listItem.step !== filters.step)
                        return false;
                }
                if(filters.id_assigned_to != undefined && filters.id_assigned_to != 'all') {
                    if(listItem.id_assigned_to != filters.id_assigned_to && listItem.id_assigned_to !== undefined)
                        return false;
                }
                return true;
            });
        }
        return list;
    };
})
    .filter('sprintFilter', function($filter){
    return function(list, filters){
        if(filters){
            return $filter("filter")(list, function(listItem){
                if(filters.id != undefined){
                    if(filters.id.indexOf(listItem.id) === -1)
                        return false;
                }
                if(filters.sprintId != undefined){
                    if(listItem.id_sprint !== filters.sprintId)
                        return false;
                }
                return true;
            });
        }
        return list;
    };
})
    .filter('cardmodalFilter', function($filter){
    return function(list, filters){
        if(filters){
            return $filter("filter")(list, function(listItem){
                if(parseInt(listItem.id_sprint) !== 0)
                    return false;
                if(parseInt(listItem.step) !== 1)
                    return false;
                if(listItem.completed === 'Y')
                    return false;
                return true;
            });
        }
        return list;
    };
});
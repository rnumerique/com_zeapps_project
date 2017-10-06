app.filter("planningFilter", ["$filter", function($filter){
	return function(list, filters){
		if(filters){
			return $filter("filter")(list, function(listItem){
				if(filters.id_project){
                    return listItem.id_project == filters.id_project;
				}
				if(filters.id_company) {
					return listItem.id_company == filters.id_company;
				}
				if(filters.id_manager) {
					return listItem.id_manager == filters.id_manager;
				}
				if(filters.id_assigned_to) {
					return listItem.id_assigned_to == filters.id_assigned_to;
				}
				return true;
			});
		}
		return list;
	};
}])
	.filter("journalFilter", ["$filter", function($filter){
		return function(list, filters){
			if(filters){
				return $filter("filter")(list, function(listItem){
                    if(filters.id_project){
                        return listItem.id_project == filters.id_project;
                    }
                    if(filters.id_company) {
                        return listItem.id_company == filters.id_company;
                    }
                    if(filters.id_manager) {
                        return listItem.id_manager == filters.id_manager;
                    }
                    if(filters.id) {
                        return listItem.id_user == filters.id;
                    }
                    return true;
				});
			}
			return list;
		};
	}])
	.filter("cardmodalFilter", ["$filter", function($filter){
		return function(list, filters){
			if(filters){
				return $filter("filter")(list, function(listItem){
					if(parseInt(listItem.id_sprint) !== 0)
						return false;
					if(parseInt(listItem.step) !== 1)
						return false;
					if(listItem.step !== "4")
						return false;
					return true;
				});
			}
			return list;
		};
	}])
	.filter("projectFilter", ["$filter", function($filter){
		return function(list, filters){
			if(filters){
				return $filter("filter")(list, function(listItem){
					if(filters.search != undefined && filters.search != "") {
						var regex = new RegExp(filters.search, "i");
						if(listItem.name_company.search(regex) == -1
							&& listItem.name_contact.search(regex) == -1
							&& listItem.title.search(regex) == -1
							&& listItem.name_manager.search(regex) == -1 )
							return false;
					}
					if(filters.id_status != undefined){
						if(listItem.id_status != filters.id_status)
							return false;
					}
					return true;
				});
			}
			return list;
		};


	}]);
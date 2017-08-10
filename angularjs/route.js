app.config(["$routeProvider",
	function ($routeProvider) {
		$routeProvider
		// PLANNING
			.when("/ng/com_zeapps_project/planning", {
				templateUrl: "/com_zeapps_project/planning/",
				controller: "ComZeappsPlanningViewCtrl"
			})

		// PROJECT
			.when("/ng/com_zeapps_project/project/create/", {
				templateUrl: "/com_zeapps_project/project/form/",
				controller: "ComZeappsProjectFormCtrl"
			})

			.when("/ng/com_zeapps_project/project/edit/:id", {
				templateUrl: "/com_zeapps_project/project/form/",
				controller: "ComZeappsProjectFormCtrl"
			})

			.when("/ng/com_zeapps_project/project/", {
				templateUrl: "/com_zeapps_project/project/overview",
				controller: "ComZeappsProjectOverviewCtrl"
			})

			.when("/ng/com_zeapps_project/project/:id", {
				templateUrl: "/com_zeapps_project/project/",
				controller: "ComZeappsProjectViewCtrl"
			})

			.when("/ng/com_zeapps_project/project/card/create/:type/:id_project", {
				templateUrl: "/com_zeapps_project/card/form/",
				controller: "ComZeappsProjectCardFormCtrl"
			})

			.when("/ng/com_zeapps_project/project/card/edit/:type/:id", {
				templateUrl: "/com_zeapps_project/card/form/",
				controller: "ComZeappsProjectCardFormCtrl"
			})

			.when("/ng/com_zeapps_project/project/categories/create/:id_project", {
				templateUrl: "/com_zeapps_project/category/form/",
				controller: "ComZeappsProjectFormCategoriesCtrl"
			})

			.when("/ng/com_zeapps_project/project/categories/edit/:id", {
				templateUrl: "/com_zeapps_project/category/form/",
				controller: "ComZeappsProjectFormCategoriesCtrl"
			})

			.when("/ng/com_zeapps_project/project/timer/new", {
				templateUrl: "/com_zeapps_project/timer/form/",
				controller: "ComZeappsProjectFormTimersCtrl"
			})

			.when("/ng/com_zeapps_project/project/timer/edit/:id", {
				templateUrl: "/com_zeapps_project/timer/form/",
				controller: "ComZeappsProjectFormTimersCtrl"
			})

		// STATUSES
			.when("/ng/com_zeapps/status", {
				templateUrl: "/com_zeapps_project/status/config/",
				controller: "ComZeappsProjectStatusConfigCtrl"
			})

		// MY WORK
			.when("/ng/com_zeapps_project/mywork", {
				templateUrl: "/com_zeapps_project/mywork/view/",
				controller: "ComZeappsProjectMyWorkCtrl"
			})

		// JOURNAL
			.when("/ng/com_zeapps_project/journal", {
				templateUrl: "/com_zeapps_project/journal/view/",
				controller: "ComZeappsProjectJournalCtrl"
			})

		// TO-DOs
			.when("/ng/com_zeapps_project/todos", {
				templateUrl: "/com_zeapps_project/todos/view/",
				controller: "ComZeappsProjectTodosCtrl"
			})

		// ARCHIVES
            .when("/ng/com_zeapps_project/archives/", {
                templateUrl: "/com_zeapps_project/project/archives/",
                controller: "ComZeappsProjectArchivesCtrl"
            })
		;
	}]);
app.config(['$routeProvider',
    function ($routeProvider) {
        $routeProvider
            // BACKLOG
            .when('/ng/com_zeapps_project/backlog', {
                templateUrl: '/com_zeapps_project/backlog/view',
                controller: 'ComZeappsBacklogViewCtrl'
            })

            .when('/ng/com_zeapps_project/backlog/:id_project', {
                templateUrl: '/com_zeapps_project/backlog/view',
                controller: 'ComZeappsBacklogViewCtrl'
            })

            .when('/ng/com_zeapps_project/backlog/edit/:id', {
                templateUrl: '/com_zeapps_project/backlog/form',
                controller: 'ComZeappsProjectFormBacklogCtrl'
            })

            .when('/ng/com_zeapps_project/backlog/new/:id_project', {
                templateUrl: '/com_zeapps_project/backlog/form/',
                controller: 'ComZeappsProjectFormBacklogCtrl'
            })

            // SPRINT
            .when('/ng/com_zeapps_project/sprint', {
                templateUrl: '/com_zeapps_project/sprint/view',
                controller: 'ComZeappsSprintViewCtrl'
            })

            .when('/ng/com_zeapps_project/sprint/create', {
                templateUrl: '/com_zeapps_project/sprint/form',
                controller: 'ComZeappsSprintFormCtrl'
            })

            .when('/ng/com_zeapps_project/sprint/create/:id_project', {
                templateUrl: '/com_zeapps_project/sprint/form',
                controller: 'ComZeappsSprintFormCtrl'
            })

            .when('/ng/com_zeapps_project/sprint/create/card/:id_project/:id_sprint/', {
                templateUrl: '/com_zeapps_project/sprint/formCard',
                controller: 'ComZeappsSprintFormCardCtrl'
            })

            .when('/ng/com_zeapps_project/sprint/edit/card/:id_project/:id_sprint/:id', {
                templateUrl: '/com_zeapps_project/sprint/formCard',
                controller: 'ComZeappsSprintFormCardCtrl'
            })

            .when('/ng/com_zeapps_project/sprint/edit/:id', {
                templateUrl: '/com_zeapps_project/sprint/form',
                controller: 'ComZeappsSprintFormCtrl'
            })

            .when('/ng/com_zeapps_project/sprint/:id_project', {
                templateUrl: '/com_zeapps_project/sprint/detail',
                controller: 'ComZeappsSprintDetailCtrl'
            })

            .when('/ng/com_zeapps_project/sprint/:id_project/:id', {
                templateUrl: '/com_zeapps_project/sprint/detail',
                controller: 'ComZeappsSprintDetailCtrl'
            })

            // SANDBOX
            .when('/ng/com_zeapps_project/sandbox', {
                templateUrl: '/com_zeapps_project/sandbox/',
                controller: 'ComZeappsSandboxViewCtrl'
            })

            .when('/ng/com_zeapps_project/sandbox/:id', {
                templateUrl: '/com_zeapps_project/sandbox/',
                controller: 'ComZeappsSandboxViewCtrl'
            })

            .when('/ng/com_zeapps_project/sandbox/new', {
                templateUrl: '/com_zeapps_project/sandbox/form/',
                controller: 'ComZeappsProjectFormSandboxCtrl'
            })

            .when('/ng/com_zeapps_project/sandbox/new/:id_project', {
                templateUrl: '/com_zeapps_project/sandbox/form/',
                controller: 'ComZeappsProjectFormSandboxCtrl'
            })

            .when('/ng/com_zeapps_project/sandbox/edit/:id', {
                templateUrl: '/com_zeapps_project/sandbox/form/',
                controller: 'ComZeappsProjectFormSandboxCtrl'
            })

            // PLANNING
            .when('/ng/com_zeapps_project/planning', {
                templateUrl: '/com_zeapps_project/planning/',
                controller: 'ComZeappsPlanningViewCtrl'
            })

            // PROJECT
            .when('/ng/com_zeapps_project/project/create/', {
                templateUrl: '/com_zeapps_project/project/form/',
                controller: 'ComZeappsProjectFormCtrl'
            })

            .when('/ng/com_zeapps_project/project/create/:id_parent', {
                templateUrl: '/com_zeapps_project/project/form/',
                controller: 'ComZeappsProjectFormCtrl'
            })

            .when('/ng/com_zeapps_project/project/edit/:id', {
                templateUrl: '/com_zeapps_project/project/form/',
                controller: 'ComZeappsProjectFormCtrl'
            })

            .when('/ng/com_zeapps_project/project/', {
                templateUrl: '/com_zeapps_project/project/',
                controller: 'ComZeappsProjectViewCtrl'
            })

            .when('/ng/com_zeapps_project/project/:id', {
                templateUrl: '/com_zeapps_project/project/',
                controller: 'ComZeappsProjectViewCtrl'
            })

            .when('/ng/com_zeapps_project/project/card/create/:type/:id_project', {
                templateUrl: '/com_zeapps_project/project/form_card/',
                controller: 'ComZeappsProjectFormCardCtrl'
            })

            .when('/ng/com_zeapps_project/project/card/edit/:type/:id', {
                templateUrl: '/com_zeapps_project/project/form_card/',
                controller: 'ComZeappsProjectFormCardCtrl'
            })

            .when('/ng/com_zeapps_project/project/categories/create/:id_project', {
                templateUrl: '/com_zeapps_project/category/form/',
                controller: 'ComZeappsProjectFormCategoriesCtrl'
            })

            .when('/ng/com_zeapps_project/project/categories/edit/:id', {
                templateUrl: '/com_zeapps_project/category/form/',
                controller: 'ComZeappsProjectFormCategoriesCtrl'
            })
        ;
    }]);
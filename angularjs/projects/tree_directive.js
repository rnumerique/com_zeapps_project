app.directive('projectTree',
    function(){
        return{
            restrict: 'E',
            replace: true,
            scope: {
                tree: '=',
                activeBranch: '='
            },
            template:   '<ul class="tree list-unstyled">' +
                            '<project-branch ng-repeat="branch in tree | orderBy:\'title\'" data-branch="branch" data-active-branch="activeBranch"></project-branch>' +
                        '</ul>'
        }
})

.directive('projectBranch', function($compile){
    return{
        restrict: 'E',
        replace: true,
        scope: {
            branch: '=',
            activeBranch: '='
        },
        template:   "<li class='branch' ng-class='{\"open\": isOpen()}'>" +
                        "<span class='branch-name'>" +
                            "<i class='fa fa-lg fa-caret-right pull-left' aria-hidden='true' ng-click='toggleBranch()' ng-hide='isOpen() || !hasBranches()'></i>" +
                            "<i class='fa fa-lg fa-caret-down pull-left' aria-hidden='true' ng-click='toggleBranch()' ng-show='isOpen() && hasBranches()'></i>" +
                            "<span class='branch-wrap pull-right' ng-class='{\"selected\": isCurrent(branch.id)}' ng-click='openBranch()'>" +
                                "<span class='fa fa-tasks' aria-hidden='true'></span>" +
                                " {{ branch.title }}" +
                            "</span>" +
                        "</span>" +
                    "</li>",
        link: function(scope, element){
            if(angular.isArray(scope.branch.branches)){
                $compile("<project-tree data-tree='branch.branches' data-active-branch='activeBranch'></project-tree>")(scope, function(cloned){
                    element.append(cloned);
                });     
            }


            scope.toggleBranch = function(){
                scope.branch.open = !scope.branch.open;
            };

            scope.openBranch = function(){
                scope.activeBranch.data = scope.branch;
                scope.branch.open = true;
            };

            scope.hasBranches = function(){
                return angular.isArray(scope.branch.branches);
            };

            scope.isOpen = function(){
                return scope.branch.open;
            };

            scope.isCurrent = function(id){
                return id == scope.activeBranch.data.id;
            };
        }
    }
});
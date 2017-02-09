<div class="modal-header">
    <h3 class="modal-title">{{titre}}</h3>
</div>


<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-striped table-condensed table-responsive" ng-show="projects.length">
                <thead>
                <tr>
                    <th i8n="Titre"></th>
                    <th i8n="Demandeur"></th>
                    <th i8n="Manager"></th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="project in projects">
                    <td><a href="#" ng-click="loadProject(project.id)" ng-bind-html="project.title"></a></td>
                    <td><a href="#" ng-click="loadProject(project.id)">{{project.name_company}}</a></td>
                    <td><a href="#" ng-click="loadProject(project.id)">{{project.name_manager}}</a></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button class="btn btn-danger" type="button" ng-click="cancel()" i8n="Annuler"></button>
</div>
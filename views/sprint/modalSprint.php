<div class="modal-header">
    <h3 class="modal-title">{{titre}}</h3>
</div>


<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-striped table-condensed table-responsive" ng-show="sprints.length">
                <thead>
                <tr>
                    <th i8n="Titre"></th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="sprint in sprints">
                    <td><a href="#" ng-click="loadSprint(sprint.id)">{{ sprint.title }}</a></td>
                </tr>
                <tr ng-if="!sprints">
                    Ce projet n'a pas de sprints.
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button class="btn btn-danger" type="button" ng-click="cancel()" i8n="Annuler"></button>
</div>
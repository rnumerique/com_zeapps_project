<div class="modal-header">
    <h3 class="modal-title">{{titre}}</h3>
</div>


<div class="modal-body">
    <div class="row">
        <div class="col-md-12 text-right">
            <button type="button" class="btn btn-xs btn-success" ng-click="showForm = !showForm">
                <i class="fa fa-fw fa-plus"></i> Sprint
            </button>
        </div>
    </div>
    <div class="row" ng-if="!showForm">
        <div class="col-md-12">
            <table class="table table-bordered table-striped table-condensed table-responsive" ng-if="sprints.length > 0">
                <thead>
                <tr>
                    <th i8n="Titre"></th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="sprint in sprints">
                    <td><a href="#" ng-click="loadSprint(sprint)">{{ sprint.title }}</a></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <form ng-if="showForm" name="formCtrl.sprintForm" id="sprintForm">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Intitulé</label>
                    <input class="form-control" type='text' ng-model="form.title">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Date de début</label>
                    <input class="form-control" type='date' ng-model="form.start_date" ng-required="true" ng-change="updateDueDate()">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Date limite</label>
                    <input class="form-control" type='date' ng-model="form.due_date" ng-required="true">
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-danger" ng-click="cancel()" i8n="Annuler"></button>
    <button type="sumbit" class="btn btn-success" ng-click="addSprint()" ng-if="showForm" form="sprintForm" ng-disabled='formCtrl.sprintForm.$invalid'>Creer</button>
</div>
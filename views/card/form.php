<div ng-controller="ComZeappsProjectCardFormCtrl">
    <form>
        <div class="well">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Intitulé</label>
                        <input class="form-control" type='text' ng-model="form.title">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Projet</label>
                        <div class="input-group">
                            <input type="text" ng-model="form.title_project" class="form-control" disabled>

                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" ng-click="removeProject()"
                                        ng-show="form.id_project != 0 && form.id_project != undefined">x
                                </button>
                                <button class="btn btn-default" type="button" ng-click="loadProject()">...</button>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4" ng-if="type == 'card'">
                    <div class="form-group">
                        <label>Assigné à</label>
                        <div class="input-group">
                            <input type="text" ng-model="form.name_assigned_to" class="form-control" disabled>

                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" ng-click="removeAssigned()"
                                        ng-show="form.id_assigned_to != 0 && form.id_assigned_to != undefined">x
                                </button>
                                <button class="btn btn-default" type="button" ng-click="loadAssigned()">...</button>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4" ng-if="type == 'card' && form.id_project">
                    <div class="form-group">
                        <label>Sprint</label>
                        <div class="input-group">
                            <input type="text" ng-model="form.title_sprint" class="form-control" disabled>

                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" ng-click="removeSprint()"
                                        ng-show="form.id_sprint != 0 && form.id_sprint != undefined">x
                                </button>
                                <button class="btn btn-default" type="button" ng-click="loadSprint()">...</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Date <span ng-if="type == 'card'">de fin</span></label>
                        <input class="form-control" type='date' ng-model="form.due_date">
                    </div>
                </div>
                <div class="col-md-6" ng-if="type == 'card'">
                    <div class="form-group">
                        <label>Temps estimé (h)</label>
                        <input class="form-control" type='number' ng-model="form.estimated_time">
                    </div>
                </div>
            </div>

            <div class="row" ng-if="type == 'card'">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" ng-model="form.description" rows="15"></textarea>
                    </div>
                </div>
            </div>

            <form-buttons></form-buttons>
        </div>
    </form>
</div>
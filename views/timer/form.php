<div id="breadcrumb">
    Gestion des projets
</div>

<div id="content">
    <form>
        <div class="well">
            <div class="row">
                <div class="col-md-5">
                    <label>Début</label>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="number" class="form-control" ng-model="start.h">
                                    <div class="input-group-addon">H</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="number" class="form-control" ng-model="start.m">
                                    <div class="input-group-addon">m</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="number" class="form-control" ng-model="start.d">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select class="form-control" ng-model="start.M">
                                    <option value="0">Janvier</option>
                                    <option value="1">Février</option>
                                    <option value="2">Mars</option>
                                    <option value="3">Avril</option>
                                    <option value="4">Mai</option>
                                    <option value="5">Juin</option>
                                    <option value="6">Juillet</option>
                                    <option value="7">Août</option>
                                    <option value="8">Septembre</option>
                                    <option value="9">Octobre</option>
                                    <option value="10">Novembre</option>
                                    <option value="11">Décembre</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="number" class="form-control" ng-model="start.y">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 col-md-offset-2">
                    <label>Fin</label>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="number" class="form-control" ng-model="end.h">
                                    <div class="input-group-addon">H</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="number" class="form-control" ng-model="end.m">
                                    <div class="input-group-addon">m</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="number" class="form-control" ng-model="end.d">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select class="form-control" ng-model="end.M">
                                    <option value="0">Janvier</option>
                                    <option value="1">Février</option>
                                    <option value="2">Mars</option>
                                    <option value="3">Avril</option>
                                    <option value="4">Mai</option>
                                    <option value="5">Juin</option>
                                    <option value="6">Juillet</option>
                                    <option value="7">Août</option>
                                    <option value="8">Septembre</option>
                                    <option value="9">Octobre</option>
                                    <option value="10">Novembre</option>
                                    <option value="11">Décembre</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="number" class="form-control" ng-model="end.y">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Commentaires</label>
                        <textarea class="form-control" ng-model="form.comment" rows="3"></textarea>
                    </div>
                </div>

                <form-buttons></form-buttons>
            </div>
        </div>
    </form>
</div>
<div class="modal-header">
    <h3 class="modal-title">{{titre}}</h3>
</div>


<div class="modal-body table">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-condensed table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>label</th>
                    <th>description</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="card in cards | orderBy:'id'" ng-click="loadCard(card)">
                    <td>{{card.id}}</td>
                    <td>{{card.title}}</td>
                    <td>{{card.description}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button class="btn btn-danger" type="button" ng-click="cancel()" i8n="Annuler"></button>
</div>
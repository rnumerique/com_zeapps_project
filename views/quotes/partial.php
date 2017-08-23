<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="text-right">
    <ze-btn fa="link" color="success" hint="Devis" always-on="true" ng-click="linkQuote()"></ze-btn>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped table-condensed table-responsive" ng-show="quotes.length">
            <thead>
            <tr>
                <th>#</th>
                <th>Libelle</th>
                <th>Destinataire</th>
                <th>Total HT (€)</th>
                <th>Total TTC (€)</th>
                <th>Date de création</th>
                <th>Date limite</th>
                <th>Responsable</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="quote in quotes">
                <td><a href="/ng/com_zeapps_crm/quote/{{quote.id_quote}}">{{quote.numerotation}}</a></td>
                <td><a href="/ng/com_zeapps_crm/quote/{{quote.id_quote}}">{{quote.libelle}}</a></td>
                <td>
                    <a href="/ng/com_zeapps_crm/quote/{{quote.id_quote}}">
                        {{quote.name_company}}
                        <span ng-if="quote.name_company && quote.name_contact">-</span>
                        {{quote.name_contact}}
                    </a>
                </td>
                <td><a href="/ng/com_zeapps_crm/quote/{{quote.id_quote}}">{{quote.total_ht}}</a></td>
                <td><a href="/ng/com_zeapps_crm/quote/{{quote.id_quote}}">{{quote.total_ttc}}</a></td>
                <td><a href="/ng/com_zeapps_crm/quote/{{quote.id_quote}}">{{quote.date_creation | date:'dd/MM/yyyy'}}</a></td>
                <td><a href="/ng/com_zeapps_crm/quote/{{quote.id_quote}}">{{quote.date_limit | date:'dd/MM/yyyy'}}</a></td>
                <td><a href="/ng/com_zeapps_crm/quote/{{quote.id_quote}}">{{quote.name_user}}</a></td>
                <td><a href="/ng/com_zeapps_crm/quote/{{quote.id_quote}}">{{quote.finalized === '1' ? 'cloturé' : ''}}</a></td>
                <td class="text-right">
                    <ze-btn fa="trash" color="danger" hint="Supprimer" direction="left" ng-click="unlinkQuote(quote)" ze-confirmation="Souhaitez-vous supprimer la liaison entre ce document et ce projet ?"></ze-btn>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="breadcrumb">
    Journal
</div>

<div id="content">
    <ze-filters class="pull-right" data-model="filter_model" data-filters="filters" data-update="applyFilters"></ze-filters>

    <div class="row">
        <div class="col-md-12">
            <calendar model="calendarModel"></calendar>
        </div>
    </div>
</div>


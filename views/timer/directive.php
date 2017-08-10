<div class='timer-hook text-center' ng-show='currentTimer'>
    <div class='timer-title' title='{{currentTimer.label}}'>
        {{currentTimer.label}}
    </div>
    <span class='timer-controls'>
        <i class='fa fa-fw fa-stop text-danger' ng-click='stop()' ng-if='project_timer'></i>
    </span>
    <span>
        {{currentTimer.interval}}
    </span>
</div>
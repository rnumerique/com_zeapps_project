<div class='timer-hook text-center' ng-show='currentTask'>
    <div class='timer-title' title='{{currentTask.label}}'>
        {{currentTask.label}}
    </div>
    <span class='timer-controls'>
        <i class='fa fa-fw fa-play text-success' ng-click='play()' ng-if='!project_timer'></i>
        <i class='fa fa-fw fa-stop text-danger' ng-click='stop()' ng-if='project_timer'></i>
    </span>
    <span>
        {{currentTask.interval}}
    </span>
</div>
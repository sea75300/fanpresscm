<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row g-0 gap-2 mb-3">
    <div class="col-12 col-xl">
        <?php $theView->button('resetDashboardSettingsPos')
            ->setText('USERS_META_RESET_DASHBOARD_POS')
            ->setIcon('undo')
            ->setClass('w-100 btn-sm')
            ->setData(['dashboard-reset-action' => 'dashboardpos']); ?>

    </div>
    <div class="col-12 col-xl">
        <?php $theView->button('resetDashboardSettingsDisabled')
            ->setText('USERS_META_RESET_DASHBOARD_DISABLED')
            ->setIcon('toggle-on')
            ->setClass('w-100 btn-sm')
            ->setData(['dashboard-reset-action' => 'dashboard_containers_disabled']); ?>
    </div>
</div>

<div id="fpcm-ui-container-disabled-list" class="list-group">
    <div class="list-group-item bg-secondary text-white">
        <?php $theView->write('DASHBOARD_MANAGE_CONTAINER_ENABLE'); ?>
    </div>
</div>
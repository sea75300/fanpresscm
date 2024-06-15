<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row g-0 mb-3">
    <div class="col">
        <?php $theView->button('resetDashboardSettings')
            ->setText('USERS_META_RESET_DASHBOARD')
            ->setIcon('undo')
            ->setClass('shadow w-100'); ?>
    </div>
</div>

<div id="fpcm-ui-container-disabled-list" class="list-group">
    <div class="list-group-item bg-secondary text-white">
        <?php $theView->write('DASHBOARD_MANAGE_CONTAINER_ENABLE'); ?>    
    </div>
</div>
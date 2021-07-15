<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row g-0" id="fpcm-dashboard-containers">
    <div class="col-12">
        <div class="row align-self-center fpcm-ui-inline-loader fpcm ui-background-white-50p ui-blurring">
            <div class="col-12 fpcm-ui-center align-self-center">
                <?php $theView->icon('spinner fa-inverse')->setSpinner('pulse')->setStack('circle')->setSize('2x'); ?>
                <span class="ps-2"><?php $theView->write('DASHBOARD_LOADING'); ?></span>
            </div>
        </div>
    </div>
</div>
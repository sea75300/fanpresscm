<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php if (!empty($topDescription)) : ?>
<div class="row g-0 mt-2 mb-3">
    <div class="col-12">
        <fieldset class="fpcm-ui-margin-none-left fpcm-ui-margin-none-right fpcm-ui-margin-md-top">
            <legend><?php $theView->write('GLOBAL_INFO'); ?></legend>
            <?php $theView->write($topDescription); ?>
        </fieldset>
    </div>
</div>
<?php endif; ?>

<div id="fpcm-dataview-<?php print $dataViewId; ?>-spinner" class="row g-0 align-self-center fpcm-ui-inline-loader fpcm ui-background-white-50p">
    <div class="col-12 fpcm-ui-center align-self-center">
        <?php $theView->icon('spinner fa-inverse')->setSpinner('pulse')->setStack('circle')->setSize('2x'); ?>
    </div>
</div>  

<div id="fpcm-dataview-<?php print $dataViewId; ?>"></div>
<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php if (!empty($topDescription)) : ?>
<div class="row g-0 pb-2 fpcm ui-background-white-50p">
    <div class="col-12">
        <fieldset>
            <legend><?php $theView->write('GLOBAL_INFO'); ?></legend>
            <p class="mx-2"><?php $theView->write($topDescription); ?></p>
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
<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <h3><?php $theView->write('MODULE_NKORGEXAMPLE_HEADLINE'); ?></h3>
    <?php foreach ($options as $key => $value) : ?>
    <div class="row no-gutters fpcm-ui-padding-md-tb">
        <div class="col-6 align-self-center"><?php $theView->escape($key); ?></div>
        <div class="col-6"><?php $theView->textInput("config[{$key}]")->setValue($value); ?></div>
    </div>
    <?php endforeach; ?>
</div>
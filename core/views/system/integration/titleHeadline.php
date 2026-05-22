<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row g-0">
    <div class="col">
    <?php $theView->textInput('titleHl[delimited]')
        ->setValue('&bull;')
        ->setText('IMPORT_DELIMITER')
        ->setLabelTypeFloat(); ?>
    </div>
    <div class="col">&nbsp;</div>
    <div class="col">&nbsp;</div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">
            <?php $theView->write('INTEGRATION_TEXT_ARTICLE_TITLE') ?>
        </h5>
        <pre class="mb-0">&lt;?php $api->showTitle('<span id="functionParamstitleHl1">&amp;bull;</span>'<span id="functionParamstitleHl2"></span>); ?&gt;</pre>
    </div>
</div>
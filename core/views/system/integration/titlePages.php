<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row g-0">
    <div class="col">
    <?php $theView->textInput('titlePages[delimited]')
        ->setValue('&bull; Page')
        ->setText('IMPORT_DELIMITER')
        ->setLabelTypeFloat(); ?>
    </div>
    <div class="col">&nbsp;</div>
    <div class="col">&nbsp;</div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">
            <?php $theView->write('INTEGRATION_TEXT_PAGE_TITLE') ?>
        </h5>
        <pre class="mb-0">&lt;?php $api->showPageNumber('<span id="functionParamstitlePages1">&amp;bull; Page</span>'<span id="functionParamstitlePages2"></span>); ?&gt;</pre>
    </div>
</div>
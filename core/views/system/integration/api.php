<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row g-0 gap-2">
    <div class="col">
    <?php $theView->textInput('api[path]')
        ->setValue($basedir)
        ->setText('INTEGRATION_TEXT_API_MAINFILE')
        ->setLabelTypeFloat(); ?>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title" id="apiTitleParams">
            <?php $theView->write('INTEGRATION_TEXT_API', [$basedir], true); ?>
        </h5>
        <pre class="mb-0">
&lt;?php include_once "fanpress/fpcmapi.php"; ?&gt;
&lt;?php $api = new fpcmAPI(); ?&gt;</pre>
    </div>
</div>
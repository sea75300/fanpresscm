<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php /* @var $file \fpcm\model\files\templatefile */ ?>
<div class="row">
    <div class="my-2">
        <?php require_once $theView->getIncludePath($toolbarTpl); ?>
    </div>
</div>

<div class="row my-2">
    <div class="col">
        <div id="fpcm-id-content-ace"><?php print $theView->escapeVal($file->getContent(), ENT_QUOTES); ?></div>
        <?php $theView->textarea('templatecode')->setValue($file->getContent(), ENT_QUOTES)->setClass('d-none'); ?>
    </div>
</div>

<div class="row my-2">
    <div class="col">
        <?php $theView->textInput('css-file')
            ->setValue('https://')
            ->setText('SYSTEM_OPTIONS_STYLESHEET')
            ->setLabelTypeFloat();
        ?>
    </div>
</div>
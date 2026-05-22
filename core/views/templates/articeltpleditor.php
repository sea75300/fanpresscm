<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php /* @var $file \fpcm\model\files\templatefile */ ?>
<?php if (!$file->isWritable()) : ?>
<div class="row modal-body p-2">
    <?php $theView->alert('danger')->setText('TEMPLATE_NOT_WRITABLE')->setIcon('lock')->setClass('d-flex align-items-center justify-content-center mb-0')->setSize('2x'); ?>
</div>
<?php endif; ?>

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
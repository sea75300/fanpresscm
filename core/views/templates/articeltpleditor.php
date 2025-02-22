<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php /* @var $file \fpcm\model\files\templatefile */ ?>
<?php if (!$file->isWritable()) : ?>
<div class="row modal-body p-2">
    <?php $theView->alert('danger')->setText('TEMPLATE_NOT_WRITABLE')->setIcon('lock')->setClass('d-flex align-items-center justify-content-center mb-0')->setSize('2x'); ?>
</div>
<?php endif; ?>

<div class="position-absolute w-100 h-100">
<?php $theView->textarea('templatecode')->setValue($file->getContent(), ENT_QUOTES); ?>
</div>
<?php $theView->saveButton('saveTemplate')->setClass('fpcm-ui-hidden'); ?>    
<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="position-absolute w-100 h-100">
<?php $theView->textarea('templatecode')->setValue($file->getContent(), ENT_QUOTES); ?>
</div>
<?php $theView->saveButton('saveTemplate')->setClass('fpcm-ui-hidden'); ?>    
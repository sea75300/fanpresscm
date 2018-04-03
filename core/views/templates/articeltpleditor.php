<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="fpcm-ui-position-absolute fpcm-ui-full-width fpcm-ui-full-height fpcm-ui-background-white-100">
<?php $theView->textarea('templatecode')->setValue($file->getContent(), ENT_QUOTES); ?>
<?php $theView->saveButton('saveTemplate')->setClass('fpcm-ui-hidden'); ?>    
</div>
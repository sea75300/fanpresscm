<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php $theView->textarea('templatecode')->setValue($file->getContent(), ENT_QUOTES); ?>
<?php $theView->saveButton('saveTemplate')->setClass('fpcm-ui-hidden'); ?>
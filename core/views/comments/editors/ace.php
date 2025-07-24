<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row">
   <?php require_once $theView->getIncludePath($toolbarTpl); ?>
</div>

<div class="row my-2">
    <div class="col">
        <div id="fpcm-id-content-ace"><?php print $theView->escapeVal($comment->getText(), ENT_QUOTES); ?></div>
        <?php $theView->textarea('comment[text]')->setClass('d-none')->setValue($comment->getText(), ENT_QUOTES); ?>        
    </div>
</div>
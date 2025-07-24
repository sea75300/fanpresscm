<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php //include $theView->getIncludePath('articles/editors/html_dialogs.php'); ?>
<div class="row">
    <?php require_once $theView->getIncludePath($toolbarTpl); ?>
</div>

<div class="row my-2">
    <div class="col">
        <div id="fpcm-id-content-ace"><?php print $theView->escapeVal($article->getContent(), ENT_QUOTES); ?></div>
        <?php $theView->textarea('article[content]')->setClass('d-none')->setValue($article->getContent(), ENT_QUOTES); ?>
    </div>
</div>
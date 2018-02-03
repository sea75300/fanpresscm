<div class="fpcm-content-wrapper">
    <h1>
        <span class="fa fa-pencil"></span> <?php $theView->lang->write('HL_ARTICLE_EDIT'); ?>
    </h1>
    
    <div class="fpcm-ui-dialog-layer fpcm-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-comments"></div>
    <?php include $theView->getIncludePath('articles/articleeditor.php'); ?>
</div>
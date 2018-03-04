<table class="fpcm-ui-table">   
    <?php if ($editorMode) : ?>
    <tr>
        <td>
            <div class="row fpcm-ui-editor-metabox fpcm-ui-padding-md-tb">
                <div class="col-sm-12 col-md-6 fpcm-ui-font-small">
                    <?php include $theView->getIncludePath('articles/times.php'); ?>
                </div>
                <div class="col-sm-12 col-md-6 fpcm-ui-align-right">
                    <?php include $theView->getIncludePath('articles/metainfo.php'); ?>
                </div>
            </div>
        </td>
    </tr>    
    <?php endif; ?>
    <tr>
        <td>
            <?php \fpcm\view\helper::textInput('article[title]', 'fpcm-full-width', $article->getTitle()); ?>
        </td>
    </tr>
    <tr>
        <td class="fpcm-ui-editor-categories">
            <?php $fieldname = 'article[categories][]'; ?>
            <?php include $theView->getIncludePath('articles/categories.php'); ?>
        </td>
    </tr>
     <tr>
        <td><?php $theView->textarea('article[content]')->setClass('fpcm-full-width')->setValue(stripslashes($article->getContent())); ?></td>
    </tr>
</table>
<table class="fpcm-ui-table">
    <?php if ($editorMode) : ?>
    <tr>
        <td>
            <div class="fpcm-ui-editor-metabox">
                <?php include $theView->getIncludePath('articles/times.php'); ?>
                <?php include $theView->getIncludePath('articles/metainfo.php'); ?>
                <div class="fpcm-clear"></div>
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
            <?php include $theView->getIncludePath('articles/categories.php'); ?>
        </td>
    </tr>
     <tr>
        <td>
            <?php \fpcm\view\helper::textArea('article[content]', 'fpcm-full-width', stripslashes($article->getContent()), false, false); ?>
        </td>
    </tr>
</table>
<table class="fpcm-ui-table">
    <?php if ($editorMode) : ?>
    <tr>
        <td>
            <div class="fpcm-ui-editor-metabox">
                <?php include dirname(__DIR__).'/times.php'; ?>
                <?php include dirname(__DIR__).'/metainfo.php'; ?>
                <div class="fpcm-clear"></div>
            </div>
        </td>
    </tr>    
    <?php endif; ?>    
    <tr>
        <td>
            <?php \fpcm\model\view\helper::textInput('article[title]', 'fpcm-full-width', $article->getTitle()); ?>
        </td>
    </tr>
    <tr>
        <td class="fpcm-ui-editor-categories">
            <?php include dirname(__DIR__).'/categories.php'; ?>
        </td>
    </tr>
     <tr>
        <td>
            <?php \fpcm\model\view\helper::textArea('article[content]', 'fpcm-full-width', stripslashes($article->getContent()), false, false); ?>
        </td>
    </tr>
</table>
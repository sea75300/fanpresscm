<div class="fpcm-tabs-general" id="fpcm-editor-tabs">
    <ul>
        <?php if ($isRevision) : ?>
        <li><a href="#tabs-article"><?php $FPCM_LANG->write('EDITOR_STATUS_REVISION'); ?></a></li>
        <?php else : ?>
        <li id="fpcm-editor-tabs-editorregister"><a href="#tabs-article"><?php $FPCM_LANG->write('ARTICLES_EDITOR'); ?></a></li>
        <?php endif; ?>
        <?php if ($showComments && !$isRevision) : ?>
        <li><a href="<?php print $FPCM_BASELINK.\fpcm\classes\tools::getControllerLink('ajax/editor/editorlist', ['id' => $article->getId(), 'view' => 'comments']); ?>">
            <?php $FPCM_LANG->write('HL_ARTICLE_EDIT_COMMENTS', [ 'count' => $commentCount ]); ?>
        </a></li>
        <?php endif; ?>
        <?php if ($showRevisions) : ?>
        <li><a href="<?php print $FPCM_BASELINK.\fpcm\classes\tools::getControllerLink('ajax/editor/editorlist', ['id' => $article->getId(), 'view' => 'revisions']); ?>">
            <?php $FPCM_LANG->write('HL_ARTICLE_EDIT_REVISIONS', [ 'count' => $revisionCount ]); ?>
        </a></li>
        <?php endif; ?>
    </ul>            

    <form method="post" action="<?php print $FPCM_SELF; ?>?module=<?php print $editorAction; ?>" name="nform">
        <div id="tabs-article">
            <div class="fpcm-ui-dialog-layer fpcm-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-filemanager"></div>            
            
            <?php include $editorFile; ?>
            <?php include __DIR__.'/buttons.php'; ?>
        </div>
        
        <?php \fpcm\model\view\helper::pageTokenField(); ?>
    </form>
</div>
<?php
    if ($timesMode) {
        $timeInfoCreate = $theView->lang->translate('EDITOR_AUTHOREDIT', array(
            '{{username}}' => isset($users[$article->getCreateuser()]) ? $users[$article->getCreateuser()] : $theView->lang->translate('GLOBAL_NOTFOUND'),
            '{{time}}'     => \fpcm\view\helper::dateText($article->getCreatetime(), false, true)
        ));

        $timeInfoChange = $theView->lang->translate('EDITOR_LASTEDIT', array(
            '{{username}}' => isset($users[$article->getChangeuser()]) ? $users[$article->getChangeuser()] : $theView->lang->translate('GLOBAL_NOTFOUND'),
            '{{time}}'     => \fpcm\view\helper::dateText($article->getChangetime(), false, true)
        ));         
    } else {
        $timeInfoCreate = $theView->lang->translate('EDITOR_AUTHOREDIT', array(
            '{{username}}' => isset($users[$article->getCreateuser()]) ? $users[$article->getCreateuser()]->getDisplayname() : $theView->lang->translate('GLOBAL_NOTFOUND'),
            '{{time}}'     => \fpcm\view\helper::dateText($article->getCreatetime(), false, true)
        ));

        $timeInfoChange = $theView->lang->translate('EDITOR_LASTEDIT', array(
            '{{username}}' => isset($users[$article->getChangeuser()]) ? $users[$article->getChangeuser()]->getDisplayname() : $theView->lang->translate('GLOBAL_NOTFOUND'),
            '{{time}}'     => \fpcm\view\helper::dateText($article->getChangetime(), false, true)
        ));        
    }            
?>

<div class="fpcm-ui-editor-metabox-left">
    <?php if (!$timesMode) : ?>
        <?php if (!$isRevision) : ?>
        <div class="fpcm-ui-editor-metabox-left-frontend">
            <?php \fpcm\view\helper::linkButton($article->getElementLink(), 'GLOBAL_FRONTEND_OPEN', '', 'fpcm-ui-button-blank fpcm-openlink-btn', '_blank'); ?>
        </div>
        <div class="fpcm-ui-editor-metabox-left-short">
            <?php \fpcm\view\helper::linkButton($article->getArticleShortLink(), 'EDITOR_ARTICLE_SHORTLINK', '', 'fpcm-ui-button-blank fpcm-articlelist-shortlink'); ?>
        </div>
        <?php endif; ?>
        <?php if ($article->getImagepath()) : ?>
        <div class="fpcm-ui-editor-metabox-left-aimg">
            <?php \fpcm\view\helper::linkButton($article->getImagepath(), 'EDITOR_ARTICLEIMAGE_SHOW', '', 'fpcm-ui-button-blank fpcm-filelist-articleimage fpcm-editor-articleimage'); ?>
        </div>
        <?php endif; ?>
    <?php endif; ?>
    
    <div class="fpcm-ui-ellipsis"><?php print $timeInfoCreate; ?><br>
    <?php print $timeInfoChange; ?>
    <?php if (!$timesMode && $isRevision) : ?><br>
        <b><?php $theView->lang->write('TEMPLATE_ARTICLE_SOURCES'); ?>:</b>
        <?php print $article->getSources(); ?>
    <?php endif; ?>
    
    </div>
</div>

<?php if (!$timesMode) : ?>
<!-- Shortlink layer -->  
<div class="fpcm-ui-dialog-layer fpcm-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-shortlink"></div>
<?php endif; ?>
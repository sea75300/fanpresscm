<?php
    if ($timesMode) {
        $timeInfoCreate = $theView->translate('EDITOR_AUTHOREDIT', array(
            '{{username}}' => isset($users[$article->getCreateuser()]) ? $users[$article->getCreateuser()] : $theView->translate('GLOBAL_NOTFOUND'),
            '{{time}}'     => $theView->dateText($article->getCreatetime())
        ));

        $timeInfoChange = $theView->translate('EDITOR_LASTEDIT', array(
            '{{username}}' => isset($users[$article->getChangeuser()]) ? $users[$article->getChangeuser()] : $theView->translate('GLOBAL_NOTFOUND'),
            '{{time}}'     => $theView->dateText($article->getChangetime())
        ));         
    } else {
        $timeInfoCreate = $theView->translate('EDITOR_AUTHOREDIT', array(
            '{{username}}' => isset($users[$article->getCreateuser()]) ? $users[$article->getCreateuser()]->getDisplayname() : $theView->translate('GLOBAL_NOTFOUND'),
            '{{time}}'     => $theView->dateText($article->getCreatetime())
        ));

        $timeInfoChange = $theView->translate('EDITOR_LASTEDIT', array(
            '{{username}}' => isset($users[$article->getChangeuser()]) ? $users[$article->getChangeuser()]->getDisplayname() : $theView->translate('GLOBAL_NOTFOUND'),
            '{{time}}'     => $theView->dateText($article->getChangetime())
        ));        
    }            
?>

<div class="fpcm-ui-editor-metabox-left">    
    <div class="fpcm-ui-ellipsis"><?php print $timeInfoCreate; ?><br>
    <?php print $timeInfoChange; ?>
    <?php if (!$timesMode && $isRevision) : ?><br>
        <b><?php $theView->write('TEMPLATE_ARTICLE_SOURCES'); ?>:</b>
        <?php print $article->getSources(); ?>
    <?php endif; ?>
    
    </div>
</div>

<?php if (!$timesMode) : ?>
<!-- Shortlink layer -->  
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-shortlink"></div>
<?php endif; ?>
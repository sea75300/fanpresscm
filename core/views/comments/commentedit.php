<form method="post" action="<?php print $comment->getEditLink(); ?>&mode=<?php print $commentsMode; ?>">
    <?php if ($commentsMode == 2) : ?>
    <div class="fpcm-dialog-wrapper">
    <?php elseif($commentsMode == 1) : ?>
    <div class="fpcm-content-wrapper">
        <h1><span class="fa fa-comments"></span> <?php $FPCM_LANG->write('HL_COMMENTS_MNG'); ?></h1>
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-category"><?php $FPCM_LANG->write('COMMENTS_EDIT'); ?></a></li>
            </ul>
            
            <div id="tabs-category">                
    <?php endif; ?>
            <?php include __DIR__.'/commenteditor.php'; ?>
    <?php if ($commentsMode == 1) : ?>
            </div>
        </div>
    <?php endif; ?>
    </div>
</form>
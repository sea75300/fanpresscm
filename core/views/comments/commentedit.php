<form method="post" action="<?php print $comment->getEditLink(); ?>&mode=<?php print $commentsMode; ?>">
    <?php if ($commentsMode == 2) : ?>
    <div class="fpcm-dialog-wrapper">
    <?php elseif($commentsMode == 1) : ?>
    <div class="fpcm-content-wrapper">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-category"><?php $theView->lang->write('COMMENTS_EDIT'); ?></a></li>
            </ul>
            
            <div id="tabs-category">                
    <?php endif; ?>
            <?php include $theView->getIncludePath('comments/editor.php'); ?>
    <?php if ($commentsMode == 1) : ?>
            </div>
        </div>
    <?php endif; ?>
    </div>
</form>
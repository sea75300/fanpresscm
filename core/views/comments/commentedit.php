<?php if ($commentsMode == 2) : ?>
<div class="fpcm-ui-inner-wrapper">
<?php elseif($commentsMode == 1) : ?>
<div class="fpcm-content-wrapper">
    <div class="fpcm-tabs-general">
        <ul>
            <li><a href="#tabs-category"><?php $theView->write('COMMENTS_EDIT'); ?></a></li>
        </ul>

        <div id="tabs-category">                
<?php endif; ?>
        <?php include $theView->getIncludePath('comments/editor.php'); ?>
<?php if ($commentsMode == 1) : ?>
        </div>
    </div>
<?php endif; ?>
</div>
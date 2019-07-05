<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="<?php if($commentsMode == 2) : ?>fpcm-ui-inner-wrapper<?php else : ?>fpcm-content-wrapper<?php endif; ?>">
    <div class="fpcm-ui-tabs-general">
        <ul>
            <li><a href="#tabs-comment"><?php $theView->write('COMMENTS_EDIT'); ?></a></li>
        </ul>

        <div id="tabs-comment">                

            <?php include $theView->getIncludePath('comments/editor.php'); ?>
        </div>
    </div>
</div>
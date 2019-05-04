<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-ui-inner-wrapper">
    <div class="fpcm-ui-tabs-general">
        <ul>
            <li><a href="#tabs-comment"><?php $theView->write('COMMENTS_EDIT'); ?></a></li>
        </ul>

        <div id="tabs-comment">                

            <?php include $theView->getIncludePath('comments/editor.php'); ?>
        </div>
    </div>
</div>
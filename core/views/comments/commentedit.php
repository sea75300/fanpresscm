<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row no-gutters <?php if($commentsMode === 1) : ?>fpcm-ui-full-height<?php endif; ?>">
    <div class="col-12">
        <div class="fpcm-content-wrapper <?php if($commentsMode === 1) : ?>fpcm-ui-full-height<?php endif; ?>">
            <div class="fpcm-ui-tabs-general">
                <ul>
                    <li><a href="#tabs-comment"><?php $theView->write('COMMENTS_EDIT'); ?></a></li>
                </ul>

                <div id="tabs-comment">                

                    <?php include $theView->getIncludePath('comments/editor.php'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
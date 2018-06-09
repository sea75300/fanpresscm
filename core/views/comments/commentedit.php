<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row no-gutters fpcm-ui-full-height">
    <div class="col-12">
        <div class="fpcm-content-wrapper fpcm-ui-full-height">
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
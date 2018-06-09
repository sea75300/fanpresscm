<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row no-gutters fpcm-ui-full-height">
    <div class="col-12">
        <div class="fpcm-content-wrapper fpcm-ui-full-height">
            <div class="fpcm-ui-tabs-general">
                <ul>
                    <li><a href="#tabs-roll"><?php $theView->write('FILE_LIST_SMILEYADD'); ?></a></li>
                </ul>            

                <div id="tabs-roll">
                    <?php include $theView->getIncludePath('smileys/editor.php'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
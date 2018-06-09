<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row no-gutters fpcm-ui-full-height">
    <div class="col-12">
        <div class="fpcm-content-wrapper fpcm-ui-full-height">
            <div class="fpcm-ui-tabs-general">
                <ul>
                    <li><a href="#tabs-category"><?php $theView->write('WORDBAN_ADD'); ?></a></li>
                </ul>            

                <div id="tabs-category">                
                    <?php include $theView->getIncludePath('wordban/editor.php'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
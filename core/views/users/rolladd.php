<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div class="fpcm-tabs-general">
        <ul>
            <li><a href="#tabs-roll"><?php $theView->write('USERS_ROLL_ADD'); ?></a></li>
        </ul>            

        <div id="tabs-roll">
            
            <div class="row fpcm-ui-padding-md-tb">
                <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
                    <?php $theView->write('USERS_ROLLS_NAME'); ?>:
                </div>
                <div class="col-sm-12 col-md-6 fpcm-ui-padding-none-lr">
                    <?php $theView->textInput('rollname'); ?>
                </div>
            </div>

        </div>
    </div>
</div>
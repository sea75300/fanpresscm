<div class="fpcm-content-wrapper">
    <h1><?php $theView->lang->write('GLOBAL_NOTFOUND'); ?></h1>
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-notfound"><?php $theView->lang->write('GLOBAL_NOTFOUND'); ?></a></li>                
            </ul>

            <div id="tabs-smiley-list">
                <p class="fpcm-ui-notfound-msg"><?php $theView->lang->write($messageVar); ?></p>
            </div>
        </div>
    
        <div class="<?php \fpcm\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
            <div class="fpcm-ui-margin-center">
                <?php fpcm\model\view\helper::linkButton($theView->basePath.$backaction, 'GLOBAL_BACK'); ?>
            </div>
        </div>
    </form> 
</div>
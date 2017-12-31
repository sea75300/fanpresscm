<div class="fpcm-content-wrapper">
    <h1><?php $FPCM_LANG->write('GLOBAL_NOTFOUND'); ?></h1>
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-notfound"><?php $FPCM_LANG->write('GLOBAL_NOTFOUND'); ?></a></li>                
            </ul>

            <div id="tabs-smiley-list">
                <p class="fpcm-ui-notfound-msg"><?php $FPCM_LANG->write($messageVar); ?></p>
            </div>
        </div>
    
        <div class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
            <div class="fpcm-ui-margin-center">
                <?php fpcm\model\view\helper::linkButton($FPCM_BASEMODULELINK.$backaction, 'GLOBAL_BACK'); ?>
            </div>
        </div>
    </form> 
</div>
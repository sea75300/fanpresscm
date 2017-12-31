<div class="fpcm-ui-center">
    <h3><span class="fa fa-language"></span> <?php $FPCM_LANG->write('INSTALLER_LANGUAGE_SELECT'); ?></h3>
    
    <div class="fpcm-half-width fpcm-ui-margin-center">
        <?php \fpcm\model\view\helper::select('language', array_flip($FPCM_LANG->getLanguages()), 'de', false, false); ?>
    </div>
</div>
<div class="fpcm-ui-center">
    <h3><span class="fa fa-language"></span> <?php $theView->write('INSTALLER_LANGUAGE_SELECT'); ?></h3>
    
    <div class="fpcm-half-width fpcm-ui-margin-center">
        <?php \fpcm\view\helper::select('language', array_flip($theView->getLanguages()), 'de', false, false); ?>
    </div>
</div>
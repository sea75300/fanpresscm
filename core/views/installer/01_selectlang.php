<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="col-12">
    <div class="row">        
        <?php $theView->select('language')
                ->setOptions($languages)
                ->setSelected('de')
                ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                ->setText('INSTALLER_LANGUAGE_SELECT'); ?>
    </div>
</div>
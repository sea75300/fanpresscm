<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row p-2 justify-content-center">  
    <div class="col-12 col-md-6 align-self-center">
        <?php $theView->select('language')
                ->setOptions($languages)
                ->setSelected('de')
                ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                ->setText('INSTALLER_LANGUAGE_SELECT'); ?>        
    </div>    
</div>
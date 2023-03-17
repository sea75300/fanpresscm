<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php $theView->select('language')
        ->setOptions($languages)
        ->setSelected('de')
        ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
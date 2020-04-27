<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="col-12 col-sm-8 col-md-6 fpcm-ui-center">
    <?php $theView->select('language')->setOptions($languages)->setSelected('de')->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
</div>
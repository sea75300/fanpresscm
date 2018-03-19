<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row align-items-center">

    <div class="col-12">
        <h3><span class="fa fa-language"></span> <?php $theView->write('INSTALLER_LANGUAGE_SELECT'); ?></h3>
    </div>

    <div class="col-12 col-md-6 fpcm-ui-center">
        <?php $theView->select('language')->setOptions(array_flip($theView->get))->setSelected('de')->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
    </div>

</div>
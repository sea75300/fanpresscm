<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-ui-inner-wrapper">

    <div class="fpcm-ui-tabs-general" id="fpcm-tabs-permissions">
        <ul>
            <li><a href="#tabs-permissions-group"><?php $theView->write('HL_OPTIONS_PERMISSIONS'); ?>: <?php print $rollname; ?></a></li>                
        </ul>

        <div id="tabs-permissions-group">
            <?php include $theView->getIncludePath('users/permissions_editor.php'); ?>
        </div>
    </div>

    <?php $theView->saveButton('permissionsSave')->setClass('fpcm-ui-hidden') ?>
</div>
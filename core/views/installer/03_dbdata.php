<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="col-12">

    <fieldset>
        <legend><?php $theView->write('INSTALLER_DBCONNECTION'); ?></legend>

        <div class="row my-2 fpcm-ui-selectmenu-border-radius-right">        
            <?php $theView->select('database[DBTYPE]')
                    ->setText('INSTALLER_DBCONNECTION_TYPE')
                    ->setOptions($sqlDrivers)
                    ->setClass('fpcm-installer-data')
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText('INSTALLER_DBCONNECTION_TYPE')
                    ->setDisplaySizesDefault()
                    ->prependLabel(); ?>
        </div>       

        <div class="row my-2">
                <?php $theView->textInput('database[DBHOST]')
                        ->setText('INSTALLER_DBCONNECTION_HOST')
                        ->setValue('localhost')
                        ->setClass('fpcm-installer-data')
                        ->setDisplaySizesDefault(); ?>
        </div>

        <div class="row my-2">
                <?php $theView->textInput('database[DBNAME]')
                        ->setText('INSTALLER_DBCONNECTION_NAME')
                        ->setClass('fpcm-installer-data')
                        ->setDisplaySizesDefault(); ?>
        </div>

        <div class="row my-2">
                <?php $theView->textInput('database[DBUSER]')
                        ->setText('INSTALLER_DBCONNECTION_USER')
                        ->setClass('fpcm-installer-data')
                        ->setDisplaySizesDefault(); ?>
        </div>

        <div class="row my-2">
                <?php $theView->textInput('database[DBPASS]')
                        ->setText('INSTALLER_DBCONNECTION_PASS')
                        ->setClass('fpcm-installer-data')
                        ->setDisplaySizesDefault(); ?>
        </div>

        <div class="row my-2">
                <?php $theView->textInput('database[DBPREF]')
                        ->setText('INSTALLER_DBCONNECTION_PREF')
                        ->setValue('fpcm4')
                        ->setClass('fpcm-installer-data')
                        ->setDisplaySizesDefault(); ?>
        </div>
    </fieldset>

</div>
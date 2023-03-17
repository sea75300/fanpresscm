<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row g-0 my-2">        
    <?php $theView->select('database[DBTYPE]')
            ->setText('INSTALLER_DBCONNECTION_TYPE')
            ->setOptions($sqlDrivers)
            ->setClass('fpcm-installer-data')
            ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
            ->setLabelTypeFloat(); ?>
</div>       

<div class="row g-0 my-2">
        <?php $theView->textInput('database[DBHOST]')
                ->setText('INSTALLER_DBCONNECTION_HOST')
                ->setPlaceholder('INSTALLER_DBCONNECTION_HOST')
                ->setValue('localhost')
                ->setClass('fpcm-installer-data')
                ->setLabelTypeFloat(); ?>
</div>

<div class="row g-0 my-2">
        <?php $theView->textInput('database[DBNAME]')
                ->setText('INSTALLER_DBCONNECTION_NAME')
                ->setPlaceholder('INSTALLER_DBCONNECTION_NAME')
                ->setClass('fpcm-installer-data')
                ->setLabelTypeFloat(); ?>
</div>

<div class="row g-0 my-2">
        <?php $theView->textInput('database[DBUSER]')
                ->setText('INSTALLER_DBCONNECTION_USER')
                ->setPlaceholder('INSTALLER_DBCONNECTION_USER')
                ->setClass('fpcm-installer-data')
                ->setLabelTypeFloat(); ?>
</div>

<div class="row g-0 my-2">
        <?php $theView->textInput('database[DBPASS]')
                ->setText('INSTALLER_DBCONNECTION_PASS')
                ->setPlaceholder('INSTALLER_DBCONNECTION_PASS')
                ->setClass('fpcm-installer-data')
                ->setLabelTypeFloat(); ?>
</div>

<div class="row g-0 my-2">
        <?php $theView->textInput('database[DBPREF]')
                ->setText('INSTALLER_DBCONNECTION_PREF')
                ->setPlaceholder('INSTALLER_DBCONNECTION_PREF')
                ->setValue('fpcm5')
                ->setClass('fpcm-installer-data')
                ->setLabelTypeFloat(); ?>
</div>
<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="col-12 col-sm-8 col-md-6">

    <div class="row fpcm-ui-padding-md-tb">
        <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
            <?php $theView->write('INSTALLER_DBCONNECTION_TYPE'); ?>
        </label>
        <div class="col-12 col-sm-7 fpcm-ui-padding-none-lr">
            <?php $theView->select('database[DBTYPE]')
                    ->setText('INSTALLER_DBCONNECTION_TYPE')
                    ->setOptions($sqlDrivers)
                    ->setClass('fpcm-installer-data')
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>
    
    <div class="row fpcm-ui-padding-md-tb">
            <?php $theView->textInput('database[DBHOST]')
                    ->setWrapper(false)
                    ->setText('INSTALLER_DBCONNECTION_HOST')
                    ->setValue('localhost')
                    ->setClass('fpcm-installer-data')
                    ->setClass('col-12 col-md-7 fpcm-ui-field-input-nowrapper-general')
                    ->setLabelClass('col-12 col-md-5 fpcm-ui-field-label-general'); ?>
    </div>

    <div class="row fpcm-ui-padding-md-tb">
            <?php $theView->textInput('database[DBNAME]')
                    ->setWrapper(false)
                    ->setText('INSTALLER_DBCONNECTION_NAME')
                    ->setClass('fpcm-installer-data')
                    ->setClass('col-12 col-md-7 fpcm-ui-field-input-nowrapper-general')
                    ->setLabelClass('col-12 col-md-5 fpcm-ui-field-label-general'); ?>
    </div>

    <div class="row fpcm-ui-padding-md-tb">
            <?php $theView->textInput('database[DBUSER]')
                    ->setWrapper(false)
                    ->setText('INSTALLER_DBCONNECTION_USER')
                    ->setClass('fpcm-installer-data')
                    ->setClass('col-12 col-md-7 fpcm-ui-field-input-nowrapper-general')
                    ->setLabelClass('col-12 col-md-5 fpcm-ui-field-label-general'); ?>
    </div>

    <div class="row fpcm-ui-padding-md-tb">
            <?php $theView->textInput('database[DBPASS]')
                    ->setWrapper(false)
                    ->setText('INSTALLER_DBCONNECTION_PASS')
                    ->setClass('fpcm-installer-data')
                    ->setClass('col-12 col-md-7 fpcm-ui-field-input-nowrapper-general')
                    ->setLabelClass('col-12 col-md-5 fpcm-ui-field-label-general'); ?>
    </div>

    <div class="row fpcm-ui-padding-md-tb">
            <?php $theView->textInput('database[DBPREF]')
                    ->setWrapper(false)
                    ->setText('INSTALLER_DBCONNECTION_PREF')
                    ->setValue('fpcm4')
                    ->setClass('fpcm-installer-data')
                    ->setClass('col-12 col-md-7 fpcm-ui-field-input-nowrapper-general')
                    ->setLabelClass('col-12 col-md-5 fpcm-ui-field-label-general'); ?>
    </div>

</div>
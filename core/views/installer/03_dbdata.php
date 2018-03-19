<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row align-items-center">

    <div class="col-12">
        <h3><span class="fa fa-database"></span> <?php $theView->write('INSTALLER_DBCONNECTION'); ?></h3>
    </div>

    <div class="col-12 col-md-6 fpcm-ui-center">

        <div class="row fpcm-ui-padding-md-tb">
            <div class="col-sm-12 col-md-3">
                <?php $theView->write('INSTALLER_DBCONNECTION_TYPE'); ?>:                
            </div>
            <div class="col-sm-12 col-md-9">
                <?php $theView->select('database[DBTYPE]')
                        ->setOptions($sqlDrivers)
                        ->setClass('fpcm-installer-data')
                        ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
            </div>
        </div>

        <div class="row fpcm-ui-padding-md-tb">
            <div class="col-sm-12 col-md-3">
                <?php $theView->write('INSTALLER_DBCONNECTION_HOST'); ?>:                
            </div>
            <div class="col-sm-12 col-md-9">
                <?php $theView->textInput('database[DBHOST]')->setValue('localhost')->setClass('fpcm-installer-data'); ?>
            </div>
        </div>

        <div class="row fpcm-ui-padding-md-tb">
            <div class="col-sm-12 col-md-3">
                <?php $theView->write('INSTALLER_DBCONNECTION_NAME'); ?>:                
            </div>
            <div class="col-sm-12 col-md-9">
                <?php $theView->textInput('database[DBNAME]')->setClass('fpcm-installer-data'); ?>
            </div>
        </div>

        <div class="row fpcm-ui-padding-md-tb">
            <div class="col-sm-12 col-md-3">
                <?php $theView->write('INSTALLER_DBCONNECTION_USER'); ?>:                
            </div>
            <div class="col-sm-12 col-md-9">
                <?php $theView->textInput('database[DBUSER]')->setClass('fpcm-installer-data'); ?>
            </div>
        </div>

        <div class="row fpcm-ui-padding-md-tb">
            <div class="col-sm-12 col-md-3">
                <?php $theView->write('INSTALLER_DBCONNECTION_PASS'); ?>:                
            </div>
            <div class="col-sm-12 col-md-9">
                <?php $theView->textInput('database[DBPASS]')->setClass('fpcm-installer-data'); ?>
            </div>
        </div>

        <div class="row fpcm-ui-padding-md-tb">
            <div class="col-sm-12 col-md-3">
                <?php $theView->write('INSTALLER_DBCONNECTION_PREF'); ?>:                
            </div>
            <div class="col-sm-12 col-md-9">
                <?php $theView->textInput('database[DBPREF]')->setValue('fpcm4')->setClass('fpcm-installer-data'); ?>
            </div>
        </div>

    </div>

</div>
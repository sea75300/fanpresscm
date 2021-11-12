<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row p-2 justify-content-center border-top border-5 border-primary">
    <div class="col-12 col-md-6">
        <fieldset>
            <div class="row my-2">        
                <?php $theView->select('database[DBTYPE]')
                        ->setText('INSTALLER_DBCONNECTION_TYPE')
                        ->setOptions($sqlDrivers)
                        ->setClass('fpcm-installer-data')
                        ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                        ->setText('INSTALLER_DBCONNECTION_TYPE'); ?>
            </div>       

            <div class="row my-2">
                    <?php $theView->textInput('database[DBHOST]')
                            ->setText('INSTALLER_DBCONNECTION_HOST')
                            ->setValue('localhost')
                            ->setClass('fpcm-installer-data') ?>
            </div>

            <div class="row my-2">
                    <?php $theView->textInput('database[DBNAME]')
                            ->setText('INSTALLER_DBCONNECTION_NAME')
                            ->setClass('fpcm-installer-data'); ?>
            </div>

            <div class="row my-2">
                    <?php $theView->textInput('database[DBUSER]')
                            ->setText('INSTALLER_DBCONNECTION_USER')
                            ->setClass('fpcm-installer-data'); ?>
            </div>

            <div class="row my-2">
                    <?php $theView->textInput('database[DBPASS]')
                            ->setText('INSTALLER_DBCONNECTION_PASS')
                            ->setClass('fpcm-installer-data'); ?>
            </div>

            <div class="row my-2">
                    <?php $theView->textInput('database[DBPREF]')
                            ->setText('INSTALLER_DBCONNECTION_PREF')
                            ->setValue('fpcm5')
                            ->setClass('fpcm-installer-data'); ?>
            </div>
        </fieldset>
    </div>
</div>
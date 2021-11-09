<?php /* @var $theView \fpcm\view\viewVars */ ?>

<div class="row g-0">
    <div class="col-12">
        <fieldset>
            <legend><?php $theView->write('GLOBAL_INFO'); ?></legend>
            <p class="px-3"><?php $theView->write('IMPORT_NOTICE_UTF8'); ?></p>
        </fieldset>
    </div>
</div>

<div class="mx-3 my-2 fpcm ui-hidden">
    <?php include $theView->getIncludePath('components/progress.php'); ?>
</div>

<div class="row py-2 row-cols-2">

    <div class="col" id="fpcm-ui-csv-upload">
        <?php include $uploadTemplatePath; ?>
    </div>    

    <div class="col" id="fpcm-ui-csv-settings">
        
        <fieldset>
            <legend><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></legend>

            <div class="row my-2">
                <?php $theView->select('import_destination')
                        ->setOptions($theView->translate('SYSTEM_IMPORT_ITEMS'))
                        ->setText('IMPORT_ITEM')
                        ->setIcon('file-csv'); ?>
            </div>

            <div class="row my-2">
                <?php $theView->textInput('import_delimiter')
                        ->setValue(';')->setMaxlenght(1)
                        ->setText('IMPORT_DELIMITER')
                        ->setIcon('cut'); ?>
            </div>

            <div class="row my-2">
                <?php $theView->textInput('import_enclosure')
                        ->setValue('"')->setMaxlenght(1)
                        ->setText('IMPORT_ENCLOSURE')
                        ->setIcon('quote-left'); ?>
            </div>

            <div class="row my-2">
                    <div class="col-form-label col-12 col-sm-6 col-md-4 align-self-center">

                        <?php $theView->icon('cog'); ?>
                        <span class="fpcm-ui-label ps-1"><?php $theView->write('HL_OPTIONS'); ?></span>
                        
                    </div>

                    <div class="col align-self-center">
                        <?php $theView->checkbox('import_first')
                                ->setValue('1')
                                ->setText('IMPORT_EXCLUDE_FIRST')
                                ->setSelected(true)
                                ->setSwitch(true); ?>

                    </div>
            </div>

        </fieldset>        
        
        <div class="row my-2"> 

            <div class="col-12 col-lg-5 px-0 pb-2 pb-lg-0 pe-lg-1">

                <fieldset>
                    <legend><?php $theView->write('IMPORT_FIELDS_OBJECT'); ?></legend>
                    <ul class="list-group fpcm-ui-csv-fields mb-2 bg-light" id="fpcm-ui-csv-fields-select"></ul>
                </fieldset>

            </div>

            <div class="col-2 px-0 d-none d-lg-block align-self-center fpcm text-center ui-color-font-grey">
                <?php $theView->icon('chevron-right')->setSize('3x'); ?>
                
            </div>

            <div class="col-12 col-lg-5 px-0 ps-lg-1">

                <fieldset class="h-100">
                    <legend><?php $theView->write('IMPORT_FIELDS_CSV'); ?></legend>
                    <ul class="list-group fpcm-ui-csv-fields mb-2 bg-light" id="fpcm-ui-csv-fields-list"></ul>
                </fieldset>

            </div>

        </div>
        
        
    </div>     
</div>

<?php $theView->hiddenInput('import_size'); ?>
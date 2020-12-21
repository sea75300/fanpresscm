<?php /* @var $theView \fpcm\view\viewVars */ ?>

<div class="row">
    <div class="col-12">
        <fieldset class="fpcm-ui-margin-none-left fpcm-ui-margin-none-right fpcm-ui-margin-md-top">
            <legend><?php $theView->write('GLOBAL_INFO'); ?></legend>
            <?php $theView->write('IMPORT_NOTICE_UTF8'); ?>
        </fieldset>
    </div>
</div>

<div class="mx-3 my-2 fpcm ui-hidden">
    <?php include $theView->getIncludePath('components/progress.php'); ?>
</div>

<div class="row py-2">

    <div class="col-12 col-md-6 mb-2 mb-md-0" id="fpcm-ui-csv-upload">
        <?php include $uploadTemplatePath; ?>
    </div>    

    <div class="col-12 col-md-6" id="fpcm-ui-csv-settings">
        
        <div class="row my-2">
            <?php $theView->select('import_destination')->setOptions($theView->translate('SYSTEM_IMPORT_ITEMS'))
                ->prependLabel()->setText('IMPORT_ITEM')->setIcon('file-csv')->setDisplaySizesDefault(); ?>
        </div>
        
        <div class="row my-2">
            <?php $theView->textInput('import_delimiter')->setValue(';')->setMaxlenght(1)
                ->setText('IMPORT_DELIMITER')->setIcon('cut')->setDisplaySizesDefault(); ?>
        </div>
        
        <div class="row my-2">
            <?php $theView->textInput('import_enclosure')->setValue('"')->setMaxlenght(1)
                ->setText('IMPORT_ENCLOSURE')->setIcon('quote-left')->setDisplaySizesDefault(); ?>
        </div>
        
        <div class="row my-2">
            <?php $theView->checkbox('import_first')->setValue('1')->setText('IMPORT_EXCLUDE_FIRST')->setSelected(true); ?>
        </div>
        
        <div class="row my-2"> 

            <div class="col-12 col-md-6 pl-0">

                <fieldset>
                    <legend><?php $theView->write('IMPORT_FIELDS_OBJECT'); ?>: <?php $theView->icon('arrow-right')->setClass('fpcm-ui-float-right'); ?></legend>
                    <ul class="fpcm-ui-list-style-none p-0 fpcm-ui-csv-fields" id="fpcm-ui-csv-fields-select"></ul>
                </fieldset>

            </div>

            <div class="col-12 col-md-6 pr-0">

                <fieldset>
                    <legend><?php $theView->write('IMPORT_FIELDS_CSV'); ?></legend>
                    <ul class="fpcm-ui-list-style-none px-0 pt-0 pb-2 fpcm-ui-csv-fields" id="fpcm-ui-csv-fields-list"></ul>
                </fieldset>

            </div>

        </div>
        
        
    </div>     
</div>

<?php $theView->hiddenInput('import_size'); ?>
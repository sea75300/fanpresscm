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
    
    <div class="col-12 col-md-6">
        
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
        
        
    </div>   
</div>

<?php $theView->hiddenInput('import_filename'); ?>
<?php $theView->hiddenInput('import_size'); ?>
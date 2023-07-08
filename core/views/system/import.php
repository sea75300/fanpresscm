<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row row-cols-1 row-cols-md-2">
    <div class="col mt-2">
        <?php $theView->alert('warning')->setText('IMPORT_NOTICE_UTF8'); ?>
    </div>
    <div class="col align-self-center d-none" id="fpcm-id-progress-col">
        <?php include $theView->getIncludePath('components/progress.php'); ?>
    </div>
</div>

<div class="row pb-3">
    <div class="col">
        <div class="accordion" id="fpcm-id-import">
            <div class="accordion-item">
                <h2 class="accordion-header" id="fpcm-id-import-upload-head">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fpcm-id-import-upload-body" aria-expanded="false" aria-controls="fpcm-id-import-upload-body">
                        <?php $theView->write('FILE_LIST_UPLOADFORM') ?>
                    </button>
                </h2>
                <div id="fpcm-id-import-upload-body" class="accordion-collapse collapse" aria-labelledby="fpcm-id-import-upload-head" data-bs-parent="#fpcm-id-import">
                    <div class="accordion-body" id="fpcm-ui-csv-upload">
                        <?php include $uploadTemplatePath; ?>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="fpcm-id-import-setting-head">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fpcm-id-import-setting-body" aria-expanded="false" aria-controls="fpcm-id-import-setting-body">
                        <?php $theView->write('IMPORT_OPTIONS') ?>
                    </button>
                </h2>
                <div id="fpcm-id-import-setting-body" class="accordion-collapse collapse" aria-labelledby="fpcm-id-import-setting-head" data-bs-parent="#fpcm-id-import">
                    <div class="accordion-body">

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

                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="fpcm-id-import-fields-head">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fpcm-id-import-fields-body" aria-expanded="false" aria-controls="fpcm-id-import-fields-body">
                        <?php $theView->write('IMPORT_FIELDS') ?>
                    </button>
                </h2>
                <div id="fpcm-id-import-fields-body" class="accordion-collapse collapse" aria-labelledby="fpcm-id-import-fields-head" data-bs-parent="#fpcm-id-import">
                    <div class="accordion-body">
                        <div class="row">
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
            </div>
        </div>
        <?php $theView->hiddenInput('import_size'); ?>
    </div>
</div>
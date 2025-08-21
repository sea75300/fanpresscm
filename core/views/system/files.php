<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row g-0 gap-2">
    <div class="col">
        <div class="row my-2 row-cols-1 row-cols-xl-2">
            <div class="col">
            <?php $theView->select('file_list_limit')
                ->setText('SYSTEM_OPTIONS_FILEMANAGER_LIMIT')
                ->setOptions($articleLimitListAcp)
                ->setSelected($globalConfig->file_list_limit); ?>
            </div>

            <div class="col">
            <?php $theView->select('file_view')
                ->setText('SYSTEM_OPTIONS_FILEMANAGER_VIEW')
                ->setOptions($filemanagerViews)
                ->setSelected($globalConfig->file_view); ?>
            </div>
        </div>

        <div class="row my-2 row-cols-1 row-cols-xl-2">
            <div class="col">
            <?php $theView->boolSelect('file_subfolders')
                    ->setText('SYSTEM_OPTIONS_NEWS_SUBFOLDERS')
                    ->setSelected($globalConfig->file_subfolders); ?>
            </div>
            <div class="col">

            </div>
        </div>

        <div class="row my-2">
            <div class="col flex-grow-1">
                <?php $theView->textInput('file_cropper_name')->setValue($globalConfig->file_cropper_name); ?>
            </div>
            <div class="col-auto align-self-center mb-3">
                <?php $theView->shorthelpButton('cropper_name')->setText('LABEL_FIELD_FILE_CROPPER_NAME_HELP'); ?>
            </div>
        </div>
    </div>
    <div class="col">

        <div class="row my-2">
            <div class="col">
            <?php $theView->select('file_thumb_size')
                ->setText('FILE_LIST_THUMB_SIZE')
                ->setOptions($thumbsizes)
                ->setSelected($globalConfig->file_thumb_size); ?>
            </div>
        </div>

        <div class="row my-2">
            <div class="col">
                <figure class="figure" id="fpcm-thumb-preview">
                    <img title="<?php $theView->write('GLOBAL_PREVIEW'); ?>" class="img-thumbnail bg-light border border-2 border-info" src="<?php print $theView->themePath; ?>logo.svg" role="presentation" style="width:<?php print $globalConfig->file_thumb_size; ?>px;height: <?php print $globalConfig->file_thumb_size; ?>px;">
                    <figcaption class="figure-caption text-end"><span><?php print $theView->escapeVal($globalConfig->file_thumb_size); ?></span> <?php $theView->write('FILE_LIST_RESOLUTION_PIXEL') ?></figcaption>
                </figure>
            </div>
        </div>
    </div>
</div>
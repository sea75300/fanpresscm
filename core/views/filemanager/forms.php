<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if($mode > 1) : ?><?php include_once $theView->getIncludePath('common/buttons.php'); ?><?php endif; ?>

<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-files-rename">
    <div class="row py-2">
        <?php $theView->textInput('newFilenameDialog')
            ->setText('FILE_LIST_FILENAME')
            ->setValue('')
            ->setIcon('edit'); ?>
    </div>    
</div>

<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-files-alttext">
    <div class="row py-2">
        <?php $theView->textInput('altTextDialog')
            ->setText('FILE_LIST_ALTTEXT')
            ->setValue('')
            ->setIcon('edit'); ?>
    </div>    
</div>

<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-files-properties">

    <div class="row mb-3 g-0">
        <label class="col-form-label col-3 me-3">
            <?php $theView->icon('calendar-alt', 'far')->setSize('lg'); ?>
            <?php $theView->write('FILE_LIST_UPLOAD_DATE'); ?>
        </label>
        <div class="col align-self-center" id="fpcm-dialog-files-properties-filetime"></div>
    </div>

    <div class="row mb-3 g-0">
        <label class="col-form-label col-3 me-3">
            <?php $theView->icon('user')->setSize('lg'); ?>
            <?php $theView->write('FILE_LIST_UPLOAD_BY'); ?>
        </label>
        <div class="col align-self-center" id="fpcm-dialog-files-properties-fileuser"></div>
    </div>

    <div class="row mb-3 g-0">
        <label class="col-form-label col-3 me-3">
            <?php $theView->icon('weight')->setSize('lg'); ?>
            <?php $theView->write('FILE_LIST_FILESIZE'); ?>
        </label>
        <div class="col align-self-center" id="fpcm-dialog-files-properties-filesize"></div>
    </div>

    <div class="row mb-3 g-0">
        <label class="col-form-label col-3 me-3">
            <?php $theView->icon('expand-arrows-alt')->setSize('lg'); ?>
            <?php $theView->write('FILE_LIST_RESOLUTION'); ?>
        </label>
        <div class="col align-self-center" id="fpcm-dialog-files-properties-resulution"></div>
    </div>

    <div class="row mb-3 g-0">
        <label class="col-form-label col-3 me-3">
            <?php $theView->icon('file-alt ')->setSize('lg'); ?>
            <?php $theView->write('FILE_LIST_FILETYPE'); ?>
        </label>
        <div class="col align-self-center" id="fpcm-dialog-files-properties-filemime"></div>
    </div>

    <div class="row mb-3 g-0">
        <label class="col-form-label col-3 me-3">
            <?php $theView->icon('hashtag')->setSize('lg'); ?>
            <?php $theView->write('FILE_LIST_FILEHASH'); ?>
        </label>
        <div class="col align-self-center" id="fpcm-dialog-files-properties-filehash"></div>
    </div>

    <div class="row g-0">
        <label class="col-form-label col-3 me-3">
            <?php $theView->icon('copyright')->setSize('lg'); ?>
            <?php $theView->write('FILE_LIST_FILECREDITS'); ?>
        </label>
        <div class="col align-self-center" id="fpcm-dialog-files-properties-credits">
            &nbsp;
        </div>
    </div>

</div>

<div class="fpcm-ui-dialog-layer fpcm-ui-hidden" id="fpcm-dialog-files-search">

    <div class="row my-3">
        
        <div class="col-12 col-md-4 my-2 my-md-0"></div>

        <div class="col-12 col-md-8 my-2 my-md-0">
            <?php $theView->textInput('filename')->setText('FILE_LIST_SEARCHTEXT')->setMaxlenght(255)->setPlaceholder(true)->setWrapper(true)->setClass('fpcm-files-search-input'); ?>
        </div>
    </div>

    <div class="row my-3">
        
        <div class="col-12 col-md-4 my-2 my-md-0">
            <?php $theView->select('combinationDatefrom')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-filessearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setData(['']); ?>
        </div>

        <div class="col-12 col-md-3 my-2 my-md-0">
            <?php $theView->dateTimeInput('datefrom')
                    ->setClass('fpcm-files-search-input fpcm-ui-full-width-date')
                    ->setMaxlenght(10)
                    ->setText('ARTICLE_SEARCH_DATE_FROM')
                    ->setPlaceholder(true)
                    ->setWrapper(true); ?>
        </div>
    </div>

    <div class="row my-3">
        
        <div class="col-12 col-md-4 my-2 my-md-0">
            <?php $theView->select('combinationDateto')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-filessearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>

        <div class="col-12 col-md-3 my-2 my-md-0">
            <?php $theView->dateTimeInput('dateto')
                    ->setClass('fpcm-files-search-input fpcm-ui-full-width-date')
                    ->setMaxlenght(10)
                    ->setText('ARTICLE_SEARCH_DATE_TO')
                    ->setPlaceholder(true)
                    ->setWrapper(true); ?>
        </div>
    </div>

    <div class="row my-3">
        
        <div class="col-12 col-md-4 my-2 my-md-0">
            <?php $theView->select('combinationUserid')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-filessearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>

        <div class="col-12 col-md-8 my-2 my-md-0">
            <?php $theView->select('userid')
                    ->setOptions($searchUsers)
                    ->setClass('fpcm-files-search-input fpcm-ui-input-select-filessearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

</div>
<?php /* @var $theView \fpcm\view\viewVars */ ?>
    <?php if($mode > 1) : ?><div class="d-none"><?php include_once $theView->getIncludePath('common/buttons.php'); ?></div><?php endif; ?>
<div class="fpcm ui-hidden" id="fpcm-dialog-files-properties">

    <div class="row mb-2 g-0">
        <label class="col-form-label col-12 col-md-3 me-3">
            <?php $theView->icon('calendar-alt', 'far')->setSize('lg'); ?>
            <?php $theView->write('GLOBAL_LASTCHANGE'); ?>
        </label>
        <div class="col align-self-center" id="fpcm-dialog-files-properties-filetime"></div>
    </div>

    <div class="row mb-2 g-0">
        <label class="col-form-label col-12 col-md-3 me-3">
            <?php $theView->icon('user')->setSize('lg'); ?>
            <?php $theView->write('FILE_LIST_UPLOAD_BY'); ?>
        </label>
        <div class="col align-self-center" id="fpcm-dialog-files-properties-fileuser"></div>
    </div>

    <div class="row mb-2 g-0">
        <label class="col-form-label col-12 col-md-3 me-3">
            <?php $theView->icon('weight')->setSize('lg'); ?>
            <?php $theView->write('FILE_LIST_FILESIZE'); ?>
        </label>
        <div class="col align-self-center" id="fpcm-dialog-files-properties-filesize"></div>
    </div>

    <div class="row mb-2 g-0">
        <label class="col-form-label col-12 col-md-3 me-3">
            <?php $theView->icon('expand-arrows-alt')->setSize('lg'); ?>
            <?php $theView->write('FILE_LIST_RESOLUTION'); ?>
        </label>
        <div class="col align-self-center" id="fpcm-dialog-files-properties-resulution"></div>
    </div>

    <div class="row mb-2 g-0">
        <label class="col-form-label col-12 col-md-3 me-3">
            <?php $theView->icon('file-alt ')->setSize('lg'); ?>
            <?php $theView->write('FILE_LIST_FILETYPE'); ?>
        </label>
        <div class="col align-self-center" id="fpcm-dialog-files-properties-filemime"></div>
    </div>

    <div class="row mb-2 g-0">
        <label class="col-form-label col-12 col-md-3 me-3">
            <?php $theView->icon('hashtag')->setSize('lg'); ?>
            <?php $theView->write('FILE_LIST_FILEHASH'); ?>
        </label>
        <div class="col align-self-center fpcm-ui-ellipsis" id="fpcm-dialog-files-properties-filehash"></div>
    </div>

    <div class="row g-0">
        <label class="col-form-label col-12 col-md-3 me-3">
            <?php $theView->icon('copyright')->setSize('lg'); ?>
            <?php $theView->write('FILE_LIST_FILECREDITS'); ?>
        </label>
        <div class="col align-self-center" id="fpcm-dialog-files-properties-credits">
            &nbsp;
        </div>
    </div>

</div>

<div class="fpcm ui-hidden" id="fpcm-dialog-files-search">

    <div class="row mb-2">
        <div class="col-12 col-md-9 my-2 my-md-0">
            <?php $theView->textInput('filename')->setText('FILE_LIST_SEARCHTEXT')->setMaxlenght(255)->setPlaceholder(true)->setClass('fpcm-files-search-input'); ?>
        </div>
        <div class="col-12 col-md-3"></div>
    </div>

    <div class="row mb-2">

        <div class="col-12 col-md-9">
            <?php $theView->dateTimeInput('datefrom')
                    ->setClass('fpcm-files-search-input fpcm-ui-full-width-date')
                    ->setText('ARTICLE_SEARCH_DATE_FROM'); ?>
        </div>
        
        <div class="col-12 col-md-3">
            <?php $theView->select('combinationDatefrom')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-filessearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setData(['']); ?>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-12 col-md-9">
            <?php $theView->dateTimeInput('dateto')
                    ->setClass('fpcm-files-search-input fpcm-ui-full-width-date')
                    ->setText('ARTICLE_SEARCH_DATE_TO'); ?>
        </div>
        
        <div class="col-12 col-md-3">
            <?php $theView->select('combinationDateto')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-filessearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>

    </div>

    <div class="row mb-2">
        <div class="col-12 col-md-9">
            <?php $theView->select('userid')
                    ->setOptions($searchUsers)
                    ->setText('ARTICLE_SEARCH_USER')
                    ->setClass('fpcm-files-search-input fpcm-ui-input-select-filessearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
        
        <div class="col-12 col-md-3">
            <?php $theView->select('combinationUserid')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-filessearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>

    </div>

</div>
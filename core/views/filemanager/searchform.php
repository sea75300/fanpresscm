<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden" id="fpcm-dialog-files-search">

    <div class="row my-3">
        
        <div class="col-12 col-md-4 my-2 my-md-0"></div>

        <div class="col-12 col-md-8 my-2 my-md-0">
            <?php $theView->textInput('filename')->setText('FILE_LIST_FILENAME')->setMaxlenght(255)->setPlaceholder(true)->setWrapper(true)->setClass('fpcm-files-search-input'); ?>
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

</div>
<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden" id="fpcm-dialog-files-search">
    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-sm-12">
            <?php $theView->textInput('filename')->setText('FILE_LIST_FILENAME')->setPlaceholder(true)->setClass('fpcm-files-search-input'); ?>
        </div>
    </div>
    
    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-sm-6">
            <?php $theView->textInput('datefrom')->setMaxlenght(10)->setText('ARTICLE_SEARCH_DATE_FROM')->setPlaceholder(true)->setClass('fpcm-files-search-input fpcm-full-width-date'); ?>
        </div>
        <div class="col-sm-6">
            <?php $theView->textInput('dateto')->setMaxlenght(10)->setText('ARTICLE_SEARCH_DATE_TO')->setPlaceholder(true)->setClass('fpcm-files-search-input fpcm-full-width-date'); ?>
        </div>
    </div>
    
    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-sm-6"></div>
        <div class="col-sm-6">
            <?php $theView->select('combination')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-files-search-input fpcm-ui-input-select-filesearch')
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>
</div>
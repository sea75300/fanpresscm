<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden" id="fpcm-dialog-comments-search">

    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-8">
            <?php $theView->textInput('text')->setClass('fpcm-comments-search-input')->setMaxlenght(255)->setText('ARTICLE_SEARCH_TEXT')->setPlaceholder(true); ?>
        </div>
        <div class="col-sm-12 col-md-4">
            <?php $theView->select('searchtype')
                    ->setOptions($searchTypes)
                    ->setClass('fpcm-comments-search-input fpcm-ui-input-select-commentsearch')
                    ->setSelected(1)
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-4">
            <?php $theView->select('spam')
                    ->setOptions($searchSpam)
                    ->setClass('fpcm-comments-search-input fpcm-ui-input-select-commentsearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
        <div class="col-sm-12 col-md-4">
            <?php $theView->select('approved')
                    ->setOptions($searchApproval)
                    ->setClass('fpcm-comments-search-input fpcm-ui-input-select-commentsearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
        <div class="col-sm-12 col-md-4">
            <?php $theView->select('private')
                    ->setOptions($searchPrivate)
                    ->setClass('fpcm-comments-search-input fpcm-ui-input-select-commentsearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-4">
            <?php $theView->textInput('datefrom')->setClass('fpcm-comments-search-input fpcm-full-width-date')->setMaxlenght(10)->setText('ARTICLE_SEARCH_DATE_FROM')->setPlaceholder(true); ?>
        </div>
        <div class="col-sm-12 col-md-4">
            <?php $theView->textInput('dateto')->setClass('fpcm-comments-search-input fpcm-full-width-date')->setMaxlenght(10)->setText('ARTICLE_SEARCH_DATE_TO')->setPlaceholder(true); ?>
        </div>
        <div class="col-sm-12 col-md-4">
        </div>
    </div>

    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-4">
            <?php $theView->textInput('articleId')->setClass('fpcm-comments-search-input fpcm-ui-input-articleid')->setMaxlenght(20)->setText('COMMMENT_SEARCH_ARTICLE')->setPlaceholder(true); ?>
        </div>
        <div class="col-sm-12 col-md-4">
        </div>
        <div class="col-sm-12 col-md-4">
            <?php $theView->select('combination')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-comments-search-input fpcm-ui-input-select-commentsearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

</div>
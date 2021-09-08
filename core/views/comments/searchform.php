<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm ui-hidden" id="fpcm-dialog-comments-search">

    <div class="row">

        <div class="col-12 col-md-9">
            <?php $theView->textInput('text')->setClass('fpcm-comments-search-input')->setMaxlenght(255)->setText('ARTICLE_SEARCH_TEXT')->setPlaceholder(true); ?>
        </div>
        
        <div class="col-12 col-md-3">
            <?php $theView->select('searchtype')
                    ->setOptions($searchTypes)
                    ->setClass('fpcm-comments-search-input fpcm-ui-input-select-commentsearch')
                    ->setSelected(0)
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

    <div class="row">

        <div class="col-12 col-md-9">
            <?php $theView->dateTimeInput('datefrom')
                    ->setClass('fpcm-comments-search-input fpcm-ui-full-width-date')
                    ->setText('ARTICLE_SEARCH_DATE_FROM'); ?>
        </div>
        
        <div class="col-12 col-md-3">
            <?php $theView->select('combinationDatefrom')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-commentsearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setData(['']); ?>
        </div>
    </div>

    <div class="row">

        <div class="col-12 col-md-9">
            <?php $theView->dateTimeInput('dateto')
                    ->setClass('fpcm-comments-search-input fpcm-ui-full-width-date')
                    ->setText('ARTICLE_SEARCH_DATE_TO'); ?>
        </div>
        
        <div class="col-12 col-md-3">
            <?php $theView->select('combinationDateto')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-commentsearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

    <div class="row">

        <div class="col-12 col-md-9">
            <?php $theView->select('spam')
                    ->setOptions($searchSpam)
                    ->setText('COMMMENT_SPAM')
                    ->setClass('fpcm-comments-search-input fpcm-ui-input-select-commentsearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
        
        <div class="col-12 col-md-3">
            <?php $theView->select('combinationSpam')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-commentsearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

    <div class="row">

        <div class="col-12 col-md-9">
            <?php $theView->select('approved')
                    ->setOptions($searchApproval)
                    ->setText('COMMMENT_APPROVE')
                    ->setClass('fpcm-comments-search-input fpcm-ui-input-select-commentsearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
        
        <div class="col-12 col-md-3">
            <?php $theView->select('combinationApproved')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-commentsearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

    <div class="row">

        <div class="col-12 col-md-9">
            <?php $theView->select('private')
                    ->setOptions($searchPrivate)
                    ->setText('COMMMENT_PRIVATE')
                    ->setClass('fpcm-comments-search-input fpcm-ui-input-select-commentsearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
        
        <div class="col-12 col-md-3">
            <?php $theView->select('combinationPrivate')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-commentsearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

    <div class="row">

        <div class="col-12 col-md-9">
            <?php $theView->textInput('articleId')
                    ->setClass('fpcm-comments-search-input fpcm-ui-input-articleid')
                    ->setText('COMMMENT_SEARCH_ARTICLE')
                    ->setPlaceholder('0'); ?>
        </div>
        
        <div class="col-12 col-md-3">
            <?php $theView->select('combinationArticleid')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-commentsearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

</div>
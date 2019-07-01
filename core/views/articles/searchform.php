<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden" id="fpcm-dialog-articles-search">

    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-8">
            <?php $theView->textInput('text')->setClass('fpcm-articles-search-input')->setMaxlenght(255)->setText('ARTICLE_SEARCH_TEXT')->setPlaceholder(true); ?>
        </div>
        <div class="col-sm-12 col-md-4">
            <?php $theView->select('searchtype')
                    ->setOptions($searchTypes)
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setSelected(-1)
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-4">
            <?php $theView->select('userid')
                    ->setOptions($searchUsers)
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
        <div class="col-sm-12 col-md-4">
            <?php $theView->select('categoryid')
                    ->setOptions($searchCategories)
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
        <div class="col-sm-12 col-md-4"></div>
    </div>

    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-4">
            <?php $theView->select('pinned')
                    ->setOptions($searchPinned)
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
        <div class="col-sm-12 col-md-4">
            <?php $theView->select('postponed')
                    ->setOptions($searchPostponed)
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
        <div class="col-sm-12 col-md-4">
            <?php $theView->select('comments')
                    ->setOptions($searchComments)
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-4">
            <?php $theView->select('approval')
                    ->setOptions($searchApproval)
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
        <div class="col-sm-12 col-md-4">
            <?php $theView->select('draft')
                    ->setOptions($searchDraft)
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
        <div class="col-sm-12 col-md-4"></div>
    </div>

    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-4">
            <?php $theView->dateTimeInput('datefrom')
                    ->setClass('fpcm-articles-search-input fpcm-ui-full-width-date')
                    ->setMaxlenght(10)
                    ->setText('ARTICLE_SEARCH_DATE_FROM')
                    ->setPlaceholder(true)
                    ->setData(['mindate' => $searchMinDate]); ?>
        </div>
        <div class="col-sm-12 col-md-4">
            <?php $theView->dateTimeInput('dateto')
                    ->setClass('fpcm-articles-search-input fpcm-ui-full-width-date')
                    ->setMaxlenght(10)
                    ->setText('ARTICLE_SEARCH_DATE_TO')
                    ->setPlaceholder(true); ?>
        </div>
        <div class="col-sm-12 col-md-4"></div>
    </div>

    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-4"></div>
        <div class="col-sm-12 col-md-4"></div>
        <div class="col-sm-12 col-md-4">
            <?php $theView->select('combination')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

</div>
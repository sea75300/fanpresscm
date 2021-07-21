<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm ui-hidden" id="fpcm-dialog-articles-search">

    <div class="row">
        
        <div class="col-12 col-md-4">
            <?php $theView->select('searchtype')
                    ->setOptions($searchTypes)
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setSelected(fpcm\model\articles\search::TYPE_COMBINED)
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>

        <div class="col-12 col-md-8">
            <?php $theView->textInput('text')->setClass('fpcm-articles-search-input')->setMaxlenght(255)->setText('ARTICLE_SEARCH_TEXT')->setPlaceholder(true)->setWrapper(true); ?>
        </div>
    </div>    

    <div class="row">
        
        <div class="col-12 col-md-4">
            <?php $theView->select('combinationDatefrom')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-articlesearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setData(['']); ?>
        </div>

        <div class="col-12 col-md-8">
            <?php $theView->dateTimeInput('datefrom')
                    ->setClass('fpcm-articles-search-input fpcm-ui-full-width-date')
                    ->setMaxlenght(10)
                    ->setText('ARTICLE_SEARCH_DATE_FROM')
                    ->setPlaceholder(true)
                    ->setData(['mindate' => $searchMinDate]); ?>
        </div>
    </div>    

    <div class="row">
        
        <div class="col-12 col-md-4">
            <?php $theView->select('combinationDateto')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-articlesearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>

        <div class="col-12 col-md-8">
            <?php $theView->dateTimeInput('dateto')
                    ->setClass('fpcm-articles-search-input fpcm-ui-full-width-date')
                    ->setMaxlenght(10)
                    ->setText('ARTICLE_SEARCH_DATE_TO')
                    ->setPlaceholder(true); ?>
        </div>
    </div>    
 
    <div class="row mb-3">
        
        <div class="col-12 col-md-4">
            <?php $theView->select('combinationUserid')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-articlesearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>

        <div class="col-12 col-md-8">
            <?php $theView->select('userid')
                    ->setOptions($searchUsers)
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

    <div class="row mb-3">
        
        <div class="col-12 col-md-4">
            <?php $theView->select('combinationCategoryid')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-articlesearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>

        <div class="col-12 col-md-8">
            <?php $theView->select('categoryid')
                    ->setOptions($searchCategories)
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

    <div class="row mb-3">
        
        <div class="col-12 col-md-4">
            <?php $theView->select('combinationPinned')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-articlesearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>

        <div class="col-12 col-md-8">
            <?php $theView->select('pinned')
                    ->setOptions($searchPinned)
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

    <div class="row mb-3">
        
        <div class="col-12 col-md-4">
            <?php $theView->select('combinationPostponed')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-articlesearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>

        <div class="col-12 col-md-8">
            <?php $theView->select('postponed')
                    ->setOptions($searchPostponed)
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

    <div class="row mb-3">
        
        <div class="col-12 col-md-4">
            <?php $theView->select('combinationComments')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-articlesearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>

        <div class="col-12 col-md-8">
            <?php $theView->select('comments')
                    ->setOptions($searchComments)
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

    <div class="row mb-3">
        
        <div class="col-12 col-md-4">
            <?php $theView->select('combinationApproval')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-articlesearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>

        <div class="col-12 col-md-8">
            <?php $theView->select('approval')
                    ->setOptions($searchApproval)
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

    <div class="row mb-3">
        
        <div class="col-12 col-md-4">
            <?php $theView->select('combinationDraft')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-articlesearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>

        <div class="col-12 col-md-8">
            <?php $theView->select('draft')
                    ->setOptions($searchDraft)
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

</div>
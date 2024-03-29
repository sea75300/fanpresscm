<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm ui-hidden mb-5" id="fpcm-dialog-articles-search">

    <div class="row mb-3">

        <div class="col-12 col-md-9">
            <?php $theView->textInput('text')
                    ->setClass('fpcm-articles-search-input')
                    ->setMaxlenght(255)
                    ->setText('ARTICLE_SEARCH_TEXT')
                    ->setPlaceholder($theView->translate('ARTICLE_SEARCH_TEXT'))
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''); ?>
        </div>
        
        <div class="col-12 col-md-3 align-self-center">
            <?php $theView->select('searchtype')
                    ->setOptions($searchTypes)
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setSelected(fpcm\model\articles\search::TYPE_COMBINED)
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText($theView->translate('ARTICLE_SEARCH_LOGIC'))
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''); ?>
        </div>
    </div>    

    <div class="row mb-3">

        <div class="col-12 col-md-9">
            <?php $theView->dateTimeInput('datefrom')
                    ->setClass('fpcm-articles-search-input')
                    ->setText('ARTICLE_SEARCH_DATE_FROM')
                    ->setMin($searchMinDate)
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''); ?>
        </div>
        
        <div class="col-12 col-md-3 align-self-center">
            <?php $theView->select('combinationDatefrom')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-articlesearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText($theView->translate('ARTICLE_SEARCH_LOGIC'))
                    ->setLabelTypeFloat()
                    ->setData([''])
                    ->setBottomSpace(''); ?>
        </div>
    </div>    

    <div class="row mb-3">

        <div class="col-12 col-md-9">
            <?php $theView->dateTimeInput('dateto')
                    ->setClass('fpcm-articles-search-input')
                    ->setText('ARTICLE_SEARCH_DATE_TO')
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''); ?>
        </div>
        
        <div class="col-12 col-md-3 align-self-center">
            <?php $theView->select('combinationDateto')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-articlesearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText($theView->translate('ARTICLE_SEARCH_LOGIC'))
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''); ?>
        </div>
    </div>    
 
    <div class="row mb-3">

        <div class="col-12 col-md-9">
            <?php $theView->select('userid')
                    ->setOptions($searchUsers)
                    ->setText('ARTICLE_SEARCH_USER')
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''); ?>
        </div>
        
        <div class="col-12 col-md-3 align-self-center">
            <?php $theView->select('combinationUserid')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-articlesearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText($theView->translate('ARTICLE_SEARCH_LOGIC'))
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''); ?>
        </div>
    </div>

    <div class="row mb-3">

        <div class="col-12 col-md-9">
            <?php $theView->select('categoryid')
                    ->setOptions($searchCategories)
                    ->setText('ARTICLE_SEARCH_CATEGORY')
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''); ?>
        </div>
        
        <div class="col-12 col-md-3 align-self-center">
            <?php $theView->select('combinationCategoryid')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-articlesearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText($theView->translate('ARTICLE_SEARCH_LOGIC'))
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''); ?>
        </div>
    </div>

    <div class="row mb-3">

        <div class="col-12 col-md-9">
            <?php $theView->select('pinned')
                    ->setOptions($searchPinned)
                    ->setText('ARTICLE_SEARCH_PINNED')
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''); ?>
        </div>
        
        <div class="col-12 col-md-3 align-self-center">
            <?php $theView->select('combinationPinned')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-articlesearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText($theView->translate('ARTICLE_SEARCH_LOGIC'))
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''); ?>
        </div>
    </div>

    <div class="row mb-3">

        <div class="col-12 col-md-9">
            <?php $theView->select('postponed')
                    ->setOptions($searchPostponed)
                    ->setText('ARTICLE_SEARCH_POSTPONED')
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''); ?>
        </div>
        
        <div class="col-12 col-md-3 align-self-center">
            <?php $theView->select('combinationPostponed')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-articlesearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText($theView->translate('ARTICLE_SEARCH_LOGIC'))
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''); ?>
        </div>
    </div>

    <div class="row mb-3">

        <div class="col-12 col-md-9">
            <?php $theView->select('comments')
                    ->setOptions($searchComments)
                    ->setText('ARTICLE_SEARCH_COMMENTS')
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''); ?>
        </div>
        
        <div class="col-12 col-md-3 align-self-center">
            <?php $theView->select('combinationComments')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-articlesearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText($theView->translate('ARTICLE_SEARCH_LOGIC'))
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''); ?>
        </div>
    </div>

    <div class="row mb-3">

        <div class="col-12 col-md-9">
            <?php $theView->select('approval')
                    ->setOptions($searchApproval)
                    ->setText('ARTICLE_SEARCH_APPROVAL')
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''); ?>
        </div>
        
        <div class="col-12 col-md-3 align-self-center">
            <?php $theView->select('combinationApproval')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-articlesearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText($theView->translate('ARTICLE_SEARCH_LOGIC'))
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''); ?>
        </div>
    </div>

    <div class="row mb-3">

        <div class="col-12 col-md-9">
            <?php $theView->select('draft')
                    ->setOptions($searchDraft)
                    ->setText('ARTICLE_SEARCH_DRAFT')
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-articlesearch')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''); ?>
        </div>
        
        <div class="col-12 col-md-3 align-self-center">
            <?php $theView->select('combinationDraft')
                    ->setOptions($searchCombination)
                    ->setClass('fpcm-ui-input-select-articlesearch-combination')
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText($theView->translate('ARTICLE_SEARCH_LOGIC'))
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''); ?>
        </div>
    </div>

</div>
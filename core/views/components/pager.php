<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if ($showPager) : ?>
<div class="row">
    <div class="col-sm-12 col-md-6 fpcm-ui-margin-center fpcm-ui-center">
        <div class="fpcm-ui-controlgroup">
            <?php $theView->linkButton('pagerBack')->setText('GLOBAL_BACK')->setUrl($backBtn > 1 ? $theView->self.'?module='.$listAction.'&page='.$backBtn : $theView->self.'?module='.$listAction)->setReadonly($backBtn ? false : true)->setIcon('chevron-circle-left')->setIconOnly(true)->setClass('fpcm-ui-pager-element'); ?>
            <?php $theView->select('pageSelect')->setOptions($pageSelectOptions)->setSelected($pageCurrent)->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)->setClass('fpcm-ui-pager-element'); ?>
            <?php $theView->linkButton('pagerNext')->setText('GLOBAL_NEXT')->setUrl($theView->self.'?module='.$listAction.'&page='.$nextBtn)->setReadonly($nextBtn ? false : true)->setIcon('chevron-circle-right')->setIconOnly(true)->setClass('fpcm-ui-pager-element'); ?>                
        </div>
    </div>
</div>
<?php endif; ?>
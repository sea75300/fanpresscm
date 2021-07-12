<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if ($showPager) : ?>
<div class="navbar">
    <div class="nav-item mx-1"><?php $theView->linkButton('pagerBack')->setText('GLOBAL_BACK')->setUrl($backBtn > 1 ? $theView->self.'?module='.$listAction.'&page='.$backBtn : $theView->self.'?module='.$listAction)->setReadonly($backBtn ? false : true)->setIcon('chevron-circle-left')->setIconOnly(true)->setClass('fpcm-ui-pager-element'); ?></div>
    <div class="nav-item mx-1 d-none d-md-block"><?php $theView->select('pageSelect')->setOptions($pageSelectOptions)->setSelected($pageCurrent)->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)->setClass('fpcm-ui-pager-element'); ?></div>
    <div class="nav-item mx-1"><?php $theView->linkButton('pagerNext')->setText('GLOBAL_NEXT')->setUrl($theView->self.'?module='.$listAction.'&page='.$nextBtn)->setReadonly($nextBtn ? false : true)->setIcon('chevron-circle-right')->setIconOnly(true)->setClass('fpcm-ui-pager-element'); ?></div>
</div>
<?php endif; ?>
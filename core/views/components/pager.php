<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if ($showPager) : ?>
<div class="row">
    <div class="btn-group justify-content-end"  role="group">
        <?php $theView->linkButton('pagerBack')->setText('GLOBAL_BACK')->setUrl($backBtn > 1 ? $theView->self.'?module='.$listAction.'&page='.$backBtn : $theView->self.'?module='.$listAction)->setReadonly($backBtn ? false : true)->setIcon('chevron-circle-left')->setIconOnly(true)->setClass('fpcm-ui-pager-element'); ?>
        <div class="dropdown" id="pageSelect">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="pageSelectButton" data-bs-toggle="dropdown" aria-expanded="false">
                Dropdown button
            </button>
            <ul class="dropdown-menu" aria-labelledby="pageSelectButton" id="pageSelectList">
            <?php foreach ($pageSelectOptions as $descr => $value) : ?>
                <li><a class="dropdown-item" href="<?php print $value; ?>"><?php print $descr; ?></a></li>
            <?php endforeach; ?>
            </ul>
        </div>            
            
            
        <?php $theView->linkButton('pagerNext')->setText('GLOBAL_NEXT')->setUrl($theView->self.'?module='.$listAction.'&page='.$nextBtn)->setReadonly($nextBtn ? false : true)->setIcon('chevron-circle-right')->setIconOnly(true)->setClass('fpcm-ui-pager-element'); ?>                
    </div>
</div>
<?php endif; ?>
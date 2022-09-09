<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php if ($theView->buttons || $theView->pager) : ?>
<div class="navbar navbar-dark border-bottom border-1 border-secondary sticky-lg-top fpcm ui-background-white-50p py-0 <?php if (!empty($toolbarClass)) : print $toolbarClass; endif; ?>" id="fpcm-ui-toolbar" data-fpcm-toolbar="<?php $theView->escape($theView->toolbarArea); ?>">
    
        <?php if ($theView->buttons) : ?>
            <div class="navbar d-flex gap-1 ms-2 me-auto pe-2">
            <?php foreach ($theView->buttons as $button) : ?>
                <?php $button->setClass('shadow-sm'); ?>
                    <?php print $button; ?>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="navbar ms-auto gap-1 me-2">
            <?php if ($theView->toolbarItemRight) : ?>
            <div class="nav-item">
                <?php print $theView->toolbarItemRight; ?>
            </div>
            <?php endif; ?>
            <?php if ($theView->pager) : ?>
            <?php print $theView->pager; ?>                
            <?php endif; ?>
        </div>        

</div>
<?php endif; ?>
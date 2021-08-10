<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php if ($theView->buttons || $theView->pager) : ?>
<div class="navbar navbar-dark fpcm ui-background-white-50p mb-3" id="fpcm-ui-toolbar">
    
    <div class="container-fluid justify-content-start">
    <?php if ($theView->buttons) : ?>
        <div class="navbar me-auto d-flex">
        <?php foreach ($theView->buttons as $button) : ?>
            <?php $button->setClass('shadow-sm'); ?>
            <div class="nav-item nav-item-left mx-1 align-self-center">
                <?php print $button; ?>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
        <div class="navbar ms-auto">
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

</div>
<?php else : ?>
<div class="mb-3"></div>
<?php endif; ?>
<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php if ($theView->buttons || $theView->pager) : ?>
<div class="navbar navbar-dark fpcm ui-background-white-50p" id="fpcm-ui-toolbar">
    
    <div class="container-fluid justify-content-start">
    <?php if ($theView->buttons) : ?>
        <div class="navbar me-auto d-flex gap-1">
        <?php foreach ($theView->buttons as $button) : ?>
            <?php $button->setClass('shadow-sm'); ?>
                <?php print $button; ?>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
        <div class="navbar ms-auto gap-1">
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
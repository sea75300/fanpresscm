<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php if ($theView->buttons || $theView->pager) : ?>
<div class="navbar fpcm ui-background-white-50p fpcm-ui-margin-lg-bottom" id="fpcm-ui-toolbar">
    
    <div class="container-fluid justify-content-start">
    <?php if ($theView->buttons) : ?>
        <div class="navbar me-auto">
        <?php foreach ($theView->buttons as $button) : ?>
            <div class="nav-item mx-1">
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
            <div class="nav-item">
                <div class="btn-group">
                    <?php print $theView->pager; ?>
                </div>
                
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    


</div>
<?php endif; ?>
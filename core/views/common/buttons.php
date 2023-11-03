<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php if ($theView->buttons || $theView->pager) : ?>
    <div 
        class="position-relative navbar navbar-dark border-bottom border-1 border-secondary sticky-lg-top fpcm ui-background-white-50p ui-blurring py-0 <?php if (!empty($toolbarClass)) : print $toolbarClass;
    endif; ?>"
        id="fpcm-ui-toolbar" 
        role="navigation"
        data-fpcm-toolbar="<?php $theView->escape($theView->toolbarArea); ?>"
        >
        
            <?php if ($theView->buttons) : ?>
            <div class="navbar d-flex gap-1 ms-2 me-auto pe-2">
                <?php
                foreach ($theView->buttons as $button) :
                    print $button;
                endforeach;
                ?>
            </div>
            <?php endif; ?>
        <div class="navbar ms-auto gap-1 me-2">
                <?php if ($theView->toolbarItemRight) : ?>
                <div class="nav-item">
                <?php print $theView->toolbarItemRight; ?>
                </div>
                <?php
            endif;

            if ($theView->pager) :
                print $theView->pager;
            endif;
            ?>

        <?php if ($theView->debugMode) : ?>
        <div class="d-flex d-lg-block justify-content-center mx-3 z-n1 text-body-tertiary fpcm ui-font-small" title="Toolbar: <?php $theView->escape($theView->toolbarArea); ?>">
            <span class="d-inline-block d-lg-block pe-3 pe-md-0">Toolbar:</span><span class="d-inline-block d-lg-block"><?php $theView->escape($theView->toolbarArea); ?></span>
        </div>
        <?php endif; ?>        
        </div>        

    </div>
<?php endif; ?>
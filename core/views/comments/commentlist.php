<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div class="fpcm-ui-tabs-general">
        <ul>
            <li><a href="#tabs-comments-active"><?php $theView->write('COMMMENT_HEADLINE'); ?></a></li>
        </ul>            

        <div id="tabs-comments-active">

            <div id="fpcm-dataview-commentlist-spinner" class="row no-gutters align-self-center fpcm-ui-inline-loader fpcm-ui-background-white-50p">
                <div class="col-12 fpcm-ui-center align-self-center">
                    <?php $theView->icon('spinner fa-inverse')->setSpinner('pulse')->setStack('circle')->setSize('2x'); ?>
                </div>
            </div> 

            <div id="fpcm-dataview-commentlist"></div>
        </div>
    </div>
</div>

<?php include $theView->getIncludePath('comments/searchform.php'); ?>
<?php if ($canEditComments && $canMassEdit) : ?><?php include $theView->getIncludePath('comments/massedit.php'); ?><?php endif; ?>


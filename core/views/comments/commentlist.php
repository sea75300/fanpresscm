<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row no-gutters fpcm-ui-full-height">
    <div class="col-12">
        <div class="fpcm-content-wrapper fpcm-ui-full-height">
            <div class="fpcm-ui-tabs-general">
                <ul>
                    <li><a href="#tabs-comments-active"><?php $theView->write('COMMMENT_HEADLINE'); ?></a></li>
                </ul>            

                <div id="tabs-comments-active">
                    <div id="fpcm-dataview-commentlist"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($canMassEdit) : ?><?php include $theView->getIncludePath('comments/searchform.php'); ?><?php endif; ?>
<?php if ($canEditComments) : ?><?php include $theView->getIncludePath('comments/massedit.php'); ?><?php endif; ?>


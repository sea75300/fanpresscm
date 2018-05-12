<div class="fpcm-content-wrapper">
    <div class="fpcm-ui-tabs-general">
        <ul>
            <li><a href="#tabs-comments-active"><?php $theView->write('COMMMENT_HEADLINE'); ?></a></li>
        </ul>            

        <div id="tabs-comments-active">
            <div id="fpcm-dataview-commentlist"></div>
        </div>
    </div>
</div>

<?php include $theView->getIncludePath('comments/searchform.php'); ?>
<?php if ($canEditComments) : ?><?php include $theView->getIncludePath('comments/massedit.php'); ?><?php endif; ?>


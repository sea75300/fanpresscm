<div class="fpcm-content-wrapper">
    <div class="fpcm-tabs-general">
        <ul>
            <li><a href="#tabs-comments-active"><?php $theView->write('HL_COMMENTS_MNG'); ?></a></li>
        </ul>            

        <div id="tabs-comments-active">
            <div id="fpcm-dataview-commentlist"></div>
        </div>
    </div>
</div>

<?php include $theView->getIncludePath('comments/searchform.php'); ?>
<?php if ($canEditComments) : ?><?php include $theView->getIncludePath('comments/massedit.php'); ?><?php endif; ?>


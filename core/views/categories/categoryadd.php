<div class="fpcm-content-wrapper">
    <div class="fpcm-ui-tabs-general">
        <ul>
            <li><a href="#tabs-category"><?php $theView->write('CATEGORIES_ADD'); ?></a></li>
        </ul>

        <div id="tabs-category">
            <?php include $theView->getIncludePath('categories/editor.php'); ?>
        </div>
    </div>
</div>
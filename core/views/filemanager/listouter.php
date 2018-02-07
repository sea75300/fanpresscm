<?php if ($mode == 2) : ?>
<div class="fpcm-inner-wrapper">
<?php endif; ?>
    
    <div class="fpcm-tabs-general">
        <ul>
            <li id="tabs-files-list-reload"><a href="#tabs-files-list"><?php $theView->lang->write('FILE_LIST_AVAILABLE'); ?></a></li>                
            <?php if ($permUpload) : ?><li><a href="#tabs-files-upload"><?php $theView->lang->write('FILE_LIST_UPLOADFORM'); ?></a></li><?php endif; ?>                
        </ul>

        <form method="post" action="<?php print $theView->self; ?>?module=files/list&mode=<?php print $mode; ?>">
            <div id="tabs-files-list">
                <div id="tabs-files-list-content"><?php include $theView->getIncludePath('filemanager/listinner.php'); ?></div>
                <?php include $theView->getIncludePath('filemanager/buttons.php'); ?>
            </div>            
        </form>

        <?php if ($permUpload) : ?>
        <div id="tabs-files-upload">
            <?php if ($newUploader) : ?>
                <?php include $theView->getIncludePath('filemanager/forms/jqupload.php'); ?>
            <?php else : ?>
                <?php include $theView->getIncludePath('filemanager/forms/phpuoload.php'); ?>
            <?php endif; ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<?php include $theView->getIncludePath('filemanager/searchform.php'); ?>
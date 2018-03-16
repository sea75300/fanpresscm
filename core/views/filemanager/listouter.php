<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-ui-inner-wrapper">
    <div class="fpcm-tabs-general" id="fpcm-files-tabs">
        <ul>
            <li data-toolbar-buttons="1" id="tabs-files-list-reload"><a href="#tabs-files-list"><?php $theView->write('FILE_LIST_AVAILABLE'); ?></a></li>                
            <?php if ($permUpload) : ?><li data-toolbar-buttons="2"><a href="#tabs-files-upload"><?php $theView->write('FILE_LIST_UPLOADFORM'); ?></a></li><?php endif; ?>                
        </ul>

        <div id="tabs-files-list">
            <div id="tabs-files-list-content"></div>
        </div>

        <?php if ($permUpload) : ?>
        <div id="tabs-files-upload">
            <?php if ($newUploader) : ?>
                <?php include $theView->getIncludePath('filemanager/forms/jqupload.php'); ?>
            <?php else : ?>
                <?php include $theView->getIncludePath('filemanager/forms/phpupload.php'); ?>
            <?php endif; ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<?php include $theView->getIncludePath('filemanager/searchform.php'); ?>


<?php if($mode > 1) : ?><?php $theView->button('opensearch', 'opensearch')->setClass('fpcm-ui-hidden'); ?><?php endif; ?>
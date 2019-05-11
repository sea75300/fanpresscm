<?php /* @var $theView fpcm\view\viewVars */ /* @var $file fpcm\model\files\image */ ?>
<?php if (!count($files)) : ?>

<p class="fpcm-ui-padding-none fpcm-ui-margin-none"><?php $theView->icon('images', 'far')->setStack('ban fpcm-ui-important-text')->setSize('lg')->setStackTop(true); ?> <?php $theView->write('GLOBAL_NOTFOUND2'); ?></p>

<?php else : ?>

<?php include $theView->getIncludePath('components/pager.php'); ?>

<div class="row">
<?php foreach($files AS $file) : ?>
    <div class="col-12 col-sm-6 col-lg-4 fpcm-ui-padding-none-lr fpcm-filelist-thumb-box fpcm-ui-center">
        <div class="fpcm-filelist-thumb-box-inner fpcm-ui-background-transition">
            <div class="fpcm-ui-center">
                <a href="<?php print $file->getImageUrl(); ?>" target="_blank" class="fpcm-link-fancybox" data-fancybox="group" >
                    <img src="<?php if (file_exists($file->getFileManagerThumbnail())) : ?><?php print $file->getFileManagerThumbnailUrl(); ?><?php else : ?><?php print $theView->themePath; ?>dummy.png<?php endif; ?>" width="100" height="100" title="<?php print $file->getFileName(); ?>">
                </a>
                
                <p class="fpcm-ui-padding-md-tb fpcm-ui-margin-none"><?php print $theView->escapeVal(basename($file->getFilename())); ?></p>
                
                <?php if (!$file->existsFolder()) : ?>
                <div class="row fpcm-ui-padding-md-tb fpcm-ui-important-text align-self-center">
                    <div class="col-12 col-md-2">
                        <?php $theView->icon('images', 'far')->setStack('ban')->setSize('lg')->setStackTop(true); ?>
                    </div>
                    <div class="col-12 col-md-10 align-self-center">
                        <?php $theView->write('FILE_LIST_UPLOAD_NOTFOUND'); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="fpcm-filelist-actions-box fpcm-ui-center fpcm-ui-font-small">
                <div class="fpcm-filelist-actions fpcm-ui-controlgroup fpcm-filelist-actions-checkbox">
                    <?php include $theView->getIncludePath('filemanager/buttons.php'); ?>
                </div>

                <div class="fpcm-ui-clear"></div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<span id="fpcm-filelist-images-finished"></span>

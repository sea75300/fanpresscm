<?php /* @var $theView fpcm\view\viewVars */ /* @var $file fpcm\model\files\image */ ?>
<?php if (!count($files)) : ?>

<p class="fpcm-ui-padding-none fpcm-ui-margin-none"><?php $theView->icon('images', 'far')->setStack('ban fpcm-ui-important-text')->setSize('lg')->setStackTop(true); ?> <?php $theView->write('GLOBAL_NOTFOUND2'); ?></p>

<?php else : ?>

<div class="fpcm-ui-margin-md-bottom">
    <?php include $theView->getIncludePath('components/pager.php'); ?>
</div>

<?php foreach($files AS $file) : ?>
<div class="row fpcm-filelist-thumb-box fpcm-ui-center">
    <div class="col-12 fpcm-ui-padding-none">
        <div class="row fpcm-filelist-thumb-box-inner fpcm-ui-background-transition p-3 my-1 ui-corner-all">

            <div class="col-12 col-lg-3 align-self-center fpcm-ui-padding-none-lr">
                <div class="fpcm-filelist-actions-box fpcm-ui-center fpcm-ui-font-small">
                    <div class="fpcm-filelist-actions fpcm-ui-controlgroup fpcm-filelist-actions-checkbox">
                        <?php include $theView->getIncludePath('filemanager/buttons.php'); ?>
                    </div>
                </div>
            </div>
 
            <div class="col-12 col-lg-3 align-self-center fpcm-ui-padding-none-lr fpcm-ui-center">
                <a href="<?php print $file->getImageUrl(); ?>" target="_blank" class="fpcm-link-fancybox" data-fancybox="group" >
                    <img src="<?php if (file_exists($file->getFileManagerThumbnail())) : ?><?php print $file->getFileManagerThumbnailUrl(); ?><?php else : ?><?php print $theView->themePath; ?>dummy.png<?php endif; ?>" width="100" height="100" title="<?php print $file->getFileName(); ?>">
                </a>
            </div>

            <div class="col-12 col-lg-3 align-self-center fpcm-ui-padding-none-lr fpcm-ui-center">
                <?php print $theView->escapeVal(basename($file->getFilename())); ?>
            </div>

            <div class="col-12 col-lg-3 align-self-center fpcm-filelist-meta fpcm-ui-align-left">
                
                <?php if (!$file->existsFolder() ) : ?>
                <div class="row fpcm-ui-padding-md-tb fpcm-ui-important-text">
                    <div class="col-5 col-sm-4 col-md-2 fpcm-ui-padding-none-lr fpcm-ui-center">
                        <?php $theView->icon('images', 'far')->setStack('ban')->setSize('lg')->setStackTop(true); ?>
                    </div>
                    <div class="col-7 col-sm-8 col-md-10 align-self-center fpcm-ui-padding-none-lr">
                        <?php $theView->write('FILE_LIST_UPLOAD_NOTFOUND'); ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>

<span id="fpcm-filelist-images-finished"></span>

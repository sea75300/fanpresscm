<?php /* @var $theView fpcm\view\viewVars */ /* @var $file fpcm\model\files\image */ ?>
<?php if ($filterError) : ?>
<p class="p-0 m-0"><?php $theView->icon('search')->setStack('ban fpcm-ui-important-text')->setSize('lg')->setStackTop(true); ?> <?php $theView->write('SEARCH_ERROR'); ?></p>

<?php elseif (!count($files)) : ?>

<p class="p-0 m-0"><?php $theView->icon('images', 'far')->setStack('ban fpcm-ui-important-text')->setSize('lg')->setStackTop(true); ?> <?php $theView->write('GLOBAL_NOTFOUND2'); ?></p>

<?php else : ?>

<div class="mb-1">
    <?php include $theView->getIncludePath('components/pager.php'); ?>
</div>

<?php foreach($files AS $file) : ?>
<div class="row fpcm-filelist-thumb-box fpcm-ui-center">
    <div class="col-12 p-0">
        <div class="row fpcm-filelist-thumb-box-inner fpcm-ui-background-transition p-3 my-1 ui-corner-all">

            <div class="col-12 col-lg-3 align-self-center px-0">
                <div class="fpcm-filelist-actions-box fpcm-ui-center fpcm-ui-font-small">
                    <div class="fpcm-filelist-actions fpcm-ui-controlgroup fpcm-filelist-actions-checkbox">
                        <?php include $theView->getIncludePath('filemanager/buttons.php'); ?>
                    </div>
                </div>
            </div>
 
            <div class="col-12 col-lg-3 align-self-center px-0 fpcm-ui-center">
                <a href="<?php print $file->getImageUrl(); ?>" target="_blank" class="fpcm-link-fancybox" data-fancybox="group" >
                    <img loading="lazy" src="<?php if (file_exists($file->getFileManagerThumbnail())) : ?><?php print $file->getFileManagerThumbnailUrl(); ?><?php else : ?><?php print $theView->themePath; ?>dummy.png<?php endif; ?>" width="100" height="100" title="<?php print $file->getFileName(); ?>">
                </a>
            </div>

            <div class="col-12 col-lg-3 align-self-center px-0 fpcm-ui-center">
                <?php print $theView->escapeVal(basename($file->getFilename())); ?>
            </div>

            <div class="col-12 col-lg-3 align-self-center fpcm-filelist-meta fpcm-ui-align-left">
                
                <?php if (!$file->existsFolder() ) : ?>
                <div class="row py-2 fpcm-ui-important-text">
                    <div class="col-5 col-sm-4 col-md-2 px-0 fpcm-ui-center">
                        <?php $theView->icon('images', 'far')->setStack('ban')->setSize('lg')->setStackTop(true); ?>
                    </div>
                    <div class="col-7 col-sm-8 col-md-10 align-self-center px-0">
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

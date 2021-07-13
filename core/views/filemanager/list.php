<?php /* @var $theView fpcm\view\viewVars */ /* @var $file fpcm\model\files\image */ ?>
<?php if ($filterError) : ?>
<p class="p-3"><?php $theView->icon('search')->setStack('ban fpcm-ui-important-text')->setSize('lg')->setStackTop(true); ?> <?php $theView->write('SEARCH_ERROR'); ?></p>
<?php elseif (!count($files)) : ?>
<p class="p-3"><?php $theView->icon('images', 'far')->setStack('ban fpcm-ui-important-text')->setSize('lg')->setStackTop(true); ?> <?php $theView->write('GLOBAL_NOTFOUND2'); ?></p>
<?php else : ?>

    <div class="row mb-1 justify-content-end">
        <div class="col-auto">
            <?php include $theView->getIncludePath('components/pager.php'); ?>
        </div>
    </div>


    <?php foreach($files AS $file) : ?>
    <div class="row g-0 px-3 fpcm ui-files-list">    
        <div class="card shadow-sm w-100 my-2 fpcm ui-files-item ui-background-transition">
            <div class="row g-0">
                <div class="col-auto">
                    <img class="img-fluid rounded-start" loading="lazy" src="<?php if (file_exists($file->getFileManagerThumbnail())) : ?><?php print $file->getFileManagerThumbnailUrl(); ?><?php else : ?><?php print $theView->themePath; ?>dummy.png<?php endif; ?>" title="<?php print $file->getFileName(); ?>">
                </div>
                <div class="col-auto align-self-center">
                    <div class="card-body">
                        <p class="card-title"><?php print $theView->escapeVal(basename($file->getFilename())); ?></p>
                        <p class="card-text">

                            <?php if (!$file->getAltText()) : ?>
                                <p><?php print $theView->escapeVal($file->getAltText()); ?></p>
                            <?php endif; ?>

                            <?php if (!$file->existsFolder()) : ?>
                            <div class="row fpcm-ui-important-text align-self-center">
                                <div class="col-12 col-md-2">
                                    <?php $theView->icon('images', 'far')->setStack('ban')->setSize('lg')->setStackTop(true); ?>
                                </div>
                                <div class="col-12 col-md-10 align-self-center">
                                    <?php $theView->write('FILE_LIST_UPLOAD_NOTFOUND'); ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </p>

                        <?php include $theView->getIncludePath('filemanager/buttons.php'); ?>
                    </div>
                </div>
            </div>            
        </div>        
    </div>
    <?php endforeach; ?>
<?php endif; ?>
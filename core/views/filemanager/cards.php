<?php /* @var $theView fpcm\view\viewVars */ /* @var $file fpcm\model\files\image */ ?>
<?php if ($filterError) : ?>
<p class="p-0 m-0"><?php $theView->icon('search')->setStack('ban fpcm-ui-important-text')->setSize('lg')->setStackTop(true); ?> <?php $theView->write('SEARCH_ERROR'); ?></p>

<?php elseif (!count($files)) : ?>

<p class="p-0 m-0"><?php $theView->icon('images', 'far')->setStack('ban fpcm-ui-important-text')->setSize('lg')->setStackTop(true); ?> <?php $theView->write('GLOBAL_NOTFOUND2'); ?></p>

<?php else : ?>

    <div class="row mb-1 justify-content-end">
        <div class="col-auto">
            <?php include $theView->getIncludePath('components/pager.php'); ?>
        </div>
    </div>

    <?php $i = 0; ?>
    <div class="card-group fpcm ui-files-card">
    <?php foreach($files AS $file) : ?>
    <?php $i++; ?>
        <div class="card w-100 my-2 mx-sm-2 rounded fpcm ui-files-item ui-background-transition">
            <img class="card-img-top rounded-top shadow-sm" loading="lazy" src="<?php if (file_exists($file->getFileManagerThumbnail())) : ?><?php print $file->getFileManagerThumbnailUrl(); ?><?php else : ?><?php print $theView->themePath; ?>dummy.png<?php endif; ?>" title="<?php print $file->getFileName(); ?>">

            <div class="card-body">
                <p class="card-title text-center"><?php print $theView->escapeVal(basename($file->getFilename())); ?></p>
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
    <?php if ($i % 4 === 0) : ?></div><div class="card-group ui-files-card"><?php endif; ?>
    <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php /* @var $theView fpcm\view\viewVars */ /* @var $file fpcm\model\files\image */ ?>
<?php if ($filterError) : ?>
<p class="p-3"><?php $theView->icon('search')->setStack('ban text-danger')->setSize('lg')->setStackTop(true); ?> <?php $theView->write('SEARCH_ERROR'); ?></p>
<?php elseif (!count($files)) : ?>
<p class="p-3"><?php $theView->icon('images', 'far')->setStack('ban text-danger')->setSize('lg')->setStackTop(true); ?> <?php $theView->write('GLOBAL_NOTFOUND2'); ?></p>
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
        <div class="card my-2 mx-sm-2 rounded fpcm ui-files-item ui-background-transition">
            
            <?php if (file_exists($file->getFileManagerThumbnail())) : ?>
                <img class="card-img-top rounded-top overflow-hidden" style="max-height: <?php print $thumbsize; ?>" loading="lazy" src="<?php print $file->getFileManagerThumbnailUrl(); ?>" title="<?php print $file->getFileName(); ?>">
            <?php else : ?>
                <div class="card-img-top text-center rounded-top h-75 my-3">
                    <div class="row g-0 h-100">
                        <div class="col align-self-center" style="height: <?php print $thumbsize; ?>">
                            <?php $theView->icon('file-image fa-inverse', 'far')->setStack('square')->setSize('3x'); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>            

            <div class="card-body">
                <p class="card-title text-center"><?php print $theView->escapeVal(basename($file->getFilename())); ?></p>
                <div class="card-text">

                    <?php if ($file->getAltText()) : ?>
                        <p class="text-center mb-0"><?php print $theView->escapeVal($file->getAltText()); ?></p>
                    <?php endif; ?>

                    <?php if (!$file->existsFolder()) : ?>
                    <div class="row text-danger">
                        <div class="col-auto text-center align-self-center">
                            <?php $theView->icon('images', 'far')->setStack('ban')->setStackTop(true); ?>
                        </div>
                        <div class="col align-self-center">
                            <?php $theView->write('FILE_LIST_UPLOAD_NOTFOUND'); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <?php include $theView->getIncludePath('filemanager/buttons.php'); ?>
            </div>
        </div>        
    <?php if ($i % 5 === 0) : ?></div><div class="card-group ui-files-card"><?php endif; ?>
    <?php endforeach; ?>
    </div>
<?php endif; ?>

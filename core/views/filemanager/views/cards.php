<?php /* @var $theView fpcm\view\viewVars */ /* @var $file fpcm\model\files\image */ ?>
<div class="justify-content-end">

    <?php if ($showPager && in_array($mode, [2, 3, 4])) : ?>
    <div class="navbar">
        <div class="container-fluid">
            <div class="navbar me-auto d-flex gap-1"></div>
            <div class="navbar ms-auto gap-1">
                <?php print $pager; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($filterError) : ?>
    <p class="p-3"><?php $theView->icon('search')->setStack('ban text-danger')->setSize('lg')->setStackTop(true); ?> <?php $theView->write('SEARCH_ERROR'); ?></p>
    <?php elseif (!count($files)) : ?>
    <p class="p-3"><?php $theView->icon('images', 'far')->setStack('ban text-danger')->setSize('lg')->setStackTop(true); ?> <?php $theView->write('GLOBAL_NOTFOUND2'); ?></p>
    <?php else : ?>
        <?php $i = 0; ?>
        <div class="card-group g-0 fpcm ui-files-card">
        <?php foreach($files AS $file) : ?>
        <?php $i++; ?>
            <div class="card my-2 mx-sm-2 rounded fpcm ui-files-item ui-background-transition shadow">

                <a href="<?php print $file->getImageUrl(); ?>"
                   class="fpcm ui-link-fancybox"
                   data-pswp-width="<?php print $file->getWidth(); ?>"
                   data-pswp-height="<?php print $file->getHeight(); ?>"
                   <?php if ($file->getAltText()) : ?>data-caption="<?php print $theView->escapeVal($file->getAltText()); ?>"<?php endif; ?>>
                <?php if ($file->hasFileManageThumbnail()) : ?>
                    <img class="card-img-top rounded-top overflow-hidden" loading="lazy" src="<?php print $file->getFileManagerThumbnailUrl(); ?>" title="<?php print $file->getFileName(); ?>" <?php if ($file->getAltText()) : ?>alt="<?php print $theView->escapeVal($file->getAltText()); ?>"<?php endif; ?> >
                <?php else : ?>
                    <img class="card-img-top rounded-top overflow-hidden p-5" loading="lazy" src="<?php print fpcm\classes\loader::libGetFileUrl('font-awesome/svg/image.svg'); ?>" title="<?php print $file->getFileName(); ?>">
                <?php endif; ?>
                </a>

                <div class="card-body">
                    <p class="card-title text-center"><?php print $theView->escapeVal(basename($file->getFilename())); ?></p>
                    <?php if ($file->getAltText()) : ?>
                    <p class="card-subtitle text-center fs-6 text-secondary-emphasis"><?php print $theView->escapeVal($file->getAltText()); ?></p>
                    <?php endif; ?>

                    <?php if (!$file->existsFolder()) : ?>
                    <div class="card-text">
                        <?php $theView->alert('danger')->setIcon('image', 'far')->setText('FILE_LIST_UPLOAD_NOTFOUND'); ?>
                    </div>
                    <?php endif; ?>

                </div>

                <div class="card-footer bg-transparent">
                    <div class="navbar gap-1 justify-content-center">
                        <?php include $theView->getIncludePath('filemanager/buttons.php'); ?>
                    </div>
                </div>
            </div>
            <?php if ($is_last($i)) : ?>
            </div><div class="card-group g-0 fpcm ui-files-card">
            <?php endif; ?>
        <?php endforeach; ?>
        <?php print implode('', array_fill(1, $addColsToEnd, '<div class="card my-2 mx-sm-2 border-0 bg-transparent">&nbsp;</div>')); ?>
        </div>
    <?php endif; ?>
</div>

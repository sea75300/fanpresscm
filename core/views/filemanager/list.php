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
        <?php foreach($files AS $file) : ?>
        <div class="row g-0 px-3 fpcm ui-files-list">    
            <div class="card w-100 my-2 fpcm ui-files-item ui-background-transition shadow">
                <div class="row g-0">
                    <div class="col-auto align-self-center me-3">
                        <a href="<?php print $file->getImageUrl(); ?>" class="fpcm ui-link-fancybox" data-fancybox="group" <?php if ($file->getAltText()) : ?>data-caption="<?php print $theView->escapeVal($file->getAltText()); ?>"<?php endif; ?>>
                        <?php if (file_exists($file->getFileManagerThumbnail())) : ?>
                            <img class="img-fluid rounded-start" loading="lazy" src="<?php print $file->getFileManagerThumbnailUrl(); ?>" title="<?php print $file->getFileName(); ?>" width="<?php print $thumbsize; ?>" height="<?php print $thumbsize; ?>">
                        <?php else : ?>
                            <img class="img-fluid rounded-start p-5" loading="lazy" src="<?php print fpcm\classes\loader::libGetFileUrl('font-awesome/svg/image.svg'); ?>" title="<?php print $file->getFileName(); ?>" width="<?php print $thumbsize; ?>" height="<?php print $thumbsize; ?>">
                        <?php endif; ?>
                        </a>
                    </div>
                    <div class="col-auto align-self-center flex-grow-1">
                        <div class="card-body">
                            <p class="card-title"><?php print $theView->escapeVal(basename($file->getFilename())); ?></p>
                            <?php if ($file->getAltText()) : ?>
                            <p class="card-subtitle fs-6 text-secondary"><?php print $theView->escapeVal($file->getAltText()); ?></p>           
                            <?php endif; ?>
                            <div class="card-text mb-3">
                                <?php if (!$file->existsFolder()) : ?>
                                    <?php $theView->alert('danger')->setIcon('image', 'far')->setText('FILE_LIST_UPLOAD_NOTFOUND'); ?>
                                <?php endif; ?>
                            </div>

                            <div class="navbar gap-1 justify-content-start">
                            <?php include $theView->getIncludePath('filemanager/buttons.php'); ?>
                            </div>
                        </div>
                    </div>
                </div>            
            </div>        
        </div>
        <?php endforeach; ?>
    <?php endif; ?>    
</div>
<?php /* @var $theView fpcm\view\viewVars */ /* @var $file fpcm\model\files\image */ ?>
<div class="justify-content-end border-top border-5 border-primary">
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


        <?php foreach($files AS $file) : ?>
        <div class="row g-0 px-3 fpcm ui-files-list">    
            <div class="card shadow-sm w-100 my-2 fpcm ui-files-item ui-background-transition">
                <div class="row g-0">
                    <div class="col-auto align-self-center">
                        <a href="<?php print $file->getImageUrl(); ?>" class="fpcm ui-link-fancybox" data-fancybox="group">
                        <?php if (file_exists($file->getFileManagerThumbnail())) : ?>
                            <img class="img-fluid rounded-start" loading="lazy" src="<?php print $file->getFileManagerThumbnailUrl(); ?>" title="<?php print $file->getFileName(); ?>" width="<?php print $thumbsize; ?>" height="<?php print $thumbsize; ?>">
                        <?php else : ?>
                            <img class="img-fluid rounded-start p-5" loading="lazy" src="<?php print fpcm\classes\loader::libGetFileUrl('font-awesome/svg/image.svg'); ?>" title="<?php print $file->getFileName(); ?>" width="<?php print $thumbsize; ?>" height="<?php print $thumbsize; ?>">
                        <?php endif; ?>
                        </a>
                    </div>
                    <div class="col-auto align-self-center">
                        <div class="card-body">
                            <p class="card-title"><?php print $theView->escapeVal(basename($file->getFilename())); ?></p>
                            <div class="card-text">

                                <?php if ($file->getAltText()) : ?>
                                    <p class="mb-0"><?php print $theView->escapeVal($file->getAltText()); ?></p>
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
                </div>            
            </div>        
        </div>
        <?php endforeach; ?>
    <?php endif; ?>    
</div>
    
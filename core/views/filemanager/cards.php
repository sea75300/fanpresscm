<?php /* @var $theView fpcm\view\viewVars */ /* @var $file fpcm\model\files\image */ ?>
<div class="justify-content-end border-top border-5 border-primary">
    <?php if ($filterError) : ?>
    <p class="p-3"><?php $theView->icon('search')->setStack('ban text-danger')->setSize('lg')->setStackTop(true); ?> <?php $theView->write('SEARCH_ERROR'); ?></p>
    <?php elseif (!count($files)) : ?>
    <p class="p-3"><?php $theView->icon('images', 'far')->setStack('ban text-danger')->setSize('lg')->setStackTop(true); ?> <?php $theView->write('GLOBAL_NOTFOUND2'); ?></p>
    <?php else : ?>
        <?php $i = 0; ?>
        <div class="card-group fpcm ui-files-card">
        <?php foreach($files AS $file) : ?>
        <?php $i++; ?>
            <div class="card my-2 mx-sm-2 rounded fpcm ui-files-item ui-background-transition">

                <a href="<?php print $file->getImageUrl(); ?>" class="fpcm ui-link-fancybox" data-fancybox="group" <?php if ($file->getAltText()) : ?>data-caption="<?php print $theView->escapeVal($file->getAltText()); ?>"<?php endif; ?>>
                <?php if (file_exists($file->getFileManagerThumbnail())) : ?>
                    <img class="card-img-top rounded-top overflow-hidden" loading="lazy" src="<?php print $file->getFileManagerThumbnailUrl(); ?>" title="<?php print $file->getFileName(); ?>">                    
                <?php else : ?>
                    <img class="card-img-top rounded-top overflow-hidden p-5" loading="lazy" src="<?php print fpcm\classes\loader::libGetFileUrl('font-awesome/svg/image.svg'); ?>" title="<?php print $file->getFileName(); ?>">
                <?php endif; ?>            
                </a>

                <div class="card-body">
                    <p class="card-title text-center"><?php print $theView->escapeVal(basename($file->getFilename())); ?></p>
                    <?php if ($file->getAltText()) : ?>
                    <p class="card-subtitle text-center fs-6 text-secondary"><?php print $theView->escapeVal($file->getAltText()); ?></p>           
                    <?php endif; ?>
                    
                    <?php if (!$file->existsFolder()) : ?>
                    <div class="card-text">
                        <?php $theView->alert('danger')->setIcon('image', 'far')->setText('FILE_LIST_UPLOAD_NOTFOUND'); ?>
                    </div>
                    <?php endif; ?>

                </div>
                
                <div class="card-footer bg-transparent">
                    <?php include $theView->getIncludePath('filemanager/buttons.php'); ?>
                </div>
            </div>        
        <?php if ($is_last($i)) : ?></div><div class="card-group ui-files-card"><?php endif; ?>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

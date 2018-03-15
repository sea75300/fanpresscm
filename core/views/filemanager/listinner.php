<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php if (!count($files)) : ?>

<p class="fpcm-ui-padding-none fpcm-ui-margin-none"><?php $theView->icon('file-image-o')->setStack('ban fpcm-ui-important-text')->setSize('lg')->setStackTop(true); ?> <?php $theView->write('GLOBAL_NOTFOUND2'); ?></p>

<?php else : ?>

    <?php foreach($files AS $file) : ?>
    <div class="col-sm-6 col-md-5 col-lg-4 fpcm-ui-padding-none-lr fpcm-filelist-thumb-box">
        <div class="fpcm-filelist-thumb-box-inner">
            <div class="fpcm-ui-center">
                <a href="<?php print $file->getImageUrl(); ?>" target="_blank" class="fpcm-link-fancybox" data-fancybox="group" >
                    <img src="<?php if (file_exists($file->getFileManagerThumbnail())) : ?><?php print $file->getFileManagerThumbnailUrl(); ?><?php else : ?><?php print $theView->themePath; ?>dummy.png<?php endif; ?>" width="100" height="100" title="<?php print $file->getFileName(); ?>">
                </a>
            </div>

            <div class="fpcm-filelist-actions-box fpcm-ui-center fpcm-ui-font-small">
                <div class="fpcm-filelist-actions fpcm-ui-controlgroup fpcm-filelist-actions-checkbox">
                    <?php if ($mode == 2) : ?>                    
                        <?php $theView->linkButton(uniqid('thumbsurl'))->setUrl($file->getThumbnailUrl())->setText('FILE_LIST_INSERT_THUMB')->setClass('fpcm-filelist-tinymce-thumb')->setIcon('star-half-o')->setIconOnly(true)->setData(['imgtext' => $file->getFilename()]); ?>
                        <?php $theView->linkButton(uniqid('imgsurl'))->setUrl($file->getImageUrl())->setText('FILE_LIST_INSERT_FULL')->setClass('fpcm-filelist-tinymce-full')->setIcon('star')->setIconOnly(true)->setData(['imgtext' => $file->getFilename()]); ?>
                    <?php elseif ($mode == 3) : ?>                    
                        <?php $theView->linkButton(uniqid('articleimg'))->setUrl($file->getImageUrl())->setText('EDITOR_ARTICLEIMAGE')->setClass('fpcm-filelist-articleimage')->setIcon('picture-o')->setIconOnly(true)->setData(['imgtext' => $file->getFilename()]); ?>
                    <?php else: ?>
                        <?php $theView->checkbox('filenames[]', 'cb_'. md5($file->getFilename()))->setClass('fpcm-ui-list-checkbox')->setValue(base64_encode($file->getFilename())); ?>
                        <?php $theView->linkButton(uniqid('thumbs'))->setUrl($file->getThumbnailUrl())->setText('FILE_LIST_OPEN_THUMB')->setClass('fpcm-filelist-link-thumb')->setIcon('file-image-o')->setIconOnly(true)->setTarget('_blank'); ?>
                        <?php $theView->linkButton(uniqid('imgurl'))->setUrl($file->getImageUrl())->setText('FILE_LIST_OPEN_FULL')->setClass('fpcm-filelist-link-full fpcm-file-list-link')->setIcon('search-plus')->setIconOnly(true)->setTarget('_blank'); ?>
                    <?php endif; ?>
                </div>

                <div class="fpcm-ui-clear"></div>
            </div> 

            <div class="fpcm-filelist-meta fpcm-ui-left fpcm-ui-font-small">
                <table class="fpcm-ui-table fpcm-ui-nobg">
                    <tr>
                        <td><strong><?php $theView->write('FILE_LIST_UPLOAD_DATE'); ?>:</strong></td>
                        <td><?php $theView->dateText($file->getFiletime()); ?></td>                    
                    </tr>
                    <tr>
                        <td><strong><?php $theView->write('FILE_LIST_UPLOAD_BY'); ?>:</strong></td>
                        <td><?php print isset($users[$file->getUserid()]) ? $users[$file->getUserid()]->getDisplayName() : $theView->translate('GLOBAL_NOTFOUND'); ?></td>                    
                    </tr>
                    <tr>
                        <td><strong><?php $theView->write('FILE_LIST_FILESIZE'); ?>:</strong></td>
                        <td><?php print \fpcm\classes\tools::calcSize($file->getFilesize()); ?></td>                    
                    </tr>
                    <tr>
                        <td><strong><?php $theView->write('FILE_LIST_RESOLUTION'); ?>:</strong></td>
                        <td><?php print $file->getWidth(); ?> <span class="fa fa-times fa-fw"></span> <?php print $file->getHeight(); ?> <?php $theView->write('FILE_LIST_RESOLUTION_PIXEL'); ?></td>                    
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

<?php endif; ?>

<div class="fpcm-ui-clear"></div>

<span id="fpcm-filelist-images-finished"></span>

<?php include $theView->getIncludePath('components/pager.php'); ?>
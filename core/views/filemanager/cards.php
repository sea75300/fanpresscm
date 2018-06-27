<?php /* @var $theView fpcm\view\viewVars */ /* @var $file fpcm\model\files\image */ ?>
<?php if (!count($files)) : ?>

<p class="fpcm-ui-padding-none fpcm-ui-margin-none"><?php $theView->icon('images', 'far')->setStack('ban fpcm-ui-important-text')->setSize('lg')->setStackTop(true); ?> <?php $theView->write('GLOBAL_NOTFOUND2'); ?></p>

<?php else : ?>

<?php include $theView->getIncludePath('components/pager.php'); ?>

<div class="row">
<?php foreach($files AS $file) : ?>
    <div class="col-12 col-sm-6 col-lg-4 fpcm-ui-padding-none-lr fpcm-filelist-thumb-box">
        <div class="fpcm-filelist-thumb-box-inner fpcm-ui-background-transition">
            <div class="fpcm-ui-center">
                <a href="<?php print $file->getImageUrl(); ?>" target="_blank" class="fpcm-link-fancybox" data-fancybox="group" >
                    <img src="<?php if (file_exists($file->getFileManagerThumbnail())) : ?><?php print $file->getFileManagerThumbnailUrl(); ?><?php else : ?><?php print $theView->themePath; ?>dummy.png<?php endif; ?>" width="100" height="100" title="<?php print $file->getFileName(); ?>">
                </a>
            </div>

            <div class="fpcm-filelist-actions-box fpcm-ui-center fpcm-ui-font-small">
                <div class="fpcm-filelist-actions fpcm-ui-controlgroup fpcm-filelist-actions-checkbox">
                    <?php $theView->checkbox('filenames[]', 'cb_'. md5($file->getFilename()))->setClass('fpcm-ui-list-checkbox')->setValue(base64_encode($file->getFilename())); ?>
                    <?php if ($mode == 2) : ?>                    
                        <?php $theView->linkButton(uniqid('thumbsurl'))->setUrl($file->getThumbnailUrl())->setText('FILE_LIST_INSERT_THUMB')->setClass('fpcm-filelist-tinymce-thumb')->setIcon('plus-square ', 'far')->setIconOnly(true)->setData(['imgtext' => $file->getFilename()]); ?>
                        <?php $theView->linkButton(uniqid('imgsurl'))->setUrl($file->getImageUrl())->setText('FILE_LIST_INSERT_FULL')->setClass('fpcm-filelist-tinymce-full')->setIcon('plus-square')->setIconOnly(true)->setData(['imgtext' => $file->getFilename()]); ?>
                    <?php elseif ($mode == 3) : ?>                    
                        <?php $theView->linkButton(uniqid('articleimg'))->setUrl($file->getImageUrl())->setText('EDITOR_ARTICLEIMAGE')->setClass('fpcm-filelist-articleimage')->setIcon('image')->setIconOnly(true)->setData(['imgtext' => $file->getFilename()]); ?>
                    <?php else: ?>
                        <?php $theView->linkButton(uniqid('thumbs'))->setUrl($file->getThumbnailUrl())->setText('FILE_LIST_OPEN_THUMB')->setClass('fpcm-filelist-link-thumb')->setIcon('image', 'far')->setIconOnly(true)->setTarget('_blank'); ?>
                        <?php $theView->linkButton(uniqid('imgurl'))->setUrl($file->getImageUrl())->setText('FILE_LIST_OPEN_FULL')->setClass('fpcm-filelist-link-full fpcm-file-list-link')->setIcon('search-plus')->setIconOnly(true)->setTarget('_blank'); ?>
                    <?php endif; ?>
                    <?php if ($canRename) : ?>
                        <?php $theView->button(uniqid('rename'))->setText('FILE_LIST_RENAME')->setIcon('edit')->setIconOnly(true)->setData(['file' => base64_encode($file->getFilename()), 'oldname' => basename($file->getFilename(), '.'.$file->getExtension())])->setClass('fpcm-filelist-rename'); ?>
                    <?php endif; ?>
                    <?php if ($permDelete) : ?>
                        <?php $theView->button(uniqid('delete'))->setText('GLOBAL_DELETE')->setIcon('trash')->setIconOnly(true)->setData(['file' => base64_encode($file->getFilename())])->setClass('fpcm-filelist-delete'); ?>
                    <?php endif; ?>
                </div>

                <div class="fpcm-ui-clear"></div>
            </div> 

            <div class="fpcm-filelist-meta fpcm-ui-left fpcm-ui-font-small">
                
                <?php if (!$file->existsFolder() ) : ?>
                <div class="row fpcm-ui-padding-md-tb fpcm-ui-important-text">
                    <div class="col-5 col-sm-4 col-md-2 fpcm-ui-padding-none-lr fpcm-ui-center">
                        <?php $theView->icon('images', 'far')->setStack('ban')->setSize('2x')->setStackTop(true); ?>
                    </div>
                    <div class="col-7 col-sm-8 col-md-10 align-self-center fpcm-ui-padding-none-lr">
                        <?php $theView->write('FILE_LIST_UPLOAD_NOTFOUND'); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="row fpcm-ui-padding-md-tb">
                    <div class="col-5 col-sm-4 col-md-2 fpcm-ui-padding-none-lr fpcm-ui-center">
                        <?php $theView->icon('calendar-alt', 'far')->setText('FILE_LIST_UPLOAD_DATE')->setSize('2x'); ?>
                    </div>
                    <div class="col-7 col-sm-8 col-md-10 align-self-center fpcm-ui-padding-none-lr">
                        <?php $theView->dateText($file->getFiletime()); ?>
                    </div>
                </div>
                
                <div class="row fpcm-ui-padding-md-tb">
                    <div class="col-5 col-sm-4 col-md-2 fpcm-ui-padding-none-lr fpcm-ui-center">
                        <?php $theView->icon('user')->setText('FILE_LIST_UPLOAD_BY')->setSize('2x'); ?>
                    </div>
                    <div class="col-7 col-sm-8 col-md-10 align-self-center fpcm-ui-padding-none-lr">
                        <?php print isset($users[$file->getUserid()]) ? $users[$file->getUserid()]->getDisplayName() : $theView->translate('USERS_SYSTEMUSER'); ?>
                    </div>
                </div>
                
                <div class="row fpcm-ui-padding-md-tb">
                    <div class="col-5 col-sm-4 col-md-2 fpcm-ui-padding-none-lr fpcm-ui-center">
                        <?php $theView->icon('weight')->setText('FILE_LIST_FILESIZE')->setSize('2x'); ?>
                    </div>
                    <div class="col-7 col-sm-8 col-md-10 align-self-center fpcm-ui-padding-none-lr">
                        <?php print \fpcm\classes\tools::calcSize($file->getFilesize()); ?>
                    </div>
                </div>
                
                <div class="row fpcm-ui-padding-md-tb">
                    <div class="col-5 col-sm-4 col-md-2 fpcm-ui-padding-none-lr fpcm-ui-center">
                        <?php $theView->icon('expand-arrows-alt')->setText('FILE_LIST_RESOLUTION')->setSize('2x'); ?>
                    </div>
                    <div class="col-7 col-sm-8 col-md-10 align-self-center fpcm-ui-padding-none-lr">
                        <?php if ($file->getWidth() && $file->getHeight() ) : ?>
                        <?php print $file->getWidth(); ?> <span class="fa fa-times fa-fw"></span> <?php print $file->getHeight(); ?> <?php $theView->write('FILE_LIST_RESOLUTION_PIXEL'); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<span id="fpcm-filelist-images-finished"></span>

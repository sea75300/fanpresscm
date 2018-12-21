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
                
                <p class="fpcm-ui-padding-md-tb fpcm-ui-margin-none"><?php print $theView->escapeVal(basename($file->getFilename())); ?></p>
                
                <?php if (!$file->existsFolder()) : ?>
                <div class="row fpcm-ui-padding-md-tb fpcm-ui-important-text align-self-center">
                    <div class="col-12 col-md-2">
                        <?php $theView->icon('images', 'far')->setStack('ban')->setSize('lg')->setStackTop(true); ?>
                    </div>
                    <div class="col-12 col-md-10 align-self-center">
                        <?php $theView->write('FILE_LIST_UPLOAD_NOTFOUND'); ?>
                    </div>
                </div>
                <?php endif; ?>
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
                    <?php if ($canRename && $file->existsFolder()) : ?>
                        <?php $theView->button(uniqid('rename'))->setText('FILE_LIST_RENAME')->setIcon('edit')->setIconOnly(true)->setData(['file' => base64_encode($file->getFilename()), 'oldname' => basename($file->getFilename(), '.'.$file->getExtension())])->setClass('fpcm-filelist-rename'); ?>
                    <?php endif; ?>
                    <?php if ($permDelete) : ?>
                        <?php $theView->button(uniqid('delete'))->setText('GLOBAL_DELETE')->setIcon('trash')->setIconOnly(true)->setData(['file' => base64_encode($file->getFilename())])->setClass('fpcm-filelist-delete'); ?>
                    <?php endif; ?>
                    
                    <?php if ($file->existsFolder()) : ?>
                        <?php $theView->button(uniqid('properties'))->setText('GLOBAL_PROPERTIES')->setIcon('info-circle')->setIconOnly(true)->setData([
                            'filename' => $file->getFilename(),
                            'filetime' => (string) $theView->dateText($file->getFiletime()),
                            'fileuser' => isset($users[$file->getUserid()]) ? $users[$file->getUserid()]->getDisplayName() : $theView->translate('USERS_SYSTEMUSER'),
                            'filesize' => \fpcm\classes\tools::calcSize($file->getFilesize()),
                            'fileresx' => $file->getWidth(),
                            'fileresy' => $file->getHeight(),
                            'filehash' => $file->getFileHash(),
                            'filemime' => $file->getMimetype(),
                        ])->setClass('fpcm-filelist-properties'); ?>
                    <?php endif; ?>
                </div>

                <div class="fpcm-ui-clear"></div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<span id="fpcm-filelist-images-finished"></span>

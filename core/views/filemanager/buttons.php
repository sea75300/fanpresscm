<?php /* @var $theView fpcm\view\viewVars */ /* @var $file fpcm\model\files\image */ ?>
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
    <?php $theView->button(uniqid('delete'))->setText('GLOBAL_DELETE')->setIcon('trash')->setIconOnly(true)->setData(['file' => base64_encode($file->getFilename()), 'filename' => $file->getFilename()])->setClass('fpcm-filelist-delete'); ?>
<?php endif; ?>
<?php if ($file->existsFolder()) : ?>
    <?php $theView->button(uniqid('properties'))->setText('GLOBAL_PROPERTIES')->setIcon('info-circle')->setIconOnly(true)->setData([
        'filename' => $file->getFilename(),
        'filetime' => (string) $theView->dateText($file->getFiletime()),
        'fileuser' => isset($users[$file->getUserid()]) ? $users[$file->getUserid()]->getDisplayName() : $theView->translate('USERS_SYSTEMUSER'),
        'filesize' => $theView->calcSize($file->getFilesize()),
        'fileresx' => $file->getWidth(),
        'fileresy' => $file->getHeight(),
        'filehash' => $file->getFileHash(),
        'filemime' => $file->getMimetype(),
    ])->setClass('fpcm-filelist-properties'); ?>
<?php endif; ?>
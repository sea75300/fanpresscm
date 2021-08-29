<?php /* @var $theView fpcm\view\viewVars */ /* @var $file fpcm\model\files\image */ ?>
<div class="navbar">
    <div class="nav-item me-2">
        <?php $theView->checkbox('filenames[]', 'cb_'. md5($file->getFilename()))->setClass('fpcm-ui-list-checkbox')->setValue(base64_encode($file->getFilename()))->setData(['gallery' => $file->getFilename()]); ?>
    </div>
    <?php if ($file->existsFolder()): ?>
        <div class="nav-item <?php print $buttonClasses; ?>">
            <?php $theView->linkButton(uniqid('imgurl'))->setUrl($file->getImageUrl())->setText('FILE_LIST_OPEN_FULL')->setClass('fpcm ui-link-fancybox')->setIcon('cloud')->setIconOnly(true)->setTarget('_blank')->setData(['fancybox' => 'group']); ?>
        </div>
    <?php endif; ?>
    <?php if (in_array($mode, [2, 4]) && $file->existsFolder()) : ?>                    
        <div class="nav-item <?php print $buttonClasses; ?>">
            <?php $theView->linkButton(uniqid('thumbsurl'))->setUrl($file->getThumbnailUrl())->setText('FILE_LIST_INSERT_THUMB')->setClass('fpcm-filelist-tinymce-thumb')->setIcon('plus-square', 'far')->setIconOnly(true)->setData(['imgtext' => $file->getAltText() ? $file->getAltText() : $file->getFilename()]); ?>
        </div>
        <div class="nav-item <?php print $buttonClasses; ?>">
            <?php $theView->linkButton(uniqid('imgsurl'))->setUrl($file->getImageUrl())->setText('FILE_LIST_INSERT_FULL')->setClass('fpcm-filelist-tinymce-full')->setIcon('plus-square')->setIconOnly(true)->setData(['imgtext' => $file->getAltText() ? $file->getAltText() : $file->getFilename()]); ?>
        </div>
    <?php elseif ($mode == 3 && $file->existsFolder()) : ?>                    
        <div class="nav-item <?php print $buttonClasses; ?>">
            <?php $theView->linkButton(uniqid('articleimg'))->setUrl($file->getImageUrl())->setText('EDITOR_ARTICLEIMAGE')->setClass('fpcm-filelist-articleimage')->setIcon('image')->setIconOnly(true)->setData(['imgtext' => $file->getFilename()]); ?>
        </div>
    <?php endif; ?>
    <?php if ($theView->permissions->uploads->rename && $file->existsFolder()) : ?>
        <div class="nav-item <?php print $buttonClasses; ?>">
            <?php $theView->button(uniqid('rename'))->setText('FILE_LIST_RENAME')->setIcon('edit')->setIconOnly(true)->setData(['file' => base64_encode($file->getFilename()), 'oldname' => basename($file->getFilename(), '.'.$file->getExtension())])->setClass('fpcm-filelist-rename'); ?>
        </div>
    <?php endif; ?>
    <?php if ($theView->permissions->uploads->add && $file->existsFolder()) : ?>
        <div class="nav-item <?php print $buttonClasses; ?>">
            <?php $theView->button(uniqid('edit'))->setText('FILE_LIST_EDIT')->setIcon('magic')->setIconOnly(true)->setClass('fpcm-filelist-link-edit')->setData(['url' => $file->getImageUrl(), 'filename' => $file->getFilename(), 'mime' => $file->getMimetype()]); ?>
        </div>
        <div class="nav-item <?php print $buttonClasses; ?>">
            <?php $theView->button(uniqid('alttext'))->setText('FILE_LIST_ALTTEXT')->setIcon('keyboard')->setIconOnly(true)->setClass('fpcm-filelist-link-alttext')->setData(['file' => base64_encode($file->getFilename()), 'alttext' => $file->getAltText()]); ?>
        </div>
    <?php endif; ?>
    <?php if ($theView->permissions->uploads->delete) : ?>
        <div class="nav-item <?php print $buttonClasses; ?>">
            <?php $theView->button(uniqid('delete'))->setText('GLOBAL_DELETE')->setIcon('trash')->setIconOnly(true)->setData(['file' => base64_encode($file->getFilename()), 'filename' => $file->getFilename()])->setClass('fpcm-filelist-delete'); ?>
        </div>
    <?php endif; ?>
    <?php if ($file->existsFolder()) : ?>
        <div class="nav-item <?php print $buttonClasses; ?>">
            <?php $theView->button(uniqid('properties'))->setText('GLOBAL_PROPERTIES')->setIcon('info-circle')->setIconOnly(true)->setData([
                'filename' => $file->getFilename(),
                'filetime' => (string) $theView->dateText($file->getFiletime()),
                'fileuser' => isset($users[$file->getUserid()]) ? $users[$file->getUserid()]->getDisplayName() : $theView->translate('USERS_SYSTEMUSER'),
                'filesize' => $theView->calcSize($file->getFilesize()),
                'fileresx' => $file->getWidth(),
                'fileresy' => $file->getHeight(),
                'filehash' => $file->getFileHash(),
                'filemime' => $file->getMimetype(),
                'credits' => $file->getIptcStr()
            ])->setClass('fpcm-filelist-properties'); ?>
        </div>
    <?php endif; ?>        
</div>

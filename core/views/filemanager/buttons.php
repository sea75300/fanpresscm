<?php /* @var $theView fpcm\view\viewVars */ /* @var $file fpcm\model\files\image */ ?>
<?php $hash = $file->getFileNameHash(); ?>
    <div class="nav-item">
        <div class="btn btn-<?php if ($theView->darkMode) : ?>dark<?php else : ?>light<?php endif; ?>">
            <?php $theView->checkbox('filenames[]', 'cb_'. $hash)
                    ->setClass('fpcm-ui-list-checkbox')
                    ->setValue($file->getCryptFileName())
                    ->setData(['gallery' => $file->getFilename()]); ?>
        </div>
    </div>
<?php if ($file->existsFolder()) : ?>
    <?php $imgTxt = $file->getAltText() ?? $file->getFilename(); ?>
    <?php if (in_array($mode, [2, 4])) : ?>
        <div class="nav-item">
            <?php $theView->linkButton(uniqid('thumbsurl'))->setUrl($file->getThumbnailUrl())->setText('FILE_LIST_INSERT_THUMB')->setClass('fpcm-filelist-tinymce-thumb')->setIcon('compress')->setIconOnly()->setData(['imgtext' => $imgTxt]); ?>
        </div>
        <div class="nav-item">
            <?php $theView->linkButton(uniqid('imgsurl'))->setUrl($file->getImageUrl())->setText('FILE_LIST_INSERT_FULL')->setClass('fpcm-filelist-tinymce-full')->setIcon('expand')->setIconOnly()->setData(['imgtext' => $imgTxt]); ?>
        </div>
    <?php elseif ($mode == 3) : ?>
        <div class="nav-item">
            <?php $theView->linkButton(uniqid('articleimg'))->setUrl($file->getImageUrl())->setText('EDITOR_ARTICLEIMAGE')->setClass('fpcm-filelist-articleimage')->setIcon('image')->setIconOnly()->setData(['imgtext' => $imgTxt]); ?>
        </div>
    <?php endif; ?>
        <div class="nav-item dropdown dropup-center dropup">

            <?php $theView->button('nbexp'.$hash)
                ->setText('GLOBAL_ACTIONS')
                ->setIcon('bars')
                ->setIconOnly()
                ->setData(['bs-toggle' => 'dropdown', 'bs-auto-close' => 'true'])
                ->setAria(['expanded' => 'false'])
                ->setClass('dropdown-toggle');
            ?>

            <ul class="dropdown-menu <?php if ($is_last($i)) : ?>dropdown-menu-end<?php endif; ?>" aria-labelledby="nbexp<?php $hash; ?>">
            <?php if ($theView->permissions->uploads->rename) : ?>
              <li>
                <?php $theView->dropdownItem(uniqid('rename'))->setText('FILE_LIST_RENAME')->setIcon('edit')->setClass('fpcm-filelist-rename')->setData(['file' => $file->getCryptFileName(), 'oldname' => basename($file->getFilename(), '.'.$file->getExtension())]); ?>
              </li>
            <?php endif; ?>
            <?php if ($theView->permissions->uploads->add) : ?>
              <li>
                <?php $theView->dropdownItem(uniqid('edit'))->setText('FILE_LIST_EDIT')->setIcon('magic')->setClass('fpcm-filelist-link-edit')->setData(['url' => $file->getImageUrl(), 'filename' => $file->getFilename(), 'mime' => $file->getMimetype()]); ?>
              </li>
              <li>
                <?php $theView->dropdownItem(uniqid('copyfile'))->setText('GLOBAL_COPY')->setIcon('copy')->setOnClick('system.createCopy', sprintf( "file:%s", $file->getCryptFileName())); ?>
              </li>
              <?php endif; ?>
              <li>
                <?php $theView->dropdownItem(uniqid('alttext'))->setText('FILE_LIST_ALTTEXT')->setIcon('keyboard')->setClass('fpcm-filelist-link-alttext')->setData(['file' => $file->getCryptFileName(), 'alttext' => $file->getAltText()]); ?>
              </li>
              <?php if ($theView->permissions->uploads->rename || $theView->permissions->uploads->add) : ?>
              <li><hr class="dropdown-divider"></li>
              <?php endif; ?>
              <li>
                <?php $theView->dropdownItem(uniqid('properties'))
                        ->setText('GLOBAL_PROPERTIES')
                        ->setIcon('info-circle')
                        ->setClass('fpcm-filelist-properties')
                        ->setData( $file->getPropertiesArray( isset($users[$file->getUserid()]) ? $users[$file->getUserid()]->getDisplayName() : $theView->translate('USERS_SYSTEMUSER') ) ); ?>
              </li>
              <?php if ($theView->permissions->uploads->delete) : ?>
              <li><hr class="dropdown-divider"></li>
              <li>
                <?php $theView->dropdownItem(uniqid('delete'))->setText('GLOBAL_DELETE')->setIcon('trash')->setClass('fpcm-filelist-delete')->setData(['file' => $file->getCryptFileName(), 'filename' => $file->getFilename()]); ?>
              </li>
              <?php endif; ?>
            </ul>
        </div>
<?php endif; ?>
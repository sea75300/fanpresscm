<?php /* @var $theView fpcm\view\viewVars */ /* @var $file fpcm\model\files\image */ ?>
    <div class="nav-item">
        <div class="btn btn-light">
            <?php $theView->checkbox('filenames[]', 'cb_'. $file->getFileNameHash())->setClass('fpcm-ui-list-checkbox')->setValue(base64_encode($file->getFilename()))->setData(['gallery' => $file->getFilename()]); ?>
        </div>
    </div>
<?php if ($file->existsFolder()) : ?>
    <?php if (in_array($mode, [2, 4])) : ?>
        <div class="nav-item">
            <?php $theView->linkButton(uniqid('thumbsurl'))->setUrl($file->getThumbnailUrl())->setText('FILE_LIST_INSERT_THUMB')->setClass('fpcm-filelist-tinymce-thumb')->setIcon('compress')->setIconOnly()->setData(['imgtext' => $file->getAltText() ? $file->getAltText() : $file->getFilename()]); ?>
        </div>
        <div class="nav-item">
            <?php $theView->linkButton(uniqid('imgsurl'))->setUrl($file->getImageUrl())->setText('FILE_LIST_INSERT_FULL')->setClass('fpcm-filelist-tinymce-full')->setIcon('expand')->setIconOnly()->setData(['imgtext' => $file->getAltText() ? $file->getAltText() : $file->getFilename()]); ?>
        </div>
    <?php elseif ($mode == 3) : ?>                    
        <div class="nav-item">
            <?php $theView->linkButton(uniqid('articleimg'))->setUrl($file->getImageUrl())->setText('EDITOR_ARTICLEIMAGE')->setClass('fpcm-filelist-articleimage')->setIcon('image')->setIconOnly()->setData(['imgtext' => $file->getFilename()]); ?>
        </div>
    <?php endif; ?>
        <div class="nav-item dropdown dropup-center dropup">

            <?php $theView->button('nbexp'.$file->getFileNameHash())
                ->setText('GLOBAL_ACTIONS')
                ->setIcon('bars')
                ->setIconOnly()
                ->setData(['bs-toggle' => 'dropdown', 'bs-auto-close' => 'true'])
                ->setAria(['expanded' => 'false'])
                ->setClass('dropdown-toggle');
            ?>

            <ul class="dropdown-menu <?php if ($is_last($i)) : ?>dropdown-menu-end<?php endif; ?>" aria-labelledby="nbexp<?php $file->getFileNameHash(); ?>">
            <?php if ($theView->permissions->uploads->rename) : ?>
              <li>
                <?php $theView->dropdownItem(uniqid('rename'))->setText('FILE_LIST_RENAME')->setIcon('edit')->setClass('fpcm-filelist-rename')->setData(['file' => base64_encode($file->getFilename()), 'oldname' => basename($file->getFilename(), '.'.$file->getExtension())]); ?>
              </li>
            <?php endif; ?>
            <?php if ($theView->permissions->uploads->add) : ?>
              <li>
                <?php $theView->dropdownItem(uniqid('edit'))->setText('FILE_LIST_EDIT')->setIcon('magic')->setClass('fpcm-filelist-link-edit')->setData(['url' => $file->getImageUrl(), 'filename' => $file->getFilename(), 'mime' => $file->getMimetype()]); ?>
              </li>
              <li>
                <?php $theView->dropdownItem(uniqid('alttext'))->setText('FILE_LIST_ALTTEXT')->setIcon('keyboard')->setClass('fpcm-filelist-link-alttext')->setData(['file' => base64_encode($file->getFilename()), 'alttext' => $file->getAltText()]); ?>
              </li>
              <?php endif; ?>
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
                <?php $theView->dropdownItem(uniqid('delete'))->setText('GLOBAL_DELETE')->setIcon('trash')->setClass('fpcm-filelist-delete')->setData(['file' => base64_encode($file->getFilename()), 'filename' => $file->getFilename()]); ?>
              </li>
              <?php endif; ?>
            </ul>
        </div>
<?php endif; ?>
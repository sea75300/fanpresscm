<?php /* @var $theView fpcm\view\viewVars */ /* @var $file fpcm\model\files\mediaFile */ ?>
<?php $hash = $file->getFileNameHash(); ?>
    <div class="nav-item">
        <?php $theView->filesSelectCheckbox('filenames[]', 'cb_'. $hash)
                ->setClass('fpcm-ui-list-checkbox ')
                ->setValue($file->getCryptFileName())
                ->setData([
                    'gallery' => $file->getFilename(),
                    'type' => 'image'
                ])
                ->setIcon('square-check', 'far')
                ->setSize('lg'); ?>
    </div>
<?php if ($file->existsFolder()) : ?>
    <?php $imgTxt = $file->getAltText() ? $file->getAltText() : $file->getFilename(); ?>
    <?php if (in_array($mode, [2, 4])) : ?>
        <div class="nav-item">
            <?php $theView->linkButton(uniqid('thumbsurl'))
                    ->setUrl($file->getThumbnailUrl())
                    ->setText('FILE_LIST_INSERT_THUMB')
                    ->setIcon('image-portrait')
                    ->setIconOnly()
                    ->setData([
                        'insert-type' => 'image',
                        'insert-fn' => ($mode == 4 ? 'poster-thumb' : 'thumb'),
                        'imgtext' => $imgTxt
                    ]); ?>
        </div>
        <div class="nav-item">
            <?php $theView->linkButton(uniqid('imgsurl'))
                    ->setUrl($file->getImageUrl())
                    ->setText('FILE_LIST_INSERT_FULL')
                    ->setIcon('image')
                    ->setIconOnly()
                    ->setData([
                        'insert-type' => 'image',
                        'insert-fn' => $mode == 4 ? 'poster-full' : 'full',
                        'imgtext' => $imgTxt
                    ]); ?>
        </div>
    <?php elseif ($mode == 3) : ?>
        <div class="nav-item">
            <?php $theView->linkButton(uniqid('articleimg'))
                    ->setUrl($file->getImageUrl())
                    ->setText('EDITOR_ARTICLEIMAGE')
                    ->setIcon('panorama')
                    ->setIconOnly()
                    ->setData([
                        'insert-type' => 'image',
                        'insert-fn' => 'articleimg',
                        'imgtext' => $imgTxt
                    ]) ?>
        </div>
    <?php endif; ?>
        <div class="nav-item dropdown dropup-center <?php if ($ddModeUp || $i === $limit) : ?>dropup<?php endif; ?>">

            <?php $theView->button('nbexp'.$hash)
                ->setText('GLOBAL_ACTIONS')
                ->setIcon('bars')
                ->setIconOnly()
                ->setData(['bs-toggle' => 'dropdown', 'bs-auto-close' => 'true'])
                ->setAria(['expanded' => 'false'])
                ->setClass('dropdown-toggle');
            ?>

            <ul class="dropdown-menu <?php if ($ddLastEnd && $is_last($i)) : ?>dropdown-menu-end<?php endif; ?>" aria-labelledby="nbexp<?php $hash; ?>">
            <?php if ($theView->permissions->uploads->rename) : ?>
              <li>
                <?php $theView->dropdownItem(uniqid('rename'))->setText('FILE_LIST_RENAME')->setIcon('edit')->setData(['action' => 'rename', 'file' => $file->getCryptFileName(), 'oldname' => basename($file->getFilename(), '.'.$file->getExtension())]); ?>
              </li>
            <?php endif; ?>
            <?php if ($theView->permissions->uploads->add) : ?>
              <li>
                <?php $theView->dropdownItem(uniqid('edit'))->setText('FILE_LIST_EDIT')->setIcon('magic')->setData(['action' => 'edit', 'url' => $file->getImageUrl(), 'filename' => $file->getFilename(), 'mime' => $file->getMimetype()]); ?>
              </li>
              <li>
                <?php $theView->dropdownItem(uniqid('copyfile'))->setText('GLOBAL_COPY')->setIcon('copy')->setOnClick('system.createCopy', sprintf( "file:%s", $file->getCryptFileName())); ?>
              </li>
              <?php endif; ?>
              <li>
                <?php $theView->dropdownItem(uniqid('alttext'))->setText('FILE_LIST_ALTTEXT')->setIcon('keyboard')->setData(['action' => 'alttext', 'file' => $file->getCryptFileName(), 'alttext' => $file->getAltText()]); ?>
              </li>
              <?php if ($theView->permissions->uploads->rename || $theView->permissions->uploads->add) : ?>
              <li><hr class="dropdown-divider"></li>
              <?php endif; ?>
              <li>
                <?php $theView->dropdownItem(uniqid('properties'))
                        ->setText('GLOBAL_PROPERTIES')
                        ->setIcon('info-circle')
                        ->setData($file->getPropertiesArray($theView->userId2Text($file->getUserid(), 'USERS_SYSTEMUSER'))); ?>
              </li>
              <?php if ($theView->permissions->uploads->delete) : ?>
              <li><hr class="dropdown-divider"></li>
              <li>
                <?php $theView->dropdownItem(uniqid('delete'))->setText('GLOBAL_DELETE')->setIcon('trash')->setData(['action' => 'delete', 'file' => $file->getCryptFileName(), 'filename' => $file->getFilename()]); ?>
              </li>
              <?php endif; ?>
            </ul>
            <?php $theView->button('reminder'.$hash)
                ->setText('HL_REMINDER')
                ->setIcon('bell')
                ->overrideButtonType($has_reminder($reminders, $file->getId(), $hasRem))
                ->setIconOnly()
                ->setData([
                    'id' => $file->getId(),
                    'reminderType' => 'files',
                    'reminderId' => $hasRem
                ]);
            ?>
        </div>
<?php endif; ?>
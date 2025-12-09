<?php /* @var $theView fpcm\view\viewVars */ /* @var $file fpcm\model\files\image */ ?>
<?php $hash = $file->getFileNameHash(); ?>
    <div class="nav-item">
        <?php $theView->filesSelectCheckbox('filenames[]', 'cb_'. $hash)
                ->setClass('fpcm-ui-list-checkbox')
                ->setValue($file->getCryptFileName())
                ->setIcon('square-check', 'far')
                ->setSize('lg'); ?>
    </div>
<?php if ($file->existsFolder()) : ?>
    <?php if ($mode === 2) : ?>
    <div class="nav-item">
        <?php $theView->linkButton(uniqid('insertVideo'))
                ->setUrl($file->getFileUrl())
                ->setText('GLOBAL_INSERT')
                ->setClass('fpcm-filelist-tinymce-video')
                ->setIcon('file-arrow-down')
                ->setIconOnly()
                ->setData([
                    'insert-type' => 'video',
                    'insert-fn' => 'video',
                ]);
        ?>
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
            <?php $theView->dropdownItem(uniqid('rename'))->setText('FILE_LIST_RENAME')->setIcon('edit')->setClass('fpcm-filelist-rename')->setData(['file' => $file->getCryptFileName(), 'oldname' => basename($file->getFilename(), '.'.$file->getExtension())]); ?>
          </li>
        <?php endif; ?>
        <?php if ($theView->permissions->uploads->add) : ?>
          <li>
            <?php $theView->dropdownItem(uniqid('copyfile'))->setText('GLOBAL_COPY')->setIcon('copy')->setOnClick('system.createCopy', sprintf( "file:%s", $file->getCryptFileName())); ?>
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
                    ->setData($file->getPropertiesArray($theView->userId2Text($file->getUserid(), 'USERS_SYSTEMUSER'))); ?>
          </li>
          <?php if ($theView->permissions->uploads->delete) : ?>
          <li><hr class="dropdown-divider"></li>
          <li>
            <?php $theView->dropdownItem(uniqid('delete'))->setText('GLOBAL_DELETE')->setIcon('trash')->setClass('fpcm-filelist-delete')->setData(['file' => $file->getCryptFileName(), 'filename' => $file->getFilename()]); ?>
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
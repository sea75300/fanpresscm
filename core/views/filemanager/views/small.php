<?php /* @var $theView fpcm\view\viewVars */ /* @var $file fpcm\model\files\image */ ?>
<div class="justify-content-end">

    <?php if ($showPager && in_array($mode, [2, 3, 4])) : ?>
    <div class="navbar">
        <div class="container-fluid">
            <div class="navbar me-auto d-flex gap-1"></div>
            <div class="navbar ms-auto gap-1">
                <?php print $pager; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($filterError) : ?>
    <p class="p-3"><?php $theView->icon('search')->setStack('ban text-danger')->setSize('lg')->setStackTop(true); ?> <?php $theView->write('SEARCH_ERROR'); ?></p>
    <?php elseif (!count($files)) : ?>
    <p class="p-3"><?php $theView->icon('images', 'far')->setStack('ban text-danger')->setSize('lg')->setStackTop(true); ?> <?php $theView->write('GLOBAL_NOTFOUND2'); ?></p>
    <?php else : ?>
        <?php $i = 0; ?>
        <div class="row">
            <div class="col">
                <div class="list-group my-2 shadow">
                    <div class="list-group-item bg-primary<?php if ($theView->darkMode) : ?>-subtle<?php endif; ?> bg-gradient text-white">
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5">
                            <div class="col align-self-center d-none d-md-block">&nbsp;</div>
                            <div class="col align-self-center">
                                <?php $theView->write('FILE_LIST_FILENAME'); ?>
                            </div>
                            <div class="col align-self-center">
                                <?php $theView->write('GLOBAL_LASTCHANGE'); ?>
                            </div>
                            <div class="col align-self-center">
                                <?php $theView->write('FILE_LIST_FILESIZE'); ?>
                            </div>
                            <div class="col align-self-center">
                                <?php $theView->write('FILE_LIST_UPLOAD_BY'); ?>
                            </div>
                        </div>
                    </div>

                <?php foreach($files AS $file) : ?>
                <?php $i++; ?>
                    <div class="list-group-item fpcm ui-files-item ui-background-white-50p ui-background-transition">
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5">
                            <div class="col align-self-center">
                                <div class="navbar gap-1 justify-content-start justify-content-lg-center">
                                    <div class="nav-item">
                                    <?php print $theView->linkButton('open'.$file->getFileHash())
                                        ->setUrl($file->getImageUrl())
                                        ->setClass('fpcm ui-link-fancybox')
                                        ->setText('FILE_LIST_OPEN_FULL')
                                        ->setIcon('file-image')
                                        ->setSize('lg')
                                        ->setIconOnly()
                                        ->setData([
                                            'fancybox' => 'group',
                                            'pswp-width' => $file->getWidth(),
                                            'pswp-height' => $file->getHeight(),
                                        ]);
                                    ?>
                                    </div>
                                    <?php include $theView->getIncludePath('filemanager/buttons.php'); ?>
                                    <?php if (!$file->existsFolder()) : ?>
                                    <div class="nav-item">

                                        <?php $theView->button('missing'.$file->getFileHash())
                                        ->overrideButtonType('danger')
                                        ->setIcon('exclamation-triangle')
                                        ->setText('FILE_LIST_UPLOAD_NOTFOUND')
                                        ->setSize('lg')
                                        ->setIconOnly(); ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col align-self-center text-truncate">
                                <span class="fw-bold"><?php print $theView->escapeVal(basename($file->getFilename())); ?></span>
                                <?php if ($file->getAltText()) : ?>
                                <br><span class="fs-6 text-secondary-emphasis"><?php print $theView->escapeVal($file->getAltText()); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="col align-self-center">
                                <?php print $theView->dateText($file->getFiletime()); ?>
                            </div>
                            <div class="col align-self-center">
                                <?php print $theView->calcSize($file->getFilesize()); ?>
                            </div>
                            <div class="col align-self-center ">
                                <?php print $theView->userId2Text($file->getUserid()); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
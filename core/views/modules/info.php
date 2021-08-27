<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="border-5 border-top border-primary">

    <div class="row g-0 my-2 gap-2 justify-content-end">
        <div class="col-auto">
            <?php $theView->button('install')->setReadonly(!$moduleInstall)->setIcon('plus-circle')->setText('MODULES_LIST_INSTALL')->setData(['hash' => $moduleKeyHash]); ?>
        </div>
        <div class="col-auto">
            <?php $theView->linkButton('download')->setRel('external')->setReadonly(!$moduleDownload)->setUrl($moduleDownload)->setIcon('cloud-download-alt')->setText('MODULES_LIST_DOWNLOAD'); ?>
        </div>
    </div>    
    
    <div class="row my-2">
        <div class="col-12 col-sm-6 col-md-3">
            <?php $theView->write('MODULES_LIST_NAME'); ?>:
        </div>
        <div class="col-12 col-sm-6 col-md-9"><?php print $theView->escape($moduleName); ?></div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-sm-6 col-md-3">
            <?php $theView->write('MODULES_LIST_VERSION_REMOTE'); ?>:
        </div>
        <div class="col-12 col-sm-6 col-md-9"><?php print $theView->escape($moduleVersion); ?></div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-sm-6 col-md-3">
            <?php $theView->write('MODULES_LIST_AUTHOR'); ?>:
        </div>
        <div class="col-12 col-sm-6 col-md-9"><?php print $theView->escape($moduleAuthor); ?></div>
    </div>

    <div class="row">
        <div class="col-12 col-sm-6 col-md-3 align-self-center">
            <?php $theView->write('MODULES_LIST_LINK'); ?>:
        </div>
        <div class="col-12 col-sm-6 col-md-9">
            <?php $theView->linkButton('link')->setRel('external')->setUrl($moduleLink)->setTarget('_blank')->setText($moduleLink)->setIcon('external-link-square-alt'); ?>
        </div>
    </div>

    <div class="row pt-3 pb-1">
        <div class="col-12 col-sm-6 col-md-3">
            <?php print $theView->write('FILE_LIST_FILEHASH'); ?>:
        </div>
        <div class="col-auto"><?php print $theView->escapeVal($moduleHash); ?></div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-sm-6 col-md-3">
            <?php $theView->write('MODULES_LIST_REQUIRE_FPCM'); ?>:
        </div>
        <div class="col-12 col-sm-6 col-md-9"><?php print $theView->escape($moduleSysVer); ?></div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-sm-6 col-md-3">
            <?php $theView->write('MODULES_LIST_REQUIRE_PHP'); ?>:
        </div>
        <div class="col-12 col-sm-6 col-md-9"><?php print $theView->escape($modulePhpVer); ?></div>
    </div>

    <div class="row my-2">
        <div class="col-12 bold">
            <?php $theView->write('MODULES_LIST_DESCRIPTION'); ?>:
        </div>
    </div>

    <div class="row pt-1 pb-3">
        <div class="col-12 pre-box"><?php print $theView->escape($moduleDescription); ?></div>
    </div>
</div>

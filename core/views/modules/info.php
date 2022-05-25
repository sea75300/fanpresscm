<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row justify-content-end mb-3">
    <div class="col-12 col-md-auto fw-bold fs-3 text-start flex-grow-1 align-self-center text-truncate">
        <h3 class="d-inline"><?php print $theView->escape($moduleName); ?></h3>
    </div>
    <div class="col-auto col-md-auto align-self-center">
        <?php $theView->button('install')->setReadonly(!$moduleInstall)->setIcon('plus-circle')->setText('MODULES_LIST_INSTALL')->setIconOnly(true)->setData(['hash' => $moduleKeyHash]); ?>
    </div>
    <div class="col-auto col-md-auto align-self-center">
        <?php $theView->linkButton('download')->setRel('external')->setReadonly(!$moduleDownload)->setUrl($moduleDownload)->setIcon('cloud-download-alt')->setIconOnly(true)->setText('MODULES_LIST_DOWNLOAD'); ?>
    </div>
    <div class="col-auto col-md-auto align-self-center">
        <?php $theView->linkButton('link')->setRel('external')->setUrl($moduleLink)->setIconOnly(true)->setTarget('_blank')->setText($moduleLink)->setIcon('external-link-square-alt'); ?>
    </div>
</div>    

<div class="row row-cols-2 mb-3">
    <div class="col fw-bold">
        <?php $theView->icon('tag')->setSize('lg'); ?>
        <?php $theView->write('MODULES_LIST_KEY'); ?>:
    </div>
    <div class="col text-truncate"><?php print $theView->escape($moduleKey); ?></div>
</div>

<div class="row row-cols-2 mb-3">
    <div class="col fw-bold">
        <?php $theView->icon('code-branch')->setSize('lg'); ?>
        <?php $theView->write('MODULES_LIST_VERSION_REMOTE'); ?>:
    </div>
    <div class="col text-truncate"><?php print $theView->escape($moduleVersion); ?></div>
</div>

<div class="row row-cols-2 mb-3">
    <div class="col fw-bold">
        <?php $theView->icon('at')->setSize('lg'); ?>
        <?php $theView->write('MODULES_LIST_AUTHOR'); ?>:
    </div>
    <div class="col text-truncate"><?php print $theView->escape($moduleAuthor); ?></div>
</div>

<div class="row row-cols-2 mb-3">
    <div class="col fw-bold">
        <?php $theView->icon('external-link-square-alt')->setSize('lg'); ?>
        <?php $theView->write('MODULES_LIST_LINK'); ?>:
    </div>
    <div class="col text-truncate"><?php print $theView->escape($moduleLink); ?></div>
</div>

<div class="row row-cols-2 mb-3">
    <div class="col fw-bold">
        <?php $theView->icon('code-pull-request')->setSize('lg'); ?>
        <?php $theView->write('MODULES_LIST_REQUIRE_FPCM'); ?>:
    </div>
    <div class="col text-truncate"><?php print $theView->escape($moduleSysVer); ?></div>
</div>

<div class="row row-cols-2 mb-3">
    <div class="col fw-bold">
        <?php $theView->icon('php', 'fa-brands')->setSize('lg'); ?>
        <?php $theView->write('MODULES_LIST_REQUIRE_PHP'); ?>:
    </div>
    <div class="col text-truncate"><?php print $theView->escape($modulePhpVer); ?></div>
</div>

<div class="row mb-5">
    <div class="col-12">        
        <?php $theView->button('topDescr')
            ->setText('MODULES_LIST_DESCRIPTION')
            ->setAria(['controls' => 'infoDescrCollapse'])
            ->setData(['bs-toggle' => 'collapse', 'bs-target' => '#infoDescrCollapse'])
            ->setIcon('chevron-down')
            ->setPrimary(); ?>

        <div class="collapse show" id="infoDescrCollapse">
            <div class="card card-body">
                <div class="pre-box"><?php print $theView->escape($moduleDescription); ?></div>
            </div>
        </div>
    </div>
</div>

<div class="row row-cols-2 mb-3">
    <div class="col-12 fw-bold">
        <?php $theView->icon('folder-tree')->setSize('lg'); ?>
        <?php $theView->write('MODULES_LIST_DATAPATH'); ?>:
    </div>
</div>

<div class="row mb-3">
    <div class="col pre-box text-secondary"><?php print $theView->escape($moduleDataPath); ?></div>
</div>

<div class="row row-cols-2 mb-3">
    <div class="col-12 fw-bold">
        <?php $theView->icon('hashtag')->setSize('lg'); ?>
        <?php $theView->write('FILE_LIST_FILEHASH'); ?>:
    </div>
</div>

<div class="row mb-3">
    <div class="col pre-box text-secondary"><?php print $theView->escapeVal($moduleHash); ?></div>
</div>
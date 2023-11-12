<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row justify-content-end mb-3">
    <div class="col-12 col-md-auto fw-bold fs-3 text-start flex-grow-1 align-self-center text-truncate">
        <h3 class="d-inline <?php if ($theView->darkMode) : ?>text-primary-emphasis<?php endif; ?>"><?php print $theView->escape($moduleName); ?></h3>
    </div>
    <div class="col-12 col-md-auto">
        <div class="btn-group" role="group">
            <?php $theView->button('install')->overrideButtonType('outline-secondary')->setReadonly(!$moduleInstall)->setIcon('plus-circle')->setText('MODULES_LIST_INSTALL')->setIconOnly()->setData(['hash' => $moduleKeyHash]); ?>
            <?php $theView->linkButton('download')->overrideButtonType('outline-secondary')->setRel('external')->setReadonly(!$moduleDownload)->setUrl($moduleDownload)->setIcon('cloud-download-alt')->setIconOnly()->setText('MODULES_LIST_DOWNLOAD'); ?>
            <?php $theView->linkButton('link')->overrideButtonType('outline-secondary')->setRel('external')->setUrl($moduleLink)->setIconOnly()->setTarget('_blank')->setText($moduleLink)->setIcon('house'); ?>
        <?php if ($moduleLicenceUrl) : ?>
            <?php $theView->linkButton('licence')->overrideButtonType('outline-secondary')->setRel('external')->setUrl($moduleLicenceUrl)->setIconOnly()->setTarget('_blank')->setText('HL_HELP_LICENCE')->setIcon('certificate'); ?>
        <?php endif; ?>
    <?php if (trim($moduleSupport)) : ?>
        <?php if (filter_var($moduleSupport, FILTER_VALIDATE_EMAIL)) : ?>
            <?php $theView->linkButton('support')->overrideButtonType('outline-secondary')->setUrl('mailto:' . $moduleSupport)->setIconOnly()->setText('MODULES_LIST_SUPPORT')->setIcon('headset'); ?>
        <?php elseif (filter_var($moduleSupport, FILTER_VALIDATE_URL)) : ?>
            <?php $theView->linkButton('support')->overrideButtonType('outline-secondary')->setRel('external')->setUrl($moduleSupport)->setIconOnly()->setTarget('_blank')->setText('MODULES_LIST_SUPPORT')->setIcon('headset'); ?>
        <?php endif; ?>    
    <?php endif; ?>

        </div>
    </div>

</div>

<?php if (!$statusInstallable) : ?>
    <?php $theView->alert('danger')->setText('MODULES_FAILED_DEPENCIES')->setIcon('project-diagram'); ?>
<?php endif; ?>

<?php if ($statusInstalled && !$statusFilesList) : ?>
    <?php $theView->alert('warning')->setText('UPDATE_VERSIONCECK_FILETXT_ERR2')->setIcon('exclamation-triangle'); ?>
<?php endif; ?>

<?php if ($statusInstalled && !$statusWritable) : ?>
    <?php $theView->alert('danger')->setText('MODULES_FAILED_FSWRITE')->setIcon('ban'); ?>
<?php endif; ?>

<div class="row row-cols-1 row-cols-lg-2 mb-3">
    <div class="col fw-bold">
        <?php $theView->icon('tag')->setSize('lg'); ?>
        <?php $theView->write('MODULES_LIST_KEY'); ?>:
    </div>
    <div class="col text-truncate"><?php print $theView->escape($moduleKey); ?></div>
</div>

<div class="row row-cols-1 row-cols-lg-2 mb-3">
    <div class="col fw-bold">
        <?php $theView->icon('code-branch')->setSize('lg'); ?>
        <?php $theView->write('MODULES_LIST_VERSION_REMOTE'); ?>:
    </div>
    <div class="col text-truncate"><?php print $theView->escape($moduleVersion); ?></div>
</div>

<hr>

<div class="row row-cols-1 row-cols-lg-2 mb-3">
    <div class="col fw-bold">
        <?php $theView->icon('at')->setSize('lg'); ?>
        <?php $theView->write('MODULES_LIST_AUTHOR'); ?>:
    </div>
    <div class="col text-truncate"><?php print $theView->escape($moduleAuthor); ?></div>
</div>

<div class="row row-cols-1 row-cols-lg-2 mb-3">
    <div class="col fw-bold">
        <?php $theView->icon('house')->setSize('lg'); ?>
        <?php $theView->write('MODULES_LIST_LINK'); ?>:
    </div>
    <div class="col text-truncate"><?php print $theView->escape($moduleLink); ?></div>
</div>

<div class="row row-cols-1 row-cols-lg-2 mb-3">
    <div class="col fw-bold">
        <?php $theView->icon('headset')->setSize('lg'); ?>
        <?php $theView->write('MODULES_LIST_SUPPORT'); ?>:
    </div>
    <div class="col text-truncate"><?php print $theView->escape($moduleSupport); ?></div>
</div>

<div class="row row-cols-1 row-cols-lg-2 mb-3">
    <div class="col fw-bold">
        <?php $theView->icon('certificate')->setSize('lg'); ?>
        <?php $theView->write('MODULES_LIST_LICENCE'); ?>:
    </div>
    <div class="col text-truncate" title="<?php print $theView->escape($moduleLicence); ?>"><?php print $theView->escape($moduleLicence); ?></div>
</div>

<hr>

<div class="row row-cols-1 row-cols-lg-2 mb-3">
    <div class="col fw-bold">
        <?php $theView->icon('code-pull-request')->setSize('lg'); ?>
        <?php $theView->write('MODULES_LIST_REQUIRE_FPCM'); ?>:
    </div>
    <div class="col text-truncate">
    <?php if (is_array($moduleSysVer)) : ?>
        <?php foreach ($moduleSysVer as $smamin => $ver) : ?>
        <ul class="list-group">
            <li class="list-group-item d-flex">
                <span class="fw-bold flex-grow-1"><?php print $theView->escape($smamin); ?></span>
                <span class="justify-content-end"><?php print $theView->escape($ver); ?></span>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <?php print $theView->escape($moduleSysVer); ?>
    <?php endif; ?>
    </div>
</div>

<div class="row row-cols-1 row-cols-lg-2 mb-3">
    <div class="col fw-bold">
        <?php $theView->icon('php', 'fa-brands')->setSize('lg'); ?>
        <?php $theView->write('MODULES_LIST_REQUIRE_PHP'); ?>:
    </div>
    <div class="col text-truncate">
    <?php if (is_array($modulePhpVer)) : ?>
        <?php foreach ($modulePhpVer as $pmamin => $pver) : ?>
        <ul class="list-group">
            <li class="list-group-item d-flex">
                <span class="fw-bold flex-grow-1"><?php print $theView->escape($pmamin); ?></span>
                <span class="justify-content-end"><?php print $theView->escape($ver); ?></span>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <?php print $theView->escape($modulePhpVer); ?>
    <?php endif; ?>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-12">        
        <?php $theView->button('topDescr')
            ->setText('MODULES_LIST_DESCRIPTION')
            ->setAria(['controls' => 'infoDescrCollapse'])
            ->setData(['bs-toggle' => 'collapse', 'bs-target' => '#infoDescrCollapse'])
            ->setIcon('chevron-down'); ?>

        <div class="collapse show mt-2" id="infoDescrCollapse">
            <div class="card card-body border-0">
                <div class="pre-box"><?php print $theView->escape($moduleDescription); ?></div>
            </div>
        </div>
    </div>
</div>

<hr>

<div class="row row-cols-1 row-cols-lg-2 mb-3">
    <div class="col-12 fw-bold">
        <?php $theView->icon('folder-tree')->setSize('lg'); ?>
        <?php $theView->write('MODULES_LIST_DATAPATH'); ?>:
    </div>
</div>

<div class="row mb-3">
    <div class="col pre-box text-secondary"><?php print $theView->escape($moduleDataPath); ?></div>
</div>

<div class="row row-cols-1 row-cols-lg-2 mb-3">
    <div class="col-12 fw-bold">
        <?php $theView->icon('hashtag')->setSize('lg'); ?>
        <?php $theView->write('FILE_LIST_FILEHASH'); ?>:
    </div>
</div>

<div class="row mb-3">
    <div class="col pre-box text-secondary"><?php print $theView->escapeVal($moduleHash); ?></div>
</div>
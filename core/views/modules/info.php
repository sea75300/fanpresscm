<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row g-0">
    <div class="col">
        <div class="card">
            <h3 class="card-header text-truncate">
                <?php print $theView->escape($moduleName); ?>
            </h3>
            <div class="card-body">
                <h6 class="card-title text-truncate"><?php $theView->icon('book')->setSize('lg'); ?> <?php $theView->write('MODULES_LIST_DESCRIPTION'); ?>:</h6>
                <div class="card-text">
                    <div class="pre-box"><?php print $theView->escape($moduleDescription); ?></div>
                </div>
            </div>
            <div class="card-footer btn-group">                
                <?php $theView->button('install')->overrideButtonType('outline-secondary')->setReadonly(!$moduleInstall)->setIcon('plus-circle')->setText('MODULES_LIST_INSTALL')->setIconOnly()->setData(['hash' => $moduleKeyHash]); ?>
                <?php $theView->linkButton('download')->overrideButtonType('outline-secondary')->setRel('external')->setReadonly(!$moduleDownload)->setUrl($moduleDownload)->setIcon('cloud-download-alt')->setIconOnly()->setText('MODULES_LIST_DOWNLOAD'); ?>
                <?php $theView->linkButton('link')->overrideButtonType('outline-secondary')->setRel('external')->setUrl($moduleLink)->setIconOnly()->setTarget('_blank')->setText($moduleLink)->setIcon('house'); ?>
            <?php if ($moduleLicenceUrl) : ?>
                <?php $theView->linkButton('licence')->overrideButtonType('outline-secondary')->setRel('external')->setUrl($moduleLicenceUrl)->setIconOnly()->setTarget('_blank')->setText('HL_HELP_LICENCE')->setIcon('certificate'); ?>
            <?php endif; ?>
            <?php if ($moduleChangelogUrl) : ?>
                <?php $theView->linkButton('changelog')->overrideButtonType('outline-secondary')->setRel('external')->setUrl($moduleChangelogUrl)->setIconOnly()->setTarget('_blank')->setText('HL_HELP_CHANGELOG')->setIcon('code-branch'); ?>
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
</div>

<hr>

<?php if (!$statusInstallable) : ?>
    <?php $theView->alert('danger')->setText('MODULES_FAILED_DEPENCIES')->setIcon('project-diagram'); ?>
<?php endif; ?>

<?php if ($statusInstalled && !$statusFilesList) : ?>
    <?php $theView->alert('warning')->setText('UPDATE_VERSIONCECK_FILETXT_ERR2')->setIcon('exclamation-triangle'); ?>
<?php endif; ?>

<?php if ($statusInstalled && !$statusWritable) : ?>
    <?php $theView->alert('danger')->setText('MODULES_FAILED_FSWRITE')->setIcon('ban'); ?>
<?php endif; ?>

<div class="list-group">
    <div class="list-group-item">
        <div class="row g-0">
            <div class="col-auto align-self-center me-2">
                <?php $theView->icon('tag')->setSize('lg'); ?>
            </div>
            <div class="col align-self-center">
                <?php $theView->write('MODULES_LIST_KEY'); ?>:
            </div>
            <div class="col-2 align-self-center text-truncate flex-grow-1">
                <?php print $theView->escape($moduleKey); ?>
            </div>
        </div>
    </div>
    <div class="list-group-item">
        <div class="row g-0">
            <div class="col-auto align-self-center me-2">
                <?php $theView->icon('code-branch')->setSize('lg'); ?>
            </div>
            <div class="col align-self-center">
                <?php $theView->write('MODULES_LIST_VERSION_REMOTE'); ?>:
            </div>
            <div class="col-2 align-self-center text-truncate flex-grow-1">
                <?php print $theView->escape($moduleVersion); ?>
            </div>
        </div>
    </div>
</div>

<hr>

<div class="list-group">
    <div class="list-group-item">
        <div class="row g-0">
            <div class="col-auto align-self-center me-2">
                <?php $theView->icon('at')->setSize('lg'); ?>
            </div>
            <div class="col align-self-center">
                <?php $theView->write('MODULES_LIST_AUTHOR'); ?>:
            </div>
            <div class="col-2 align-self-center text-truncate flex-grow-1">
                <?php print $theView->escape($moduleAuthor); ?>
            </div>
        </div>
    </div>
    <div class="list-group-item">
        <div class="row g-0">
            <div class="col-auto align-self-center me-2">
                <?php $theView->icon('house')->setSize('lg'); ?>
            </div>
            <div class="col align-self-center">
                <?php $theView->write('MODULES_LIST_LINK'); ?>:
            </div>
            <div class="col-2 align-self-center text-truncate flex-grow-1">
                <?php print $theView->escape($moduleLink); ?>
            </div>
        </div>
    </div>
    <div class="list-group-item">
        <div class="row g-0">
            <div class="col-auto align-self-center me-2">
                <?php $theView->icon('headset')->setSize('lg'); ?>
            </div>
            <div class="col align-self-center">
                <?php $theView->write('MODULES_LIST_SUPPORT'); ?>:
            </div>
            <div class="col-2 align-self-center text-truncate flex-grow-1">
                <?php print $theView->escape($moduleSupport); ?>
            </div>
        </div>
    </div>
    <div class="list-group-item">
        <div class="row g-0">
            <div class="col-auto align-self-center me-2">
                <?php $theView->icon('certificate')->setSize('lg'); ?>
            </div>
            <div class="col align-self-center">
                <?php $theView->write('MODULES_LIST_LICENCE'); ?>:
            </div>
            <div class="col-2 align-self-center text-truncate flex-grow-1">
                <?php print $theView->escape($moduleLicence); ?>
            </div>
        </div>
    </div>
</div>

<hr>

<div class="list-group">
    <div class="list-group-item">
        <div class="row g-0">
            <div class="col-auto align-self-center me-2">
                <?php $theView->icon('code-pull-request')->setSize('lg'); ?>
            </div>
            <div class="col align-self-center">
                <?php $theView->write('MODULES_LIST_REQUIRE_FPCM'); ?>:
            </div>
            <div class="col-2 align-self-center text-truncate flex-grow-1">
                <?php if (is_array($moduleSysVer)) : ?>
                    <ul class="list-group">
                    <?php foreach ($moduleSysVer as $smamin => $ver) : ?>
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
    </div>
    <div class="list-group-item">
        <div class="row g-0">
            <div class="col-auto align-self-center me-2">
                <?php $theView->icon('php', 'fa-brands')->setSize('lg'); ?>
            </div>
            <div class="col align-self-center">
                <?php $theView->write('MODULES_LIST_REQUIRE_PHP'); ?>:
            </div>
            <div class="col-2 align-self-center text-truncate flex-grow-1">
            <?php if (is_array($modulePhpVer)) : ?>
                <ul class="list-group">
                <?php foreach ($modulePhpVer as $pmamin => $pver) : ?>
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
    </div>
</div>

<hr>

<div class="list-group">
    <div class="list-group-item">
        <div class="row row-cols-1 row-cols-lg-1 g-0">
            <div class="col align-self-center">
                <?php $theView->icon('folder-tree'); ?>
                <?php $theView->write('MODULES_LIST_DATAPATH'); ?>
            </div>
            <div class="col pre-box text-secondary text-truncate"><?php print $theView->escape($moduleDataPath); ?></div>
        </div>
    </div>
    <div class="list-group-item">
        <div class="row row-cols-1 row-cols-lg-1 g-0">
            <div class="col align-self-center">
                <?php $theView->icon('hashtag'); ?>
                <?php $theView->write('FILE_LIST_FILEHASH'); ?>
            </div>
            <div class="col pre-box text-secondary text-truncate"><?php print $theView->escape($moduleHash); ?></div>
        </div>
    </div>
</div>
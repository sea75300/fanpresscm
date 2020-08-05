<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div class="fpcm-ui-tabs-general" id="fpcm-tabs-modules">
        <ul>
            <li data-dataview-list="modulesLocal"><a href="<?php print $theView->controllerLink('ajax/modules/fetch', ['mode' => 'local']); ?>"><?php $theView->write('MODULES_LIST_HEADLINE'); ?></a></li>
            <li data-dataview-list="modulesRemote"><a href="<?php print $theView->controllerLink('ajax/modules/fetch', ['mode' => 'remote']); ?>"><?php $theView->write('MODULES_LIST_AVAILABLE'); ?></a></li>
            <?php if ($theView->permissions->modules->install && $canUpload) : ?><li><a href="#tabs-modules-upload"><?php $theView->write('MODULES_LIST_UPLOAD'); ?></a></li><?php endif; ?>
        </ul>

        <?php if ($theView->permissions->modules->install && $canUpload) : ?>
        <div id="tabs-modules-upload">
            <?php include $uploadTemplatePath; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
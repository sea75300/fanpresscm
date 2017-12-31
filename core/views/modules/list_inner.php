<table class="fpcm-ui-table fpcm-ui-modules">
    <tr>
        <th></th>
        <th></th>
        <th class="fpcm-ui-modules-key"></th>
        <th class="fpcm-ui-modules-version fpcm-ui-center"><?php $FPCM_LANG->write('MODULES_LIST_VERSION_LOCAL'); ?></th>
        <th class="fpcm-ui-modules-version fpcm-ui-center"><?php $FPCM_LANG->write('MODULES_LIST_VERSION_REMOTE'); ?></th>
        <th class="fpcm-th-select-row"><?php fpcm\model\view\helper::checkbox('fpcm-select-all', '', '', '', 'fpcm-select-all', false); ?></th>
    </tr>
    
    <?php \fpcm\model\view\helper::notFoundContainer($modules, 6); ?>

    <tr><td  class="fpcm-td-spacer" colspan="6"></td></tr>
    <?php foreach ($modules as $module) :  ?>                
    <tr class="fpcm-ui-modules-updates<?php print $module->hasUpdates(); ?>">
        <td class="fpcm-ui-center">
        <?php if ($module->isInstalled()) : ?>
            <?php if ($module->dependenciesOk() && $module->currentLanguageIncluded()) : ?>
                <?php if ($module->getStatus()) : ?>
                    <?php \fpcm\model\view\helper::linkButton($FPCM_BASEMODULELINK.'modules/config&key='.$module->getkey(), 'MODULES_LIST_CONFIGURE', str_replace('/', '', $module->getKey()), 'fpcm-ui-button-blank fpcm-modules-configuremodule fpcm-loader'); ?>
                    <?php if ($permissionEnable) : ?>
                        <?php \fpcm\model\view\helper::linkButton('#', 'MODULES_LIST_DISABLE', str_replace('/', '', $module->getKey()), 'fpcm-ui-button-blank fpcm-modulelist-singleaction-disable'); ?>
                    <?php endif; ?>
                <?php else : ?>
                    <?php \fpcm\model\view\helper::dummyButton('MODULES_LIST_CONFIGURE', 'fpcm-ui-button-blank fpcm-modules-configuremodule fpcm-ui-readonly'); ?>
                    <?php if ($permissionEnable) : ?>
                        <?php \fpcm\model\view\helper::linkButton('#', 'MODULES_LIST_ENABLE', str_replace('/', '', $module->getKey()), 'fpcm-ui-button-blank fpcm-modulelist-singleaction-enable'); ?>
                    <?php endif; ?>           
                <?php endif; ?>
            <?php elseif (!$module->currentLanguageIncluded()) : ?>    
                <?php \fpcm\model\view\helper::dummyButton('MODULES_FAILED_LANGUAGE', 'fpcm-ui-button-blank fpcm-modules-depencerror'); ?>
            <?php elseif (!$module->dependenciesOk()) : ?>    
                <?php \fpcm\model\view\helper::dummyButton('MODULES_FAILED_DEPENCIES', 'fpcm-ui-button-blank fpcm-modules-depencerror'); ?>
            <?php endif; ?>

            <?php if ($permissionUninstall) : ?>
                <?php if ($module->getStatus()) : ?>
                    <?php \fpcm\model\view\helper::dummyButton('MODULES_LIST_UNINSTALL', 'fpcm-ui-button-blank fpcm-modulelist-singleaction-uninstall fpcm-ui-readonly'); ?>
                <?php else : ?>
                    <?php \fpcm\model\view\helper::linkButton('#', 'MODULES_LIST_UNINSTALL', str_replace('/', '', $module->getKey()), 'fpcm-ui-button-blank fpcm-modulelist-singleaction-uninstall'); ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($permissionInstall && $module->hasUpdates()) : ?>
                <?php \fpcm\model\view\helper::linkButton('#', 'MODULES_LIST_UPDATE', str_replace('/', '', $module->getKey()), 'fpcm-ui-button-blank fpcm-modulelist-singleaction-update'); ?>
            <?php endif; ?>
        <?php elseif (!$module->isInstalled() && $permissionInstall) : ?>
            <?php \fpcm\model\view\helper::linkButton('#', 'MODULES_LIST_INSTALL', str_replace('/', '', $module->getKey()), 'fpcm-ui-button-blank fpcm-modulelist-singleaction-install'); ?>
        <?php endif; ?>
        </td>
        <td><?php fpcm\model\view\helper::linkButton('', \fpcm\model\view\helper::escapeVal($module->getName()), str_replace('/', '', $module->getKey()), 'fpcm-module-openinfo-link'); ?></td>
        <td class="fpcm-ui-modules-key"><?php print \fpcm\model\view\helper::escapeVal($module->getKey()); ?></td>
        <td class="fpcm-ui-modules-version fpcm-ui-center" id="fpcm-module-version<?php print $module->getKey(); ?>"><?php print \fpcm\model\view\helper::escapeVal($module->getVersion()); ?></td>
        <td class="fpcm-ui-modules-version fpcm-ui-center" id="fpcm-module-versionrem<?php print $module->getKey(); ?>"><?php print \fpcm\model\view\helper::escapeVal($module->getVersionRemote()); ?></td>
        <td class="fpcm-td-select-row">
        <?php if ($module->isInstalled()) : ?>
            <?php fpcm\model\view\helper::checkbox('moduleksys[]', 'fpcm-list-selectbox', base64_encode($module->getKey()), '', 'cb_'.str_replace('/', '', $module->getKey()), false) ?>
        <?php else : ?>
            <?php fpcm\model\view\helper::checkbox('moduleksys[]', 'fpcm-list-selectbox', base64_encode($module->getKey().'_version'.$module->getVersionRemote()), '', 'cb_'.str_replace('/', '', $module->getKey()), false) ?>
        <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
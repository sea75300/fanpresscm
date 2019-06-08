<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\modules;

/**
 * Module actions trait
 * 
 * @package fpcm\controller\traits\modules\moduleactions
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait moduleactions {

    /**
     * Modul-Liste in View zuweisen
     * @param \fpcm\model\modules\modulelist $moduleList
     * @param bool $addInfoLayer
     */
    public function assignModules(\fpcm\model\modules\modulelist $moduleList, $addInfoLayer = true)
    {
        $remote = $moduleList->getModulesRemote();
        $modules = array_merge($remote, $moduleList->getModulesLocal());

        $jsInfo = [];
        foreach ($modules as $key => $moduleItem) {

            if (isset($remote[$key])) {
                $moduleItem->setVersionRemote($remote[$key]->getVersionRemote());
            }

            $dependencies = $moduleItem->getDependencies();

            $depencyData = [];
            if (count($dependencies)) {
                foreach ($dependencies as $mkey => $version) {
                    $depencyData[] = $mkey . ' - ' . $this->language->translate('VERSION') . ' ' . $version;
                }
            }

            $jsInfo[str_replace('/', '', $moduleItem->getKey())] = array(
                'key' => $moduleItem->getKey(),
                'description' => $moduleItem->getDescription(),
                'author' => $moduleItem->getAuthor(),
                'link' => $moduleItem->getLink() ? '<a href="' . $moduleItem->getLink() . '" target="_blank">' . $moduleItem->getLink() . '</a>' : '',
                'dependencies' => count($depencyData) ? implode('<br>', $depencyData) : '-',
                'version' => $moduleItem->getVersion(),
                'versionrem' => $moduleItem->getVersionRemote()
            );
        }

        $this->view->assign('modules', $modules);

        if ($addInfoLayer) {
            $this->view->addJsVars(array('fpcmModuleLayerInfos' => $jsInfo));
        }

        $permissionsArray = $this->getPermissionsArray();
        
        $this->view->assign('permissionInstall', $permissionsArray['canInstall']);
        $this->view->assign('permissionUninstall', $permissionsArray['canUnInstall']);
        $this->view->assign('permissionEnable', $permissionsArray['canEnable']);
    }

    /**
     * 
     * @return array
     * @since FPCM 4.1.2
     */
    public function getPermissionsArray() : array
    {
        return [
            'canInstall' => $this->permissions->check(['modules' => 'install']),
            'canUnInstall' => $this->permissions->check(['modules' => 'uninstall']),
            'canEnable' => $this->permissions->check(['modules' => 'configure'])
        ];
    }

}

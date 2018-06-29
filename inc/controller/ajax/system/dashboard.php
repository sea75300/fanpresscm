<?php

/**
 * Dashboard controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\system;

class dashboard extends \fpcm\controller\abstracts\ajaxController {

    /**
     * Dashboard-Container-Array
     * @var array
     */
    protected $containers = [];

    /**
     * Get view path for controller
     * @return string
     */
    protected function getViewPath()
    {
        return 'dashboard/list';
    }

    /**
     * Controller-Processing
     * @return boolean
     */
    public function process()
    {
        $this->getClasses();
        $this->view->assign('containers', $this->containers);
    }

    /**
     * Container-Klassen ermitteln
     */
    protected function getClasses()
    {
        $containers = array_map(array($this, 'parseClassname'), glob(\fpcm\classes\dirs::getIncDirPath('model' . DIRECTORY_SEPARATOR . 'dashboard' . DIRECTORY_SEPARATOR . '*.php')));
        $containers = $this->events->trigger('dashboardContainersLoad', $containers);

        $viewVars = $this->view->getViewVars();
        $jsFiles = [];
        foreach ($containers as $container) {

            /* @var $containerObj \fpcm\model\abstracts\dashcontainer */
            $containerObj = new $container();

            if (!is_a($containerObj, '\fpcm\model\abstracts\dashcontainer')) {
                trigger_error('Dashboard container class "' . $container . '" must be an instance of "\fpcm\model\abstracts\dashcontainer".');
                continue;
            }

            if (count($containerObj->getPermissions()) && !$this->permissions->check($containerObj->getPermissions())) {
                continue;
            }

            $pos = $containerObj->getPosition().'_'.$containerObj->getName();
            if (isset($this->containers[$pos])) {
                trigger_error('Error parse dashboard container, position ' . $pos . ' already taken!');
                continue;
            }

            $jsFiles = array_merge($jsFiles, $containerObj->getJavascriptFiles());

            $this->view->addJsVars($containerObj->getJavascriptVars());
            $this->view->addJsLangVars($containerObj->getJavascriptLangVars());

            $containerViewVars = $containerObj->getControllerViewVars();
            $viewVars = array_merge($viewVars, $containerViewVars);

            $this->containers[$pos] = $containerObj;
        }

        $this->view->setViewVars($viewVars);
        $this->view->assign('jsFiles', $jsFiles);
        ksort($this->containers);
    }

    /**
     * Container-Klassen-Name parsen
     * @param string $filename
     * @return string
     */
    protected function parseClassname($filename)
    {
        return '\\fpcm\\model\\dashboard\\' . basename($filename, '.php');
    }

}

?>
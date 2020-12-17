<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system;

/**
 * Backup manager controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class import extends \fpcm\controller\abstracts\controller
implements \fpcm\controller\interfaces\isAccessible {

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->options;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $this->view = new \fpcm\view\view('dummy');
        
        $uploader = \fpcm\components\components::getFileUploader();
        
        
        $this->view->addJsVars(array_merge([
            'uploadDest' => 'csv'
        ], $uploader->getJsVars() ));

        $this->view->addCssFiles($uploader->getCssFiles());
        $this->view->addJsLangVars(array_merge($uploader->getJsLangVars()));
        $this->view->addJsFiles(array_merge(['system/import.js'], $uploader->getJsFiles() ));
        $this->view->addJsFilesLate($uploader->getJsFilesLate());

        $this->view->setViewVars($uploader->getViewVars());

        $this->view->addTabs('import_main', [
            (new \fpcm\view\helper\tabItem('main'))->setText('IMPORT_MAIN')->setFile('system/import.php')
        ]);

        $this->view->addButton( (new \fpcm\view\helper\button('importStart'))->setText('IMPORT_START') );
        
        $this->view->assign('progressbarName', 'csvimport');
        
        $this->view->render();
    }

}

?>

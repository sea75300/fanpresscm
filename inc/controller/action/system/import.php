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
            'uploadDest' => 'csv',
            'fields' => $this->fetchFields()
        ], $uploader->getJsVars() ));

        $this->view->addCssFiles($uploader->getCssFiles());
        $this->view->addJsLangVars(array_merge(['IMPORT_FILE', 'IMPORT_PROGRESS'], $uploader->getJsLangVars()));
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

    public function fetchFields() : array
    {
        $list = $this->language->translate('SYSTEM_IMPORT_ITEMS');
        if (!is_array($list) || !count($list)) {
            return [];
        }
        
        $ns = '\\fpcm\\model\\';
        
        $list = array_filter($list, function ($item) use ($ns) {
            return is_subclass_of($ns.$item, '\fpcm\model\interfaces\isCsvImportable');
        });

        if (!is_array($list) || !count($list)) {
            return [];
        }

        $result = [];
        
        foreach ($list as $item) {
            
            $class = $ns . $item;
            $result[str_replace('\\', '_', $item)] = (new $class)->getFields();

            $this->view->addJsLangVars(array_keys($result[str_replace('\\', '_', $item)]));
            
        }

        return $result;
    }
}

?>

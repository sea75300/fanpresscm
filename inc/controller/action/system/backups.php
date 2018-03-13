<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system;

/**
 * Backup manager controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class backups extends \fpcm\controller\abstracts\controller {

    use \fpcm\controller\traits\common\dataView;
    
    protected function getPermissions()
    {
        return ['system' => 'backups'];
    }

    protected function getHelpLink()
    {
        return 'hl_options';
    }

    public function request()
    {
        if (!$this->getRequestVar('save')) {
            return true;
        }

        $filePath = $this->getRequestVar('save', [
            \fpcm\classes\http::FPCM_REQFILTER_URLDECODE,
            \fpcm\classes\http::FPCM_REQFILTER_BASE64DECODE
        ]);
        
        $filePath = $this->crypt->decrypt($filePath);
        $file = new \fpcm\model\files\dbbackup($filePath);

        if (!$file->exists()) {
            $this->view = new \fpcm\view\error('GLOBAL_NOTFOUND_FILE');
            return false;
        }

        header('Content-Description: File Transfer');
        header('Content-Type: ' . $file->getMimetype());
        header('Content-Disposition: attachment; filename="' . $file->getFilename() . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $file->getFilesize());
        readfile($file->getFullpath());
        exit;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $folderList = new \fpcm\model\files\backuplist();
        $this->items = $folderList->getFolderList();
        rsort($this->items);
        $this->initDataView();
        $this->view->assign('headline', 'HL_BACKUPS');
        $this->view->addJsFiles(['backups.js']);
        $this->view->render();
    }

    protected function getDataViewCols()
    {
        return [
            (new \fpcm\components\dataView\column('button', ''))->setSize(1)->setAlign('center'),
            (new \fpcm\components\dataView\column('name', 'FILE_LIST_FILENAME')),
            (new \fpcm\components\dataView\column('size', 'FILE_LIST_FILESIZE'))->setSize(3),
        ];
    }

    protected function getDataViewName()
    {
        return 'backups';
    }

    /**
     * 
     * @param string $file
     * @return \fpcm\components\dataView\row
     */
    protected function initDataViewRow($file)
    {
        $basename = basename($file);
        
        $url = \fpcm\classes\tools::getFullControllerLink('system/backups', ['save' => urlencode( base64_encode( $this->crypt->encrypt($basename) ) ) ] );

        return new \fpcm\components\dataView\row([
            new \fpcm\components\dataView\rowCol('button', (new \fpcm\view\helper\linkButton('download'.md5($basename)))->setUrl($url)->setText('GLOBAL_DOWNLOAD')->setIconOnly(true)->setIcon('download') , '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
            new \fpcm\components\dataView\rowCol('name', $basename),
            new \fpcm\components\dataView\rowCol('size', \fpcm\classes\tools::calcSize(filesize($file)) ),
        ]);
    }

    
}

?>

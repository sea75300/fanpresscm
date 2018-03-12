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

    protected function getPermissions()
    {
        return ['system' => 'backups'];
    }

    protected function getViewPath()
    {
        return 'components/dataview';
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
        $files = $folderList->getFolderList();
        $count = count($files);

        rsort($files);
        
        $dataView = new \fpcm\components\dataView\dataView('backups');
        
        if (!$count) {
            $dataView->addColumns([
                (new \fpcm\components\dataView\column('title', 'FILE_LIST_FILENAME'))->setSize(12),
            ]);
        }
        else {
            $dataView->addColumns([
                (new \fpcm\components\dataView\column('button', ''))->setSize(1)->setAlign('center'),
                (new \fpcm\components\dataView\column('name', 'FILE_LIST_FILENAME')),
                (new \fpcm\components\dataView\column('size', 'FILE_LIST_FILESIZE'))->setSize(3),
            ]);            
        }
        
        if (!$count) {
            $dataView->addRow(
                new \fpcm\components\dataView\row([
                    new \fpcm\components\dataView\rowCol('title', 'GLOBAL_NOTFOUND2', 'fpcm-ui-padding-md-lr'),
                ]
            ));
        }
        else {
            foreach ($files as $file) {

                $url = \fpcm\classes\tools::getFullControllerLink('system/backups', ['save' => urlencode( base64_encode( $this->crypt->encrypt(basename($file)) ) ) ] );

                $dataView->addRow(
                    new \fpcm\components\dataView\row([
                        new \fpcm\components\dataView\rowCol('button', (new \fpcm\view\helper\linkButton('download'.md5(basename($file))))->setUrl($url)->setText('GLOBAL_DOWNLOAD')->setIconOnly(true)->setIcon('download') , '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                        new \fpcm\components\dataView\rowCol('name', basename($file)),
                        new \fpcm\components\dataView\rowCol('size', \fpcm\classes\tools::calcSize(filesize($file)) ),
                    ]
                ));
            }
        }

        $this->view->addDataView($dataView);
        $this->view->assign('headline', 'HL_BACKUPS');
        $this->view->addJsFiles(['backups.js']);
        $this->view->render();
    }

}

?>

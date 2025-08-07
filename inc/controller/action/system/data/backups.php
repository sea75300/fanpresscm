<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system\data;

/**
 * Backup manager controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class backups
extends \fpcm\controller\abstracts\controller
implements \fpcm\controller\interfaces\requestFunctions
{

    use \fpcm\controller\traits\common\dataView;

    protected $i = 0;

    protected $deletePrevent = 5;

    protected $basePath;

    /**
     *
     * @var \fpcm\components\dataView\dataView
     */
    protected $dataView;

    /**
     *
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->backups;
    }

    protected function getHelpLink()
    {
        return 'HL_BACKUPS';
    }

    protected function onDelete() : bool
    {

        if (!$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return true;
        }

        $files = $this->request->fromPOST('files', [
            \fpcm\model\http\request::FILTER_URLDECODE,
            \fpcm\model\http\request::FILTER_BASE64DECODE,
            \fpcm\model\http\request::FILTER_DECRYPT
        ]);

        if (!is_array($files) || !count($files)) {
            return true;
        }

        $exists = [];
        $failed = [];
        $success = [];

        array_walk($files, function ($file) use (&$success, &$exists, &$failed) {

            $buf = new \fpcm\model\files\dbbackup($file);
            if (!$buf->exists()) {
                $exists[] = $file;
                return;
            }

            if (!$buf->isValidDataFolder('', \fpcm\classes\dirs::DATA_DBDUMP)) {
                $failed[] = $file;
                return;
            }

            if (!$buf->delete()) {
                $failed[] = $file;
                return;
            }

            $success[] = $file;

        });

        if (count($exists)) {
            $msg = $this->language->translate('GLOBAL_NOTFOUND_FILE').implode(', ', $exists);
            $this->view->addErrorMessage($msg);
            return true;
        }

        if (count($failed)) {
            $this->view->addErrorMessage('DELETE_FAILED_FILES', [
                '{{filenames}}' => implode(', ', $failed)
            ]);

            return true;
        }

        $this->view->addNoticeMessage('DELETE_SUCCESS_FILES', [
            '{{filenames}}' => implode(', ', $success)
        ]);

        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $this->basePath = \fpcm\model\files\ops::removeBaseDir(\fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_DBDUMP), true);

        $isPg = \fpcm\classes\loader::getObject('\fpcm\classes\database')->getDbtype() === \fpcm\classes\database::DBTYPE_POSTGRES;

        $this->view->addButton((new \fpcm\view\helper\deleteButton('delete'))->setClickConfirm()->setReadonly($isPg)->setIconOnly(false));
        $this->view->addJsFiles(['system/backups.js']);

        if ($isPg) {
            $this->view->addErrorMessage('BACKUPS_NOTICE_POSTGRES');
            $this->items = [];
            $this->initDataView();
            $this->view->render();
            return true;
        }


        $folderList = new \fpcm\model\files\backuplist();
        $this->items = $folderList->getFolderList();
        rsort($this->items);
        $this->initDataView();
        $this->view->setFormAction('system/backups');
        $this->view->render();
    }

    protected function getDataViewTabs() : array
    {
        return [
            (new \fpcm\view\helper\tabItem('tabs-'.$this->getDataViewName().'-list'))
                ->setText('HL_BACKUPS')
                ->setFile('components/dataview_inline.php')
        ];
    }

    protected function getDataViewCols()
    {
        $cbxa = (new \fpcm\view\helper\checkbox('fpcm-select-all'))->setClass('fpcm-select-all');

        return [
            (new \fpcm\components\dataView\column('select', $cbxa))->setSize(1)->setAlign('center'),
            (new \fpcm\components\dataView\column('name', 'FILE_LIST_FILENAME'))->setSize(10),
            (new \fpcm\components\dataView\column('size', 'FILE_LIST_FILESIZE'))->setSize(1),
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
        $hash = md5($basename);

        $val = urlencode(base64_encode( $this->crypt->encrypt($basename)));

        $this->i++;

        $ro = $this->i <= $this->deletePrevent;

        $cbx = (new \fpcm\view\helper\checkbox('files[]', 'files'.$hash))
                ->setClass($ro ? '' : 'fpcm-ui-list-checkbox')
                ->setValue($val)
                ->setReadonly($ro);

        return new \fpcm\components\dataView\row([
            new \fpcm\components\dataView\rowCol('select', $cbx, '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
            new \fpcm\components\dataView\rowCol('name', sprintf('%s<br><span class="text-body-secondary fpcm ui-font-small">%s %s/%s</span>', $basename, (new \fpcm\view\helper\icon('folder-tree'))->setText('MODULES_LIST_DATAPATH'), $this->basePath, $basename)),
            new \fpcm\components\dataView\rowCol('size', \fpcm\classes\tools::calcSize(filesize($file)) ),
        ]);
    }

}

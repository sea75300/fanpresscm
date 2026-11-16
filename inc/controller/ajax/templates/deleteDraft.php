<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\templates;

/**
 * AJAX delete draft
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.4.0-a1
 */
class deleteDraft extends \fpcm\controller\abstracts\ajaxController
{

    /**
     * Access granted?
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->drafts;
    }

    /**
     *
     * @return bool
     */
    public function request(): bool
    {
        $hash = $this->request->fromPOST('hash');
        if (!$hash) {
            trigger_error('Cannot delete draft, invalid data provided!!');
            $this->response->setCode(403)->fetch();
            return false;
        }
        
        
        $file = (new \fpcm\model\files\templatefilelist)->getByHash($hash);

        if (!$file instanceof \fpcm\model\files\templatefile) {
            trigger_error('Cannot delete draft, invalid data provided!!');
            $this->response->setCode(403)->fetch();
        }

        if (!$file->isWritable()) {
            $this->response->setReturnData( new \fpcm\model\http\responseMessage(
                new \fpcm\view\message(
                    $this->language->translate('FILE_NOT_WRITABLE'),
                    \fpcm\view\message::TYPE_ERROR
                )    
            ))->fetch();
            return false;
        }

        if (!$file->delete()) {
            $this->response->setReturnData( new \fpcm\model\http\responseMessage(
                new \fpcm\view\message(
                    $this->language->translate('DELETE_FAILED_FILES', [
                        '{{filenames}}' => $file->getFilename()
                    ]),
                    \fpcm\view\message::TYPE_ERROR
                )    
            ))->fetch();
            return false;
        }

        $this->response->setCode(200);
        return true;
    }

}

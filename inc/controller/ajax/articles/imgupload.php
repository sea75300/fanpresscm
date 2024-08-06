<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\articles;

/**
 * Änderungen an Bildern in TinyMCE auf Server Speichern
 * 
 * @package fpcm\controller\ajax\articles\removeeditortags
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @since 3.5
 */
class imgupload extends \fpcm\controller\abstracts\ajaxController
{

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->uploads->visible && $this->permissions->uploads->add;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        if (!isset($_FILES['file'])) {
            $this->response->setCode(500)->addHeaders('HTTP/1.1 500 Internal Server Error')->fetch();
        }

        $data = $_FILES['file'];
        $name = $data['name'];

        $localPath = \fpcm\model\files\ops::getUploadPath($name, $this->config->file_subfolders);
        if (file_exists($localPath)) {
            \fpcm\model\files\image::getCropperFilename($name);
        }

        $uploader = new \fpcm\model\files\fileuploader([
            'tmp_name' => [$data['tmp_name']],
            'name' => [$name],
            'type' => [$data['type']],
        ]);

        $result = $uploader->processUpload($this->session->getUserId());

        if (!count($result['error']) && count($result['success'])) {
            $this->response->setReturnData([
                'location' => \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_UPLOADS, $uploader->getUploadFileName($name))
            ])->fetch();
        }

        $this->response->setCode(500)->addHeaders('HTTP/1.1 500 Internal Server Error')->fetch();
    }

}

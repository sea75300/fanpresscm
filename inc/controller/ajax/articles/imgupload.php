<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\articles;

/**
 * Änderungen an Bildern in TinyMCE auf Server Speichern
 * 
 * @package fpcm\controller\ajax\articles\removeeditortags
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @since FPCM 3.5
 */
class imgupload extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

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
            header("HTTP/1.0 500 Server Error");
            exit;
        }

        $data = $_FILES['file'];
        $name = $data['name'];

        $localPath = \fpcm\model\files\ops::getUploadPath($name, $this->config->file_subfolders);
        if (file_exists($localPath)) {
            $name = explode('.', $name);
            $name[0] .= '_cropped' . date('Ymd') . $this->session->getUserId();
            $name = implode('.', $name);
        }

        $uploader = new \fpcm\model\files\fileuploader([
            'tmp_name' => [$data['tmp_name']],
            'name' => [$name],
            'type' => [$data['type']],
        ]);

        $result = $uploader->processUpload($this->session->getUserId());

        if (!count($result['error']) && count($result['success'])) {
            $this->returnData = ['location' => \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_UPLOADS, $uploader->getUploadFileName($name))];
            $this->getSimpleResponse();
        }

        header("HTTP/1.0 500 Server Error");
    }

}

?>
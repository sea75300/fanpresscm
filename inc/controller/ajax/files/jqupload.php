<?php

/**
 * AJAX jQuery uploader controller
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\files;

/**
 * AJAX Controller for jQuery uploader
 * 
 * @package fpcm\controller\ajax\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class jqupload extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

    /**
     *
     * @var string
     */
    protected $dest = '';

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
        require_once \fpcm\classes\loader::libGetFilePath('jqupload/server/fpcmUploadHandler.php');

        $config = $this->processByParam('getConfig', 'dest');
        if ($config === self::ERROR_PROCESS_BYPARAMS) {
            $this->response->setCode('501')->addHeaders('HTTP/1.1 501 Not Implemented')->fetch();
        }

        $config['script_url'] = \fpcm\classes\tools::getFullControllerLink('ajax/jqupload', [ 'dest' => $this->dest ]);
        
        new \fpcmUploadHandler($config);

    }

    /**
     * 
     * @return array
     */
    protected function getConfigDefault() : array
    {
        return [
            'upload_dir' => \fpcm\model\files\ops::getUploadPath(DIRECTORY_SEPARATOR, $this->config->file_subfolders),
            'upload_url' => \fpcm\model\files\ops::getUploadUrl('/', $this->config->file_subfolders),
            'accept_file_types' => \fpcm\components\fileupload\jqupload::FILETYPES_IMG,
            'image_versions' => array(
                'thumbnail' => array(
                    'upload_dir' => \fpcm\model\files\ops::getUploadPath(DIRECTORY_SEPARATOR.'thumbs'.DIRECTORY_SEPARATOR),
                    'upload_url' => \fpcm\model\files\ops::getUploadUrl('/thumbs/'),
                    'crop' => false,
                    'max_width' => $this->config->file_img_thumb_width,
                    'max_height' => $this->config->file_img_thumb_height
                )
            ),
            'min_width' => false,
            'max_width' => false,
            'min_height' => false,
            'max_height' => false
        ];
    }

    /**
     * 
     * @return array
     */
    protected function getConfigDrafts() : array
    {
        return [
            'upload_dir' => \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_DRAFTS, DIRECTORY_SEPARATOR),
            'upload_url' => \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_DRAFTS, '/'),
            'accept_file_types' => \fpcm\components\fileupload\jqupload::FILETYPES_DRAFTS,
            'image_versions' => array(),
            'min_width' => false,
            'max_width' => false,
            'min_height' => false,
            'max_height' => false
        ];
    }

    /**
     * 
     * @return array
     */
    protected function getConfigModules() : array
    {
        $unique = \fpcm\classes\tools::getHash($this->session->getSessionId().$this->session->getUserId());

        return [
            'upload_dir' => \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, DIRECTORY_SEPARATOR. $unique. DIRECTORY_SEPARATOR),
            'upload_url' => \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_TEMP, '/'. $unique . '/'),
            'accept_file_types' => \fpcm\components\fileupload\jqupload::FILETYPES_MODULES,
            'max_number_of_files' => 1,
            'image_versions' => array(),
            'replace_dots_in_filenames' => null,
            'min_width' => false,
            'max_width' => false,
            'min_height' => false,
            'max_height' => false
        ];
    }

}

?>
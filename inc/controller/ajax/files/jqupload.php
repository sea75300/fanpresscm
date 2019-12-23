<?php

/**
 * AJAX jQuery uploader controller
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
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
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->uploads->add;
    }
    
    /**
     * Controller-Processing
     */
    public function process()
    {
        require_once \fpcm\classes\loader::libGetFilePath('jqupload/server/fpcmUploadHandler.php');
        
        new \fpcmUploadHandler([
            'script_url' => \fpcm\classes\tools::getFullControllerLink('ajax/jqupload'),
            'upload_dir' => \fpcm\model\files\ops::getUploadPath(DIRECTORY_SEPARATOR, $this->config->file_subfolders),
            'upload_url' => \fpcm\model\files\ops::getUploadUrl('/', $this->config->file_subfolders),
            'accept_file_types' => '/\.(gif|jpe?g|png|bmp)$/i',
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
        ]);

    }

}

?>
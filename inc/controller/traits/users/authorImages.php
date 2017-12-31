<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\controller\traits\users;
    
    /**
     * Author image processing trait
     * 
     * @package fpcm\controller\traits\users\authorImages
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    trait authorImages {

        /**
         * Author-Avatar hochlanden
         * @param \fpcm\model\users\author $author
         * @return boolean
         */
        protected function uploadImage($author) {

            if (!$author instanceof \fpcm\model\users\author) {
                return false;
            }
            
            $files = \fpcm\classes\http::getFiles();

            if ($this->buttonClicked('uploadFile') && !is_null($files)) {
                $uploader = new \fpcm\model\files\fileuploader($files);
                $res = $uploader->processAuthorImageUpload($author->getImage());

                $this->cache->cleanup('authorImages', 'system');
                if ($res == true) {
                    $this->view->addNoticeMessage('SAVE_SUCCESS_UPLOADAUTHORIMG');
                    return true;
                }

                $this->view->addErrorMessage('SAVE_FAILED_UPLOADAUTHORIMG');
                return false;
            }
            
        }

        /**
         * Author-Avatar löschen
         * @param \fpcm\model\users\author $author
         * @return boolean
         */
        protected function deleteImage($author) {

            if (!$author instanceof \fpcm\model\users\author) {
                return false;
            }

            if (!$this->buttonClicked('fileDelete')) {
                return true;
            }

            $res = true;
            foreach (\fpcm\model\files\image::$allowedExts as $ext) {

                $filename = $author->getImage().'.'.$ext;
                $authorImage = new \fpcm\model\files\authorImage($filename);
                if (!$authorImage->exists()) {
                    continue;
                }

                $res = $res && $authorImage->delete();
            }
            
            $this->cache->cleanup('authorImages', 'system');
            if ($res == true) {
                $this->view->addNoticeMessage('DELETE_SUCCESS_FILEAUTHORIMG');
                return true;
            }

            $this->view->addErrorMessage('DELETE_FAILED_FILEAUTHORIMG');
            return false;
        }


    }
?>
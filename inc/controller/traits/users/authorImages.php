<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\users;

/**
 * Author image processing trait
 * 
 * @package fpcm\controller\traits\users\authorImages
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait authorImages {

    /**
     *
     * @var \fpcm\components\fileupload\uploader
     */
    protected $uploader;

    /**
     *
     * @var \Sonata\GoogleAuthenticator\GoogleAuthenticator
     */
    protected $gAuth;

    /**
     * Author-Avatar hochlanden
     * @param \fpcm\model\users\author $author
     * @return bool
     */
    protected function uploadImage($author)
    {
        if (!$author instanceof \fpcm\model\users\author) {
            return false;
        }

        if (!$this->uploader instanceof \fpcm\components\fileupload\htmlupload) {
            return false;
        }

        if (!$this->buttonClicked('uploadFile')) {
            return false;
        }

        $files = $this->request->fromFiles();
        if ($files === null) {
            return false;
        }
        
        $uploader = new \fpcm\model\files\fileuploader($files);
        $res = $uploader->processAuthorImageUpload($author->getImage());

        $this->cache->cleanup();
        if ($res == true) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_UPLOADAUTHORIMG');
            return true;
        }

        $this->view->addErrorMessage('SAVE_FAILED_UPLOADAUTHORIMG');
        return false;
    }

    /**
     * Author-Avatar löschen
     * @param \fpcm\model\users\author $author
     * @return bool
     */
    protected function deleteImage($author)
    {
        if (!$author instanceof \fpcm\model\users\author) {
            return false;
        }

        if (!$this->buttonClicked('fileDelete')) {
            return true;
        }

        $res = true;
        foreach (\fpcm\model\files\image::$allowedExts as $ext) {

            $filename = $author->getImage() . '.' . $ext;
            $authorImage = new \fpcm\model\files\authorImage($filename);
            if (!$authorImage->exists()) {
                continue;
            }

            $res = $res && $authorImage->delete();
        }

        $this->cache->cleanup();
        if ($res == true) {
            $this->view->addNoticeMessage('DELETE_SUCCESS_FILEAUTHORIMG');
            return true;
        }

        $this->view->addErrorMessage('DELETE_FAILED_FILEAUTHORIMG');
        return false;
    }

    /**
     * 
     * @return bool
     */
    protected function twoFactorAuthForm()
    {
        $enabled = $this->config->system_2fa_auth;
        $this->view->assign('twoFaAuth', $enabled);
        if (!$enabled) {
            return false;
        }
        
        if ($this->user->getAuthtoken()) {
            $this->view->assign('qrCode', false);
            $this->view->assign('secret', false);
            return true;
        }

        $secret = $this->gAuth->generateSecret();
        $this->view->assign('qrCode', \Sonata\GoogleAuthenticator\GoogleQrUrl::generate($this->user->getEmail(), $secret, $this->language->translate('HEADLINE')));
        $this->view->assign('secret', $secret);
        return true;
    }

    /**
     * 
     * @param \fpcm\model\users\author $author
     * @return bool
     */
    protected function initUploader(\fpcm\model\users\author $author) : bool
    {
        $this->uploader = \fpcm\components\components::getFileUploader();

        if (!trim($this->uploader->getTemplate()) || !realpath($this->uploader->getTemplate())) {
            trigger_error('Undefined file upload template given in '.$this->uploader->getTemplate());
            $this->execDestruct = false;
            return false;
        }        

        $this->view->setViewVars($this->uploader->getViewVars());
        $this->view->addJsFiles($this->uploader->getJsFiles());
        $this->view->addJsFiles(['users/userimage.js']);        
        $this->view->addJsVars(array_merge([
            'uploadDest' => 'userimage&uid=' . $author->getId(),
            'userImgRedir' => \fpcm\classes\tools::getFullControllerLink('system/profile', [
                'rg' => 1
            ])
        ], $this->uploader->getJsVars() ));        
        
        
        $this->view->addCssFiles($this->uploader->getCssFiles());
        $this->view->addJsLangVars($this->uploader->getJsLangVars());        
        $this->view->addJsFilesLate($this->uploader->getJsFilesLate());
        $this->view->setJsModuleFiles($this->uploader->getJsModuleFiles());
        $this->view->assign('hideDropArea', true);
        
        return true;
    }

}

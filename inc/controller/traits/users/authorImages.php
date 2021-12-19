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
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait authorImages {

    /**
     *
     * @var \fpcm\components\fileupload\htmlupload
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

        $files = $this->request->fromFiles();
        if ($this->buttonClicked('uploadFile') && !is_null($files)) {
            $uploader = new \fpcm\model\files\fileuploader($files);
            $res = $uploader->processAuthorImageUpload($author->getImage());

            $this->cache->cleanup('system/author' . $author->getImage() . '_image');
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

        $this->cache->cleanup('system/author' . $author->getImage() . '_image');
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
    
    protected function initUploader() : bool
    {
        $this->uploader = \fpcm\components\components::getFileUploader('\\fpcm\\components\\fileupload\\htmlupload');
        $this->view->setViewVars($this->uploader->getViewVars());
        $this->view->addJsFiles($this->uploader->getJsFiles());
        $this->view->addJsVars($this->uploader->getJsVars());
        return true;
    }

}

?>
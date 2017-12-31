<?php

    namespace fpcm\controller\ajax\articles;
    
    /**
     * Änderungen an Bildern in TinyMCE auf Server Speichern
     * 
     * @package fpcm\controller\ajax\articles\removeeditortags
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @since FPCM 3.5
     */
    class imgupload extends \fpcm\controller\abstracts\ajaxController {

        /**
         * Request-Handler
         * @return bool
         */
        public function request() {

            if ($this->session->exists() && is_object($this->permissions) && $this->permissions->check(['uploads' => 'add'])) {
                return true;
            }

            header("HTTP/1.0 500 Server Error");
            return false;
        }

        /**
         * Controller-Processing
         */
        public function process() {

            if (!parent::process()) return false;

            if (isset($_FILES['file'])) {
                $data = $_FILES['file'];

                $name = $data['name'];
                if (file_exists(\fpcm\classes\baseconfig::$uploadDir.$name)) {
                    $name     = explode('.', $data['name']);
                    $name[0] .= '_cropped'. date('Ymd').$this->session->getUserId();
                    $name     = implode('.', $name);
                }

                $uploader = new \fpcm\model\files\fileuploader([
                    'tmp_name'  => [$data['tmp_name']],
                    'name'      => [$name],
                    'type'      => [$data['type']],
                ]);

                $result = $uploader->processUpload($this->session->getUserId());

                if (!count($result['error']) && count($result['success'])) {
                    die(json_encode(['location' => \fpcm\classes\baseconfig::$uploadRootPath.$name]));
                }

            }

            header("HTTP/1.0 500 Server Error");

        }
    }
?>
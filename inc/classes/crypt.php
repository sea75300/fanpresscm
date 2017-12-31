<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\classes;

    /**
     * Crypt wrapper class
     * 
     * @package fpcm\classes\crypt
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.5
     */ 
    final class crypt {

        /**
         * Methode zur Verschlüsselung
         * @var string
         */
        protected $method = '';

        /**
         * Passwort für Verschlüsselung
         * @var string
         */
        protected $passwd = '';

        /**
         * IV-String für für Verschlüsselung
         * @var string
         */
        protected $iv     = '';

        /**
         * Flag, ob OpenSSL für PHP verfügbvar ist
         * @var bool
         */
        protected $hasCrypt = false;

        /**
         * Flag, ob Konfiguratzion verfügbvar ist
         * @var bool
         */
        protected $hasConfig = false;

        /**
         * Liste von Chipher für Verschlüsselung
         * @var array
         */
        protected $checkChiphers = [
            'aes256',
            'aes192',
            'aes128',
            'blowfish',
            'desx',
            'des3',
            'des',
        ];

        /**
         * Konstruktor
         * @return boolean
         */
        public function __construct() {

            $this->hasCrypt = function_exists('openssl_encrypt') && function_exists('openssl_decrypt');
            if (!$this->hasCrypt || baseconfig::installerEnabled()) {
                return false;
            }

            if (!function_exists('baseconfig::getCryptConfig')) {
                return false;
            }
            
            $conf = baseconfig::getCryptConfig();
            if (!is_array($conf) || !count($conf)) {
                $this->hasConfig = false;
                return false;
            }

            $this->hasConfig = true;
            foreach ($conf as $key => $value) {
                $this->$key = $value;
            }

        }

        /**
         * Daten verschlüsseln
         * @param string $data
         * @return string
         */
        public function encrypt($data) {

            if (is_array($data) || is_object($data)) {
                $data = json_encode($data);
            }

            if (!$this->hasCompleteConfig()) {
                return $this->simpleEncrypt($data);
            }

            $result = openssl_encrypt($data, $this->method, $this->passwd, 0, $this->iv);
            if ($result === false) {
                trigger_error('Crypt error: '.openssl_error_string());
                return $this->simpleEncrypt($data);
            }

            return $result;
        }

        /**
         * Daten entschlüsseln
         * @param string $data
         * @return string
         */
        public function decrypt($data) {

            if (is_array($data) || is_object($data)) {
                $data = json_decode($data);
            }

            if (!$this->hasCompleteConfig()) {
                return $this->simpleDecrypt($data);
            }

            $result = openssl_decrypt($data, $this->method, $this->passwd, 0, $this->iv);
            if ($result === false) {
                trigger_error('Crypt error: '.openssl_error_string());
                return $this->simpleDecrypt($data);
            }

            return $result;
        }

        /**
         * Initialisiert Crypt Key und Methode
         * @return bool
         */
        public function initCrypt() {
            
            if (!$this->hasCrypt) {
                return false;
            }

            if ($this->hasConfig) {
                return true;
            }

            $ciphers = array_diff(openssl_get_cipher_methods(), openssl_get_cipher_methods(true));
            $ciphers = array_diff($this->checkChiphers, $ciphers);

            if (!is_array($ciphers) || !count($ciphers)) {
                return false;
            }

            $method  = array_shift($ciphers);

            $config = [
                'method' => $method,
                'passwd' => security::createPasswordHash(uniqid(mt_rand()), security::createSalt()),
                'iv'     => substr(uniqid(mt_rand().microtime(true)), 0, openssl_cipher_iv_length($method))
            ];

            if (!$config['iv']) {
                return false;
            }

            return file_put_contents(baseconfig::$configDir.'crypt.php', '<?php'.PHP_EOL.' $config = '.var_export($config, true).PHP_EOL.'?>');
            
        }

        /**
         * einfache Verschlüsselung via base64_encode und str_rot13
         * @param string $data
         * @return string
         */
        private function simpleEncrypt($data) {
            return str_rot13(base64_encode(str_rot13(base64_encode(str_rot13(base64_encode($data))))));
        }

        /**
         * einfache Entschlüsselung via base64_decode und str_rot13
         * @param string $data
         * @return string
         */
        private function simpleDecrypt($data) {
            return base64_decode(str_rot13(base64_decode(str_rot13(base64_decode(str_rot13($data))))));
        }

        /**
         * Check ob Konfiguration vollständig
         * @return boolean
         */
        private function hasCompleteConfig() {

            if (!$this->hasCrypt || !$this->hasConfig || !$this->method || !$this->passwd || !$this->iv) {
                return false;
            }

            return true;
        }

    }

?>
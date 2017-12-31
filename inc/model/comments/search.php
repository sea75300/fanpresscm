<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\comments;

    /**
     * Comment search wrapper object
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm\model\comments
     * @since FPCM 3.5
     * 
     * @property int $articleid Artikel-ID
     * @property int $datefrom seit Datum X.Y.Z
     * @property int $dateto bis Datum X.Y.Z
     * @property string $text via Kommentar-Text, Name, Webseite und E-Mail-Adresse
     * @property string $combination logische Verknüpfung AND/OR
     * @property string $ipaddress IP-Adresse
     * @property bool $searchtype Suchtyp
     * @property bool $spam nur als Spam markierte Kommentare
     * @property bool $private nur als Privat markierte Kommentare
     * @property bool $approved nur als Freigegeben markierte Kommentare
     * @property bool $unapproved nur als nicht Freigegeben markierte Kommentare
     * @property array $limit Abfrage einschränken
     * @property array $orderby Array von Sortierungen in SQL-Syntax
     */ 
    class search extends \fpcm\model\abstracts\searchWrapper {

        /**
         * Liefert Daten zurück, die über Eigenschaften erzeugt wurden
         * @return array
         */
        public function getData() {
            
            if (!isset($this->data['searchtype'])) {
                $this->data['searchtype'] = 0;
            }
            
            return $this->data;
        }
        
    }
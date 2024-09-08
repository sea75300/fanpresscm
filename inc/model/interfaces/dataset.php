<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\interfaces;

/**
 * Model-Interface
 *
 * @package fpcm\model\interfaces
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
interface dataset {

    /**
     * Gibt Object-ID zurück
     * @return int
     */
    public function getId();

    /**
     * Initialisiert Objekt mit Daten aus Datenbank
     * @return bool
     */
    public function init();

    /**
     * Existiert Objekt in Datenbank
     * @return bool
     */
    public function exists();

    /**
     * Speichert ein Objekt in der Datenbank
     * @return bool
     */
    public function save();

    /**
     * Aktualisiert ein Objekt in der Datenbank
     * @return bool
     */
    public function update();

    /**
     * Löscht ein Objekt in der Datenbank
     * @return bool
     */
    public function delete();

}

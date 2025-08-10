<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\module;

/**
 * Comment search wrapper object
 *
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\module
 * @since 5.3-dev
 *
 * @property string $text search by module name or key
 * @property string $status search by module status
 */
class search extends \fpcm\model\abstracts\searchWrapper {

    /**
     * Liefert Daten zurück, die über Eigenschaften erzeugt wurden
     * @return array
     */
    public function getData()
    {
        if (isset($this->data['status'])) {
            $this->data['status'] = match ($this->data['status']) {
                'active' => 1,
                'inactive' => 0,
                default => -1
            };
        }

        return $this->data;
    }

}

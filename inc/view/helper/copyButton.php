<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Edit link button view helper object
 *
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.2.2-a1
 */
final class copyButton extends button {

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        parent::init();
        $this->class .= ' fpcm-ui-button-copy';
        $this->iconOnly = true;
        $this->setText('GLOBAL_COPY');
        $this->setIcon('copy');
    }
    
    /**
     * Set copy params
     * @param \fpcm\model\abstracts\dataset $object
     * @param string $type
     * @return $this
     */
    final public function setCopyParams($object, string $type)
    {
        if (!is_object($object)) {
            trigger_error('Invalid parameter, $object must be an object');
            return $this;
        }

        if (!method_exists($object, 'getId')) {
            trigger_error('Invalid parameter, $object must provide a functiond "getId()"');
            return $this;
        }

        $this->setOnClick('system.createCopy', sprintf("%s:%s", $type, $object->getId()));
        return $this;
    }

}

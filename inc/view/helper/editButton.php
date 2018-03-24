<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Edit link button view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class editButton extends linkButton {

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        parent::init();
        $this->class .= ' fpcm-ui-button-edit fpcm-loader';
        $this->iconOnly = true;
        $this->setText('GLOBAL_EDIT');
        $this->setIcon('edit');
    }

    /**
     * Set URL by given object
     * @param \fpcm\model\abstracts\dataset $object
     * @return $this
     */
    final public function setUrlbyObject($object, $paramsString = '')
    {
        if (!is_object($object)) {
            trigger_error('Invalid parameter, $object must be an object');
            return $this;
        }

        if (!method_exists($object, 'getEditLink')) {
            trigger_error('Invalid parameter for $object of class ' . get_class($object) . ', method getEditLink() not found');
            return $this;
        }

        $this->url = $object->getEditLink() . $paramsString;

        if (method_exists($object, 'getEditPermission')) {
            $this->readonly = $object->getEditPermission() ? false : true;
        }

        if (method_exists($object, 'getId')) {
            $this->name .= $object->getId();
            $this->id .= $object->getId();
        }

        return $this;
    }

}

?>
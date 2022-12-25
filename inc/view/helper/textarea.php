<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Text input view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class textarea extends input {

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        $this->class = 'form-control';
    }

    /**
     * Return element string
     * @return string
     */
    protected function getString()
    {
        
        if (str_starts_with($this->text, 'LABEL_FIELD_')) {
            $this->text = '';
        }
        
        if ($this->text) {
            
            if (!trim($this->labelSize) && !trim($this->fieldSize)) {
                $this->setDisplaySizesDefault();
            }            

            return sprintf(
                '<div class="%s %s"><label title="%s" class="col-form-label pe-3 %s" for="%s">%s%s</label><textarea %s %s %s %s %s>%s</textarea></div>',                    
                $this->labelType,
                $this->bottomSpace,
                $this->text,
                $this->getLabelSize(),
                $this->id,
                $this->getIconString(),
                $this->getDescriptionTextString('ps-1'),
                $this->getNameIdString(),
                $this->getClassString(),
                $this->getReadonlyString(),
                $this->getPlaceholderString(),
                $this->getDataString(),
                $this->value
            );
        }
        
        return sprintf(
            '<textarea %s %s %s %s %s>%s</textarea>',
            $this->getNameIdString(),
            $this->getClassString(),
            $this->getReadonlyString(),
            $this->getPlaceholderString(),
            $this->getDataString(),
            $this->value
        );
    }

}

?>
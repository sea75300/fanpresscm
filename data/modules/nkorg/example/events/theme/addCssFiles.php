<?php

namespace fpcm\modules\nkorg\example\events\theme;

final class addCssFiles extends \fpcm\modules\nkorg\example\events\eventBase  {

    public function run()
    {
        $this->data[] = \fpcm\classes\dirs::getDataUrl(
            \fpcm\classes\dirs::DATA_MODULES,
            str_replace('\\', '/', $this->key).'/templates/excample.css'
        );
        
        $this->logEvent(__METHOD__);
        $this->logEvent($this->data);
        
        return $this->data;
    }

}

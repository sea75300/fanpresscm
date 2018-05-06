<?php

namespace fpcm\modules\nkorg\example\events\logs;

final class getRow extends \fpcm\modules\nkorg\example\events\eventBase {

    public function run()
    {
        if ($this->data['log'] != 'nkorg-example') {
            return new \fpcm\components\dataView\row([]);
        }

        return new \fpcm\components\dataView\row([
            new \fpcm\components\dataView\rowCol('time', $this->data['item']['time'], 'fpcm-ui-dataview-align-self-start'),
            new \fpcm\components\dataView\rowCol('text', new \fpcm\view\helper\escape($this->data['item']['text']), 'pre-box'),
        ]);        
    }

}
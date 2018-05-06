<?php

namespace fpcm\modules\nkorg\example\events\logs;

final class getCols extends \fpcm\modules\nkorg\example\events\eventBase {

    public function run()
    {
        if ($this->data != 'nkorg-example') {
            return [];
        }

        return [
            (new \fpcm\components\dataView\column('time', 'LOGS_LIST_TIME', 'fpcm-ui-padding-md-left'))->setSize(2),
            (new \fpcm\components\dataView\column('text', 'LOGS_LIST_TEXT'))->setSize(10),
        ];
    }

}

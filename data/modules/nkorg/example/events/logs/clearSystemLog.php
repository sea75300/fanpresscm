<?php

namespace fpcm\modules\nkorg\example\events\logs;

class clearSystemLog extends \fpcm\modules\nkorg\example\events\eventBase {

    public function run()
    {
        if ($this->data != 'nkorg-example') {
            return true;
        }

        return $this->cleanupLog();
    }

}

<?php

namespace fpcm\modules\nkorg\example\events\modules;

class installAfter extends \fpcm\modules\nkorg\example\events\eventBase {

    public function run()
    {
        $this->logEvent(__METHOD__);
        return true;
    }

}

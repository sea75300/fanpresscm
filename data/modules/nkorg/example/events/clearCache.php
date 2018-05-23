<?php

namespace fpcm\modules\nkorg\example\events;

final class clearCache extends \fpcm\modules\nkorg\example\events\eventBase {

    public function run()
    {
        $this->logEvent(__METHOD__);
        $this->logEvent($this->data);
        return null;
    }
}

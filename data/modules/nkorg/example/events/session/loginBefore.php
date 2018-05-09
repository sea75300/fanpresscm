<?php

namespace fpcm\modules\nkorg\example\events\session;

final class loginBefore extends \fpcm\modules\nkorg\example\events\eventBase {

    public function run()
    {
        $this->logEvent(__METHOD__.' login data is');
        $this->logEvent($this->data);
        return $this->data;
    }

}

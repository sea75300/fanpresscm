<?php

namespace fpcm\modules\nkorg\example\events\userroll;

final class update extends \fpcm\modules\nkorg\example\events\eventBase {

    public function run()
    {
        return $this->data.' '. microtime(true).' '.$this->key.' '. get_class($this);
    }

}

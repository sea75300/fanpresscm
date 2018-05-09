<?php

namespace fpcm\modules\nkorg\example\events\user;

final class save extends \fpcm\modules\nkorg\example\events\eventBase {

    public function run()
    {
        $this->data['usrinfo'] = $this->data['usrinfo'].' '. microtime(true).' '.$this->key.' '. get_class($this);
        return $this->data;
    }

}

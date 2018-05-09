<?php

namespace fpcm\modules\nkorg\example\events\comments;

final class save extends \fpcm\modules\nkorg\example\events\eventBase {

    public function run()
    {
        $this->data['name'] = $this->data['name'].' '. microtime(true).' '.$this->key.' '. get_class($this);
        return $this->data;
    }

}

<?php

namespace fpcm\modules\nkorg\example\events\wordban;

final class save extends \fpcm\modules\nkorg\example\events\eventBase {

    public function run()
    {
        $this->data['searchtext'] = $this->data['searchtext'].' '. microtime(true).' '.$this->key.' '. get_class($this);
        return $this->data;
    }

}

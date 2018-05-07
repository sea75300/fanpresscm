<?php

namespace fpcm\modules\nkorg\example\events\article;

final class update extends \fpcm\modules\nkorg\example\events\eventBase {

    public function run()
    {
        $this->data['title'] = $this->data['title'].' '. microtime(true).' '.$this->key.' '. get_class($this);
        return $this->data;
    }

}

<?php

namespace fpcm\modules\nkorg\example\events\article;

final class getShortLink extends \fpcm\modules\nkorg\example\events\eventBase {

    public function run()
    {
        $this->data['url'] = 'This short link was remove by events '. get_class($this).' in '.$this->key;
        return $this->data;
    }

}

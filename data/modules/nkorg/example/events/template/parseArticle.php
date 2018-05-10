<?php

namespace fpcm\modules\nkorg\example\events\template;

final class parseArticle extends \fpcm\modules\nkorg\example\events\eventBase {

    public function run()
    {
        $this->data['{{example}}'] = 'This is an example template placeholder!';
        $this->logEvent(__METHOD__);
        $this->logEvent($this->data);
        return $this->data;
    }
    
}

<?php

namespace fpcm\modules\nkorg\example\events;

final class runSystemCheck extends \fpcm\modules\nkorg\example\events\eventBase {

    public function run()
    {
        $this->data['Example test'] = new \fpcm\model\system\syscheckOption(true, false, true);
        
        $this->logEvent(__METHOD__);
        $this->logEvent($this->data);
        
        return $this->data;
    }

}

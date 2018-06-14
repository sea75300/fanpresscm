<?php

namespace fpcm\modules\nkorg\example\events\navigation;

final class add extends \fpcm\modules\nkorg\example\events\eventBase {

    public function run()
    {
        $this->data[\fpcm\model\theme\navigationItem::AREA_AFTER][] = (new \fpcm\model\theme\navigationItem())->setDescription('Example Module')->setIcon('fa fa-bullhorn fa-fw')->setUrl('example/test');
        
        $this->logEvent(__METHOD__);
        $this->logEvent($this->data);
        
        return $this->data;
    }

}

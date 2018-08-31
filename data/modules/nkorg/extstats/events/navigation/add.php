<?php

namespace fpcm\modules\nkorg\extstats\events\navigation;

final class add extends \fpcm\module\event {

    public function run()
    {
        $this->data[\fpcm\model\theme\navigationItem::AREA_AFTER][] = (new \fpcm\model\theme\navigationItem())
                ->setDescription('MODULE_NKORGEXTSTATS_HEADLINE')
                ->setIcon('fa fa-chart-pie fa-fw')
                ->setUrl('extstats/statistics');

        return $this->data;
    }

    public function init()
    {
        return true;
    }

}

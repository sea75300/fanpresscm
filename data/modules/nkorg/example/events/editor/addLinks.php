<?php

namespace fpcm\modules\nkorg\example\events\editor;

final class addLinks extends \fpcm\modules\nkorg\example\events\eventBase {

    public function run()
    {
        return [
            [
                'label' => '',
                'value' => ''
            ],
            [
                'label' => 'Google',
                'value' => 'https://google.de'
            ],
            [
                'label' => 'Yahoo',
                'value' => 'https://yahoo.de'
            ],
            [
                'label' => 'Bing',
                'value' => 'https://bing.de'
            ]
        ];
    }

}

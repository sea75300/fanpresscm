<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\dashboard;

/**
 * Dashboard controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.1-dev
 */
class containerManager extends \fpcm\controller\abstracts\ajaxController
{
    use \fpcm\controller\traits\common\isAccessibleTrue,
        \fpcm\controller\traits\system\dashPermissions;

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {
        $notFound = [
            'hl' => (string) (new \fpcm\view\helper\icon('box'))->setStack('ban text-danger')->setSize('lg')->setStackTop(true) . ' ' . $this->language->translate('GLOBAL_NOTFOUND2'),
            'code' => -1
        ];

        $disabledContainer = $this->session->getCurrentUser()->getUserMeta('dashboard_containers_disabled');
        if (!is_array($disabledContainer) || !count($disabledContainer)) {
            $this->response->setReturnData([$notFound])->fetch();
        }

        /* @var $item \fpcm\model\abstracts\dashcontainer */
        array_walk($disabledContainer, function (&$item) {
            $item = new $item;
        });
        
        $disabledContainer = array_filter($disabledContainer, function ($item) {
            return $this->checkPermissions($item);
        });

        if (!is_array($disabledContainer) || !count($disabledContainer)) {
            $this->response->setReturnData([$notFound])->fetch();
        }
        
        $notFound = null;

        $res = [];
        foreach ($disabledContainer as $item) {
            $res[] = [
                'hl' => $this->language->translate($item->getHeadline()),
                'code' => base64_encode($item::class)
            ];
        }

        $this->response->setReturnData($res)->fetch();
    }

}

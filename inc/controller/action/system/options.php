<?php

/**
 * Option edit controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system;

class options extends \fpcm\controller\abstracts\controller {

    use \fpcm\controller\traits\common\timezone;

    /**
     *
     * @var \fpcm\model\system\config
     */
    protected $config;

    /**
     *
     * @var int
     */
    protected $syscheck = 0;

    /**
     *
     * @var array
     */
    protected $newconfig = [];

    /**
     *
     * @var bool
     */
    protected $mailSettingsChanged = false;

    protected function getPermissions()
    {
        return ['system' => 'options'];
    }

    protected function getViewPath() : string
    {
        return 'system/options';
    }

    protected function getHelpLink()
    {
        return 'hl_options';
    }

    /**
     * Request-Handler
     * @return boolean
     */
    public function request()
    {

        $this->config = new \fpcm\model\system\config(false, false);

        if ($this->getRequestVar('syscheck')) {
            $this->syscheck = $this->getRequestVar('syscheck', array(9));
        }

        if ($this->buttonClicked('configSave') && !$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return true;
        }

        if ($this->buttonClicked('configSave')) {
            $this->newconfig = $this->getRequestVar();

            if (!isset($this->newconfig['twitter_events'])) {
                $this->newconfig['twitter_events'] = ['create' => 0, 'update' => 0];
            }

            foreach ($this->config->twitter_events as $key => $value) {
                $this->newconfig['twitter_events'][$key] = (isset($this->newconfig['twitter_events'][$key]) && $this->newconfig['twitter_events'][$key] ? 1 : 0);
            }

            if (!isset($this->newconfig['twitter_data'])) {
                $this->newconfig['twitter_data'] = ['consumer_key' => '', 'consumer_secret' => '', 'user_token' => '', 'user_secret' => ''];
            }

            foreach ($this->config->twitter_data as $key => $value) {
                $this->newconfig['twitter_data'][$key] = isset($this->newconfig['twitter_data'][$key]) ? $this->newconfig['twitter_data'][$key] : '';
            }

            foreach ($this->config->smtp_settings as $key => $value) {
                $this->newconfig['smtp_settings'][$key] = isset($this->newconfig['smtp_settings'][$key]) ? $this->newconfig['smtp_settings'][$key] : '';
            }

            $this->config->setNewConfig($this->newconfig);
            $this->config->prepareDataSave();

            $this->mailSettingsChanged = (\fpcm\classes\tools::getHash(json_encode($this->config->smtp_settings)) === \fpcm\classes\tools::getHash(json_encode($this->newconfig['smtp_settings'])) ? false : true);

            if (!$this->config->update()) {
                $this->view->addErrorMessage('SAVE_FAILED_OPTIONS');
                return true;
            }

            $this->view->addNoticeMessage('SAVE_SUCCESS_OPTIONS');
        }

        if ($this->buttonClicked('twitterDisconnect')) {
            $twitterData = $this->config->twitter_data;

            $twitterData['user_token'] = '';
            $twitterData['user_secret'] = '';

            $this->config->setNewConfig(array(
                'twitter_data' => json_encode($twitterData),
                'twitter_events' => json_encode(array('create' => 0, 'update' => 0))
            ));
            if (!$this->config->update()) {
                $this->view->addNoticeMessage('SAVE_FAILED_OPTIONS');
                return true;
            }

            $this->view->addNoticeMessage('SAVE_SUCCESS_OPTIONS');
        }

        return true;
    }

    /**
     * Controller-Processing
     * @return boolean
     */
    public function process()
    {
        $this->view->assign('timezoneAreas', $this->getTimeZonesAreas());

        $this->view->assign('systemModes', [
            'SYSTEM_OPTIONS_USEMODE_IFRAME' => 0,
            'SYSTEM_OPTIONS_USEMODE_PHPINCLUDE' => 1
        ]);
        $this->view->assign('editors', \fpcm\components\components::getArticleEditors());

        $sorts = array(
            'SYSTEM_OPTIONS_NEWS_BYINTERNALID' => 'id',
            'SYSTEM_OPTIONS_NEWS_BYAUTHOR' => 'createuser',
            'SYSTEM_OPTIONS_NEWS_BYWRITTENTIME' => 'createtime',
            'SYSTEM_OPTIONS_NEWS_BYEDITEDTIME' => 'changetime',
        );
        $this->view->assign('sorts', $sorts);

        $sortOrders = array(
            'SYSTEM_OPTIONS_NEWS_ORDERASC' => 'ASC',
            'SYSTEM_OPTIONS_NEWS_ORDERDESC' => 'DESC'
        );
        $this->view->assign('sortsOrders', $sortOrders);

        $templates = new \fpcm\model\pubtemplates\templatelist();

        $this->view->assign('articleTemplates', $templates->getArticleTemplates());
        $this->view->assign('commentTemplates', $templates->getCommentTemplates());

        $this->view->assign('globalConfig', $this->config->getData());
        $this->view->assign('languages', array_flip($this->language->getLanguages()));

        $this->view->assign('notify', [
            'SYSTEM_OPTIONS_COMMENT_NOTIFY_GLOBAL' => 0,
            'SYSTEM_OPTIONS_COMMENT_NOTIFY_AUTHOR' => 1,
            'SYSTEM_OPTIONS_COMMENT_NOTIFY_ALL' => 2
        ]);

        $this->view->assign('smtpEncryption', \fpcm\classes\email::getEncryptions());

        $this->view->assign('filemanagerViews', \fpcm\components\components::getFilemanagerViews());

        $this->view->assign('articleLimitList', \fpcm\model\system\config::getArticleLimits());
        $this->view->assign('articleLimitListAcp', \fpcm\model\system\config::getAcpArticleLimits());
        $this->view->assign('defaultFontsizes', \fpcm\model\system\config::getDefaultFontsizes());

        $twitter = new \fpcm\model\system\twitter();

        $this->view->assign('twitterIsActive', $twitter->checkConnection());
        $this->view->assign('twitterScreenName', $twitter->getUsername());

        $smtpActive = false;
        if ($this->config->smtp_enabled) {
            $mail = new \fpcm\classes\email('', '', '');
            $smtpActive = $mail->checkSmtp();
        }

        if ($smtpActive && $this->buttonClicked('configSave') && $this->mailSettingsChanged) {
            $this->view->addNoticeMessage('SYSTEM_OPTIONS_EMAIL_ACTIVE');
        }

        $this->view->assign('smtpActive', $smtpActive);
        $this->view->addJsFiles(['options.js', 'systemcheck.js']);
        $this->view->addJsVars([
            'runSysCheck' => $this->syscheck,
            'dtMasks' => $this->getDateTimeMasks()
        ]);

        $this->view->setFormAction('system/options');
        
        $buttons = [
            (new \fpcm\view\helper\saveButton('configSave'))->setClass('fpcm-ui-maintoolbarbuttons-tab1'.($this->syscheck ? ' fpcm-ui-hidden' : '')),
            (new \fpcm\view\helper\button('syschecksubmitstats', 'syschecksubmitstats'))->setText('SYSTEM_OPTIONS_SYSCHECK_SUBMITSTATS')->setClass('fpcm-ui-maintoolbarbuttons-tab2 fpcm-ui-hidden')->setIcon('chart-line'),
        ];

        if (\fpcm\classes\baseconfig::canConnect() && $this->permissions->check(['system' => 'update'])) {
            $buttons[] = (new \fpcm\view\helper\button('checkUpdate', 'checkUpdate'))->setText('PACKAGES_MANUALCHECK')->setIcon('sync');
        }

        $this->view->assign('tabs', [
            (new \fpcm\view\helper\tabItem('general'))->setText('SYSTEM_HL_OPTIONS_GENERAL')->setWrapper(false)->setUrl('#tabs-options-general')->setData([
                'toolbar-buttons' => 1
            ]),
            (new \fpcm\view\helper\tabItem('editor'))->setText('SYSTEM_HL_OPTIONS_EDITOR')->setWrapper(false)->setUrl('#tabs-options-editor')->setData([
                'toolbar-buttons' => 1
            ]),
            (new \fpcm\view\helper\tabItem('articles'))->setText('SYSTEM_HL_OPTIONS_ARTICLES')->setWrapper(false)->setUrl('#tabs-options-news')->setData([
                'toolbar-buttons' => 1
            ]),
            (new \fpcm\view\helper\tabItem('comments'))->setText('SYSTEM_HL_OPTIONS_COMMENTS')->setWrapper(false)->setUrl('#tabs-options-comments')->setData([
                'toolbar-buttons' => 1
            ]),
            (new \fpcm\view\helper\tabItem('twitter'))->setText('SYSTEM_HL_OPTIONS_TWITTER')->setWrapper(false)->setUrl('#tabs-options-twitter')->setData([
                'toolbar-buttons' => 1
            ]),
            (new \fpcm\view\helper\tabItem('extended'))->setText('GLOBAL_EXTENDED')->setWrapper(false)->setUrl('#tabs-options-extended')->setData([
                'toolbar-buttons' => 1
            ]),
            (new \fpcm\view\helper\tabItem('syscheck'))->setText('SYSTEM_HL_OPTIONS_SYSCHECK')->setWrapper(false)->setUrl(\fpcm\classes\tools::getFullControllerLink('ajax/syscheck'))->setData([
                'toolbar-buttons' => 2
            ]),
        ]);
        
        $this->view->addButtons($buttons);
        $this->view->render();
    }

}

?>
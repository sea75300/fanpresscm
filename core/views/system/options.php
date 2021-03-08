<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div id="fpcm-options-tabs" class="fpcm-ui-tabs-general ui-tabs ui-corner-all ui-widget ui-widget-content">
        <ul class="ui-tabs-nav ui-corner-all ui-helper-reset ui-helper-clearfix ui-widget-header">
            <?php foreach ($tabs as $tab) : ?><?php print $tab; ?><?php endforeach; ?>
        </ul>

        <div id="tabs-options-general" class="fpcm tabs-register ui-tabs-panel ui-corner-bottom ui-widget-content">

            <div class="row no-gutters">

                <div class="col-12">
                    <fieldset>
                        <legend><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></legend>
                        
                        <div class="row py-2">
                            <?php $theView->textInput('system_email')
                                ->setValue($globalConfig->system_email)                                        
                                ->setText('GLOBAL_EMAIL')
                                ->setType('email'); ?>
                        </div>
                        
                        <div class="row py-2">
                            <?php $theView->textInput('system_url')
                                ->setValue($globalConfig->system_url)                                        
                                ->setText('SYSTEM_OPTIONS_URL')
                                ->setType('url'); ?>
                        </div>
                        
                        <div class="row py-2">
                            <?php $theView->textInput('system_dtmask')
                                ->setValue($globalConfig->system_dtmask)                                        
                                ->setText('SYSTEM_OPTIONS_DATETIMEMASK')
                                ->setDisplaySizesDefault()
                                ->setFieldSize(['xs' => 4, 'md' => 2]); ?>

                            <div class="col align-self-center">
                                <?php $theView->shorthelpButton('dtmask')->setText('SYSTEM_OPTIONS_DATETIMEMASK_HELP')->setUrl('http://php.net/manual/function.date.php'); ?>
                            </div>
                        </div>

                        <div class="row py-2">
                            <?php $theView->select('system_timezone')
                                    ->setText('SYSTEM_OPTIONS_TIMEZONE')
                                    ->setOptions($timezoneAreas)
                                    ->setSelected($globalConfig->system_timezone)
                                    ->setOptGroup(true); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->select('system_lang')
                                    ->setText('SYSTEM_OPTIONS_LANG')
                                    ->setOptions($languages)
                                    ->setSelected($globalConfig->system_lang)
                                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->select('articles_acp_limit')
                                    ->setText('SYSTEM_OPTIONS_ACPARTICLES_LIMIT')
                                    ->setOptions($articleLimitListAcp)
                                    ->setSelected($globalConfig->articles_acp_limit)
                                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->select('system_cache_timeout')
                                    ->setText('SYSTEM_OPTIONS_CACHETIMEOUT')
                                    ->setOptions($theView->translate('SYSTEM_OPTIONS_CACHETIMEOUT_INTERVAL'))
                                    ->setSelected($globalConfig->system_cache_timeout)
                                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->select('system_trash_cleanup')
                                    ->setText('SYSTEM_OPTIONS_TRASH_CLEANUP_DAYS')
                                    ->setOptions($theView->translate('SYSTEM_OPTIONS_TRASH_CLEANUP_DAYS_LIST'))
                                    ->setSelected($globalConfig->system_trash_cleanup)
                                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>
                    </fieldset>
                </div>

                <div class="col-12 mt-2">
                    <fieldset>
                        <legend><?php $theView->write('HL_FRONTEND'); ?></legend>

                        <div class="row py-2">
                            <?php $theView->textInput('system_css_path')
                                    ->setValue($globalConfig->system_css_path, ENT_QUOTES)
                                    ->setText($theView->translate('SYSTEM_OPTIONS_STYLESHEET'))
                                    ->setType('url')
                                    ->setPlaceholder('http://'.$_SERVER['HTTP_HOST'].'/style/style.css'); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->select('system_mode')->setText('SYSTEM_OPTIONS_USEMODE')->setOptions($systemModes)->setSelected($globalConfig->system_mode)->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->boolSelect('system_loader_jquery')
                                    ->setText('SYSTEM_OPTIONS_INCLUDEJQUERY')
                                    ->setSelected($globalConfig->system_loader_jquery); ?>

                            <div class="col align-self-center">
                                <?php $theView->shorthelpButton('jqueryInclude')->setText('SYSTEM_OPTIONS_INCLUDEJQUERY_YES'); ?>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>

        <div id="tabs-options-editor">

            <div class="row no-gutters">
                <div class="col-12">
                    <fieldset>
                        <legend><?php $theView->write('SYSTEM_OPTIONS_NEWS_EDITOR_SETTINGS'); ?></legend>

                        <div class="row py-2">
                            <?php $theView->select('system_editor')
                                    ->setText('SYSTEM_OPTIONS_NEWS_EDITOR')
                                    ->setOptions($editors)
                                    ->setSelected(base64_encode($globalConfig->system_editor))
                                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); 
                            ?>
                        </div>                        

                        <div class="row py-2">
                            <?php $theView->select('system_editor_fontsize')
                                ->setText('SYSTEM_OPTIONS_NEWS_EDITOR_FONTSIZE')
                                    ->setOptions($defaultFontsizes)
                                    ->setSelected($globalConfig->system_editor_fontsize)
                                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->boolSelect('articles_revisions')
                                    ->setText('SYSTEM_OPTIONS_REVISIONS_ENABLED')
                                    ->setSelected($globalConfig->articles_revisions); ?>
                        </div>                        

                        <div class="row py-2">
                                <?php $theView->select('articles_revisions_limit')
                                        ->setText('SYSTEM_OPTIONS_NEWS_REVISIONS_LIMIT')
                                        ->setOptions($theView->translate('SYSTEM_OPTIONS_NEWS_REVISIONS_LIMIT_LIST'))
                                        ->setSelected($globalConfig->articles_revisions_limit)
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>                        

                        <div class="row py-2">
                            <?php $theView->boolSelect('articles_imageedit_persistence')
                                    ->setText('SYSTEM_OPTIONS_NEWS_EDITOR_IMGTOOLS')
                                    ->setSelected($globalConfig->articles_imageedit_persistence); ?>
                        </div>                        
                    </fieldset>
                    
                    <fieldset class="my-2">
                        <legend><?php $theView->write('SYSTEM_OPTIONS_NEWS_EDITOR_CSS'); ?></legend>
                        <?php $theView->textarea('system_editor_css')
                                ->setValue($globalConfig->system_editor_css, ENT_QUOTES)
                                ->setClass('fpcm ui-textarea-medium ui-textarea-noresize ui-full-width mt-2'); ?>
                    </fieldset>
                </div>

                <div class="col-12 mt-2">
                    <fieldset>
                        <legend><?php $theView->write('HL_FILES_MNG'); ?></legend>                       

                        <div class="row py-2">
                            <?php $theView->boolSelect('file_subfolders')
                                    ->setText('SYSTEM_OPTIONS_NEWS_SUBFOLDERS')
                                    ->setSelected($globalConfig->file_subfolders); ?>
                        </div>                          

                        <div class="row py-2">
                            <?php $theView->select('file_list_limit')
                                    ->setText('SYSTEM_OPTIONS_FILEMANAGER_LIMIT')
                                    ->setOptions($articleLimitListAcp)
                                    ->setSelected($globalConfig->file_list_limit)
                                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>                          

                        <div class="row py-2">
                            <?php $theView->select('file_view')
                                    ->setText('SYSTEM_OPTIONS_FILEMANAGER_VIEW')
                                    ->setOptions($filemanagerViews)
                                    ->setSelected($globalConfig->file_view)
                                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>                          
                    </fieldset>

                    <fieldset class="fpcm-ui-margin-md-left my-2" >
                        <legend><?php $theView->write('SYSTEM_OPTIONS_NEWSSHOWIMGTHUMBSIZE'); ?></legend>

                        <div class="row py-2">
                            <?php $theView->textInput('file_img_thumb_width')
                                    ->setText('SYSTEM_OPTIONS_NEWSSHOWMAXIMGSIZEWIDTH')
                                    ->setValue($globalConfig->file_img_thumb_width)
                                    ->setMaxlenght(5)
                                    ->setClass('fpcm-ui-spinner'); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->textInput('file_img_thumb_height')
                                    ->setText('SYSTEM_OPTIONS_NEWSSHOWMAXIMGSIZEHEIGHT')
                                    ->setValue($globalConfig->file_img_thumb_height)
                                    ->setMaxlenght(5)
                                    ->setClass('fpcm-ui-spinner'); ?>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>

        <div id="tabs-options-news">

            <div class="row no-gutters">
                <div class="col-12">
                    <fieldset>
                        <legend><?php $theView->write('HL_FRONTEND'); ?></legend>

                        <div class="row py-2">
                            <?php $theView->select('articles_limit')
                                    ->setText('SYSTEM_OPTIONS_NEWSSHOWLIMIT')
                                    ->setOptions($articleLimitList)
                                    ->setSelected($globalConfig->articles_limit)
                                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->select('articles_template_active')
                                    ->setText('SYSTEM_OPTIONS_ACTIVENEWSTEMPLATE')
                                    ->setOptions($articleTemplates)
                                    ->setSelected($globalConfig->articles_template_active)
                                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->select('article_template_active')
                                    ->setText('SYSTEM_OPTIONS_ACTIVENEWSTEMPLATESINGLE')
                                    ->setOptions($articleTemplates)
                                    ->setSelected($globalConfig->article_template_active)
                                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>	
                        </div>

                        <div class="row py-2">
                            <?php $theView->select('articles_sort')
                                    ->setText('SYSTEM_OPTIONS_NEWS_SORTING')
                                    ->setOptions($sorts)
                                    ->setSelected($globalConfig->articles_sort)
                                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->select('articles_sort_order')
                                    ->setText('SYSTEM_OPTIONS_NEWS_SORTING_ORDER')
                                    ->setOptions($sortsOrders)
                                    ->setSelected($globalConfig->articles_sort_order)
                                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>

                        <div class="row py-2">
                                <?php $theView->boolSelect('system_show_share')->setText('SYSTEM_OPTIONS_NEWSSHOWSHARELINKS')->setSelected($globalConfig->system_show_share); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->boolSelect('system_share_count')->setText('SYSTEM_OPTIONS_NEWSSHARECOUNT')->setSelected($globalConfig->system_share_count); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->boolSelect('articles_link_urlrewrite')->setText('SYSTEM_OPTIONS_NEWS_URLREWRITING')->setSelected($globalConfig->articles_link_urlrewrite); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->boolSelect('articles_rss')->setText('SYSTEM_OPTIONS_NEWS_ENABLEFEED')->setSelected($globalConfig->articles_rss); ?>
                        </div>
                    </fieldset>
                </div>

                <div class="col-12 mt-2">
                    <fieldset>
                        <legend><?php $theView->write('HL_ARCHIVE'); ?></legend>

                        <div class="row py-2">
                            <?php $theView->boolSelect('articles_archive_show')->setText('SYSTEM_OPTIONS_ARCHIVE_LINK')->setSelected($globalConfig->articles_archive_show); ?>
                        </div> 

                        <div class="row py-2">
                            <?php $theView->dateTimeInput('articles_archive_datelimit')
                                    ->setValue($globalConfig->articles_archive_datelimit ? $theView->dateText($globalConfig->articles_archive_datelimit, 'Y-m-d') : '')
                                    ->setText('SYSTEM_OPTIONS_NEWS_ARCHIVELIMIT')
                                    ->setPlaceholder($theView->translate('SYSTEM_OPTIONS_NEWS_ARCHIVELIMIT_EMPTY'))
                                    ->setData(['maxDate' => '-3m'])
                                    ->setDisplaySizesDefault()
                                    ->setFieldSize(['xs' => 4, 'md' => 2]); ?>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>

        <div id="tabs-options-comments">
            <div class="row no-gutters">
                <div class="col-12">
                    <fieldset>
                        <legend><?php $theView->write('COMMMENT_HEADLINE'); ?></legend>

                        <div class="row py-2">
                            <?php $theView->boolSelect('system_comments_enabled')->setText('SYSTEM_OPTIONS_COMMENT_ENABLED_GLOBAL')->setSelected($globalConfig->system_comments_enabled); ?>		
                        </div>

                        <div class="row py-2">
                            <?php $theView->boolSelect('comments_privacy_optin')->setText('SYSTEM_OPTIONS_COMMENT_PRIVACYOPTIN')->setSelected($globalConfig->comments_privacy_optin); ?>		
                        </div>

                        <div class="row py-2">
                            <?php $theView->select('comments_notify')
                                    ->setOptions($notify)
                                    ->setText('SYSTEM_OPTIONS_COMMENT_NOTIFY')
                                    ->setSelected($globalConfig->comments_notify)
                                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->select('comments_template_active')
                                    ->setOptions($commentTemplates)
                                    ->setText('SYSTEM_OPTIONS_ACTIVECOMMENTTEMPLATE')
                                    ->setSelected($globalConfig->comments_template_active)
                                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->select('comments_flood')
                                    ->setOptions($theView->translate('SYSTEM_OPTIONS_FLOODPROTECTION_INTERVALS'))
                                    ->setText('SYSTEM_OPTIONS_FLOODPROTECTION')
                                    ->setSelected($globalConfig->comments_flood)
                                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->boolSelect('comments_email_optional')->setText('SYSTEM_OPTIONS_COMMENTEMAIL')->setSelected($globalConfig->comments_email_optional); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->boolSelect('comments_confirm')->setText('SYSTEM_OPTIONS_COMMENT_APPROVE')->setSelected($globalConfig->comments_confirm); ?>		
                        </div>
                    </fieldset>
                </div>

                <div class="col-12 mt-2">
                    <fieldset>
                        <legend><?php $theView->write('SYSTEM_OPTIONS_CAPTCHASETTING'); ?></legend>
                        
                        <div class="row py-2">
                            <?php $theView->textInput('comments_antispam_question')
                                ->setValue($globalConfig->comments_antispam_question)
                                ->setText('SYSTEM_OPTIONS_ANTISPAMQUESTION'); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->textInput('comments_antispam_answer')
                                ->setValue($globalConfig->comments_antispam_answer)
                                ->setText('SYSTEM_OPTIONS_ANTISPAMANSWER'); ?>
                        </div>
                        
                        <div class="row py-2">
                            <?php $theView->textInput('comments_markspam_commentcount')
                                    ->setText('SYSTEM_OPTIONS_COMMENT_MARKSPAM_PASTCHECK')
                                    ->setValue($globalConfig->comments_markspam_commentcount)
                                    ->setMaxlenght(5)
                                    ->setClass('fpcm-ui-spinner'); ?>
                        </div>
                    </fieldset>
                </div>                
            </div>
        </div>

        <div id="tabs-options-extended">
            <div class="row no-gutters">
                <div class="col-12">
                    <fieldset>
                        <legend><?php $theView->write('SYSTEM_HL_OPTIONS_SECURITY'); ?></legend>

                        <div class="row py-2">
                            <?php $theView->boolSelect('system_maintenance')->setText('SYSTEM_OPTIONS_MAINTENANCE')->setSelected($globalConfig->system_maintenance); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->select('system_session_length')
                                    ->setOptions($theView->translate('SYSTEM_OPTIONS_SESSIONLENGHT_INTERVALS'))
                                    ->setText('SYSTEM_OPTIONS_SESSIONLENGHT')
                                    ->setSelected($globalConfig->system_session_length)
                                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->textInput('system_loginfailed_locked')
                                    ->setText('SYSTEM_OPTIONS_LOGIN_MAXATTEMPTS')
                                    ->setValue($globalConfig->system_loginfailed_locked)
                                    ->setMaxlenght(5)
                                    ->setClass('fpcm-ui-spinner'); ?>
                        </div>

                        <div class="row py-2">
                                <?php $theView->boolSelect('system_2fa_auth')->setText('SYSTEM_OPTIONS_LOGIN_TWOFACTORAUTH')->setSelected($globalConfig->system_2fa_auth); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->boolSelect('system_passcheck_enabled')
                                    ->setText('SYSTEM_OPTIONS_USERS_PASSCHECK')
                                    ->setSelected($globalConfig->system_passcheck_enabled); ?>

                            <div class="col align-self-center">
                                <?php $theView->shorthelpButton('pwndpass')->setText('GLOBAL_OPENNEWWIN')->setUrl('https://haveibeenpwned.com/passwords'); ?>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="col-12">
                    <fieldset class="my-2" >
                        <legend><?php $theView->write('SYSTEM_OPTIONS_EXTENDED_UPDATES'); ?></legend>

                        <div class="row py-2">
                            <?php $theView->boolSelect('system_updates_emailnotify')->setText('SYSTEM_OPTIONS_EXTENDED_EMAILUPDATES')->setSelected($globalConfig->system_updates_emailnotify); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->boolSelect('system_updates_devcheck')->setText('SYSTEM_OPTIONS_EXTENDED_DEVUPDATES')->setSelected($globalConfig->system_updates_devcheck); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->select('system_updates_manual')
                                    ->setOptions($theView->translate('SYSTEM_OPTIONS_UPDATESMANUAL'))
                                    ->setText('SYSTEM_OPTIONS_EXTENDED_UPDATESMANCHK')
                                    ->setSelected($globalConfig->system_updates_manual)
                                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>

        <div id="tabs-options-twitter">

            <div class="row no-gutters">
                <div class="col-12">
                    <fieldset>
                        <legend><?php $theView->write('SYSTEM_OPTIONS_TWITTER_CONSTATE'); ?></legend>
                        <div class="row py-2">
                            <div class="col-12 col-md-6 align-self-center px-0">
                            <?php if (!$globalConfig->twitter_data->consumer_key || !$globalConfig->twitter_data->consumer_secret || !$twitterIsActive) : ?>
                                <?php $theView->linkButton('twitterConnect')->setText('SYSTEM_OPTIONS_TWITTER_CONNECT')->setUrl('https://apps.twitter.com/')->setTarget('_blank')->setIcon('twitter', 'fab'); ?>
                            <?php elseif ($globalConfig->twitter_data->user_token && $globalConfig->twitter_data->user_secret && $twitterIsActive) : ?>
                                <?php $theView->submitButton('twitterDisconnect')->setText('SYSTEM_OPTIONS_TWITTER_DISCONNECT')->setClass('fpcm-ui-button-confirm')->setIcon('trash'); ?>
                            <?php endif; ?>

                            <?php $theView->shorthelpButton('twittercon')->setText('HL_HELP')->setUrl('#')->setData(['ref' => urlencode(base64_encode('SYSTEM_OPTIONS_TWITTER_CONNECTION'))])->setClass('fpcm-ui-help-dialog'); ?>
                            </div>
                            <div class="col-12 col-md-6 align-self-center">
                            <?php if ($twitterIsActive) : ?>
                                <?php $theView->icon('twitter', 'fab')->setStack('check fpcm-ui-editor-metainfo fpcm ui-status-075')->setSize('lg')->setStackTop(true); ?>
                                <?php $theView->write('SYSTEM_OPTIONS_TWITTER_ACTIVE', ['{{screenname}}' => $twitterScreenName]); ?>
                            <?php endif; ?>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="row no-gutters my-2">
                <div class="col-12">
                    <fieldset>
                        <legend><?php $theView->write('SYSTEM_OPTIONS_TWITTER_EVENTS'); ?></legend>
                        <div class="row py-2">
                            <div class="col-12 align-self-center px-0">
                                <?php $theView->checkbox('twitter_events[create]', 'twitter_events_create')->setText('SYSTEM_OPTIONS_TWITTER_EVENTCREATE')->setSelected($globalConfig->twitter_events->create)->setLabelClass('mr-2'); ?>
                                <?php $theView->checkbox('twitter_events[update]', 'twitter_events_update')->setText('SYSTEM_OPTIONS_TWITTER_EVENTUPDATE')->setSelected($globalConfig->twitter_events->update); ?>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="row no-gutters my-2">
                <div class="col-12">
                    <fieldset>
                        <legend><?php $theView->write('SYSTEM_OPTIONS_TWITTER_CREDENTIALS'); ?></legend>

                        <div class="row py-2">
                            <?php $theView->textInput('twitter_data[consumer_key]')
                                ->setValue($globalConfig->twitter_data->consumer_key)
                                ->setText('SYSTEM_OPTIONS_TWITTER_CONSUMER_KEY')
                                ->setDisplaySizes(['xs' => 12, 'md' => 3], ['xs' => 12, 'md' => 9]); ?>
                        </div>                        

                        <div class="row py-2">
                            <?php $theView->textInput('twitter_data[consumer_secret]')
                                ->setValue($globalConfig->twitter_data->consumer_secret)
                                ->setText('SYSTEM_OPTIONS_TWITTER_CONSUMER_SECRET')
                                ->setDisplaySizes(['xs' => 12, 'md' => 3], ['xs' => 12, 'md' => 9]); ?>
                        </div>                        

                        <div class="row py-2">
                            <?php $theView->textInput('twitter_data[user_token]')
                                ->setValue($globalConfig->twitter_data->user_token)
                                ->setText('SYSTEM_OPTIONS_TWITTER_USER_TOKEN')
                                ->setDisplaySizes(['xs' => 12, 'md' => 3], ['xs' => 12, 'md' => 9]); ?>
                        </div>                        

                        <div class="row py-2">
                            <?php $theView->textInput('twitter_data[user_secret]')
                                ->setValue($globalConfig->twitter_data->user_secret)
                                ->setText('SYSTEM_OPTIONS_TWITTER_USER_SECRET')
                                ->setDisplaySizes(['xs' => 12, 'md' => 3], ['xs' => 12, 'md' => 9]); ?>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div> 
        


        <div id="tabs-options-smtp">
            <div class="row no-gutters">
                <div class="col-12">
                <?php if ($smtpActive) : ?>
                    <fieldset>
                        <legend><?php $theView->write('SYSTEM_OPTIONS_TWITTER_CONSTATE'); ?></legend>

                        <div class="row py-2">
                            <div class="col-12">
                                <?php $theView->icon('envelope')->setStack('check fpcm-ui-editor-metainfo fpcm ui-status-075')->setSize('lg')->setStackTop(true); ?>
                                <?php $theView->write('SYSTEM_OPTIONS_EMAIL_ACTIVE'); ?>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="row no-gutters my-2">
                <div class="col-12">                
                <?php endif; ?>

                    <fieldset>
                        <legend><?php $theView->write('SYSTEM_OPTIONS_TWITTER_CREDENTIALS'); ?></legend>

                        <div class="row py-2">
                                <?php $theView->boolSelect('smtp_enabled')->setText('SYSTEM_OPTIONS_EMAIL_ENABLED')->setSelected($globalConfig->smtp_enabled); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->textInput('smtp_settings[addr]')
                                    ->setType('email')
                                    ->setValue($globalConfig->smtp_settings->addr)
                                    ->setReadonly(($globalConfig->smtp_enabled ? false : true))
                                    ->setText('GLOBAL_EMAIL')
                                    ->setPlaceholder('mail@example.com')
                                    ->setClass('fpcm-ui-options-smtp-input'); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->textInput('smtp_settings[srvurl]')
                                    ->setValue($globalConfig->smtp_settings->srvurl)
                                    ->setReadonly(($globalConfig->smtp_enabled ? false : true))
                                    ->setText('SYSTEM_OPTIONS_EMAIL_SERVER')
                                    ->setPlaceholder('mail.example.com')
                                    ->setClass('fpcm-ui-options-smtp-input'); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->textInput('smtp_settings[port]')
                                    ->setValue($globalConfig->smtp_settings->port)
                                    ->setReadonly(($globalConfig->smtp_enabled ? false : true))
                                    ->setText('SYSTEM_OPTIONS_EMAIL_PORT')
                                    ->setPlaceholder('25')
                                    ->setClass('fpcm-ui-options-smtp-input'); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->textInput('smtp_settings[user]')
                                    ->setValue($globalConfig->smtp_settings->user)
                                    ->setReadonly(($globalConfig->smtp_enabled ? false : true))
                                    ->setText('SYSTEM_OPTIONS_EMAIL_USERNAME')
                                    ->setPlaceholder('mail@example.com')
                                    ->setClass('fpcm-ui-options-smtp-input'); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->passwordInput('smtp_settings[pass]')
                                    ->setText('SYSTEM_OPTIONS_EMAIL_PASSWORD')
                                    ->setReadonly(($globalConfig->smtp_enabled ? false : true))
                                    ->setClass('fpcm-ui-options-smtp-input')
                                    ->setPlaceholder(trim($globalConfig->smtp_settings->pass) ? '*****' : ''); ?>
                        </div>

                        <div class="row py-2">
                            <?php $theView->select('smtp_settings[encr]')
                                    ->setOptions($smtpEncryption)
                                    ->setText('SYSTEM_OPTIONS_EMAIL_ENCRYPTED')
                                    ->setSelected($globalConfig->smtp_settings->encr)
                                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                                    ->setReadonly(($globalConfig->smtp_enabled ? false : true)); ?>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>

    </div>

</div>

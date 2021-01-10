<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div id="fpcm-options-tabs" class="fpcm-ui-tabs-general ui-tabs ui-corner-all ui-widget ui-widget-content">
        <ul class="ui-tabs-nav ui-corner-all ui-helper-reset ui-helper-clearfix ui-widget-header">
            <?php foreach ($tabs as $tab) : ?><?php print $tab; ?><?php endforeach; ?>
        </ul>

        <div id="tabs-options-general" class="fpcm tabs-register ui-tabs-panel ui-corner-bottom ui-widget-content">

            <div class="row no-gutters">

                <div class="col-sm-12 col-lg-6">
                    <fieldset class="fpcm-ui-margin-md-right">
                        <legend><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></legend>
                        
                        <div class="row fpcm-ui-padding-md-tb">
                            <?php $theView->textInput('system_email')
                                ->setValue($globalConfig['system_email'])                                        
                                ->setText('GLOBAL_EMAIL')
                                ->setType('email')
                                ->setDisplaySizes(['xs' => 12, 'md' => 5], ['xs' => 12, 'md' => 7]); ?>
                        </div>
                        
                        <div class="row fpcm-ui-padding-md-tb">
                            <?php $theView->textInput('system_url')
                                ->setValue($globalConfig['system_url'])                                        
                                ->setText('SYSTEM_OPTIONS_URL')
                                ->setType('url')
                                ->setDisplaySizes(['xs' => 12, 'md' => 5], ['xs' => 12, 'md' => 7]); ?>
                        </div>
                        
                        <div class="row fpcm-ui-padding-md-tb">
                            <?php $theView->textInput('system_dtmask')
                                ->setValue($globalConfig['system_dtmask'])                                        
                                ->setText('SYSTEM_OPTIONS_DATETIMEMASK')
                                ->setDisplaySizes(['xs' => 12, 'md' => 5], ['xs' => 12, 'md' => 5]); ?>

                            <div class="align-self-center col-sm-1 col-md-1 fpcm-ui-padding-md-lr">
                                <?php $theView->shorthelpButton('dtmask')->setText('SYSTEM_OPTIONS_DATETIMEMASK_HELP')->setUrl('http://php.net/manual/function.date.php'); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_TIMEZONE'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->select('system_timezone')->setOptions($timezoneAreas)->setSelected($globalConfig['system_timezone'])->setOptGroup(true); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_LANG'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->select('system_lang')->setOptions($languages)->setSelected($globalConfig['system_lang'])->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_ACPARTICLES_LIMIT'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->select('articles_acp_limit')
                                        ->setOptions($articleLimitListAcp)
                                        ->setSelected($globalConfig['articles_acp_limit'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_CACHETIMEOUT'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->select('system_cache_timeout')->setOptions($theView->translate('SYSTEM_OPTIONS_CACHETIMEOUT_INTERVAL'))->setSelected($globalConfig['system_cache_timeout'])->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_TRASH_CLEANUP_DAYS'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->select('system_trash_cleanup')->setOptions($theView->translate('SYSTEM_OPTIONS_TRASH_CLEANUP_DAYS_LIST'))->setSelected($globalConfig['system_trash_cleanup'])->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="col-sm-12 col-lg-6">
                    <fieldset class="fpcm-ui-margin-md-left">
                        <legend><?php $theView->write('HL_FRONTEND'); ?></legend>

                        <div class="row fpcm-ui-padding-md-tb">
                            <?php $theView->textInput('system_css_path')
                                    ->setValue($globalConfig['system_css_path'], ENT_QUOTES)
                                    ->setText($theView->translate('SYSTEM_OPTIONS_STYLESHEET'))
                                    ->setType('url')
                                    ->setPlaceholder('http://'.$_SERVER['HTTP_HOST'].'/style/style.css')
                                    ->setDisplaySizes(['xs' => 12, 'md' => 5], ['xs' => 12, 'md' => 7]); ?>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_USEMODE'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->select('system_mode')->setOptions($systemModes)->setSelected($globalConfig['system_mode'])->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_INCLUDEJQUERY'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->boolSelect('system_loader_jquery')->setSelected($globalConfig['system_loader_jquery']); ?>
                                <?php $theView->shorthelpButton('jqueryInclude')->setText('SYSTEM_OPTIONS_INCLUDEJQUERY_YES'); ?>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>

        <div id="tabs-options-editor">

            <div class="row no-gutters">
                <div class="col-sm-12 col-lg-6">
                    <fieldset class="fpcm-ui-margin-md-right">
                        <legend><?php $theView->write('SYSTEM_OPTIONS_NEWS_EDITOR_SETTINGS'); ?></legend>

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWS_EDITOR'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->select('system_editor')->setOptions($editors)->setSelected(base64_encode($globalConfig['system_editor']))->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>                        

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWS_EDITOR_FONTSIZE'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->select('system_editor_fontsize')
                                        ->setOptions($defaultFontsizes)
                                        ->setSelected($globalConfig['system_editor_fontsize'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_REVISIONS_ENABLED'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->boolSelect('articles_revisions')->setSelected($globalConfig['articles_revisions']); ?>
                            </div>
                        </div>                        

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWS_REVISIONS_LIMIT'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->select('articles_revisions_limit')
                                        ->setOptions($theView->translate('SYSTEM_OPTIONS_NEWS_REVISIONS_LIMIT_LIST'))
                                        ->setSelected($globalConfig['articles_revisions_limit'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>                        

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWS_EDITOR_IMGTOOLS'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->boolSelect('articles_imageedit_persistence')->setSelected($globalConfig['articles_imageedit_persistence']); ?>
                            </div>
                        </div>                        
                    </fieldset>
                    
                    <fieldset class="fpcm-ui-margin-md-right fpcm-ui-margin-md-top">
                        <legend><?php $theView->write('SYSTEM_OPTIONS_NEWS_EDITOR_CSS'); ?></legend>
                        <?php $theView->textarea('system_editor_css')->setValue($globalConfig['system_editor_css'], ENT_QUOTES)->setClass('fpcm-ui-textarea-medium fpcm-ui-full-width mt-2'); ?>
                    </fieldset>
                </div>

                <div class="col-sm-12 col-lg-6">
                    <fieldset class="fpcm-ui-margin-md-left">
                        <legend><?php $theView->write('HL_FILES_MNG'); ?></legend>                       

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWS_SUBFOLDERS'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->boolSelect('file_subfolders')->setSelected($globalConfig['file_subfolders']); ?>
                            </div>
                        </div>                          

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_FILEMANAGER_LIMIT'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->select('file_list_limit')
                                        ->setOptions($articleLimitListAcp)
                                        ->setSelected($globalConfig['file_list_limit'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>                          

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_FILEMANAGER_VIEW'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->select('file_view')->setOptions($filemanagerViews)->setSelected($globalConfig['file_view'])->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>                          
                    </fieldset>

                    <fieldset class="fpcm-ui-margin-md-left fpcm-ui-margin-md-top" >
                        <legend><?php $theView->write('SYSTEM_OPTIONS_NEWSSHOWIMGTHUMBSIZE'); ?></legend>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-md-12 col-lg-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWSSHOWMAXIMGSIZEWIDTH'); ?>
                            </div>
                            <div class="align-self-center col-md-12 col-lg-7 mx-0 px-0">
                                <?php $theView->textInput('file_img_thumb_width')
                                        ->setText('')
                                        ->setValue($globalConfig['file_img_thumb_width'])
                                        ->setMaxlenght(5)
                                        ->setClass('fpcm-ui-spinner'); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-md-12 col-lg-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWSSHOWMAXIMGSIZEHEIGHT'); ?>
                            </div>
                            <div class="align-self-center col-md-12 col-lg-7 mx-0 px-0">
                                <?php $theView->textInput('file_img_thumb_height')
                                        ->setText('')
                                        ->setValue($globalConfig['file_img_thumb_height'])
                                        ->setMaxlenght(5)
                                        ->setClass('fpcm-ui-spinner'); ?>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>

        <div id="tabs-options-news">

            <div class="row no-gutters">
                <div class="col-sm-12 col-lg-6">
                    <fieldset class="fpcm-ui-margin-md-right">
                        <legend><?php $theView->write('HL_FRONTEND'); ?></legend>

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWSSHOWLIMIT'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->select('articles_limit')
                                        ->setOptions($articleLimitList)
                                        ->setSelected($globalConfig['articles_limit'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_ACTIVENEWSTEMPLATE'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->select('articles_template_active')
                                        ->setOptions($articleTemplates)
                                        ->setSelected($globalConfig['articles_template_active'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_ACTIVENEWSTEMPLATESINGLE'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->select('article_template_active')
                                        ->setOptions($articleTemplates)
                                        ->setSelected($globalConfig['article_template_active'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>	
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWS_SORTING'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->select('articles_sort')
                                        ->setOptions($sorts)
                                        ->setSelected($globalConfig['articles_sort'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWS_SORTING_ORDER'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                    <?php $theView->select('articles_sort_order')
                                            ->setOptions($sortsOrders)
                                            ->setSelected($globalConfig['articles_sort_order'])
                                            ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWSSHOWSHARELINKS'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->boolSelect('system_show_share')->setSelected($globalConfig['system_show_share']); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWSSHARECOUNT'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->boolSelect('system_share_count')->setSelected($globalConfig['system_share_count']); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWS_URLREWRITING'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->boolSelect('articles_link_urlrewrite')->setSelected($globalConfig['articles_link_urlrewrite']); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWS_ENABLEFEED'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->boolSelect('articles_rss')->setSelected($globalConfig['articles_rss']); ?>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="col-sm-12 col-lg-6">
                    <fieldset class="fpcm-ui-margin-md-left">
                        <legend><?php $theView->write('HL_ARCHIVE'); ?></legend>

                        <div class="row fpcm-ui-padding-md-tb">
                            <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_ARCHIVE_LINK'); ?>
                            </label>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->boolSelect('articles_archive_show')->setSelected($globalConfig['articles_archive_show']); ?>
                            </div>
                        </div> 

                        <div class="row fpcm-ui-padding-md-tb">
                            <?php $theView->dateTimeInput('articles_archive_datelimit')
                                    ->setValue($globalConfig['articles_archive_datelimit'] ? $theView->dateText($globalConfig['articles_archive_datelimit'], 'Y-m-d') : '')
                                    ->setText('SYSTEM_OPTIONS_NEWS_ARCHIVELIMIT')
                                    ->setPlaceholder($theView->translate('SYSTEM_OPTIONS_NEWS_ARCHIVELIMIT_EMPTY'))
                                    ->setData(['maxDate' => '-3m'])
                                    ->setDisplaySizes(['xs' => 12, 'md' => 5], ['xs' => 12, 'md' => 7]); ?>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>

        <div id="tabs-options-comments">
            <div class="row no-gutters">
                <div class="col-sm-12 col-lg-6">
                    <fieldset class="fpcm-ui-margin-md-right">
                        <legend><?php $theView->write('COMMMENT_HEADLINE'); ?></legend>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_COMMENT_ENABLED_GLOBAL'); ?>
                            </div>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->boolSelect('system_comments_enabled')->setSelected($globalConfig['system_comments_enabled']); ?>		
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_COMMENT_PRIVACYOPTIN'); ?>
                            </div>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->boolSelect('comments_privacy_optin')->setSelected($globalConfig['comments_privacy_optin']); ?>		
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_COMMENT_NOTIFY'); ?>
                            </div>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->select('comments_notify')
                                        ->setOptions($notify)
                                        ->setSelected($globalConfig['comments_notify'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_ACTIVECOMMENTTEMPLATE'); ?>
                            </div>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->select('comments_template_active')
                                        ->setOptions($commentTemplates)
                                        ->setSelected($globalConfig['comments_template_active'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_FLOODPROTECTION'); ?>
                            </div>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->select('comments_flood')
                                        ->setOptions($theView->translate('SYSTEM_OPTIONS_FLOODPROTECTION_INTERVALS'))
                                        ->setSelected($globalConfig['comments_flood'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_COMMENTEMAIL'); ?>
                            </div>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->boolSelect('comments_email_optional')->setSelected($globalConfig['comments_email_optional']); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_COMMENT_APPROVE'); ?>
                            </div>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->boolSelect('comments_confirm')->setSelected($globalConfig['comments_confirm']); ?>		
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="col-sm-12 col-lg-6">
                    <fieldset class="fpcm-ui-margin-md-left">
                        <legend><?php $theView->write('SYSTEM_OPTIONS_CAPTCHASETTING'); ?></legend>
                        
                        <div class="row fpcm-ui-padding-md-tb">
                            <?php $theView->textInput('comments_antispam_question')
                                ->setValue($globalConfig['comments_antispam_question'])
                                ->setText('SYSTEM_OPTIONS_ANTISPAMQUESTION')
                                ->setDisplaySizesDefault(); ?>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <?php $theView->textInput('comments_antispam_answer')
                                ->setValue($globalConfig['comments_antispam_answer'])
                                ->setText('SYSTEM_OPTIONS_ANTISPAMANSWER')
                                ->setDisplaySizesDefault(); ?>
                        </div>
                        
                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-12 col-md-3 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_COMMENT_MARKSPAM_PASTCHECK'); ?>
                            </div>
                            <div class="align-self-center col-12 col-md-9 mx-0 px-0">
                                <?php $theView->textInput('comments_markspam_commentcount')
                                        ->setText('')
                                        ->setValue($globalConfig['comments_markspam_commentcount'])
                                        ->setMaxlenght(5)
                                        ->setClass('fpcm-ui-spinner'); ?>
                            </div>
                        </div>
                    </fieldset>
                </div>                
            </div>
        </div>

        <div id="tabs-options-extended">
            <div class="row no-gutters">
                <div class="col-sm-12 col-lg-6">
                    <fieldset class="fpcm-ui-margin-md-right">
                        <legend><?php $theView->write('SYSTEM_HL_OPTIONS_SECURITY'); ?></legend>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_MAINTENANCE'); ?>
                            </div>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->boolSelect('system_maintenance')->setSelected($globalConfig['system_maintenance']); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_SESSIONLENGHT'); ?>
                            </div>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->select('system_session_length')
                                        ->setOptions($theView->translate('SYSTEM_OPTIONS_SESSIONLENGHT_INTERVALS'))
                                        ->setSelected($globalConfig['system_session_length'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-md-12 col-lg-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_LOGIN_MAXATTEMPTS'); ?>
                            </div>
                            <div class="align-self-center col-md-12 col-lg-7 mx-0 px-0">
                                <?php $theView->textInput('system_loginfailed_locked')
                                        ->setText('')
                                        ->setValue($globalConfig['system_loginfailed_locked'])
                                        ->setMaxlenght(5)
                                        ->setClass('fpcm-ui-spinner'); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_LOGIN_TWOFACTORAUTH'); ?>
                            </div>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->boolSelect('system_2fa_auth')->setSelected($globalConfig['system_2fa_auth']); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_USERS_PASSCHECK'); ?>
                            </div>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->boolSelect('system_passcheck_enabled')->setSelected($globalConfig['system_passcheck_enabled']); ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="fpcm-ui-margin-md-right fpcm-ui-margin-md-top" >
                        <legend><?php $theView->write('SYSTEM_OPTIONS_EXTENDED_UPDATES'); ?></legend>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_EXTENDED_EMAILUPDATES'); ?>
                            </div>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->boolSelect('system_updates_emailnotify')->setSelected($globalConfig['system_updates_emailnotify']); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_EXTENDED_DEVUPDATES'); ?>
                            </div>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->boolSelect('system_updates_devcheck')->setSelected($globalConfig['system_updates_devcheck']); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_EXTENDED_UPDATESMANCHK'); ?>
                            </div>
                            <div class="col-12 col-sm-7 px-0">
                                <?php $theView->select('system_updates_manual')
                                        ->setOptions($theView->translate('SYSTEM_OPTIONS_UPDATESMANUAL'))
                                        ->setSelected($globalConfig['system_updates_manual'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="col-sm-12 col-lg-6">
                    <fieldset class="fpcm-ui-margin-md-left">
                        <legend><?php $theView->write('SYSTEM_OPTIONS_EXTENDED_EMAILSUBMISSION'); ?></legend>

                        <?php if ($smtpActive) : ?>
                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="col-12">
                                <?php $theView->icon('envelope')->setStack('check fpcm-ui-editor-metainfo fpcm ui-status-075')->setSize('lg')->setStackTop(true); ?>
                                <?php $theView->write('SYSTEM_OPTIONS_EMAIL_ACTIVE'); ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-12 col-sm-6 col-md-3 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_EMAIL_ENABLED'); ?>
                            </div>
                            <div class="col-12 col-sm-6 col-sm-9 px-0">
                                <?php $theView->boolSelect('smtp_enabled')->setSelected($globalConfig['smtp_enabled']); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <?php $theView->textInput('smtp_settings[addr]')
                                    ->setType('email')
                                    ->setValue($globalConfig['smtp_settings']->addr)
                                    ->setReadonly(($globalConfig['smtp_enabled'] ? false : true))
                                    ->setText('GLOBAL_EMAIL')
                                    ->setPlaceholder('mail@example.com')
                                    ->setClass('fpcm-ui-options-smtp-input')
                                    ->setDisplaySizesDefault(); ?>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <?php $theView->textInput('smtp_settings[srvurl]')
                                    ->setValue($globalConfig['smtp_settings']->srvurl)
                                    ->setReadonly(($globalConfig['smtp_enabled'] ? false : true))
                                    ->setText('SYSTEM_OPTIONS_EMAIL_SERVER')
                                    ->setPlaceholder('mail.example.com')
                                    ->setClass('fpcm-ui-options-smtp-input')
                                    ->setDisplaySizesDefault(); ?>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <?php $theView->textInput('smtp_settings[port]')
                                    ->setValue($globalConfig['smtp_settings']->port)
                                    ->setReadonly(($globalConfig['smtp_enabled'] ? false : true))
                                    ->setText('SYSTEM_OPTIONS_EMAIL_PORT')
                                    ->setPlaceholder('25')
                                    ->setClass('fpcm-ui-options-smtp-input')
                                    ->setDisplaySizesDefault(); ?>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <?php $theView->textInput('smtp_settings[user]')
                                    ->setValue($globalConfig['smtp_settings']->user)
                                    ->setReadonly(($globalConfig['smtp_enabled'] ? false : true))
                                    ->setText('SYSTEM_OPTIONS_EMAIL_USERNAME')
                                    ->setPlaceholder('mail@example.com')
                                    ->setClass('fpcm-ui-options-smtp-input')
                                    ->setDisplaySizesDefault(); ?>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <?php $theView->passwordInput('smtp_settings[pass]')
                                    ->setText('SYSTEM_OPTIONS_EMAIL_PASSWORD')
                                    ->setReadonly(($globalConfig['smtp_enabled'] ? false : true))
                                    ->setClass('fpcm-ui-options-smtp-input')
                                    ->setPlaceholder(trim($globalConfig['smtp_settings']->pass) ? '*****' : '')
                                    ->setDisplaySizesDefault(); ?>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-12 col-sm-6 col-md-3 fpcm-ui-field-label-general">
                                <?php $theView->write('SYSTEM_OPTIONS_EMAIL_ENCRYPTED'); ?>
                            </div>
                            <div class="col-12 col-sm-6 col-sm-9 px-0">
                                <?php $theView->select('smtp_settings[encr]')
                                        ->setOptions($smtpEncryption)
                                        ->setSelected($globalConfig['smtp_settings']->encr)
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                                        ->setReadonly(($globalConfig['smtp_enabled'] ? false : true)); ?>
                            </div>
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
                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="col-12 col-md-6 align-self-center px-0">
                            <?php if (!$globalConfig['twitter_data']['consumer_key'] || !$globalConfig['twitter_data']['consumer_secret'] || !$twitterIsActive) : ?>
                                <?php $theView->linkButton('twitterConnect')->setText('SYSTEM_OPTIONS_TWITTER_CONNECT')->setUrl('https://apps.twitter.com/')->setTarget('_blank')->setIcon('twitter', 'fab'); ?>
                            <?php elseif ($globalConfig['twitter_data']['user_token'] && $globalConfig['twitter_data']['user_secret'] && $twitterIsActive) : ?>
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

            <div class="row no-gutters fpcm-ui-margin-md-top">
                <div class="col-12">
                    <fieldset>
                        <legend><?php $theView->write('SYSTEM_OPTIONS_TWITTER_EVENTS'); ?></legend>
                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="col-12 align-self-center px-0">
                                <?php $theView->checkbox('twitter_events[create]', 'twitter_events_create')->setText('SYSTEM_OPTIONS_TWITTER_EVENTCREATE')->setSelected($globalConfig['twitter_events']['create']); ?>
                                <?php $theView->checkbox('twitter_events[update]', 'twitter_events_update')->setText('SYSTEM_OPTIONS_TWITTER_EVENTUPDATE')->setSelected($globalConfig['twitter_events']['update']); ?>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="row no-gutters fpcm-ui-margin-md-top">
                <div class="col-12">
                    <fieldset>
                        <legend><?php $theView->write('SYSTEM_OPTIONS_TWITTER_CREDENTIALS'); ?></legend>

                        <div class="row fpcm-ui-padding-md-tb">
                            <?php $theView->textInput('twitter_data[consumer_key]')
                                ->setValue($globalConfig['twitter_data']['consumer_key'])
                                ->setText('SYSTEM_OPTIONS_TWITTER_CONSUMER_KEY')
                                ->setDisplaySizes(['xs' => 12, 'md' => 3], ['xs' => 12, 'md' => 9]); ?>
                        </div>                        

                        <div class="row fpcm-ui-padding-md-tb">
                            <?php $theView->textInput('twitter_data[consumer_secret]')
                                ->setValue($globalConfig['twitter_data']['consumer_secret'])
                                ->setText('SYSTEM_OPTIONS_TWITTER_CONSUMER_SECRET')
                                ->setDisplaySizes(['xs' => 12, 'md' => 3], ['xs' => 12, 'md' => 9]); ?>
                        </div>                        

                        <div class="row fpcm-ui-padding-md-tb">
                            <?php $theView->textInput('twitter_data[user_token]')
                                ->setValue($globalConfig['twitter_data']['user_token'])
                                ->setText('SYSTEM_OPTIONS_TWITTER_USER_TOKEN')
                                ->setDisplaySizes(['xs' => 12, 'md' => 3], ['xs' => 12, 'md' => 9]); ?>
                        </div>                        

                        <div class="row fpcm-ui-padding-md-tb">
                            <?php $theView->textInput('twitter_data[user_secret]')
                                ->setValue($globalConfig['twitter_data']['user_secret'])
                                ->setText('SYSTEM_OPTIONS_TWITTER_USER_SECRET')
                                ->setDisplaySizes(['xs' => 12, 'md' => 3], ['xs' => 12, 'md' => 9]); ?>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div> 
    </div>
</div>

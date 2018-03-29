<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div class="fpcm-tabs-general">
        <ul>
            <li data-toolbar-buttons="1"><a href="#tabs-options-general"><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></a></li>
            <li data-toolbar-buttons="1"><a href="#tabs-options-editor"><?php $theView->write('SYSTEM_HL_OPTIONS_EDITOR'); ?></a></li>
            <li data-toolbar-buttons="1"><a href="#tabs-options-news"><?php $theView->write('SYSTEM_HL_OPTIONS_ARTICLES'); ?></a></li>
            <li data-toolbar-buttons="1"><a href="#tabs-options-comments"><?php $theView->write('SYSTEM_HL_OPTIONS_COMMENTS'); ?></a></li>
            <li data-toolbar-buttons="1"><a href="#tabs-options-twitter"><?php $theView->write('SYSTEM_HL_OPTIONS_TWITTER'); ?></a></li>
            <li data-toolbar-buttons="1"><a href="#tabs-options-extended"><?php $theView->write('GLOBAL_EXTENDED'); ?></a></li>
            <li data-toolbar-buttons="2" id="tabs-options-syscheck"><a href="#tabs-options-check"><?php $theView->write('SYSTEM_HL_OPTIONS_SYSCHECK'); ?></a></li>
        </ul>

        <div id="tabs-options-general">
            
            <div class="row no-gutters">
                <div class="col-sm-12 col-lg-6">
                    <fieldset>
                        <legend><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></legend>
                        
                        <div class="row fpcm-ui-padding-md-tb fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('GLOBAL_EMAIL'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-7">
                                <?php $theView->textInput('system_email')->setValue($globalConfig['system_email']); ?>		
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_URL'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-7">
                                <?php $theView->textInput('system_url')->setValue($globalConfig['system_url']); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_DATETIMEMASK'); ?>:
                            </div>
                            <div class="align-self-center col-sm-11 col-md-auto">
                                <?php $theView->textInput('system_dtmask')->setValue($globalConfig['system_dtmask']); ?>
                            </div>
                            <div class="align-self-center col-sm-1 col-md-1 fpcm-ui-padding-md-lr">
                                <?php $theView->shorthelpButton('dtmask')->setText('SYSTEM_OPTIONS_DATETIMEMASK_HELP')->setUrl('http://php.net/manual/function.date.php'); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_TIMEZONE'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->select('system_timezone')->setOptions($timezoneAreas)->setSelected($globalConfig['system_timezone'])->setOptGroup(true); ?>
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_LANG'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->select('system_lang')->setOptions($languages)->setSelected($globalConfig['system_lang'])->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_ACPARTICLES_LIMIT'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->select('articles_acp_limit')
                                        ->setOptions($articleLimitListAcp)
                                        ->setSelected($globalConfig['articles_acp_limit'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_CACHETIMEOUT'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->select('system_cache_timeout')->setOptions($theView->translate('SYSTEM_OPTIONS_CACHETIMEOUT_INTERVAL'))->setSelected($globalConfig['system_cache_timeout'])->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="col-sm-12 col-lg-6">
                    <fieldset>
                        <legend><?php $theView->write('HL_FRONTEND'); ?></legend>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_STYLESHEET'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-7">
                                <?php $theView->textInput('system_css_path')->setValue($globalConfig['system_css_path'], ENT_QUOTES); ?>
                            </div>
                        </div>
                        
                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-lg-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_USEMODE'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-lg-4">
                                <?php $theView->select('system_mode')->setOptions($systemModes)->setSelected($globalConfig['system_mode'])->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                            <div class="align-self-center col-sm-12 col-lg-3">
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-md-12 col-lg-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_INCLUDEJQUERY'); ?>:
                            </div>
                            <div class="align-self-center col-md-12 col-lg-auto">
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
                    <fieldset>
                        <legend><?php $theView->write('SYSTEM_OPTIONS_NEWS_EDITOR_SETTINGS'); ?></legend>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWS_EDITOR'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->select('system_editor')->setOptions($editors)->setSelected(base64_encode($globalConfig['system_editor']))->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                            </div>
                        </div>
                        
                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWS_EDITOR_FONTSIZE'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->select('system_editor_fontsize')
                                        ->setOptions($defaultFontsizes)
                                        ->setSelected($globalConfig['system_editor_fontsize'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_REVISIONS_ENABLED'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->boolSelect('articles_revisions')->setSelected($globalConfig['articles_revisions']); ?>            
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWS_REVISIONS_LIMIT'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->select('articles_revisions_limit')
                                        ->setOptions($theView->translate('SYSTEM_OPTIONS_NEWS_REVISIONS_LIMIT_LIST'))
                                        ->setSelected($globalConfig['articles_revisions_limit'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                            </div>
                        </div>
                        
                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWS_EDITOR_IMGTOOLS'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->boolSelect('articles_imageedit_persistence')->setSelected($globalConfig['articles_imageedit_persistence']); ?>
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                            </div>
                        </div>
            
                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWS_EDITOR_CSS'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-7">
                                <?php $theView->textarea('system_editor_css')->setValue($globalConfig['system_editor_css'], ENT_QUOTES)->setClass('fpcm-ui-textarea-medium fpcm-ui-full-width'); ?>
                            </div>
                        </div>
                    </fieldset>
                </div>
                
                <div class="col-sm-12 col-lg-6">
                    <fieldset>
                        <legend><?php $theView->write('HL_FILES_MNG'); ?></legend>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWS_NEWUPLOADER'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->boolSelect('file_uploader_new')->setSelected($globalConfig['file_uploader_new']); ?>
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_FILEMANAGER_LIMIT'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->select('file_list_limit')
                                        ->setOptions($articleLimitListAcp)
                                        ->setSelected($globalConfig['file_list_limit'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                            </div>
                        </div>
                    </fieldset>
                    
                    <fieldset class="fpcm-ui-margin-none-left fpcm-ui-margin-md-top" >
                        <legend><?php $theView->write('SYSTEM_OPTIONS_NEWSSHOWIMGTHUMBSIZE'); ?></legend>
                        
                       <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-12 col-md-5">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWSSHOWMAXIMGSIZEWIDTH'); ?>:
                            </div>
                            <div class="col-12 col-md-7">
                                <?php $theView->textInput('file_img_thumb_width')->setClass('fpcm-ui-spinner')->setValue($globalConfig['file_img_thumb_width'])->setMaxlenght(5)->setWrapper(false); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-12 col-md-5">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWSSHOWMAXIMGSIZEHEIGHT'); ?>:
                            </div>
                            <div class="col-12 col-md-7">
                                <?php $theView->textInput('file_img_thumb_height')->setClass('fpcm-ui-spinner')->setValue($globalConfig['file_img_thumb_height'])->setMaxlenght(5)->setWrapper(false); ?>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>

        <div id="tabs-options-news">

            <div class="row no-gutters">
                <div class="col-sm-12 col-lg-6">
                    <fieldset>
                        <legend><?php $theView->write('HL_FRONTEND'); ?></legend>
                        
                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWSSHOWLIMIT'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->select('articles_limit')
                                        ->setOptions($articleLimitList)
                                        ->setSelected($globalConfig['articles_limit'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_ACTIVENEWSTEMPLATE'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->select('articles_template_active')
                                        ->setOptions($articleTemplates)
                                        ->setSelected($globalConfig['articles_template_active'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_ACTIVENEWSTEMPLATESINGLE'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->select('article_template_active')
                                        ->setOptions($articleTemplates)
                                        ->setSelected($globalConfig['article_template_active'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>		
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-12 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWS_SORTING'); ?>:
                            </div>
                            <div class="align-self-center col-12 fpcm-ui-center">
                                <div class="fpcm-ui-controlgroup fpcm-ui-padding-md-tb">
                                    <?php $theView->select('articles_sort')
                                            ->setOptions($sorts)
                                            ->setSelected($globalConfig['articles_sort'])
                                            ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                                    <?php $theView->select('articles_sort_order')
                                            ->setOptions($sortsOrders)
                                            ->setSelected($globalConfig['articles_sort_order'])
                                            ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWSSHOWSHARELINKS'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->boolSelect('system_show_share')->setSelected($globalConfig['system_show_share']); ?>
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWS_URLREWRITING'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->boolSelect('articles_link_urlrewrite')->setSelected($globalConfig['articles_link_urlrewrite']); ?>
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWS_ENABLEFEED'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->boolSelect('articles_rss')->setSelected($globalConfig['articles_rss']); ?>
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="col-sm-12 col-lg-6">
                    <fieldset>
                        <legend><?php $theView->write('HL_ARCHIVE'); ?></legend>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_ARCHIVE_LINK'); ?>:                    
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->boolSelect('articles_archive_show')->setSelected($globalConfig['articles_archive_show']); ?>		
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-md-12 col-lg-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_NEWS_ARCHIVELIMIT'); ?>:
                            </div>
                            <div class="align-self-center col-md-12 col-lg-6">
                                <?php $theView->textInput('articles_archive_datelimit')->setValue($globalConfig['articles_archive_datelimit'] ? $theView->dateText($globalConfig['articles_archive_datelimit'], 'Y-m-d') : ''); ?>
                            </div>
                            <div class="align-self-center col-md-12 col-lg-1">
                                <?php $theView->shorthelpButton('dtmask')->setText('SYSTEM_OPTIONS_NEWS_ARCHIVELIMIT_EMPTY'); ?>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>

        <div id="tabs-options-comments">
            <div class="row no-gutters">
                <div class="col-sm-12 col-lg-6">
                    <fieldset>
                        <legend><?php $theView->write('HL_COMMENTS_MNG'); ?></legend>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_COMMENT_ENABLED_GLOBAL'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->boolSelect('system_comments_enabled')->setSelected($globalConfig['system_comments_enabled']); ?>		
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_COMMENT_NOTIFY'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->select('comments_notify')
                                        ->setOptions($notify)
                                        ->setSelected($globalConfig['comments_notify'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_ACTIVECOMMENTTEMPLATE'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->select('comments_template_active')
                                        ->setOptions($commentTemplates)
                                        ->setSelected($globalConfig['comments_template_active'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_FLOODPROTECTION'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->select('comments_flood')
                                        ->setOptions($theView->translate('SYSTEM_OPTIONS_FLOODPROTECTION_INTERVALS'))
                                        ->setSelected($globalConfig['comments_flood'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_COMMENTEMAIL'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->boolSelect('comments_email_optional')->setSelected($globalConfig['comments_email_optional']); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_COMMENT_APPROVE'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->boolSelect('comments_confirm')->setSelected($globalConfig['comments_confirm']); ?>		
                            </div>
                        </div>
                    </fieldset>
                </div>
                
                <div class="col-sm-12 col-lg-6">
                    <fieldset>
                        <legend><?php $theView->write('SYSTEM_OPTIONS_CAPTCHASETTING'); ?></legend>
                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_ANTISPAMQUESTION'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-7">
                                <?php $theView->textInput('comments_antispam_question')->setValue($globalConfig['comments_antispam_question']); ?>		
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_ANTISPAMANSWER'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-7">
                                <?php $theView->textInput('comments_antispam_answer')->setValue($globalConfig['comments_antispam_answer']); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_COMMENT_MARKSPAM_PASTCHECK'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-7">
                                <?php $theView->textInput('comments_markspam_commentcount')
                                        ->setClass('fpcm-ui-spinner')
                                        ->setValue($globalConfig['comments_markspam_commentcount'])
                                        ->setMaxlenght(5)
                                        ->setWrapper(false); ?>
                            </div>
                        </div>
                    </fieldset>
                </div>                
            </div>
        </div>

        <div id="tabs-options-extended">
            <div class="row no-gutters">
                <div class="col-sm-12 col-lg-6">
                    <fieldset>
                        <legend><?php $theView->write('SYSTEM_HL_OPTIONS_SECURITY'); ?></legend>
                        
                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-lg-6 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_MAINTENANCE'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-lg-6">
                                <?php $theView->boolSelect('system_maintenance')->setSelected($globalConfig['system_maintenance']); ?>		
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-lg-6 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_SESSIONLENGHT'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-lg-6">
                                <?php $theView->select('system_session_length')
                                        ->setOptions($theView->translate('SYSTEM_OPTIONS_SESSIONLENGHT_INTERVALS'))
                                        ->setSelected($globalConfig['system_session_length'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-lg-6 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_LOGIN_MAXATTEMPTS'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-lg-6">
                                <?php $theView->textInput('system_loginfailed_locked')
                                        ->setClass('fpcm-ui-spinner')
                                        ->setValue($globalConfig['system_loginfailed_locked'])
                                        ->setMaxlenght(5)
                                        ->setWrapper(false); ?>
                            </div>
                        </div>
                    </fieldset>
                    
                    <fieldset class="fpcm-ui-margin-none-left fpcm-ui-margin-md-right fpcm-ui-margin-md-top" >
                        <legend><?php $theView->write('SYSTEM_OPTIONS_EXTENDED_UPDATES'); ?></legend>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-lg-6 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_EXTENDED_EMAILUPDATES'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-lg-6">
                                <?php $theView->boolSelect('system_updates_emailnotify')->setSelected($globalConfig['system_updates_emailnotify']); ?>		
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-lg-6 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_EXTENDED_DEVUPDATES'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-lg-6">
                                <?php $theView->boolSelect('system_updates_devcheck')->setSelected($globalConfig['system_updates_devcheck']); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-lg-6 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_EXTENDED_UPDATESMANCHK'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-lg-6">
                                <?php $theView->select('system_updates_manual')
                                        ->setOptions($theView->translate('SYSTEM_OPTIONS_UPDATESMANUAL'))
                                        ->setSelected($globalConfig['system_updates_manual'])
                                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                            </div>
                        </div>
                    </fieldset>
                </div>
                
                <div class="col-sm-12 col-lg-6">
                    <fieldset>
                        <legend><?php $theView->write('SYSTEM_OPTIONS_EXTENDED_EMAILSUBMISSION'); ?></legend>
                        
                        <?php if ($smtpActive) : ?>
                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="col-12">
                                <?php $theView->icon('envelope')->setStack('check fpcm-ui-editor-metainfo fpcm-ui-status-075')->setSize('lg')->setStackTop(true); ?>
                                <?php $theView->write('SYSTEM_OPTIONS_EMAIL_ACTIVE'); ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-4 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_EMAIL_ENABLED'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->boolSelect('smtp_enabled')->setSelected($globalConfig['smtp_enabled']); ?>
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-4 fpcm-ui-padding-none-lr">
                                <?php $theView->write('GLOBAL_EMAIL'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-8">
                                <?php $theView->textInput('smtp_settings[addr]')
                                        ->setClass('fpcm-ui-options-smtp-input')
                                        ->setValue($globalConfig['smtp_settings']['addr'])
                                        ->setReadonly(($globalConfig['smtp_enabled'] ? false : true)); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-4 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_EMAIL_SERVER'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-8">
                                <?php $theView->textInput('smtp_settings[srvurl]')
                                        ->setClass('fpcm-ui-options-smtp-input')
                                        ->setValue($globalConfig['smtp_settings']['srvurl'])
                                        ->setReadonly(($globalConfig['smtp_enabled'] ? false : true)); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-4 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_EMAIL_PORT'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-8">
                                <?php $theView->textInput('smtp_settings[port]')
                                        ->setClass('fpcm-ui-options-smtp-input')
                                        ->setValue($globalConfig['smtp_settings']['port'])
                                        ->setReadonly(($globalConfig['smtp_enabled'] ? false : true)); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-4 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_EMAIL_USERNAME'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-8">
                                <?php $theView->textInput('smtp_settings[user]')
                                        ->setClass('fpcm-ui-options-smtp-input')
                                        ->setValue($globalConfig['smtp_settings']['user'])
                                        ->setReadonly(($globalConfig['smtp_enabled'] ? false : true)); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-4 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_EMAIL_PASSWORD'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-8">
                                <?php $theView->textInput('smtp_settings[pass]')
                                        ->setClass('fpcm-ui-options-smtp-input')
                                        ->setValue($globalConfig['smtp_settings']['pass'])
                                        ->setReadonly(($globalConfig['smtp_enabled'] ? false : true)); ?>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-4 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_EMAIL_ENCRYPTED'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-8">
                                <?php $theView->select('smtp_settings[encr]')
                                        ->setOptions($smtpEncryption)
                                        ->setSelected($globalConfig['smtp_settings']['encr'])
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
                            <div class="col-12 col-md-6 align-self-center fpcm-ui-padding-none-lr">
                            <?php if (!$globalConfig['twitter_data']['consumer_key'] || !$globalConfig['twitter_data']['consumer_secret'] || !$twitterIsActive) : ?>
                                <?php $theView->linkButton('twitterConnect')->setText('SYSTEM_OPTIONS_TWITTER_CONNECT')->setUrl('https://apps.twitter.com/')->setTarget('_blank'); ?>
                            <?php elseif ($globalConfig['twitter_data']['user_token'] && $globalConfig['twitter_data']['user_secret'] && $twitterIsActive) : ?>
                                <?php $theView->submitButton('twitterDisconnect')->setText('SYSTEM_OPTIONS_TWITTER_DISCONNECT')->setClass('fpcm-ui-button-confirm'); ?>
                            <?php endif; ?>

                            <?php $theView->shorthelpButton('twittercon')->setText('HL_HELP')->setUrl(\fpcm\classes\tools::getFullControllerLink('system/help', ['ref' => urlencode(base64_encode('SYSTEM_OPTIONS_TWITTER_CONNECTION')) ]))->setClass('fpcm-ui-help-dialog'); ?>
                            </div>
                            <div class="col-12 col-md-6 align-self-center">
                            <?php if ($twitterIsActive) : ?>
                                <?php $theView->icon('twitter', 'fab')->setStack('check fpcm-ui-editor-metainfo fpcm-ui-status-075')->setSize('lg')->setStackTop(true); ?>
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
                            <div class="col-12 align-self-center fpcm-ui-padding-none-lr">
                                <div class="fpcm-ui-controlgroup">
                                    <?php $theView->checkbox('twitter_events[create]', 'twitter_events_create')->setText('SYSTEM_OPTIONS_TWITTER_EVENTCREATE')->setSelected($globalConfig['twitter_events']['create'])->setIcon('plus'); ?>
                                    <?php $theView->checkbox('twitter_events[update]', 'twitter_events_update')->setText('SYSTEM_OPTIONS_TWITTER_EVENTUPDATE')->setSelected($globalConfig['twitter_events']['update'])->setIcon('retweet'); ?>
                                </div>     
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
                            <div class="align-self-center col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_TWITTER_CONSUMER_KEY'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->textInput('twitter_data[consumer_key]')->setValue($globalConfig['twitter_data']['consumer_key']); ?>
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_TWITTER_CONSUMER_SECRET'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->textInput('twitter_data[consumer_secret]')->setValue($globalConfig['twitter_data']['consumer_secret']); ?>
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_TWITTER_USER_TOKEN'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->textInput('twitter_data[user_token]')->setValue($globalConfig['twitter_data']['user_token']); ?>
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="align-self-center col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
                                <?php $theView->write('SYSTEM_OPTIONS_TWITTER_USER_SECRET'); ?>:
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                                <?php $theView->textInput('twitter_data[user_secret]')->setValue($globalConfig['twitter_data']['user_secret']); ?>
                            </div>
                            <div class="align-self-center col-sm-12 col-md-auto">
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div> 

        <div id="tabs-options-check"></div>
    </div>

</div>
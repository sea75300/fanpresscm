<div class="fpcm-content-wrapper">
    <h1>
        <span class="fa fa-cog"></span> <?php $FPCM_LANG->write('HL_OPTIONS_SYSTEM'); ?>
    </h1>
    <form method="post" action="<?php print $FPCM_SELF; ?>?module=system/options">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-options-general"><?php $FPCM_LANG->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></a></li>
                <li><a href="#tabs-options-editor"><?php $FPCM_LANG->write('SYSTEM_HL_OPTIONS_EDITOR'); ?></a></li>
                <li><a href="#tabs-options-news"><?php $FPCM_LANG->write('SYSTEM_HL_OPTIONS_ARTICLES'); ?></a></li>
                <li><a href="#tabs-options-comments"><?php $FPCM_LANG->write('SYSTEM_HL_OPTIONS_COMMENTS'); ?></a></li>
                <?php if ($showTwitter) : ?> 
                <li><a href="#tabs-options-twitter"><?php $FPCM_LANG->write('SYSTEM_HL_OPTIONS_TWITTER'); ?></a></li>
                <?php endif; ?>
                <li><a href="#tabs-options-security"><?php $FPCM_LANG->write('SYSTEM_HL_OPTIONS_SECURITY'); ?></a></li>
                <li><a href="#tabs-options-extended"><?php $FPCM_LANG->write('GLOBAL_EXTENDED'); ?></a></li>
                <li id="tabs-options-syscheck"><a href="#tabs-options-check"><?php $FPCM_LANG->write('SYSTEM_HL_OPTIONS_SYSCHECK'); ?></a></li>
            </ul>

            <div id="tabs-options-general">
                <table class="fpcm-ui-table fpcm-ui-options">
                    <tr>			
                        <td><?php $FPCM_LANG->write('GLOBAL_EMAIL'); ?>:</td>
                        <td><?php fpcm\model\view\helper::textInput('system_email', '', $globalConfig['system_email']); ?></td>		
                    </tr>			
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_URL'); ?>:</td>
                        <td><?php fpcm\model\view\helper::textInput('system_url', '', $globalConfig['system_url']); ?></td>
                    </tr>	
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_LANG'); ?>:</td>
                        <td><?php fpcm\model\view\helper::select('system_lang', $languages, $globalConfig['system_lang'], false, false); ?></td>				 
                    </tr>		
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_TIMEZONE'); ?>:</td>
                        <td><?php fpcm\model\view\helper::selectGroup('system_timezone', $timezoneAreas, $globalConfig['system_timezone']); ?></td>
                    </tr>						
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_DATETIMEMASK'); ?>:</td>
                        <td>
                            <?php fpcm\model\view\helper::textInput('system_dtmask', '', $globalConfig['system_dtmask']); ?>
                            <?php \fpcm\model\view\helper::shortHelpButton($FPCM_LANG->translate('SYSTEM_OPTIONS_DATETIMEMASK_HELP'), '', 'http://us2.php.net/manual/function.date.php', '_blank'); ?>
                        </td>
                    </tr>
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_CACHETIMEOUT'); ?>:</td>
                        <td><?php fpcm\model\view\helper::select('system_cache_timeout', $FPCM_LANG->translate('SYSTEM_OPTIONS_CACHETIMEOUT_INTERVAL'), $globalConfig['system_cache_timeout'], false, false); ?></td>
                    </tr>                               
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_USEMODE'); ?>:</td>
                        <td><?php fpcm\model\view\helper::select('system_mode', $systemModes, $globalConfig['system_mode'], false, false); ?></td>		
                    </tr>			 
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_STYLESHEET'); ?>:</td>
                        <td><?php fpcm\model\view\helper::textInput('system_css_path', '', $globalConfig['system_css_path']); ?></td>
                    </tr>
                    <tr>
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_INCLUDEJQUERY'); ?>:</td>
                        <td>
                            <?php fpcm\model\view\helper::boolSelect('system_loader_jquery', $globalConfig['system_loader_jquery']); ?>
                            <?php \fpcm\model\view\helper::shortHelpButton($FPCM_LANG->translate('SYSTEM_OPTIONS_INCLUDEJQUERY_YES')); ?>
                        </td>
                    </tr>
                </table>
            </div>

            <div id="tabs-options-editor">
                <table class="fpcm-ui-table fpcm-ui-options">
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_NEWS_EDITOR'); ?>:</td>
                        <td><?php fpcm\model\view\helper::select('system_editor', $editors, $globalConfig['system_editor'], false, false); ?></td>		
                    </tr>
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_NEWS_EDITOR_FONTSIZE'); ?>:</td>
                        <td><?php fpcm\model\view\helper::select('system_editor_fontsize', $defaultFontsizes, $globalConfig['system_editor_fontsize'], false, false); ?></td>
                    </tr>
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_REVISIONS_ENABLED'); ?>:</td>
                        <td>
                            <?php fpcm\model\view\helper::boolSelect('articles_revisions', $globalConfig['articles_revisions']); ?>
                        </td>		
                    </tr>
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_NEWS_REVISIONS_LIMIT'); ?>:</td>
                        <td><?php fpcm\model\view\helper::select('articles_revisions_limit', $FPCM_LANG->translate('SYSTEM_OPTIONS_NEWS_REVISIONS_LIMIT_LIST'), $globalConfig['articles_revisions_limit'], false, false); ?></td>
                    </tr>
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_NEWS_ENABLETRASH'); ?>:</td>
                        <td><?php fpcm\model\view\helper::boolSelect('articles_trash', $globalConfig['articles_trash']); ?></td>		
                    </tr>
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_NEWS_NEWUPLOADER'); ?>:</td>
                        <td><?php fpcm\model\view\helper::boolSelect('file_uploader_new', $globalConfig['file_uploader_new']); ?></td>
                    </tr>
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_FILEMANAGER_LIMIT'); ?>:</td>
                        <td><?php fpcm\model\view\helper::select('file_list_limit', $articleLimitListAcp, $globalConfig['file_list_limit'], false, false); ?></td>
                    </tr>
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_NEWS_EDITOR_IMGTOOLS'); ?>:</td>
                        <td><?php fpcm\model\view\helper::boolSelect('articles_imageedit_persistence', $globalConfig['articles_imageedit_persistence']); ?></td>
                    </tr>
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_NEWSSHOWIMGTHUMBSIZE'); ?>:</td>
                        <td>
                            <div class="fpcm-ui-buttonset">
                                <?php fpcm\model\view\helper::textInput('file_img_thumb_width', 'ui-spinner-input', $globalConfig['file_img_thumb_width'], false, 5, false, false); ?>
                                <label for="file_img_thumb_width" class="ui-controlgroup-label"><span class="fa fa-times fa-fw"></span></label>                                
                                <?php fpcm\model\view\helper::textInput('file_img_thumb_height', 'ui-spinner-input', $globalConfig['file_img_thumb_height'], false, 5, false, false); ?>
                                <label for="file_img_thumb_height" class="ui-controlgroup-label"><?php $FPCM_LANG->write('SYSTEM_OPTIONS_NEWSSHOWMAXIMGSIZEPIXELS'); ?></label>
                            </div>
                        </td>	
                    </tr>
                    <tr>			
                        <td class="fpcm-align-top"><?php $FPCM_LANG->write('SYSTEM_OPTIONS_NEWS_EDITOR_CSS'); ?>:</td>
                        <td>
                            <?php fpcm\model\view\helper::textArea('system_editor_css', 'fpcm-ui-half-width fpcm-options-cssclasses', $globalConfig['system_editor_css'], false); ?>
                        </td>	
                    </tr>
                </table>
            </div>

            <div id="tabs-options-news">
                <table class="fpcm-ui-table fpcm-ui-options">
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_NEWSSHOWLIMIT'); ?>:</td>
                        <td><?php fpcm\model\view\helper::select('articles_limit', $articleLimitList, $globalConfig['articles_limit'], false, false); ?></td>
                    </tr>
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_ACPARTICLES_LIMIT'); ?>:</td>
                        <td><?php fpcm\model\view\helper::select('articles_acp_limit', $articleLimitListAcp, $globalConfig['articles_acp_limit'], false, false); ?></td>
                    </tr>				
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_ACTIVENEWSTEMPLATE'); ?>:</td>
                        <td><?php fpcm\model\view\helper::select('articles_template_active', $articleTemplates, $globalConfig['articles_template_active'], false, false); ?></td>
                    </tr>				
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_ACTIVENEWSTEMPLATESINGLE'); ?>:</td>
                        <td><?php fpcm\model\view\helper::select('article_template_active', $articleTemplates, $globalConfig['article_template_active'], false, false); ?></td>		
                    </tr>
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_NEWS_SORTING'); ?>:</td>
                        <td>
                            <div class="fpcm-ui-buttonset">
                                <?php fpcm\model\view\helper::select('articles_sort', $sorts, $globalConfig['articles_sort'], false, false); ?>
                                <?php fpcm\model\view\helper::select('articles_sort_order', $sortsOrders, $globalConfig['articles_sort_order'], false, false); ?>
                            </div>
                        </td>		
                    </tr>                        

                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_NEWSSHOWSHARELINKS'); ?>:</td>
                        <td><?php fpcm\model\view\helper::boolSelect('system_show_share', $globalConfig['system_show_share']); ?></td>		
                    </tr>
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_NEWS_URLREWRITING'); ?>:</td>
                        <td><?php fpcm\model\view\helper::boolSelect('articles_link_urlrewrite', $globalConfig['articles_link_urlrewrite']); ?></td>
                    </tr>
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_NEWS_ENABLEFEED'); ?>:</td>
                        <td><?php fpcm\model\view\helper::boolSelect('articles_rss', $globalConfig['articles_rss']); ?></td>
                    </tr>
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_ARCHIVE_LINK'); ?>:</td>
                        <td><?php fpcm\model\view\helper::boolSelect('articles_archive_show', $globalConfig['articles_archive_show']); ?></td>		
                    </tr>
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_NEWS_ARCHIVELIMIT'); ?>:</td>
                        <td><?php fpcm\model\view\helper::textInput('articles_archive_datelimit', '', $globalConfig['articles_archive_datelimit'] ? fpcm\model\view\helper::dateText($globalConfig['articles_archive_datelimit'], 'Y-m-d', true) : ''); ?>
                            <?php \fpcm\model\view\helper::shortHelpButton($FPCM_LANG->translate('SYSTEM_OPTIONS_NEWS_ARCHIVELIMIT_EMPTY')); ?>
                        </td>
                    </tr>
                </table>                    
            </div>

            <div id="tabs-options-comments">
                <table class="fpcm-ui-table fpcm-ui-options">
                   <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_COMMENT_ENABLED_GLOBAL'); ?>:</td>
                        <td><?php fpcm\model\view\helper::boolSelect('system_comments_enabled', $globalConfig['system_comments_enabled']); ?></td>		
                   </tr>                                                
                   <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_ACTIVECOMMENTTEMPLATE'); ?>:</td>
                        <td><?php fpcm\model\view\helper::select('comments_template_active', $commentTemplates, $globalConfig['comments_template_active'], false, false); ?></td>
                   </tr>
                   <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_FLOODPROTECTION'); ?>:</td>
                        <td><?php fpcm\model\view\helper::select('comments_flood', $FPCM_LANG->translate('SYSTEM_OPTIONS_FLOODPROTECTION_INTERVALS'), $globalConfig['comments_flood'], false, false); ?></td>
                   </tr>		
                   <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_COMMENTEMAIL'); ?>:</td>
                        <td><?php fpcm\model\view\helper::boolSelect('comments_email_optional', $globalConfig['comments_email_optional']); ?></td>
                   </tr>	
                   <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_COMMENT_APPROVE'); ?>:</td>
                        <td><?php fpcm\model\view\helper::boolSelect('comments_confirm', $globalConfig['comments_confirm']); ?></td>		
                   </tr>	
                   <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_COMMENT_NOTIFY'); ?>:</td>
                        <td><?php fpcm\model\view\helper::select('comments_notify', $notify, $globalConfig['comments_notify'], false, false); ?></td>
                   </tr>	 
                   <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_ANTISPAMQUESTION'); ?>:</td>
                        <td><?php fpcm\model\view\helper::textInput('comments_antispam_question', '', $globalConfig['comments_antispam_question']); ?></td>		
                   </tr>			 
                   <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_ANTISPAMANSWER'); ?>:</td>
                        <td><?php fpcm\model\view\helper::textInput('comments_antispam_answer', '', $globalConfig['comments_antispam_answer']); ?></td>
                   </tr>	 
                   <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_COMMENT_MARKSPAM_PASTCHECK'); ?>:</td>
                        <td><?php fpcm\model\view\helper::textInput('comments_markspam_commentcount', 'fpcm-ui-spinner', $globalConfig['comments_markspam_commentcount'], false, 5, false, false); ?></td>		
                   </tr>	
               </table>
            </div>
            
            <div id="tabs-options-security">
                <table class="fpcm-ui-table fpcm-ui-options">
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_MAINTENANCE'); ?>:</td>
                        <td><?php fpcm\model\view\helper::boolSelect('system_maintenance', $globalConfig['system_maintenance']); ?></td>		
                    </tr>			 			 
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_SESSIONLENGHT'); ?>:</td>
                        <td><?php fpcm\model\view\helper::select('system_session_length', $FPCM_LANG->translate('SYSTEM_OPTIONS_SESSIONLENGHT_INTERVALS'), $globalConfig['system_session_length'], false, false); ?></td>
                    </tr>			 			 
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_LOGIN_MAXATTEMPTS'); ?>:</td>
                        <td><?php fpcm\model\view\helper::textInput('system_loginfailed_locked', 'fpcm-ui-spinner', $globalConfig['system_loginfailed_locked'], false, 5, false, false); ?></td>
                    </tr>
                </table>
            </div>
            
            <div id="tabs-options-extended">
                <table class="fpcm-ui-table fpcm-ui-options">
                    <tr>			
                         <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_EXTENDED_EMAILUPDATES'); ?>:</td>
                         <td><?php fpcm\model\view\helper::boolSelect('system_updates_emailnotify', $globalConfig['system_updates_emailnotify']); ?></td>		
                    </tr>
                    <tr>			
                         <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_EXTENDED_DEVUPDATES'); ?>:</td>
                         <td><?php fpcm\model\view\helper::boolSelect('system_updates_devcheck', $globalConfig['system_updates_devcheck']); ?></td>		
                    </tr>
                    <tr>			
                         <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_EXTENDED_UPDATESMANCHK'); ?>:</td>
                         <td><?php fpcm\model\view\helper::select('system_updates_manual', $FPCM_LANG->translate('SYSTEM_OPTIONS_UPDATESMANUAL'), $globalConfig['system_updates_manual'], false, false); ?></td>
                    </tr>
                    <?php if ($smtpActive) : ?>
                    <tr class="fpcm-td-spacer"><td colspan="2"></td></tr>
                    <tr>
                        <th></th>
                        <th><span class="fa fa-check-square fa-align-right"></span> <?php $FPCM_LANG->write('SYSTEM_OPTIONS_EMAIL_ACTIVE'); ?></th>
                    </tr>
                    <?php endif; ?>
                    <tr>	
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_EMAIL_ENABLED'); ?>:</td>
                        <td>
                            <?php fpcm\model\view\helper::boolSelect('smtp_enabled', $globalConfig['smtp_enabled']); ?>
                        </td>
                    </tr>
                    <tr>			
                        <td><?php $FPCM_LANG->write('GLOBAL_EMAIL'); ?>:</td>
                        <td><?php fpcm\model\view\helper::textInput('smtp_settings[addr]', 'fpcm-ui-options-smtp-input', $globalConfig['smtp_settings']['addr'], ($globalConfig['smtp_enabled'] ? false : true)); ?></td>
                    </tr>
                    <tr>	
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_EMAIL_SERVER'); ?>:</td>
                        <td><?php fpcm\model\view\helper::textInput('smtp_settings[srvurl]', 'fpcm-ui-options-smtp-input', $globalConfig['smtp_settings']['srvurl'], ($globalConfig['smtp_enabled'] ? false : true)); ?></td>
                    </tr>
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_EMAIL_PORT'); ?>:</td>
                        <td><?php fpcm\model\view\helper::textInput('smtp_settings[port]', 'fpcm-ui-options-smtp-input', $globalConfig['smtp_settings']['port'], ($globalConfig['smtp_enabled'] ? false : true)); ?></td>
                    </tr>
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_EMAIL_USERNAME'); ?>:</td>
                        <td><?php fpcm\model\view\helper::textInput('smtp_settings[user]', 'fpcm-ui-options-smtp-input', $globalConfig['smtp_settings']['user'], ($globalConfig['smtp_enabled'] ? false : true)); ?></td>
                    </tr>
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_EMAIL_PASSWORD'); ?>:</td>
                        <td><?php fpcm\model\view\helper::textInput('smtp_settings[pass]', 'fpcm-ui-options-smtp-input', $globalConfig['smtp_settings']['pass'], ($globalConfig['smtp_enabled'] ? false : true)); ?></td>
                    </tr>
                    <tr>			
                        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_EMAIL_ENCRYPTED'); ?>:</td>
                        <td><?php fpcm\model\view\helper::select('smtp_settings[encr]', $smtpEncryption, $globalConfig['smtp_settings']['encr'], true, true, ($globalConfig['smtp_enabled'] ? false : true)); ?></td>
                    </tr>
               </table>
            </div>

            <?php if ($showTwitter) : ?>
            <div id="tabs-options-twitter">
                <?php include_once __DIR__.'/twitter.php'; ?>
            </div> 
            <?php endif; ?>

            <div id="tabs-options-check"></div>
        </div>

        <div class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
            <div class="fpcm-ui-margin-center">
                <?php fpcm\model\view\helper::saveButton('configSave', 'fpcm-loader'); ?>
                <?php fpcm\model\view\helper::linkButton('#', 'SYSTEM_OPTIONS_SYSCHECK_SUBMITSTATS', 'fpcmsyschecksubmitstats', 'fpcm-hidden'); ?>
            </div>
        </div>

        <?php \fpcm\model\view\helper::pageTokenField(); ?>
        
    </form> 
</div>
<div class="fpcm-ui-center">
    <h3><span class="fa fa-cog"></span> <?php $FPCM_LANG->write('INSTALLER_SYSTEMCONFIG'); ?></h3>
    
    <div class="fpcm-ui-left">
        <table class="fpcm-ui-table fpcm-ui-options">
            <tr>			
                <td><?php $FPCM_LANG->write('GLOBAL_EMAIL'); ?>:</td>
                <td><?php fpcm\model\view\helper::textInput('conf[system_email]', '', ''); ?></td>		
            </tr>			
            <tr>			
                <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_URL'); ?>:</td>
                <td><?php fpcm\model\view\helper::textInput('conf[system_url]', '', 'http://'.$_SERVER['HTTP_HOST'].'/index.php'); ?></td>
            </tr>	
            <tr>			
                <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_LANG'); ?>:</td>
                <td><?php fpcm\model\view\helper::select('conf[system_lang]', array_flip($FPCM_LANG->getLanguages()), $FPCM_LANG->getLangCode(), false, false); ?></td>				 
            </tr>		
            <tr>			
                <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_TIMEZONE'); ?>:</td>
                <td><?php fpcm\model\view\helper::selectGroup('conf[system_timezone]', $timezoneAreas, 'Europe/Berlin'); ?></td>		
            </tr>						
            <tr>			
                <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_DATETIMEMASK'); ?>:</td>
                <td>
                    <?php fpcm\model\view\helper::textInput('conf[system_dtmask]', '', 'd.m.Y H:i:s'); ?>
                    <?php \fpcm\model\view\helper::shortHelpButton($FPCM_LANG->translate('SYSTEM_OPTIONS_DATETIMEMASK_HELP'), '', 'http://us2.php.net/manual/function.date.php', '_blank'); ?>
                </td>
            </tr>			 			 
            <tr>			
                <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_SESSIONLENGHT'); ?>:</td>
                <td><?php fpcm\model\view\helper::select('conf[system_session_length]', $FPCM_LANG->translate('SYSTEM_OPTIONS_SESSIONLENGHT_INTERVALS'), '3600', false, false); ?></td>
            </tr>
            <tr>			
                <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_CACHETIMEOUT'); ?>:</td>
                <td><?php fpcm\model\view\helper::select('conf[system_cache_timeout]', $FPCM_LANG->translate('SYSTEM_OPTIONS_CACHETIMEOUT_INTERVAL'), '86400', false, false); ?></td>
            </tr>                               
            <tr>			
                <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_USEMODE'); ?>:</td>
                <td><?php fpcm\model\view\helper::select('conf[system_mode]', $systemModes, '1', false, false); ?></td>		
            </tr>
            <tr>			
                <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_ANTISPAMQUESTION'); ?>:</td>
                <td><?php fpcm\model\view\helper::textInput('conf[comments_antispam_question]', '', ''); ?></td>		
            </tr>			 
            <tr>			
                <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_ANTISPAMANSWER'); ?>:</td>
                <td><?php fpcm\model\view\helper::textInput('conf[comments_antispam_answer]', '', ''); ?></td>
            </tr>
        </table>
    </div>
</div>
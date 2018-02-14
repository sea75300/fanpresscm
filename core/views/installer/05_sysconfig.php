<div class="fpcm-ui-center">
    <h3><span class="fa fa-cog"></span> <?php $theView->write('INSTALLER_SYSTEMCONFIG'); ?></h3>
    
    <div class="fpcm-ui-left">
        <table class="fpcm-ui-table fpcm-ui-options">
            <tr>			
                <td><?php $theView->write('GLOBAL_EMAIL'); ?>:</td>
                <td><?php fpcm\view\helper::textInput('conf[system_email]', '', ''); ?></td>		
            </tr>			
            <tr>			
                <td><?php $theView->write('SYSTEM_OPTIONS_URL'); ?>:</td>
                <td><?php fpcm\view\helper::textInput('conf[system_url]', '', fpcm\classes\dirs::getRootUrl('index.php')); ?></td>
            </tr>	
            <tr>			
                <td><?php $theView->write('SYSTEM_OPTIONS_LANG'); ?>:</td>
                <td><?php fpcm\view\helper::select('conf[system_lang]', array_flip($theView->getLanguages()), $theView->langCode, false, false); ?></td>				 
            </tr>		
            <tr>			
                <td><?php $theView->write('SYSTEM_OPTIONS_TIMEZONE'); ?>:</td>
                <td><?php fpcm\view\helper::selectGroup('conf[system_timezone]', $timezoneAreas, 'Europe/Berlin'); ?></td>		
            </tr>						
            <tr>			
                <td><?php $theView->write('SYSTEM_OPTIONS_DATETIMEMASK'); ?>:</td>
                <td>
                    <?php fpcm\view\helper::textInput('conf[system_dtmask]', '', 'd.m.Y H:i:s'); ?>                    
                    <?php $theView->shorthelpButton('dtmask')->setText('SYSTEM_OPTIONS_DATETIMEMASK_HELP')->setUrl('http://php.net/manual/function.date.php'); ?>
                </td>
            </tr>			 			 
            <tr>			
                <td><?php $theView->write('SYSTEM_OPTIONS_SESSIONLENGHT'); ?>:</td>
                <td><?php fpcm\view\helper::select('conf[system_session_length]', $theView->translate('SYSTEM_OPTIONS_SESSIONLENGHT_INTERVALS'), '3600', false, false); ?></td>
            </tr>
            <tr>			
                <td><?php $theView->write('SYSTEM_OPTIONS_CACHETIMEOUT'); ?>:</td>
                <td><?php fpcm\view\helper::select('conf[system_cache_timeout]', $theView->translate('SYSTEM_OPTIONS_CACHETIMEOUT_INTERVAL'), FPCM_CACHE_DEFAULT_TIMEOUT, false, false); ?></td>
            </tr>                               
            <tr>			
                <td><?php $theView->write('SYSTEM_OPTIONS_USEMODE'); ?>:</td>
                <td><?php fpcm\view\helper::select('conf[system_mode]', $systemModes, '1', false, false); ?></td>		
            </tr>
            <tr>			
                <td><?php $theView->write('SYSTEM_OPTIONS_ANTISPAMQUESTION'); ?>:</td>
                <td><?php fpcm\view\helper::textInput('conf[comments_antispam_question]', '', ''); ?></td>		
            </tr>			 
            <tr>			
                <td><?php $theView->write('SYSTEM_OPTIONS_ANTISPAMANSWER'); ?>:</td>
                <td><?php fpcm\view\helper::textInput('conf[comments_antispam_answer]', '', ''); ?></td>
            </tr>
        </table>
    </div>
</div>
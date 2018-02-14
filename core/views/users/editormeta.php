    <table class="fpcm-ui-table fpcm-ui-options">
        <tr>
            <td><?php $theView->write('SYSTEM_OPTIONS_TIMEZONE'); ?>:</td>
            <td>
                <?php fpcm\view\helper::selectGroup('usermeta[system_timezone]', $timezoneAreas, $author->getUserMeta('system_timezone')); ?>	
            </td>
        </tr>
        <tr>
            <td><?php $theView->write('SYSTEM_OPTIONS_LANG'); ?>:</td>
            <td>
                <?php \fpcm\view\helper::select('usermeta[system_lang]', $languages, $author->getUserMeta('system_lang'), false, false); ?>
            </td>
        </tr>                
        <tr>
            <td><?php $theView->write('SYSTEM_OPTIONS_DATETIMEMASK'); ?>:</td>
            <td>
                <?php \fpcm\view\helper::textInput('usermeta[system_dtmask]', '', $author->getUserMeta('system_dtmask')); ?>
                <?php $theView->shorthelpButton('dtmask')->setText('SYSTEM_OPTIONS_DATETIMEMASK_HELP')->setUrl('http://php.net/manual/function.date.php'); ?>
            </td>
        </tr>
        <tr>
            <td><?php $theView->write('SYSTEM_OPTIONS_ACPARTICLES_LIMIT'); ?>:</td>
            <td>
                <?php fpcm\view\helper::select('usermeta[articles_acp_limit]', $articleLimitList, $author->getUserMeta('articles_acp_limit'), false, false); ?>
            </td>
        </tr>
        <tr>			
            <td><?php $theView->write('SYSTEM_OPTIONS_NEWS_EDITOR_FONTSIZE'); ?>:</td>
            <td><?php fpcm\view\helper::select('usermeta[system_editor_fontsize]', $defaultFontsizes, $author->getUserMeta('system_editor_fontsize'), false, false); ?></td>
        </tr>
        <tr>			
            <td><?php $theView->write('SYSTEM_OPTIONS_NEWS_NEWUPLOADER'); ?>:</td>
            <td><?php $theView->boolSelect('usermeta[file_uploader_new]')->setSelected($author->getUserMeta('file_uploader_new')); ?></td>
        </tr>
    </table>
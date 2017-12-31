<table class="fpcm-ui-table fpcm-ui-options fpcm-ui-options-twitter">
    <?php if ($twitterIsActive) : ?>
    <tr>
        <th></th>
        <th><span class="fa fa-check-square fa-align-right"></span> <?php $FPCM_LANG->write('SYSTEM_OPTIONS_TWITTER_ACTIVE', array('{{screenname}}' => $twitterScreenName)); ?></th>
    </tr>
    <tr class="fpcm-td-spacer"><td colspan="2"></td></tr>
    <?php endif; ?>
    <tr>
        <td></td>
        <td>
            <?php if (!$globalConfig['twitter_data']['consumer_key'] || !$globalConfig['twitter_data']['consumer_secret'] || !$twitterIsActive) : ?>
                <?php \fpcm\model\view\helper::linkButton('https://apps.twitter.com/', 'SYSTEM_OPTIONS_TWITTER_CONNECT', '', '', '_blank'); ?>
            <?php endif; ?>
            
            <?php if ($globalConfig['twitter_data']['user_token'] && $globalConfig['twitter_data']['user_secret'] && $twitterIsActive) : ?>
                <?php \fpcm\model\view\helper::submitButton('twitterDisconnect', 'SYSTEM_OPTIONS_TWITTER_DISCONNECT', 'fpcm-ui-actions-genreal'); ?>
            <?php endif; ?>

            <?php \fpcm\model\view\helper::shortHelpButton($FPCM_LANG->translate('HL_HELP'), '', \fpcm\classes\baseconfig::$rootPath.'index.php?module=system/help&ref='.base64_encode('system_options_twitter_connection'), '_blank'); ?>
        </td>
    </tr>
    <tr>			
        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_TWITTER_EVENTS'); ?>:</td>
        <td>
            <div class="fpcm-ui-buttonset">
                <?php fpcm\model\view\helper::checkbox('twitter_events[create]', '', 1, 'SYSTEM_OPTIONS_TWITTER_EVENTCREATE', 'twitter_events_create', $globalConfig['twitter_events']['create']); ?>
                <?php fpcm\model\view\helper::checkbox('twitter_events[update]', '', 1, 'SYSTEM_OPTIONS_TWITTER_EVENTUPDATE', 'twitter_events_update', $globalConfig['twitter_events']['update']); ?>                
            </div>        
        </td>
    </tr>
    <tr>	
        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_TWITTER_CONSUMER_KEY'); ?>:</td>
        <td><?php fpcm\model\view\helper::textInput('twitter_data[consumer_key]', '', $globalConfig['twitter_data']['consumer_key']); ?></td>
    </tr>
    <tr>			
        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_TWITTER_CONSUMER_SECRET'); ?>:</td>
        <td><?php fpcm\model\view\helper::textInput('twitter_data[consumer_secret]', '', $globalConfig['twitter_data']['consumer_secret']); ?></td>
    </tr>
    <tr>			
        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_TWITTER_USER_TOKEN'); ?>:</td>
        <td><?php fpcm\model\view\helper::textInput('twitter_data[user_token]', '', $globalConfig['twitter_data']['user_token']); ?></td>
    </tr>
    <tr>			
        <td><?php $FPCM_LANG->write('SYSTEM_OPTIONS_TWITTER_USER_SECRET'); ?>:</td>
        <td><?php fpcm\model\view\helper::textInput('twitter_data[user_secret]', '', $globalConfig['twitter_data']['user_secret']); ?></td>
    </tr>
</table>
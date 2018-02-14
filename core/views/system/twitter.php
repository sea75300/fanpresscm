<table class="fpcm-ui-table fpcm-ui-options fpcm-ui-options-twitter">
    <?php if ($twitterIsActive) : ?>
    <tr>
        <th></th>
        <th><span class="fa fa-check-square fa-align-right"></span> <?php $theView->write('SYSTEM_OPTIONS_TWITTER_ACTIVE', array('{{screenname}}' => $twitterScreenName)); ?></th>
    </tr>
    <tr class="fpcm-td-spacer"><td colspan="2"></td></tr>
    <?php endif; ?>
    <tr>
        <td></td>
        <td>
            <?php if (!$globalConfig['twitter_data']['consumer_key'] || !$globalConfig['twitter_data']['consumer_secret'] || !$twitterIsActive) : ?>
                <?php \fpcm\view\helper::linkButton('https://apps.twitter.com/', 'SYSTEM_OPTIONS_TWITTER_CONNECT', '', '', '_blank'); ?>
            <?php endif; ?>
            
            <?php if ($globalConfig['twitter_data']['user_token'] && $globalConfig['twitter_data']['user_secret'] && $twitterIsActive) : ?>
                <?php \fpcm\view\helper::submitButton('twitterDisconnect', 'SYSTEM_OPTIONS_TWITTER_DISCONNECT', 'fpcm-ui-actions-genreal'); ?>
            <?php endif; ?>

            <?php $theView->shorthelpButton('dtmask')->setText('HL_HELP')->setUrl(\fpcm\classes\tools::getFullControllerLink('system/help', ['ref' => base64_encode('system_options_twitter_connection')])); ?>
        </td>
    </tr>
    <tr>			
        <td><?php $theView->write('SYSTEM_OPTIONS_TWITTER_EVENTS'); ?>:</td>
        <td>
            <div class="fpcm-ui-toolbar">
                <?php fpcm\view\helper::checkbox('twitter_events[create]', '', 1, 'SYSTEM_OPTIONS_TWITTER_EVENTCREATE', 'twitter_events_create', $globalConfig['twitter_events']['create']); ?>
                <?php fpcm\view\helper::checkbox('twitter_events[update]', '', 1, 'SYSTEM_OPTIONS_TWITTER_EVENTUPDATE', 'twitter_events_update', $globalConfig['twitter_events']['update']); ?>                
            </div>        
        </td>
    </tr>
    <tr>	
        <td><?php $theView->write('SYSTEM_OPTIONS_TWITTER_CONSUMER_KEY'); ?>:</td>
        <td><?php fpcm\view\helper::textInput('twitter_data[consumer_key]', '', $globalConfig['twitter_data']['consumer_key']); ?></td>
    </tr>
    <tr>			
        <td><?php $theView->write('SYSTEM_OPTIONS_TWITTER_CONSUMER_SECRET'); ?>:</td>
        <td><?php fpcm\view\helper::textInput('twitter_data[consumer_secret]', '', $globalConfig['twitter_data']['consumer_secret']); ?></td>
    </tr>
    <tr>			
        <td><?php $theView->write('SYSTEM_OPTIONS_TWITTER_USER_TOKEN'); ?>:</td>
        <td><?php fpcm\view\helper::textInput('twitter_data[user_token]', '', $globalConfig['twitter_data']['user_token']); ?></td>
    </tr>
    <tr>			
        <td><?php $theView->write('SYSTEM_OPTIONS_TWITTER_USER_SECRET'); ?>:</td>
        <td><?php fpcm\view\helper::textInput('twitter_data[user_secret]', '', $globalConfig['twitter_data']['user_secret']); ?></td>
    </tr>
</table>
<?php /* @var $theView \fpcm\view\viewVars */ ?>
<table class="fpcm-ui-table fpcm-ui-options fpcm-ui-options-twitter">
    <?php if ($twitterIsActive) : ?>
    <tr>
        <th></th>
        <th><span class="fa fa-check-square fa-align-right"></span> <?php $theView->write('SYSTEM_OPTIONS_TWITTER_ACTIVE', ['{{screenname}}' => $twitterScreenName]); ?></th>
    </tr>
    <tr class="fpcm-td-spacer"><td colspan="2"></td></tr>
    <?php endif; ?>
    <tr>
        <td></td>
        <td>
            <?php if (!$globalConfig['twitter_data']['consumer_key'] || !$globalConfig['twitter_data']['consumer_secret'] || !$twitterIsActive) : ?>
                <?php $theView->linkButton('twitterConnect')->setText('SYSTEM_OPTIONS_TWITTER_CONNECT')->setUrl('https://apps.twitter.com/')->setTarget('_blank'); ?>
            <?php endif; ?>
            
            <?php if ($globalConfig['twitter_data']['user_token'] && $globalConfig['twitter_data']['user_secret'] && $twitterIsActive) : ?>
                <?php $theView->submitButton('twitterDisconnect')->setText('SYSTEM_OPTIONS_TWITTER_DISCONNECT')->setClass('fpcm-ui-button-confirm'); ?>
            <?php endif; ?>

            <?php $theView->shorthelpButton('dtmask')->setText('HL_HELP')->setUrl(\fpcm\classes\tools::getFullControllerLink('system/help', ['ref' => base64_encode('system_options_twitter_connection')])); ?>
        </td>
    </tr>
    <tr>			
        <td><?php $theView->write('SYSTEM_OPTIONS_TWITTER_EVENTS'); ?>:</td>
        <td>
            <div class="fpcm-ui-controlgroup">
                <?php $theView->checkbox('twitter_events[create]', 'twitter_events_create')->setText('SYSTEM_OPTIONS_TWITTER_EVENTCREATE')->setSelected($globalConfig['twitter_events']['create']); ?>
                <?php $theView->checkbox('twitter_events[update]', 'twitter_events_update')->setText('SYSTEM_OPTIONS_TWITTER_EVENTUPDATE')->setSelected($globalConfig['twitter_events']['update']); ?>
            </div>        
        </td>
    </tr>
    <tr>	
        <td><?php $theView->write('SYSTEM_OPTIONS_TWITTER_CONSUMER_KEY'); ?>:</td>
        <td><?php $theView->textInput('twitter_data[consumer_key]')->setValue($globalConfig['twitter_data']['consumer_key']); ?></td>
    </tr>
    <tr>			
        <td><?php $theView->write('SYSTEM_OPTIONS_TWITTER_CONSUMER_SECRET'); ?>:</td>
        <td><?php $theView->textInput('twitter_data[consumer_secret]')->setValue($globalConfig['twitter_data']['consumer_secret']); ?></td>
    </tr>
    <tr>			
        <td><?php $theView->write('SYSTEM_OPTIONS_TWITTER_USER_TOKEN'); ?>:</td>
        <td><?php $theView->textInput('twitter_data[user_token]')->setValue($globalConfig['twitter_data']['user_token']); ?></td>
    </tr>
    <tr>			
        <td><?php $theView->write('SYSTEM_OPTIONS_TWITTER_USER_SECRET'); ?>:</td>
        <td><?php $theView->textInput('twitter_data[user_secret]')->setValue($globalConfig['twitter_data']['user_secret']); ?></td>
    </tr>
</table>
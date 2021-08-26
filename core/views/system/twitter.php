<?php /* @var $theView \fpcm\view\viewVars */ ?>
<fieldset class="mb-2">
    <legend><?php $theView->write('SYSTEM_OPTIONS_TWITTER_CONSTATE'); ?></legend>
    
    <div class="row my-2">
        <div class="col-auto">
        <?php if ($twitterIsActive) : ?>
            <?php $theView->icon('twitter', 'fab')->setStack('check fpcm-ui-editor-metainfo opacity-75')->setSize('lg')->setStackTop(true); ?>
            <?php $theView->write('SYSTEM_OPTIONS_TWITTER_ACTIVE', ['{{screenname}}' => $twitterScreenName]); ?>
        <?php endif; ?>
        </div>
        <div class="col-auto align-self-center">
            <div class="row g-0">
                <?php if (!$globalConfig->twitter_data->consumer_key || !$globalConfig->twitter_data->consumer_secret || !$twitterIsActive) : ?>
                    <?php $theView->linkButton('twitterConnect')->setText('SYSTEM_OPTIONS_TWITTER_CONNECT')->setUrl('https://apps.twitter.com/')->setTarget('_blank')->setIcon('twitter', 'fab'); ?>
                <?php elseif ($globalConfig->twitter_data->user_token && $globalConfig->twitter_data->user_secret && $twitterIsActive) : ?>
                    <?php $theView->submitButton('twitterDisconnect')->setText('SYSTEM_OPTIONS_TWITTER_DISCONNECT')->setClass('fpcm-ui-button-confirm')->setIcon('trash'); ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-auto align-self-center mx-3">
            <?php $theView->shorthelpButton('twittercon')->setText('HL_HELP')->setUrl('#')->setData(['ref' => urlencode(base64_encode('SYSTEM_OPTIONS_TWITTER_CONNECTION'))])->setClass('ui-help-dialog'); ?>
        </div>
    </div>
</fieldset>

<fieldset class="mb-2">
    <legend><?php $theView->write('SYSTEM_OPTIONS_TWITTER_EVENTS'); ?></legend>

    <div class="row my-2">
        <div class="col-auto">
            <?php $theView->checkbox('twitter_events[create]', 'twitter_events_create')
                    ->setText('SYSTEM_OPTIONS_TWITTER_EVENTCREATE')
                    ->setSelected($globalConfig->twitter_events->create)
                    ->setSwitch(true); ?>
        </div>
        <div class="col-auto">
            <?php $theView->checkbox('twitter_events[update]', 'twitter_events_update')
                    ->setText('SYSTEM_OPTIONS_TWITTER_EVENTUPDATE')
                    ->setSelected($globalConfig->twitter_events->update)
                    ->setSwitch(true); ?>
        </div>
    </div>
</fieldset>

<fieldset class="mb-2">
    <legend><?php $theView->write('SYSTEM_OPTIONS_TWITTER_CREDENTIALS'); ?></legend>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->textInput('twitter_data[consumer_key]')
            ->setValue($globalConfig->twitter_data->consumer_key)
            ->setText('SYSTEM_OPTIONS_TWITTER_CONSUMER_KEY'); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->textInput('twitter_data[consumer_secret]')
            ->setValue($globalConfig->twitter_data->consumer_secret)
            ->setText('SYSTEM_OPTIONS_TWITTER_CONSUMER_SECRET'); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->textInput('twitter_data[user_token]')
            ->setValue($globalConfig->twitter_data->user_token)
            ->setText('SYSTEM_OPTIONS_TWITTER_USER_TOKEN'); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->textInput('twitter_data[user_secret]')
            ->setValue($globalConfig->twitter_data->user_secret)
            ->setText('SYSTEM_OPTIONS_TWITTER_USER_SECRET'); ?>
        </div>
    </div>
</fieldset>
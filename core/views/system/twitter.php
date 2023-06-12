<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row py-2 row-cols-1 row-cols-md-2">
    <div class="col align-self-center">
    <?php if ($twitterIsActive) : ?>
        <?php $theView->alert('success')->setText('SYSTEM_OPTIONS_TWITTER_ACTIVE', ['{{screenname}}' => $twitterScreenName])->setIcon('twitter', 'fab')->setClass('mb-1 mb-md-0'); ?>
    <?php elseif (defined('FPCM_TWITTER_DSIABLE_API') && FPCM_TWITTER_DSIABLE_API) : ?>
        <?php $theView->alert('dark')
                ->setText('Twitter API connector has been disabled, see https://www.heise.de/news/Twitter-macht-API-Zugang-kostenpflichtig-mit-einer-Woche-Vorlaufzeit-7480995.html.')
                ->setIcon('ban text-danger')
                ->setSize('lg')
                ->setClass('mb-1 mb-md-0'); ?>
    <?php else : ?>
        <?php $theView->alert('secondary')->setText('SYSTEM_OPTIONS_TWITTER_CONSTATE')->setIcon('twitter', 'fab')->setClass('mb-1 mb-md-0'); ?>
    <?php endif; ?>
    </div>
    <div class="col align-self-center">

        <?php if (!$globalConfig->twitter_data->consumer_key || !$globalConfig->twitter_data->consumer_secret || !$twitterIsActive) : ?>
            <?php $theView->linkButton('twitterConnect')->setText('SYSTEM_OPTIONS_TWITTER_CONNECT')->setUrl(fpcm\classes\tools::getControllerLink('system/twitter'))->setTarget('_blank')->setIcon('twitter', 'fab'); ?>
        <?php elseif ($globalConfig->twitter_data->user_token && $globalConfig->twitter_data->user_secret && $twitterIsActive) : ?>
            <?php $theView->submitButton('twitterDisconnect')->setText('SYSTEM_OPTIONS_TWITTER_DISCONNECT')->setClass('fpcm ui-button-confirm')->setIcon('trash'); ?>
        <?php endif; ?>

    </div>
</div>

<div class="row my-2 row-cols-1 row-cols-md-2">
    <div class="col">
        <legend class="rounded-top"><?php $theView->write('SYSTEM_OPTIONS_TWITTER_CREDENTIALS'); ?></legend>

            <?php $theView->textInput('twitter_data[consumer_key]')
                ->setValue($globalConfig->twitter_data->consumer_key)
                ->setText('SYSTEM_OPTIONS_TWITTER_CONSUMER_KEY')
                ->setLabelTypeFloat(); ?>

            <?php $theView->textInput('twitter_data[consumer_secret]')
                ->setValue($globalConfig->twitter_data->consumer_secret)
                ->setText('SYSTEM_OPTIONS_TWITTER_CONSUMER_SECRET')
                ->setLabelTypeFloat(); ?>

            <?php $theView->textInput('twitter_data[user_token]')
                ->setValue($globalConfig->twitter_data->user_token)
                ->setText('SYSTEM_OPTIONS_TWITTER_USER_TOKEN')
                ->setLabelTypeFloat(); ?>

            <?php $theView->textInput('twitter_data[user_secret]')
                ->setValue($globalConfig->twitter_data->user_secret)
                ->setText('SYSTEM_OPTIONS_TWITTER_USER_SECRET')
                ->setLabelTypeFloat(); ?>
    </div>
    <div class="col">
        
        <div class="list-group">
            <div class="list-group-item bg-secondary text-white"><?php $theView->icon('twitter fab'); ?> <?php $theView->write('SYSTEM_OPTIONS_TWITTER_EVENTS'); ?></div>
            
            <div class="list-group-item">
                <?php $theView->checkbox('twitter_events[create]', 'twitter_events_create')
                        ->setText('SYSTEM_OPTIONS_TWITTER_EVENTCREATE')
                        ->setSelected($globalConfig->twitter_events->create)
                        ->setSwitch(true); ?>
            </div>
            <div class="list-group-item">
                <?php $theView->checkbox('twitter_events[update]', 'twitter_events_update')
                        ->setText('SYSTEM_OPTIONS_TWITTER_EVENTUPDATE')
                        ->setSelected($globalConfig->twitter_events->update)
                        ->setSwitch(true); ?>
            </div>
        </div>
        
        <div class="list-group mt-3">
            <div class="list-group-item bg-secondary text-white"><?php $theView->icon('timeline'); ?> <?php $theView->write('GLOBAL_EXTENDED'); ?></div>
            <div class="list-group-item">
                <?php $theView->checkbox('twitter_events[timeline]', 'twitter_events_timeline')
                        ->setText('SYSTEM_OPTIONS_TWITTER_EVENTTIMELINE')
                        ->setSelected($globalConfig->twitter_events->timeline)
                        ->setSwitch(true); ?>
            </div>
        </div>
    </div>
</div>


<fieldset class="mb-2">

</fieldset>

<fieldset class="mb-2">

</fieldset>
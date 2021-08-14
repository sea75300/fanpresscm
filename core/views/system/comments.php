<?php /* @var $theView \fpcm\view\viewVars */ ?>
<fieldset class="mb-2">
    <legend><?php $theView->write('COMMMENT_HEADLINE'); ?></legend>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->boolSelect('system_comments_enabled')->setText('SYSTEM_OPTIONS_COMMENT_ENABLED_GLOBAL')->setSelected($globalConfig->system_comments_enabled); ?>		
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->boolSelect('comments_privacy_optin')->setText('SYSTEM_OPTIONS_COMMENT_PRIVACYOPTIN')->setSelected($globalConfig->comments_privacy_optin); ?>		
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->select('comments_notify')
                ->setOptions($notify)
                ->setText('SYSTEM_OPTIONS_COMMENT_NOTIFY')
                ->setSelected($globalConfig->comments_notify)
                ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->select('comments_template_active')
                ->setOptions($commentTemplates)
                ->setText('SYSTEM_OPTIONS_ACTIVECOMMENTTEMPLATE')
                ->setSelected($globalConfig->comments_template_active)
                ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->select('comments_flood')
                ->setOptions($theView->translate('SYSTEM_OPTIONS_FLOODPROTECTION_INTERVALS'))
                ->setText('SYSTEM_OPTIONS_FLOODPROTECTION')
                ->setSelected($globalConfig->comments_flood)
                ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->boolSelect('comments_email_optional')->setText('SYSTEM_OPTIONS_COMMENTEMAIL')->setSelected($globalConfig->comments_email_optional); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->boolSelect('comments_confirm')->setText('SYSTEM_OPTIONS_COMMENT_APPROVE')->setSelected($globalConfig->comments_confirm); ?>		
        </div>
    </div>
</fieldset>

<fieldset class="mb-2">
    <legend><?php $theView->write('SYSTEM_OPTIONS_CAPTCHASETTING'); ?></legend>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->textInput('comments_antispam_question')
            ->setValue($globalConfig->comments_antispam_question)
            ->setText('SYSTEM_OPTIONS_ANTISPAMQUESTION'); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->textInput('comments_antispam_answer')
            ->setValue($globalConfig->comments_antispam_answer)
            ->setText('SYSTEM_OPTIONS_ANTISPAMANSWER'); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->numberInput('comments_markspam_commentcount')
                ->setText('SYSTEM_OPTIONS_COMMENT_MARKSPAM_PASTCHECK')
                ->setValue($globalConfig->comments_markspam_commentcount)
                ->setMaxlenght(5); ?>
        </div>
    </div>
</fieldset>
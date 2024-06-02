<?php /* @var $theView \fpcm\view\viewVars */ ?>
<fieldset class="mb-2">
    <legend><?php $theView->write('COMMMENT_HEADLINE'); ?></legend>

    <div class="row g-0 my-2">
        <div class="col-12 col-md-8">
            <div class="row row-cols-1 row-cols-xl-2">
                <div class="col">
                    <?php $theView->boolSelect('system_comments_enabled')->setText('SYSTEM_OPTIONS_COMMENT_ENABLED_GLOBAL')->setSelected($globalConfig->system_comments_enabled); ?>
                </div>

                <div class="col">
                    <?php $theView->boolSelect('comments_default_active')->setText('SYSTEM_OPTIONS_COMMENT_DEFAULT_ACTIVE')->setSelected($globalConfig->comments_default_active); ?>
                </div>
            </div>            
        </div>
    </div>       

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->select('comments_notify')
                ->setOptions($notify)
                ->setText('SYSTEM_OPTIONS_COMMENT_NOTIFY')
                ->setSelected($globalConfig->comments_notify); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
            <div class="row g-0">
                <div class="col flex-grow-1">
                <?php $theView->select('comments_template_active')
                    ->setOptions($commentTemplates)
                    ->setText('SYSTEM_OPTIONS_ACTIVECOMMENTTEMPLATE')
                    ->setSelected($globalConfig->comments_template_active); ?>
                </div>
                <div class="col-auto align-self-center mx-3 mb-3">
                    <?php $theView->linkButton('system_url_link')
                        ->setText('GLOBAL_EDIT')
                        ->setUrl(sprintf('%s&module=templates/templates&rg=2', $theView->basePath))
                        ->setIcon('file-pen')
                        ->setIconOnly(); ?>
                </div>
            </div>
        </div>
    </div>    

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->select('comments_flood')
                ->setOptions($theView->translate('SYSTEM_OPTIONS_FLOODPROTECTION_INTERVALS'))
                ->setText('SYSTEM_OPTIONS_FLOODPROTECTION')
                ->setSelected($globalConfig->comments_flood); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->boolSelect('comments_privacy_optin')->setText('SYSTEM_OPTIONS_COMMENT_PRIVACYOPTIN')->setSelected($globalConfig->comments_privacy_optin); ?>		
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
                ->setValue($globalConfig->comments_markspam_commentcount); ?>
        </div>
    </div>
</fieldset>
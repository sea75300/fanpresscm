<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
        <?php $theView->write('SYSTEM_OPTIONS_TIMEZONE'); ?>:        
    </div>
    <div class="col-sm-12 col-md-8 fpcm-ui-padding-none-lr">
        <?php $theView->select('usermeta[system_timezone]')->setOptions($timezoneAreas)->setSelected($author->getUserMeta('system_timezone'))->setOptGroup(true); ?>
    </div>
</div>
<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
        <?php $theView->write('SYSTEM_OPTIONS_LANG'); ?>:        
    </div>
    <div class="col-sm-12 col-md-8 fpcm-ui-padding-none-lr">
        <?php $theView->select('usermeta[system_lang]')->setOptions($languages)->setSelected($author->getUserMeta('system_lang'))->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
    </div>
</div>
<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">        
        <?php $theView->write('SYSTEM_OPTIONS_DATETIMEMASK'); ?>:
    </div>
    <div class="align-self-center col-sm-11 col-md-4 fpcm-ui-padding-none-lr">
        <?php $theView->textInput('system_dtmask')->setValue($author->getUserMeta('system_dtmask')); ?>
    </div>
    <div class="align-self-center col-sm-1 col-md-auto fpcm-ui-padding-md-lr">
        <?php $theView->shorthelpButton('dtmask')->setText('SYSTEM_OPTIONS_DATETIMEMASK_HELP')->setUrl('http://php.net/manual/function.date.php'); ?>
    </div>
</div>
<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
        <?php $theView->write('SYSTEM_OPTIONS_ACPARTICLES_LIMIT'); ?>:
    </div>
    <div class="col-sm-12 col-md-8 fpcm-ui-padding-none-lr">
        <?php $theView->select('usermeta[articles_acp_limit]')
                ->setOptions($articleLimitList)
                ->setSelected($author->getUserMeta('articles_acp_limit'))
                ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
    </div>
</div>
<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">        
        <?php $theView->write('SYSTEM_OPTIONS_NEWS_EDITOR_FONTSIZE'); ?>:
    </div>
    <div class="col-sm-12 col-md-8 fpcm-ui-padding-none-lr">
        <?php $theView->select('usermeta[system_editor_fontsize]')
                ->setOptions($defaultFontsizes)
                ->setSelected($author->getUserMeta('system_editor_fontsize'))
                ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
    </div>
</div>
<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">        
        <?php $theView->write('SYSTEM_OPTIONS_NEWS_NEWUPLOADER'); ?>:
    </div>
    <div class="col-sm-12 col-md-8 fpcm-ui-padding-none-lr">
        <?php $theView->boolSelect('usermeta[file_uploader_new]')->setSelected($author->getUserMeta('file_uploader_new')); ?>
    </div>
</div>
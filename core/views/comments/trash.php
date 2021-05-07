<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div class="fpcm-ui-tabs-general ui-tabs ui-corner-all ui-widget ui-widget-content">
        <ul class="ui-tabs-nav ui-corner-all ui-helper-reset ui-helper-clearfix ui-widget-header">
            <?php $theView->tabItem('tabs-comments-active')->setText('ARTICLES_TRASH')->setUrl('#tabs-comments-active'); ?>
        </ul>            

        <div id="tabs-comments-active" class="fpcm tabs-register ui-tabs-panel ui-corner-bottom ui-widget-content">

            <div id="fpcm-dataview-commenttrash-spinner" class="row g-0 align-self-center fpcm-ui-inline-loader fpcm ui-background-white-50p">
                <div class="col-12 fpcm-ui-center align-self-center">
                    <?php $theView->icon('spinner fa-inverse')->setSpinner('pulse')->setStack('circle')->setSize('2x'); ?>
                </div>
            </div>             

            <div id="fpcm-dataview-commenttrash"></div>
        </div>
    </div>
</div>
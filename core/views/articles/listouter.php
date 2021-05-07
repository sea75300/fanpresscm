<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div class="fpcm-ui-tabs-general ui-tabs ui-corner-all ui-widget ui-widget-content">
        <ul class="ui-tabs-nav ui-corner-all ui-helper-reset ui-helper-clearfix ui-widget-header">
            <?php $theView->tabItem('tabs-article-list')->setText($tabHeadline)->setUrl('#tabs-article-list'); ?>
        </ul>

        <div id="tabs-article-list">

            <div id="fpcm-dataview-articlelist-spinner" class="row g-0 align-self-center fpcm-ui-inline-loader fpcm ui-background-white-50p">
                <div class="col-12 fpcm-ui-center align-self-center">
                    <?php $theView->icon('spinner fa-inverse')->setSpinner('pulse')->setStack('circle')->setSize('2x'); ?>
                </div>
            </div>            
            
            <div id="fpcm-dataview-articlelist"></div>
        </div>
    </div>

    <?php if ($includeSearchForm) : ?><?php include $theView->getIncludePath('articles/searchform.php'); ?><?php endif; ?>
    <?php if ($includeMassEditForm && $theView->permissions->editArticlesMass()) : ?><?php include $theView->getIncludePath('articles/massedit.php'); ?><?php endif; ?>
</div>
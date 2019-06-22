<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div class="fpcm-ui-tabs-general">
        <ul class="fpcm-tabs-articles-headers">
            <li><a href="#tabs-article-list"><?php $theView->write($tabHeadline); ?></a></li>
        </ul>

        <div id="tabs-article-list">

            <div id="fpcm-dataview-articlelist-spinner" class="row no-gutters align-self-center fpcm-ui-inline-loader fpcm-ui-background-white-50p">
                <div class="col-12 fpcm-ui-center align-self-center">
                    <?php $theView->icon('spinner fa-inverse')->setSpinner('pulse')->setStack('circle')->setSize('2x'); ?>
                </div>
            </div>            
            
            <div id="fpcm-dataview-articlelist"></div>
        </div>
    </div>

    <?php include $theView->getIncludePath('articles/searchform.php'); ?>
    <?php if ($canEdit && $permMassEdit) : ?><?php include $theView->getIncludePath('articles/massedit.php'); ?><?php endif; ?>
</div>
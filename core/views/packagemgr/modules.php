<div class="fpcm-content-wrapper">
    <h1><span class="fa fa-refresh" id="fpcm-ui-headspinner"></span> <?php $theView->lang->write($modeHeadline); ?></h1>
    <div class="fpcm-tabs-general">
        <ul>
            <li><a href="#tabs-updater-general"><?php $theView->lang->write($modeHeadline); ?></a></li>
        </ul>

        <div id="tabs-updater-general">
        <?php if (isset($nokeys)) : ?>
            <?php $theView->lang->write('GLOBAL_NOTFOUND2'); ?>
        <?php else : ?>
            <?php fpcm\view\helper::progressBar('fpcm-updater-progressbar'); ?>
            <div class="fpcm-updater-list"></div>
        <?php endif; ?>
        </div>
    </div>
</div>

<div class="<?php \fpcm\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
    <div class="fpcm-ui-margin-center">
        <?php fpcm\view\helper::linkButton($theView->basePath.'modules/list', 'MODULES_LIST_BACKTOLIST', '', 'fpcm-ui-pager-buttons fpcm-back-button fpcm-loader'); ?>
    </div>
</div>
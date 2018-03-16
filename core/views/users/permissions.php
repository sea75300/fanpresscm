<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-ui-inner-wrapper">

    <div class="fpcm-tabs-general" id="fpcm-tabs-permissions">
        <ul>
            <li><a href="#tabs-permissions-group"><?php $theView->write('HL_OPTIONS_PERMISSIONS'); ?>: <?php print $rollname; ?></a></li>                
        </ul>

        <div id="tabs-permissions-group">
            <div class="row fpcm-ui-permissions-container">
                <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb">
                    <h2><?php $theView->write('PERMISSION_ARTICLES'); ?></h2>
                    <?php foreach ($permissions['article'] as $key => $value) : ?>
                        <?php $theView->checkbox("permissions[article][{$key}]", "{$rollId}_article_{$key}")->setText('PERMISSION_ARTICLE_'.strtoupper($key))->setSelected($value); ?>
                    <?php endforeach; ?>                
                </div>

                <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb">
                    <h2><?php $theView->write('PERMISSION_COMMENTS'); ?></h2>
                    <?php foreach ($permissions['comment'] as $key => $value) : ?>
                        <?php $theView->checkbox("permissions[comment][{$key}]", "{$rollId}_comment_{$key}")->setText('PERMISSION_COMMENT_'.strtoupper($key))->setSelected($value); ?>
                    <?php endforeach; ?>
                </div>                

                <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb">
                    <h2><?php $theView->write('PERMISSION_UPLOADS'); ?></h2>
                    <?php foreach ($permissions['uploads'] as $key => $value) : ?>
                        <?php $theView->checkbox("permissions[uploads][{$key}]", "{$rollId}_uploads_{$key}")->setText('PERMISSION_UPLOADS_'.strtoupper($key))->setSelected($value); ?>
                    <?php endforeach; ?>
                </div>                

                <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb">
                    <h2><?php $theView->write('PERMISSION_SYSTEM'); ?></h2>
                    <?php foreach ($permissions['system'] as $key => $value) : ?>
                        <?php $readOnly = ($key == 'permissions' && $rollId == 1) ? true : false; ?>
                        <?php $theView->checkbox("permissions[system][{$key}]", "{$rollId}_system_{$key}")->setText('PERMISSION_SYSTEM_'.strtoupper($key))->setSelected($value)->setReadonly($readOnly); ?>
                        <?php if ($readOnly) : ?><?php $theView->hiddenInput("permissions[system][{$key}]"); ?><?php endif; ?>
                    <?php endforeach; ?>
                </div>                

                <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb">
                    <h2><?php $theView->write('PERMISSION_MODULES'); ?></h2>
                    <?php foreach ($permissions['modules'] as $key => $value) : ?>
                        <?php $theView->checkbox("permissions[modules][{$key}]", "{$rollId}_modules_{$key}")->setText('PERMISSION_MODULES_'.strtoupper($key))->setSelected($value); ?>
                    <?php endforeach; ?>
                </div>                
            </div>
        </div>
    </div>

    <?php $theView->saveButton('permissionsSave')->setClass('fpcm-ui-hidden') ?>
    
</div>
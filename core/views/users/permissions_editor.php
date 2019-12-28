<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row fpcm-ui-permissions-container">
    <div class="col-sm-12 col-md-6">

        <div class="row no-gutters fpcm-ui-margin-lg-bottom">
            <div class="col-12">
                <fieldset>
                    <legend><?php $theView->write('PERMISSION_ARTICLES'); ?></legend>
                    <?php foreach ($permissions['article'] as $key => $value) : ?>
                        <?php $theView->checkbox("permissions[article][{$key}]", "{$rollId}_article_{$key}")->setText('PERMISSION_ARTICLE_' . strtoupper($key))->setSelected($value)->setLabelClass('fpcm-ui-align-left'); ?>
                    <?php endforeach; ?>
                </fieldset>
            </div>
        </div>

        <div class="row no-gutters fpcm-ui-margin-lg-bottom">
            <div class="col-12">
                <fieldset>
                    <legend><?php $theView->write('PERMISSION_UPLOADS'); ?></legend>
                    <?php foreach ($permissions['uploads'] as $key => $value) : ?>
                        <?php $theView->checkbox("permissions[uploads][{$key}]", "{$rollId}_uploads_{$key}")->setText('PERMISSION_UPLOADS_' . strtoupper($key))->setSelected($value)->setLabelClass('fpcm-ui-align-left'); ?>
                    <?php endforeach; ?>
                </fieldset>
            </div>
        </div>

        <div class="row no-gutters fpcm-ui-margin-lg-bottom">
            <div class="col-12">
                <fieldset>
                    <legend><?php $theView->write('PERMISSION_MODULES'); ?></legend>
                    <?php foreach ($permissions['modules'] as $key => $value) : ?>
                        <?php $theView->checkbox("permissions[modules][{$key}]", "{$rollId}_modules_{$key}")->setText('PERMISSION_MODULES_' . strtoupper($key))->setSelected($value)->setLabelClass('fpcm-ui-align-left'); ?>
                    <?php endforeach; ?>
                </fieldset>
            </div>
        </div>

    </div>

    <div class="col-sm-12 col-md-6">

        <div class="row no-gutters fpcm-ui-margin-lg-bottom">
            <div class="col-12">
                <fieldset>
                    <legend><?php $theView->write('PERMISSION_COMMENTS'); ?></legend>
                    <?php foreach ($permissions['comment'] as $key => $value) : ?>
                        <?php $theView->checkbox("permissions[comment][{$key}]", "{$rollId}_comment_{$key}")->setText('PERMISSION_COMMENT_' . strtoupper($key))->setSelected($value)->setLabelClass('fpcm-ui-align-left'); ?>
                    <?php endforeach; ?>
                </fieldset>
            </div>
        </div>

        <div class="row no-gutters fpcm-ui-margin-lg-bottom">
            <div class="col-12">
                <fieldset>
                    <legend><?php $theView->write('PERMISSION_SYSTEM'); ?></legend>
                    <?php foreach ($permissions['system'] as $key => $value) : ?>
                        <?php $readOnly = ($key == 'permissions' && $rollId == 1) ? true : false; ?>
                        <?php $theView->checkbox("permissions[system][{$key}]", "{$rollId}_system_{$key}")->setText('PERMISSION_SYSTEM_' . strtoupper($key))->setSelected($value)->setReadonly($readOnly)->setLabelClass('fpcm-ui-align-left'); ?>
                        <?php if ($readOnly) : ?><?php $theView->hiddenInput("permissions[system][{$key}]"); ?><?php endif; ?>
                    <?php endforeach; ?>
                </fieldset>
            </div>
        </div>

    </div>
</div>
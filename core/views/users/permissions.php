<div class="fpcm-inner-wrapper">
    
    <form method="post" action="<?php print $FPCM_SELF; ?>?module=users/permissions&roll=<?php print $rollId; ?>">
        <div class="fpcm-tabs-general" id="fpcm-tabs-permissions">
            <ul>
                <li><a href="#tabs-permissions-group"><?php $FPCM_LANG->write('HL_OPTIONS_PERMISSIONS'); ?>: <?php print $rollname; ?></a></li>                
            </ul>

            <div id="tabs-permissions-group">

                <div class="fpcm-ui-permissions-container">
                    <div class="fpcm-ui-permissions-container-inner">
                        <h2><?php $FPCM_LANG->write('PERMISSION_ARTICLES'); ?></h2>
                        <?php foreach ($permissions['article'] as $key => $value) : ?>
                            <?php fpcm\model\view\helper::checkbox("permissions[article][{$key}]", '', 1, $FPCM_LANG->translate('PERMISSION_ARTICLE_'.strtoupper($key)), "{$rollId}_article_{$key}", $value, false); ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="fpcm-ui-permissions-container">
                    <div class="fpcm-ui-permissions-container-inner">
                        <h2><?php $FPCM_LANG->write('PERMISSION_COMMENTS'); ?></h2>
                        <?php foreach ($permissions['comment'] as $key => $value) : ?>
                            <?php fpcm\model\view\helper::checkbox("permissions[comment][{$key}]", '', 1, $FPCM_LANG->translate('PERMISSION_COMMENT_'.strtoupper($key)), "{$rollId}_comment_{$key}", $value, false); ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="fpcm-clear"></div>

                <div class="fpcm-ui-permissions-container">
                    <div class="fpcm-ui-permissions-container-inner">
                        <h2><?php $FPCM_LANG->write('PERMISSION_UPLOADS'); ?></h2>
                        <?php foreach ($permissions['uploads'] as $key => $value) : ?>
                            <?php fpcm\model\view\helper::checkbox("permissions[uploads][{$key}]", '', 1, $FPCM_LANG->translate('PERMISSION_UPLOADS_'.strtoupper($key)), "{$rollId}_uploads_{$key}", $value, false); ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="fpcm-ui-permissions-container">
                    <div class="fpcm-ui-permissions-container-inner">
                        <h2><?php $FPCM_LANG->write('PERMISSION_SYSTEM'); ?></h2>
                        <?php foreach ($permissions['system'] as $key => $value) : ?>
                            <?php $readOnly = ($key == 'permissions' && $rollId == 1) ? true : false; ?>
                            <?php fpcm\model\view\helper::checkbox("permissions[system][{$key}]", '', 1, $FPCM_LANG->translate('PERMISSION_SYSTEM_'.strtoupper($key)), "{$rollId}_system_{$key}", $value, $readOnly); ?>
                            <?php if ($readOnly) : ?><input type="hidden" name="<?php print "permissions[system][{$key}]"; ?>" value="1"><?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="fpcm-ui-permissions-container">
                    <div class="fpcm-ui-permissions-container-inner">
                        <h2><?php $FPCM_LANG->write('PERMISSION_MODULES'); ?></h2>
                        <?php foreach ($permissions['modules'] as $key => $value) : ?>
                            <?php fpcm\model\view\helper::checkbox("permissions[modules][{$key}]", '', 1, $FPCM_LANG->translate('PERMISSION_MODULES_'.strtoupper($key)), "{$rollId}_modules_{$key}", $value, false); ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="fpcm-clear"></div>

            </div>
        </div>

        <div class="fpcm-hidden"><?php fpcm\model\view\helper::saveButton('permissionsSave', 'fpcm-loader'); ?></div>

        <?php \fpcm\model\view\helper::pageTokenField(); ?>

    </form>

</div>
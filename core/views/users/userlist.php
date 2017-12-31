<div class="fpcm-content-wrapper">
    <h1>
        <span class="fa fa-users"></span> <?php $FPCM_LANG->write('HL_OPTIONS_USERS'); ?>
    </h1>
    <form method="post" action="<?php print $FPCM_SELF; ?>?module=users/list">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-users-active"><?php $FPCM_LANG->write('USERS_LIST_ACTIVE'); ?></a></li>
                <?php if (count($usersDisabled)) : ?><li><a href="#tabs-users-inactive"><?php $FPCM_LANG->write('USERS_LIST_DISABLED'); ?></a></li><?php endif; ?>
                <?php if ($rollPermissions) : ?><li><a href="#tabs-users-rolls"><?php $FPCM_LANG->write('USERS_LIST_ROLLS'); ?></a></li><?php endif; ?>
            </ul>            
            
            <div id="tabs-users-active">
                <table class="fpcm-ui-table fpcm-ui-users">
                    <tr>
                        <th></th>
                        <th><?php $FPCM_LANG->write('GLOBAL_USERNAME'); ?></th>
                        <th><?php $FPCM_LANG->write('GLOBAL_EMAIL'); ?></th>
                        <th class="fpcm-ui-center fpcm-ui-users-registeredtime"><?php $FPCM_LANG->write('USERS_REGISTEREDTIME'); ?></th>           
                        <th class="fpcm-th-select-row"></th>         
                    </tr>
                    <tr class="fpcm-td-spacer"><td></td></tr>
                    
                    <?php foreach($usersActive AS $rollId => $usersList) : ?>
                        <tr>
                            <th></th>
                            <th colspan="6"><?php $FPCM_LANG->write('USERS_ROLL'); ?>: <?php if (isset($usersRolls[$rollId])) : ?><?php print $usersRolls[$rollId]; ?><?php else : ?><?php $FPCM_LANG->write('GLOBAL_NOTFOUND'); ?><?php endif; ?></th>
                        </tr>
                        <?php if (count($usersList)) : ?><tr class="fpcm-td-spacer"><td></td></tr><?php endif; ?>
                    
                        <?php foreach($usersList AS $user) : ?>
                        <tr>
                            <td class="fpcm-ui-editbutton-col"><?php \fpcm\model\view\helper::editButton($user->getEditLink()); ?></td>
                            <td><strong><?php print \fpcm\model\view\helper::escapeVal($user->getUserName()); ?></strong></td>
                            <td><a href="mailto:<?php print \fpcm\model\view\helper::escapeVal($user->getEmail()); ?>"><?php print \fpcm\model\view\helper::escapeVal($user->getEmail()); ?></a>
                                <?php fpcm\model\view\helper::badge([
                                    'title' => 'USERS_ARTICLE_COUNT',
                                    'value' => isset($articleCounts[$user->getId()]) ? $articleCounts[$user->getId()] : 0,
                                    'class' => 'fpcm-ui-badge-userarticles'
                                ]); ?>
                            </td>
                            <td class="fpcm-ui-center fpcm-ui-users-registeredtime"><?php \fpcm\model\view\helper::dateText($user->getRegistertime()); ?></td>
                            <td class="fpcm-td-select-row"><input type="radio" name="useridsa" value="<?php print $user->getId(); ?>" <?php if ($user->getId() == $currentUser) : ?>disabled="disabled"<?php endif; ?>></td>      
                        </tr>      
                        <?php endforeach; ?>

                        <?php if (count($usersList)) : ?><tr class="fpcm-td-spacer"><td></td></tr><?php endif; ?>
                    <?php endforeach; ?>
                </table>
                
                <div class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
                    <div class="fpcm-ui-margin-center">
                        <?php fpcm\model\view\helper::linkButton($FPCM_BASEMODULELINK.'users/add', 'USERS_ADD', '', 'fpcm-loader fpcm-newuser-btn'); ?>
                        <?php fpcm\model\view\helper::submitButton('disableUser', 'GLOBAL_DISABLE', 'fpcm-loader fpcm-ui-useractions-disable'); ?>
                        <?php fpcm\model\view\helper::deleteButton('deleteActive'); ?>
                    </div>
                </div>
            </div>
            
            <?php if (count($usersDisabled)) : ?>
            <div id="tabs-users-inactive">
                <table class="fpcm-ui-table fpcm-ui-users">
                    <tr>
                        <th></th>
                        <th><?php $FPCM_LANG->write('GLOBAL_USERNAME'); ?></th>
                        <th><?php $FPCM_LANG->write('GLOBAL_EMAIL'); ?></th>
                        <th class="fpcm-ui-center fpcm-ui-users-registeredtime"><?php $FPCM_LANG->write('USERS_REGISTEREDTIME'); ?></th>           
                        <th class="fpcm-th-select-row"></th>         
                    </tr>
                    <tr class="fpcm-td-spacer"><td></td></tr>

                    <?php foreach($usersDisabled AS $rollId => $usersList) : ?>
                        <tr>
                            <th></th>
                            <th colspan="6"><?php $FPCM_LANG->write('USERS_ROLL'); ?>: <?php if (isset($usersRolls[$rollId])) : ?><?php print $usersRolls[$rollId]; ?><?php else : ?><?php $FPCM_LANG->write('GLOBAL_NOTFOUND'); ?><?php endif; ?></th>
                        </tr>
                        <?php if (count($usersList)) : ?><tr class="fpcm-td-spacer"><td></td></tr><?php endif; ?>
                    
                        <?php foreach($usersList AS $user) : ?>
                        <tr>
                            <td class="fpcm-ui-editbutton-col"><?php \fpcm\model\view\helper::editButton($user->getEditLink()); ?></td>
                            <td><strong><?php print \fpcm\model\view\helper::escapeVal($user->getUserName()); ?></strong></td>
                            <td><a href="mailto:<?php print \fpcm\model\view\helper::escapeVal($user->getEmail()); ?>"><?php print \fpcm\model\view\helper::escapeVal($user->getEmail()); ?></a>
                                <?php fpcm\model\view\helper::badge([
                                    'title' => 'USERS_ARTICLE_COUNT',
                                    'value' => isset($articleCounts[$user->getId()]) ? $articleCounts[$user->getId()] : 0,
                                    'class' => 'fpcm-ui-badge-userarticles'
                                ]); ?>
                            </td>
                            <td class="fpcm-ui-center fpcm-ui-users-registeredtime"><?php \fpcm\model\view\helper::dateText($user->getRegistertime()); ?></td>
                            <td class="fpcm-td-select-row"><input type="radio" name="useridsd" value="<?php print $user->getId(); ?>"></td>
                        </tr>      
                        <?php endforeach; ?>

                        <?php if (count($usersList)) : ?><tr class="fpcm-td-spacer"><td></td></tr><?php endif; ?>
                    <?php endforeach; ?>
                </table>
                
                <div class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
                    <div class="fpcm-ui-margin-center">
                        <?php fpcm\model\view\helper::submitButton('enableUser', 'GLOBAL_ENABLE', 'fpcm-loader fpcm-ui-useractions-enable'); ?>
                        <?php fpcm\model\view\helper::deleteButton('deleteDisabled'); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($rollPermissions) : ?>
            <div id="tabs-users-rolls">
                <table class="fpcm-ui-table fpcm-ui-users">
                    <tr>
                        <th></th>
                        <th><?php $FPCM_LANG->write('USERS_ROLLS_NAME'); ?></th>  
                        <th class="fpcm-th-select-row"></th>         
                    </tr>
                    <tr class="fpcm-td-spacer"><td></td></tr>
                    <?php foreach($usersRollList AS $rollName => $rollid) : ?>
                    <tr>
                        <td class="fpcm-ui-editbutton-col">
                            <?php \fpcm\model\view\helper::editButton($FPCM_BASEMODULELINK.'users/editroll&id='.$rollid, ($rollid <= 3 ? false : true)); ?>
                            <?php \fpcm\model\view\helper::linkButton($FPCM_BASEMODULELINK.'users/permissions&roll='.$rollid, 'USERS_ROLLS_PERMISSIONS', '', 'fpcm-ui-button-blank fpcm-passreset-btn fpcm-ui-rolllist-permissionedit'); ?>
                        </td>
                        <td><strong><?php print \fpcm\model\view\helper::escapeVal($rollName); ?></strong></td>
                        <td class="fpcm-td-select-row"><input type="radio" name="rollids" value="<?php print $rollid; ?>" <?php if ($rollid <= 3) : ?>disabled="disabled"<?php endif; ?>></td>
                    </tr>      
                    <?php endforeach; ?>
                </table>
                
                <div class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
                    <div class="fpcm-ui-margin-center">
                        <?php fpcm\model\view\helper::linkButton($FPCM_BASEMODULELINK.'users/addroll', 'USERS_ROLL_ADD', '', 'fpcm-loader fpcm-new-btn'); ?>
                        <?php fpcm\model\view\helper::deleteButton('deleteRoll'); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <?php include_once __DIR__.'/userlist_dialogs.php'; ?>
        
        <?php \fpcm\model\view\helper::pageTokenField(); ?>
    </form>
</div>
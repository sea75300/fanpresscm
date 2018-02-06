<div class="fpcm-content-wrapper">
    <h1>
        <span class="fa fa-users"></span> <?php $theView->lang->write('HL_OPTIONS_USERS'); ?>
    </h1>
    <form method="post" action="<?php print $theView->self; ?>?module=users/list">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-users-active"><?php $theView->lang->write('USERS_LIST_ACTIVE'); ?></a></li>
                <?php if (count($usersDisabled)) : ?><li><a href="#tabs-users-inactive"><?php $theView->lang->write('USERS_LIST_DISABLED'); ?></a></li><?php endif; ?>
                <?php if ($rollPermissions) : ?><li><a href="#tabs-users-rolls"><?php $theView->lang->write('USERS_LIST_ROLLS'); ?></a></li><?php endif; ?>
            </ul>            
            
            <div id="tabs-users-active">
                <table class="fpcm-ui-table fpcm-ui-users">
                    <tr>
                        <th></th>
                        <th><?php $theView->lang->write('GLOBAL_USERNAME'); ?></th>
                        <th><?php $theView->lang->write('GLOBAL_EMAIL'); ?></th>
                        <th class="fpcm-ui-center fpcm-ui-users-registeredtime"><?php $theView->lang->write('USERS_REGISTEREDTIME'); ?></th>           
                        <th class="fpcm-th-select-row"></th>         
                    </tr>
                    <tr class="fpcm-td-spacer"><td></td></tr>
                    
                    <?php foreach($usersActive AS $rollId => $usersList) : ?>
                        <tr>
                            <th></th>
                            <th colspan="6"><?php $theView->lang->write('USERS_ROLL'); ?>: <?php if (isset($usersRolls[$rollId])) : ?><?php print $usersRolls[$rollId]; ?><?php else : ?><?php $theView->lang->write('GLOBAL_NOTFOUND'); ?><?php endif; ?></th>
                        </tr>
                        <?php if (count($usersList)) : ?><tr class="fpcm-td-spacer"><td></td></tr><?php endif; ?>
                    
                        <?php foreach($usersList AS $user) : ?>
                        <tr>
                            <td class="fpcm-ui-editbutton-col"><?php \fpcm\view\helper::editButton($user->getEditLink()); ?></td>
                            <td><strong><?php print \fpcm\view\helper::escapeVal($user->getUserName()); ?></strong></td>
                            <td><a href="mailto:<?php print \fpcm\view\helper::escapeVal($user->getEmail()); ?>"><?php print \fpcm\view\helper::escapeVal($user->getEmail()); ?></a>
                                <?php (new \fpcm\view\helper\badge('user_article_count'.$user->getId()))
                                        ->setValue(isset($articleCounts[$user->getId()]) ? $articleCounts[$user->getId()] : 0)
                                        ->setIcon('book')
                                        ->setText('USERS_ARTICLE_COUNT');
                                ?>
                            </td>
                            <td class="fpcm-ui-center fpcm-ui-users-registeredtime"><?php \fpcm\view\helper::dateText($user->getRegistertime()); ?></td>
                            <td class="fpcm-td-select-row"><input type="radio" name="useridsa" value="<?php print $user->getId(); ?>" <?php if ($user->getId() == $currentUser) : ?>disabled="disabled"<?php endif; ?>></td>      
                        </tr>      
                        <?php endforeach; ?>

                        <?php if (count($usersList)) : ?><tr class="fpcm-td-spacer"><td></td></tr><?php endif; ?>
                    <?php endforeach; ?>
                </table>
                
                <div class="<?php \fpcm\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
                    <div class="fpcm-ui-margin-center">
                        <?php fpcm\view\helper::linkButton($theView->basePath.'users/add', 'USERS_ADD', '', 'fpcm-loader fpcm-newuser-btn'); ?>
                        <?php fpcm\view\helper::submitButton('disableUser', 'GLOBAL_DISABLE', 'fpcm-loader fpcm-ui-useractions-disable'); ?>
                        <?php fpcm\view\helper::deleteButton('deleteActive'); ?>
                    </div>
                </div>
            </div>
            
            <?php if (count($usersDisabled)) : ?>
            <div id="tabs-users-inactive">
                <table class="fpcm-ui-table fpcm-ui-users">
                    <tr>
                        <th></th>
                        <th><?php $theView->lang->write('GLOBAL_USERNAME'); ?></th>
                        <th><?php $theView->lang->write('GLOBAL_EMAIL'); ?></th>
                        <th class="fpcm-ui-center fpcm-ui-users-registeredtime"><?php $theView->lang->write('USERS_REGISTEREDTIME'); ?></th>           
                        <th class="fpcm-th-select-row"></th>         
                    </tr>
                    <tr class="fpcm-td-spacer"><td></td></tr>

                    <?php foreach($usersDisabled AS $rollId => $usersList) : ?>
                        <tr>
                            <th></th>
                            <th colspan="6"><?php $theView->lang->write('USERS_ROLL'); ?>: <?php if (isset($usersRolls[$rollId])) : ?><?php print $usersRolls[$rollId]; ?><?php else : ?><?php $theView->lang->write('GLOBAL_NOTFOUND'); ?><?php endif; ?></th>
                        </tr>
                        <?php if (count($usersList)) : ?><tr class="fpcm-td-spacer"><td></td></tr><?php endif; ?>
                    
                        <?php foreach($usersList AS $user) : ?>
                        <tr>
                            <td class="fpcm-ui-editbutton-col"><?php \fpcm\view\helper::editButton($user->getEditLink()); ?></td>
                            <td><strong><?php print \fpcm\view\helper::escapeVal($user->getUserName()); ?></strong></td>
                            <td><a href="mailto:<?php print \fpcm\view\helper::escapeVal($user->getEmail()); ?>"><?php print \fpcm\view\helper::escapeVal($user->getEmail()); ?></a>
                                <?php (new \fpcm\view\helper\badge('user_article_count'.$user->getId()))
                                        ->setValue(isset($articleCounts[$user->getId()]) ? $articleCounts[$user->getId()] : 0)
                                        ->setIcon('book')
                                        ->setText('USERS_ARTICLE_COUNT');
                                ?>
                            </td>
                            <td class="fpcm-ui-center fpcm-ui-users-registeredtime"><?php \fpcm\view\helper::dateText($user->getRegistertime()); ?></td>
                            <td class="fpcm-td-select-row"><input type="radio" name="useridsd" value="<?php print $user->getId(); ?>"></td>
                        </tr>      
                        <?php endforeach; ?>

                        <?php if (count($usersList)) : ?><tr class="fpcm-td-spacer"><td></td></tr><?php endif; ?>
                    <?php endforeach; ?>
                </table>
                
                <div class="<?php \fpcm\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
                    <div class="fpcm-ui-margin-center">
                        <?php fpcm\view\helper::submitButton('enableUser', 'GLOBAL_ENABLE', 'fpcm-loader fpcm-ui-useractions-enable'); ?>
                        <?php fpcm\view\helper::deleteButton('deleteDisabled'); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($rollPermissions) : ?>
            <div id="tabs-users-rolls">
                <table class="fpcm-ui-table fpcm-ui-users">
                    <tr>
                        <th></th>
                        <th><?php $theView->lang->write('USERS_ROLLS_NAME'); ?></th>  
                        <th class="fpcm-th-select-row"></th>         
                    </tr>
                    <tr class="fpcm-td-spacer"><td></td></tr>
                    <?php foreach($usersRollList AS $rollName => $rollid) : ?>
                    <tr>
                        <td class="fpcm-ui-editbutton-col">
                            <?php \fpcm\view\helper::editButton($theView->basePath.'users/editroll&id='.$rollid, ($rollid <= 3 ? false : true)); ?>
                            <?php \fpcm\view\helper::linkButton($theView->basePath.'users/permissions&roll='.$rollid, 'USERS_ROLLS_PERMISSIONS', '', 'fpcm-ui-button-blank fpcm-passreset-btn fpcm-ui-rolllist-permissionedit'); ?>
                        </td>
                        <td><strong><?php print \fpcm\view\helper::escapeVal($rollName); ?></strong></td>
                        <td class="fpcm-td-select-row"><input type="radio" name="rollids" value="<?php print $rollid; ?>" <?php if ($rollid <= 3) : ?>disabled="disabled"<?php endif; ?>></td>
                    </tr>      
                    <?php endforeach; ?>
                </table>
                
                <div class="<?php \fpcm\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
                    <div class="fpcm-ui-margin-center">
                        <?php fpcm\view\helper::linkButton($theView->basePath.'users/addroll', 'USERS_ROLL_ADD', '', 'fpcm-loader fpcm-new-btn'); ?>
                        <?php fpcm\view\helper::deleteButton('deleteRoll'); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <?php include $theView->getIncludePath('users/userlist_dialogs.php'); ?>        
        <?php $theView->pageTokenField('pgtkn'); ?>
    </form>
</div>
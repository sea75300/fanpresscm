<div class="fpcm-content-wrapper">
    <div class="fpcm-tabs-general" id="fpcm-ui-tabs-users">
        <ul>
            <li data-toolbar-buttons="1"><a href="#tabs-users-active"><?php $theView->write('USERS_LIST_ACTIVE'); ?></a></li>
            <?php if (count($usersDisabled)) : ?><li data-toolbar-buttons="2"><a href="#tabs-users-inactive"><?php $theView->write('USERS_LIST_DISABLED'); ?></a></li><?php endif; ?>
            <?php if ($rollPermissions) : ?><li data-toolbar-buttons="3"><a href="#tabs-users-rolls"><?php $theView->write('USERS_LIST_ROLLS'); ?></a></li><?php endif; ?>
        </ul>            

        <div id="tabs-users-active">
            <table class="fpcm-ui-table fpcm-ui-users">
                <tr>
                    <th></th>
                    <th><?php $theView->write('GLOBAL_USERNAME'); ?></th>
                    <th><?php $theView->write('GLOBAL_EMAIL'); ?></th>
                    <th class="fpcm-ui-center fpcm-ui-users-registeredtime"><?php $theView->write('USERS_REGISTEREDTIME'); ?></th>           
                    <th class="fpcm-th-select-row"></th>         
                </tr>
                <tr class="fpcm-td-spacer"><td></td></tr>

                <?php foreach($usersActive AS $rollId => $usersList) : ?>
                    <tr>
                        <th></th>
                        <th colspan="6"><?php $theView->write('USERS_ROLL'); ?>: <?php if (isset($usersRolls[$rollId])) : ?><?php print $usersRolls[$rollId]; ?><?php else : ?><?php $theView->write('GLOBAL_NOTFOUND'); ?><?php endif; ?></th>
                    </tr>
                    <?php if (count($usersList)) : ?><tr class="fpcm-td-spacer"><td></td></tr><?php endif; ?>

                    <?php foreach($usersList AS $user) : ?>
                    <tr>
                        <td class="fpcm-ui-editbutton-col"><?php $theView->editButton('usrEditBtn'.$user->getId())->setUrlbyObject($user); ?></td>
                        <td><strong><?php print $theView->escape($user->getUserName()); ?></strong></td>
                        <td><a href="mailto:<?php print $theView->escape($user->getEmail()); ?>"><?php print $theView->escape($user->getEmail()); ?></a>
                            <?php $theView->badge('user_article_count'.$user->getId())
                                    ->setValue(isset($articleCounts[$user->getId()]) ? $articleCounts[$user->getId()] : 0)
                                    ->setIcon('book')
                                    ->setText('USERS_ARTICLE_COUNT');
                            ?>
                        </td>
                        <td class="fpcm-ui-center fpcm-ui-users-registeredtime"><?php $theView->dateText($user->getRegistertime()); ?></td>
                        <td class="fpcm-td-select-row"><input type="radio" name="useridsa" value="<?php print $user->getId(); ?>" <?php if ($user->getId() == $currentUser) : ?>disabled="disabled"<?php endif; ?>></td>      
                    </tr>      
                    <?php endforeach; ?>

                    <?php if (count($usersList)) : ?><tr class="fpcm-td-spacer"><td></td></tr><?php endif; ?>
                <?php endforeach; ?>
            </table>
        </div>

        <?php if (count($usersDisabled)) : ?>
        <div id="tabs-users-inactive">
            <table class="fpcm-ui-table fpcm-ui-users">
                <tr>
                    <th></th>
                    <th><?php $theView->write('GLOBAL_USERNAME'); ?></th>
                    <th><?php $theView->write('GLOBAL_EMAIL'); ?></th>
                    <th class="fpcm-ui-center fpcm-ui-users-registeredtime"><?php $theView->write('USERS_REGISTEREDTIME'); ?></th>           
                    <th class="fpcm-th-select-row"></th>         
                </tr>
                <tr class="fpcm-td-spacer"><td></td></tr>

                <?php foreach($usersDisabled AS $rollId => $usersList) : ?>
                    <tr>
                        <th></th>
                        <th colspan="6"><?php $theView->write('USERS_ROLL'); ?>: <?php if (isset($usersRolls[$rollId])) : ?><?php print $usersRolls[$rollId]; ?><?php else : ?><?php $theView->write('GLOBAL_NOTFOUND'); ?><?php endif; ?></th>
                    </tr>
                    <?php if (count($usersList)) : ?><tr class="fpcm-td-spacer"><td></td></tr><?php endif; ?>

                    <?php foreach($usersList AS $user) : ?>
                    <tr>
                        <td class="fpcm-ui-editbutton-col"><?php $theView->editButton('usrDisEditBtn'.$user->getId())->setUrlbyObject($user); ?></td>
                        <td><strong><?php print $theView->escape($user->getUserName()); ?></strong></td>
                        <td><a href="mailto:<?php print $theView->escape($user->getEmail()); ?>"><?php print $theView->escape($user->getEmail()); ?></a>
                            <?php $theView->badge('user_article_count'.$user->getId())
                                    ->setValue(isset($articleCounts[$user->getId()]) ? $articleCounts[$user->getId()] : 0)
                                    ->setIcon('book')
                                    ->setText('USERS_ARTICLE_COUNT');
                            ?>
                        </td>
                        <td class="fpcm-ui-center fpcm-ui-users-registeredtime"><?php $theView->dateText($user->getRegistertime()); ?></td>
                        <td class="fpcm-td-select-row"><input type="radio" name="useridsd" value="<?php print $user->getId(); ?>"></td>
                    </tr>      
                    <?php endforeach; ?>

                    <?php if (count($usersList)) : ?><tr class="fpcm-td-spacer"><td></td></tr><?php endif; ?>
                <?php endforeach; ?>
            </table>
        </div>
        <?php endif; ?>

        <?php if ($rollPermissions) : ?>
        <div id="tabs-users-rolls">
            <table class="fpcm-ui-table fpcm-ui-users">
                <tr>
                    <th></th>
                    <th><?php $theView->write('USERS_ROLLS_NAME'); ?></th>  
                    <th class="fpcm-th-select-row"></th>         
                </tr>
                <tr class="fpcm-td-spacer"><td></td></tr>
                <?php foreach($usersRollList AS $rollName => $rollid) : ?>
                <tr>
                    <td class="fpcm-ui-editbutton-col">
                        <?php $theView->editButton('rollEditBtn'.$rollid)->setUrl($theView->basePath.'users/editroll&id='.$rollid)->setReadonly($rollid <= 3 ? true : false); ?>
                        <?php $theView->linkButton('rollPermBtn'.$rollid)->setUrl($theView->basePath.'users/permissions&id='.$rollid)->setText('USERS_ROLLS_PERMISSIONS')->setClass('fpcm-ui-rolllist-permissionedit')->setIcon('key')->setIconOnly(true); ?>
                    </td>
                    <td><strong><?php print $theView->escape($rollName); ?></strong></td>
                    <td class="fpcm-td-select-row"><input type="radio" name="rollids" value="<?php print $rollid; ?>" <?php if ($rollid <= 3) : ?>disabled="disabled"<?php endif; ?>></td>
                </tr>      
                <?php endforeach; ?>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <?php include $theView->getIncludePath('users/userlist_dialogs.php'); ?>        
    <?php $theView->pageTokenField('pgtkn'); ?>
</div>
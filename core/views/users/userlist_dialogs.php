<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="fpcm ui-hidden fpcm-editor-dialog" id="fpcm-dialog-users-select-delete">  
    <div class="row py-2">
        <?php $theView->select('articles[action]', 'articlesaction')
                ->setText('USERS_ARTICLES_SELECT')
                ->setOptions($theView->translate('USERS_ARTICLES_LIST'))
                ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
    </div>
 
    <div class="row py-2">
        <?php $theView->select('articles[user]', 'articlesuser')
                ->setText('USERS_ARTICLES_USER')
                ->setOptions($usersListSelect); ?>
    </div>
</div>

<div class="fpcm ui-hidden fpcm-editor-dialog" id="fpcm-dialog-users-permissions-edit"></div>
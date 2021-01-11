<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-users-select-delete">  
    <div class="row py-2">
        <?php $theView->select('articles[action]', 'articlesaction')
                ->setText('USERS_ARTICLES_SELECT')
                ->setOptions($theView->translate('USERS_ARTICLES_LIST'))
                ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                ->setLabelSize(['xs' => 12, 'md' => 6])
                ->prependLabel(); ?>
    </div>
 
    <div class="row py-2">
        <?php $theView->select('articles[user]', 'articlesuser')
                ->setText('USERS_ARTICLES_USER')
                ->setOptions($usersListSelect)
                ->setLabelSize(['xs' => 12, 'md' => 6])
                ->prependLabel(); ?>
    </div>
</div>

<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-users-permissions-edit"></div>
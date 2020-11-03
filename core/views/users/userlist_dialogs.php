<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-users-select-delete">  
    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-3 px-0">
            <?php $theView->write('USERS_ARTICLES_SELECT'); ?>:
        </div>
        <div class="col-sm-12 col-md-6 px-0">
            <?php $theView->select('articles[action]', 'articlesaction')->setOptions($theView->translate('USERS_ARTICLES_LIST'))->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>
 
    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-3 px-0">
            <?php $theView->write('USERS_ARTICLES_USER'); ?>:
        </div>
        <div class="col-sm-12 col-md-6 px-0">
            <?php $theView->select('articles[user]', 'articlesuser')->setOptions($usersListSelect); ?>
        </div>
    </div>
</div>

<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-users-permissions-edit"></div>
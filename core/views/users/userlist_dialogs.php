<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-users-select-delete">  
    <table class="fpcm-ui-table">
        <tr>
            <td><label><?php $theView->write('USERS_ARTICLES_SELECT'); ?>:</label></td>
        </tr>
        <tr>
            <td><?php \fpcm\view\helper::select('articles[action]', $theView->translate('USERS_ARTICLES_LIST'), null, false, false); ?></td>
        </tr>
        <tr>
            <td><label><?php $theView->write('USERS_ARTICLES_USER'); ?>:</label></td>
        </tr>
        <tr>
            <td><?php \fpcm\view\helper::select('articles[user]', $usersListSelect); ?></td>
        </tr>
    </table>
</div>

<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-users-permissions-edit"></div>
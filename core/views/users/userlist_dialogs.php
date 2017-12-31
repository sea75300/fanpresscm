<div class="fpcm-ui-dialog-layer fpcm-hidden fpcm-editor-dialog" id="fpcm-dialog-users-select-delete">  
    <table class="fpcm-ui-table">
        <tr>
            <td><label><?php $FPCM_LANG->write('USERS_ARTICLES_SELECT'); ?>:</label></td>
        </tr>
        <tr>
            <td><?php \fpcm\model\view\helper::select('articles[action]', $FPCM_LANG->translate('USERS_ARTICLES_LIST'), null, false, false); ?></td>
        </tr>
        <tr>
            <td><label><?php $FPCM_LANG->write('USERS_ARTICLES_USER'); ?>:</label></td>
        </tr>
        <tr>
            <td><?php \fpcm\model\view\helper::select('articles[user]', $usersListSelect); ?></td>
        </tr>
    </table>
</div>

<div class="fpcm-ui-dialog-layer fpcm-hidden fpcm-editor-dialog" id="fpcm-dialog-users-permissions-edit"></div>
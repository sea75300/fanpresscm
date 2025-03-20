/**
 * FanPress CM user editing Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 5.3
 */
if (fpcm === undefined) {
    var fpcm = {};
    fpcm.users
}

if (fpcm.users === undefined) {
    fpcm.users = {};
}

fpcm.users.confirmExec = function (_ui, _closeDlg) {

    fpcm.ui_dialogs.confirm({
        clickYes: function () {
            fpcm.ajax.execFunction('users/actions', _ui.dataset.fn, {
                data:  {
                    oid: _ui.dataset.oid,
                    moveAction: fpcm.dom.fromId('fpcm-id-articles-action').length ? fpcm.dom.fromId('fpcm-id-articles-action').val() : null,
                    moveTo: fpcm.dom.fromId('fpcm-id-articles-user').length ? fpcm.dom.fromId('fpcm-id-articles-user').val() : null
                },
                pageToken: 'ajax/users/actions',
                execDone: function (_result) {
                    fpcm.ui.addMessage(_result);

                    if (_closeDlg) {
                        fpcm.ui_dialogs.close(_closeDlg);
                    }

                    if (_result.type === 'success') {
                        setTimeout(function () {

                            if (_ui.dataset.redirect) {
                                window.location.href = _ui.dataset.redirect;
                            }
                            else {
                                window.location.reload();
                            }

                        }, 1000);
                    }
                }
            });
        }
    });

    return false;
};

fpcm.users.moveDeleteArticles = function(_ui) {

    let _dlgContent = fpcm.ui_dialogs.fromDOM('deleteForm');
    if (!_dlgContent) {
        return;
    }

    fpcm.ui_dialogs.create({
        id: 'users-select-delete',
        title: 'USERS_ARTICLES_SELECT',
        closeButton: true,
        directAssignToDom: true,
        content: fpcm.ui_dialogs.fromDOM('deleteForm'),
        dlButtons: [
            {
                text: 'GLOBAL_OK',
                icon: "check",
                closeClick: true,
                primary: true,
                click: function() {
                    fpcm.users.confirmExec(_ui, 'users-select-delete');
                }
            }
        ]
    });

    return false;

};
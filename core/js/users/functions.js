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

    let _formData = fpcm.vars.jsvars.dialoags.deleteForm.fields;

    let _form = document.createElement('div');

    for (var _i in _formData) {

        let _field = _formData[_i];

        let _tmp = new fpcm.ui.forms[_field.type];

        _tmp.name = _field.name;
        _tmp.id = _field.id;
        _tmp.label = _field.text;
        _tmp.class = _field.class;
        _tmp.options = _field.options;
        _tmp.disabled = _field.readonly;
        _tmp.wrapper = `${_field.labelType} ${_field.bottomSpace}`;

        if (!_tmp.assignFormObject) {
            continue;
        }

        _tmp.assignFormObject(_field);

        let _row = document.createElement('div');
        _row.className = 'row mb-3';

        let _colDescr = document.createElement('div');
        _colDescr.className = 'col-12';

        _tmp.assignToDom(_colDescr);

        _row.appendChild(_colDescr);

        _form.appendChild(_row);
    }

    fpcm.ui_dialogs.create({
        id: 'users-select-delete',
        title: 'USERS_ARTICLES_SELECT',
        closeButton: true,
        directAssignToDom: true,
        content: _form,
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
/**
 * FanPress CM Users Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.users = {

    init: function() {

        if (fpcm.dataview.exists('userlist')) {
            fpcm.dataview.render('userlist', {
                onRenderAfter: function () {
                    fpcm.dom.bindClick('.fpcm.ui-userlist-actione', function (_ev, _ui) {
                        return fpcm.users[_ui.dataset.dest](_ui);
                    });
                }
            });
        };
        
        if (fpcm.dataview.exists('rollslist')) {
            fpcm.dataview.render('rollslist', {
                onRenderAfter: function() {

                    fpcm.dom.bindClick('.fpcm.ui-rolls-edit', function (_ev, _ui) {
                        _ev.preventDefault();
                        fpcm.ui_dialogs.create({
                            id: 'edit-permissions',
                            url: _ui.attributes.href.value,
                            title: 'HL_OPTIONS_PERMISSIONS',
                            closeButton: true,
                            dlButtons: [
                                {
                                    text: 'GLOBAL_SAVE',
                                    icon: "check",
                                    primary: true,
                                    click: function () {
                                        fpcm.dom.fromId('fpcm-dialog-edit-permissions-frame').contents().find('#btnPermissionsSave').click();
                                    },
                                }
                            ]
                        });
                        
                        return false;
                    });

                    fpcm.dom.bindClick('.fpcm.ui-rollslist-action-delete', function (_ev, _ui) {
                        return fpcm.users.confirmExec(_ui);
                    });
                }
            });
        };

        if (fpcm.vars.jsvars.chartData) {
            fpcm.ui_chart.draw(fpcm.vars.jsvars.chartData);
        }
    },
    
    moveDeleteArticles: function(_ui) {

        fpcm.ui_dialogs.create({
            id: 'users-select-delete',
            title: 'USERS_ARTICLES_SELECT',
            closeButton: true,
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
            ],
            dlOnClose: function (event, ui) {
                fpcm.dom.fromId('articlesaction').val('');
                fpcm.dom.fromId('articlesuser').val('');
            }
        });

        return false;

    },
    
    confirmExec: function (_ui, _closeDlg) {

        fpcm.ui_dialogs.confirm({
            clickYes: function () {
                fpcm.ajax.execFunction('users/actions', _ui.dataset.fn, {
                    data:  {
                        oid: _ui.dataset.oid,
                        moveAction: fpcm.dom.fromId('articlesaction').length ? fpcm.dom.fromId('articlesaction').val() : null,
                        moveTo: fpcm.dom.fromId('articlesuser').length ? fpcm.dom.fromId('articlesuser').val() : null
                    },
                    pageToken: 'ajax/users/actions',
                    execDone: function (_result) {
                        fpcm.ui.addMessage(_result);

                        if (_closeDlg) {
                            fpcm.ui_dialogs.close(_closeDlg);
                        }

                        if (_result.type === 'success') {
                            setTimeout(function () {
                                window.location.reload();
                            }, 1000);
                        }
                    }
                });   
            }
        });
        
        
        return false;
    }
};
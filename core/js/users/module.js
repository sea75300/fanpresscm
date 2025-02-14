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
    }

};
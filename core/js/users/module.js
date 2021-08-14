/**
 * FanPress CM Users Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.users = {

    init: function() {

        if (fpcm.dataview.exists('userlist')) {
            fpcm.dataview.render('userlist');
        };
        
        if (fpcm.dataview.exists('rollslist')) {
            fpcm.dataview.render('rollslist', {
                onRenderAfter: function() {
                    
                    let _el = fpcm.dom.fromClass('fpcm.ui-rolls-edit');
                    _el.unbind('click');
                    _el.click(function (_ev) {
                        _ev.preventDefault();
                        fpcm.ui.dialog({
                            id: 'edit-permissions',
                            url: this.attributes.href.value,
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
                }
            });
        };

        if (fpcm.vars.jsvars.chartData) {
            fpcm.ui_chart.draw(fpcm.vars.jsvars.chartData);
        }
    },
    
    initMoveDeleteArticles: function() {

        if (fpcm.users.continueDelete) {
            return true;
        }

        fpcm.ui_loader.hide();
        fpcm.ui.dialog({
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
                        fpcm.ui.confirmDialog({
                            clickYes: function() {
                                fpcm.users.continueDelete = true;
                                fpcm.dom.fromId('btnDeleteUser').trigger('click');
                            }
                        });
                    }
                }
            ],
            dlOnClose: function (event, ui) {
                fpcm.dom.fromId('articlesaction').val('');
                fpcm.dom.fromId('articlesuser').val('');
                fpcm.ui_loader.hide();
            }
        });

        return false;

    }
};
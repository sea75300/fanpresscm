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

        fpcm.dom.fromId('btnDeleteUser').click(function() {
            return fpcm.users.initMoveDeleteArticles();
        });

        if (fpcm.dataview.exists('userlist')) {
            fpcm.dataview.render('userlist', {
                onRenderAfter: function() {
                    fpcm.ui.assignControlgroups();
                }
            });
        };
        
        if (fpcm.dataview.exists('rollslist')) {
            fpcm.dataview.render('rollslist', {
                onRenderAfter: function() {
                    fpcm.dom.fromClass('fpcm.ui-link-fancybox').fancybox();
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

        var size = fpcm.ui.getDialogSizes();

        fpcm.ui_loader.hide();
        fpcm.ui.dialog({
            id         : 'users-select-delete',
            dlWidth    : size.width,
            title      : fpcm.ui.translate('USERS_ARTICLES_SELECT'),
            dlButtons  : [
                {
                    text: fpcm.ui.translate('GLOBAL_OK'),
                    icon: "ui-icon-check",                    
                    click: function() {
                        
                        fpcm.dom.fromTag(this).dialog('close');

                        fpcm.ui.dialog({
                            title: fpcm.ui.translate('GLOBAL_CONFIRM'),
                            content: fpcm.ui.translate('CONFIRM_MESSAGE'),
                            dlWidth: size.width,
                            dlButtons: [
                                {
                                    text: fpcm.ui.translate('GLOBAL_YES'),
                                    icon: "ui-icon-check",                    
                                    click: function() {
                                        fpcm.users.continueDelete = true;
                                        fpcm.dom.fromId('btnDeleteUser').trigger('click');
                                        fpcm.dom.fromTag(this).dialog('close');
                                    }
                                },
                                {
                                    text: fpcm.ui.translate('GLOBAL_NO'),
                                    icon: "ui-icon-closethick",
                                    click: function() {
                                        fpcm.dom.fromTag(this).dialog('close');
                                        fpcm.dom.fromId('articlesaction').val('').selectmenu("refresh");
                                        fpcm.dom.fromId('articlesuser').val('').selectmenu("refresh");
                                    }
                                }
                            ]
                        });
                    }
                },
                {
                    text: fpcm.ui.translate('GLOBAL_CLOSE'),
                    icon: "ui-icon-closethick",                    
                    click: function() {
                        fpcm.dom.fromTag(this).dialog('close');
                        fpcm.dom.fromId('articlesaction').val('').selectmenu("refresh");
                        fpcm.dom.fromId('articlesuser').val('').selectmenu("refresh");
                        fpcm.ui_loader.hide();
                    }
                }                            
            ],
            dlOnOpen: function (event, ui) {

                fpcm.ui.selectmenu('#articlesaction', {
                    appendTo: '#fpcm-dialog-users-select-delete',
                });

                fpcm.ui.selectmenu('#articlesuser', {
                    appendTo: '#fpcm-dialog-users-select-delete',
                });

            },
            dlOnClose: function (event, ui) {
                fpcm.dom.fromTag(this).dialog('destroy');
            }
        });

        return false;

    }
};
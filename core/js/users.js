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

        jQuery('#btnDeleteUser').click(function() {
            return fpcm.users.initMoveDeleteArticles();
        });
        
        fpcm.ui.tabs('#fpcm-ui-tabs-users', {
            addMainToobarToggle: true
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
                    fpcm.ui.assignControlgroups();
                    fpcm.users.initPermissionEdit();
                }
            });
        };

    },
    
    initMoveDeleteArticles: function() {

        if (fpcm.users.continueDelete) {
            return true;
        }

        var size = fpcm.ui.getDialogSizes();

        fpcm.ui.showLoader(false);
        fpcm.ui.dialog({
            id         : 'users-select-delete',
            dlWidth    : size.width,
            title      : fpcm.ui.translate('USERS_ARTICLES_SELECT'),
            dlButtons  : [
                {
                    text: fpcm.ui.translate('GLOBAL_OK'),
                    icon: "ui-icon-check",                    
                    click: function() {
                        
                        jQuery(this).dialog('close');

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
                                        jQuery('#btnDeleteUser').trigger('click');
                                        jQuery(this).dialog('close');
                                    }
                                },
                                {
                                    text: fpcm.ui.translate('GLOBAL_NO'),
                                    icon: "ui-icon-closethick",
                                    click: function() {
                                        jQuery(this).dialog('close');
                                        jQuery('#articlesaction').val('').selectmenu("refresh");
                                        jQuery('#articlesuser').val('').selectmenu("refresh");
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
                        jQuery(this).dialog('close');
                        jQuery('#articlesaction').val('').selectmenu("refresh");
                        jQuery('#articlesuser').val('').selectmenu("refresh");
                        fpcm.ui.showLoader(false);
                    }
                }                            
            ],
            dlOnOpen: function (event, ui) {

                fpcm.ui.selectmenu('#articlesaction', {
                    appendTo: '#fpcm-dialog-users-select-delete'
                });

                fpcm.ui.selectmenu('#articlesuser', {
                    appendTo: '#fpcm-dialog-users-select-delete'
                });

            },
            dlOnClose: function (event, ui) {
                jQuery(this).dialog('destroy');
            }
        });

        return false;

    },
    
    initPermissionEdit: function () {
        jQuery('.fpcm-ui-rolllist-permissionedit').click(function() {

            var sizes = fpcm.ui.getDialogSizes(top, 0.75);
            var link  = jQuery(this).attr('href');

            fpcm.ui.dialog({
                id       : 'users-permissions-edit',
                dlWidth  : sizes.width,
                dlHeight : fpcm.ui.getDialogSizes(top, 0.75).height,
                resizable: true,
                title    : fpcm.ui.translate('HL_OPTIONS_PERMISSIONS'),
                dlButtons  : [
                    {
                        text: fpcm.ui.translate('GLOBAL_SAVE'),
                        icon: "ui-icon-disk",                        
                        click: function() {
                            jQuery(this).children('iframe').contents().find('#btnPermissionsSave').trigger('click');
                            fpcm.ui.showLoader(false);
                        }
                    },
                    {
                        text: fpcm.ui.translate('GLOBAL_CLOSE'),
                        icon: "ui-icon-closethick",                    
                        click: function() {
                            jQuery(this).dialog('close');
                            fpcm.ui.showLoader(false);
                        }
                    }                            
                ],
                dlOnOpen: function (event, ui) {                
                    fpcm.ui.appendHtml(this, '<iframe class="fpcm-ui-full-width"  src="' + link + '"></iframe>');
                },
                dlOnClose: function( event, ui ) {
                    jQuery(this).empty();
                }
            });

            return false;
        });
    }
};
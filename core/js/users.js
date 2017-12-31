/**
 * FanPress CM Users Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.users = {

    init: function() {

        jQuery('.fpcm-ui-useractions-diable').click(function() {
            if (jQuery(this).hasClass('fpcm-noloader')) jQuery(this).removeClass('fpcm-noloader');
            if (!confirm(fpcm.ui.translate('confirmMessage'))) {
                jQuery(this).addClass('fpcm-noloader');
                return false;
            }            
        });
                
        jQuery('.fpcm-ui-useractions-enable').click(function() {
            if (jQuery(this).hasClass('fpcm-noloader')) jQuery(this).removeClass('fpcm-noloader');
            if (!confirm(fpcm.ui.translate('confirmMessage'))) {
                jQuery(this).addClass('fpcm-noloader');
                return false;
            }
        });

        jQuery('#btnDeleteActive').off('click');
        jQuery('#btnDeleteDisabled').off('click');

        jQuery('#btnDeleteActive').click(function() {
            return fpcm.users.initMoveDeleteArticles('btnDeleteActive');
        });

        jQuery('#btnDeleteDisabled').click(function() {
            return fpcm.users.initMoveDeleteArticles('btnDeleteDisabled');
        });
        
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
                            console.log('save btn');
                            
                            jQuery(this).children('iframe').contents().find('#btnPermissionsSave').trigger('click');
                            fpcm.ui.showLoader(false);
                        }
                    },
                    {
                        text: fpcm.ui.translate('close'),
                        icon: "ui-icon-closethick",                    
                        click: function() {
                            jQuery(this).dialog('close');
                            fpcm.ui.showLoader(false);
                        }
                    }                            
                ],
                dlOnOpen: function (event, ui) {                
                    fpcm.ui.appendHtml(this, '<iframe class="fpcm-full-width"  src="' + link + '"></iframe>');
                },
                dlOnClose: function( event, ui ) {
                    jQuery(this).empty();
                }
            });
            
            return false;
        });

    },
    
    initMoveDeleteArticles: function(clickBtn) {

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
                            title: fpcm.ui.translate('confirmHL'),
                            content: fpcm.ui.translate('confirmMessage'),
                            dlWidth: size.width,
                            dlButtons: [
                                {
                                    text: fpcm.ui.translate('yes'),
                                    icon: "ui-icon-check",                    
                                    click: function() {
                                        fpcm.users.continueDelete = true;
                                        jQuery('#' + clickBtn).trigger('click');
                                        jQuery(this).dialog('close');
                                    }
                                },
                                {
                                    text: fpcm.ui.translate('no'),
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
                    text: fpcm.ui.translate('close'),
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

    }
};
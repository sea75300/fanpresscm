/**
 * FanPress CM Module Liste Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.modulelist = {

    init: function() {

        fpcm.system.checkForUpdates();
        var btnUpdateAll = jQuery('#runUpdateAll');
        if (btnUpdateAll.length && fpcm.vars.jsvars.updateAllkeys && fpcm.vars.jsvars.updateAllkeys.length) {
            btnUpdateAll.click(function (){

                fpcm.ui.confirmDialog({
                    clickYes: function () {
                        var url = fpcm.vars.actionPath + 'package/modupdate&key=';
                        var urls = [];
                        
                        jQuery.each(fpcm.vars.jsvars.updateAllkeys, function( index, value ) {
                            urls[index] = url + value + '&keepMaintenance=' + (index == fpcm.vars.jsvars.updateAllkeys.length - 1 ? 0 : 1);
                        });
                        
                        urls = urls.reverse();
                        
                        jQuery.each(urls, function (i, dest) {
                            fpcm.ui.openWindow(dest);
                        });

                    },
                    clickNo: function () {
                        jQuery(this).dialog("close");
                    }
                });

            });
        }

        fpcm.modulelist.tabs = fpcm.ui.tabs('#fpcm-tabs-modules',
        {
            addTabScroll: true,
            initDataViewJson: true,         
            hideLoaderOnRequestDone: true,
            dataViewWrapperClass: 'fpcm-ui-modulelist',
            initDataViewOnRenderAfter: fpcm.modulelist.initButtons,
            initDataViewJsonBefore:function(event, ui) {
                jQuery('.fpcm-ui-modulelist').remove();
            },            
            beforeActivate: function( event, ui ) {
                jQuery(ui.oldTab).unbind('click');
            },
        });
        
        fpcm.modulelist.tabs.tabs('load', 0);

    },
    
    initButtons: function () {

        fpcm.ui.assignCheckboxes();
        fpcm.ui.assignControlgroups();

        jQuery('a.fpcm-ui-modulelist-action-local-update').click(function() {

            var destUrl = jQuery(this).attr('href');

            fpcm.ui.confirmDialog({
                clickYes: function () {
                    fpcm.ui.relocate(destUrl);
                    return false;
                },
                clickNo: function () {
                    jQuery(this).dialog("close");
                    return false;
                }
            });
            
            return false;
        });

        jQuery('button.fpcm-ui-modulelist-action-local').click(function() {
            
            var btnEl = jQuery(this);

            var params = {
                action: btnEl.attr('data-action'),
                key: btnEl.attr('data-key'),
            };

            var fromDir = btnEl.attr('data-dir');
            if (fromDir) {
                params.fromDir = fromDir;
            }

            fpcm.ui.dialog({
                title: fpcm.ui.translate('GLOBAL_CONFIRM'),
                content: fpcm.ui.translate('CONFIRM_MESSAGE'),
                dlWidth: fpcm.ui.getDialogSizes(top, 0.35).width,
                dlButtons: [
                    {
                        text: fpcm.ui.translate('GLOBAL_YES'),
                        icon: "ui-icon-check",                    
                        click: function () {
                            fpcm.ui.showLoader(true);
                            fpcm.ajax.exec('modules/exec', {
                                data: params,
                                execDone: function () {
                                    var result = fpcm.ajax.getResult('modules/exec', true);
                                    if (result.code !== undefined && result.code < 1) {
                                        var msg = '';
                                        switch (result.code) {
                                            case fpcm.vars.jsvars.codes.installFailed :
                                                msg = 'MODULES_FAILED_INSTALL';
                                                break;
                                            case fpcm.vars.jsvars.codes.uninstallFailed :
                                                msg = 'MODULES_FAILED_UNINSTALL';
                                                break;
                                            case fpcm.vars.jsvars.codes.enabledFailed :
                                                msg = 'MODULES_FAILED_ENABLE';
                                                break;
                                            case fpcm.vars.jsvars.codes.disabledFailed :
                                                msg = 'MODULES_FAILED_DISABLE';
                                                break;
                                        }

                                        fpcm.ui.addMessage({
                                            txt: msg,
                                            type: 'error'
                                        }, true);
                                    }
                                    
                                    fpcm.modulelist.tabs.tabs('load', 0);
                                }
                            });

                            jQuery( this ).dialog( "close" );
                        }
                    },
                    {
                        text: fpcm.ui.translate('GLOBAL_NO'),
                        icon: "ui-icon-closethick",
                        click: function () {
                    jQuery( this ).dialog( "close" );
                }
                    }
                ]
            });
        });

        jQuery('button.fpcm-ui-modulelist-info').click(function() {
            var btnEl = jQuery(this);
            fpcm.ui.dialog({
                id: 'modulelist-infos',
                title: fpcm.ui.translate('MODULES_LIST_INFORMATIONS'),
                resizable: true,
                dlButtons: [
                    {
                        text: fpcm.ui.translate('GLOBAL_CLOSE'),
                        icon: "ui-icon-closethick",                
                        click: function() {
                            jQuery( this ).dialog( "close" );
                        }
                    }
                ],
                dlOnOpen: function () {
                    
                    var link = btnEl.attr('data-link');

                    jQuery('#fpcm-modulelist-info-name').text(btnEl.attr('data-name'));
                    jQuery('#fpcm-modulelist-info-author').text(btnEl.attr('data-author'));
                    jQuery('#fpcm-modulelist-info-link').html('<a href="' + link + '" target="_blank">' + link + '</a>');
                    jQuery('#fpcm-modulelist-info-require-system').text(btnEl.attr('data-system'));
                    jQuery('#fpcm-modulelist-info-require-php').text(btnEl.attr('data-php'));
                    jQuery('#fpcm-modulelist-info-description').html(btnEl.attr('data-descr'));
                },
                dlOnClose: function() {
                    jQuery('#fpcm-modulelist-info-name').empty();
                    jQuery('#fpcm-modulelist-info-author').empty();
                    jQuery('#fpcm-modulelist-info-link').empty();
                    jQuery('#fpcm-modulelist-info-require-system').empty();
                    jQuery('#fpcm-modulelist-info-require-php').empty();
                    jQuery('#fpcm-modulelist-info-description').empty();
                }
            });
            
        });
        
    }
 
};
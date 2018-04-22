/**
 * FanPress CM system javascript functions
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.system = {
    
    init: function() {
        
        if (!fpcm.vars.jsvars.noRefresh) {
            fpcm.system.doRefresh();
            setInterval(fpcm.system.doRefresh, 60000);
        }
        
        fpcm.system.initPasswordFieldActions();
        fpcm.system.showHelpDialog();
    },
    
    initPasswordFieldActions: function() {

        jQuery('#password_confirm').focusout(function () {
            var password = jQuery('#password').val();
            var confirm  = jQuery(this).val();

            if (password != confirm) {
                fpcm.ui.addMessage({
                    type: 'error',
                    txt : fpcm.ui.translate('SAVE_FAILED_PASSWORD_MATCH')
                });
            }

            return false;
        });

        jQuery("#genPasswd" ).click(function () {
            fpcm.system.generatePasswdString();
            return false;
        });

    },
    
    showSessionCheckDialog: function () {

        if (!fpcm.vars.jsvars.sessionCheck) {
            return true;
        }

        fpcm.ui.dialog({
            content: fpcm.ui.translate('SESSION_TIMEOUT'),
            dlButtons: buttons = [
                {
                    text: fpcm.ui.translate('GLOBAL_YES'),
                    icon: "ui-icon-check",
                    click: function() {
                        fpcm.ui.relocate(fpcm.vars.actionPath + 'system/login');
                        jQuery(this).dialog('close');
                    }
                },
                {
                    text: fpcm.ui.translate('GLOBAL_NO'),
                    icon: "ui-icon-closethick",
                    click: function() {
                        fpcm.vars.jsvars.sessionCheck = true;
                        jQuery(this).dialog('close');
                        jQuery(this).remove();
                    }
                }
            ],
            id: 'sessioncheck'
        });
        
        fpcm.vars.jsvars.sessionCheck = false;
    },
    
    doRefresh: function() {

        fpcm.ajax.exec('refresh', {
            data: {
                articleId: fpcm.vars.jsvars.articleId
            },
            execDone: function() {                
                
                var result = fpcm.ajax.getResult('refresh', true);

                if (fpcm.vars.jsvars.articleId == 1) {
                    fpcm.editor.showInEditDialog(result);
                }
                
                if (result.sessionCode == 0 && fpcm.vars.jsvars.sessionCheck) {
                    fpcm.system.showSessionCheckDialog();
                }

            },
        })
        
    },
   
    generatePasswdString: function() {

        var randVal = new Int8Array(1);
        crypto.getRandomValues(randVal);
        var passwd  = generatePassword(12, false, /[\w\d\?\-]/) + randVal[0];

        jQuery('#password').val(passwd);
        jQuery('#password_confirm').val(passwd);
        
        return false;
    },
    
    initMassEditDialog: function(func, dialogId, list) {

        var dialogIdCom = '#fpcm-dialog-' + dialogId;

        fpcm.ui.selectmenu('.fpcm-ui-input-select-massedit', {
            width: '100%',
            appendTo: dialogIdCom
        });

        if (typeof list.initWidgets === 'function') {
            list.initWidgets(dialogIdCom);
        }

        var size = fpcm.ui.getDialogSizes();

        fpcm.ui.dialog({
            id       : dialogId,
            dlWidth  : size.width,
            resizable: true,
            title    : fpcm.ui.translate('GLOBAL_EDIT_SELECTED'),
            dlButtons  : [
                {
                    text: fpcm.ui.translate('GLOBAL_SAVE'),
                    icon: "ui-icon-check",
                    click: function() {

                        fpcm.ui.confirmDialog({
                            clickYes: function() {

                                var objectIDs = fpcm.ui.getCheckboxCheckedValues('.fpcm-ui-list-checkbox');
                                if (objectIDs.length == 0) {
                                    fpcm.ui.showLoader(false);
                                    return false;
                                }

                                var mefields = jQuery('.fpcm-ui-input-massedit');
                                var params = {
                                    fields: {},
                                    ids     : fpcm.ajax.toJSON(objectIDs),
                                    pageTkn : fpcm.vars.jsvars.masseditPageToken
                                };

                                jQuery.each(mefields, function (key, obj) {
                                    var objVal  = jQuery(obj).val();
                                    var objName = jQuery(obj).attr('name'); 
                                    params.fields[objName] = objVal;
                                });

                                params.fields.categories = fpcm.ui.getCheckboxCheckedValues('.fpcm-ui-input-massedit-categories');
                                fpcm.system.execMassEdit(func, params);

                                jQuery(this).dialog('close');
                                
                            },
                           
                            clickNo: function() {
                                jQuery(this).dialog('close');
                            }
                        });
                    }
                },                    
                {
                    text: fpcm.ui.translate('GLOBAL_CLOSE'),
                    icon: "ui-icon-closethick" ,                        
                    click: function() {
                        jQuery(this).dialog('close');
                    }
                }                            
            ],
            dlOnClose: function( event, ui ) {
                fpcm.ui.showLoader(false);
            }
        });

        return false;
    },
    
    execMassEdit: function(func, params) {
        fpcm.ajax.post(func, {
            data     : params,
            execDone : function () {

                var res = fpcm.ajax.fromJSON(fpcm.ajax.getResult(func));

                if (res !== null && res.code == 1) {
                    fpcm.ui.relocate(window.location.href);
                    return true;
                }

                fpcm.ui.addMessage({
                    type : 'error',
                    id   : 'fpcm-articles-massedit',
                    icon : 'exclamation-triangle',
                    txt  : fpcm.vars.jsvars.massEditSaveFailed
                }, true);

            }
        });
    },
    
    clearCache: function (params) {
        
        if (!params) {
            params = {};
        }

        fpcm.ui.showLoader(true);

        fpcm.ajax.get('cache', {
            data: params,
            execDone: function () {
                fpcm.ui.showLoader(false);
                
                var data = fpcm.ajax.getResult('cache', true);
                fpcm.ui.addMessage({
                    type : data.type,
                    icon : data.icon,
                    txt  : data.txt
                }, true);
            }
        });
        
        return false;        
    },
    
    showHelpDialog: function () {

        jQuery('.fpcm-ui-help-dialog').click(function () {+

            fpcm.ui.dialog({
               id: 'help' ,
                dlWidth    : fpcm.ui.getDialogSizes(top, 0.75).width,
                dlHeight   : fpcm.ui.getDialogSizes(top, 0.85).height,
                resizable  : true,
                title      : fpcm.ui.translate('HL_HELP'),
                content    : fpcm.ui.createIFrame({
                    src    : 'src="' + jQuery(this).attr('href') + '" ',
                    id     : 'mainhelp'
                }),
                dlButtons  : [
                    {
                        text: fpcm.ui.translate('GLOBAL_CLOSE'),
                        icon: "ui-icon-closethick",                    
                        click: function() {
                            jQuery(this).dialog('close');
                        }
                    }
                ],
                dlOnClose: function( event, ui ) {
                    jQuery('#fpcm-dialog-mainhelp').remove();
                }
            });

            return false;
        });
        
    },
    
    checkForUpdates: function () {
        jQuery('#checkUpdate').click(function () {
            fpcm.ui.showLoader(true);
            fpcm.ajax.get('cronasync', {
                data    : {
                    cjId: 'updateCheck'
                },
                execDone: fpcm.ui.showLoader(false)
            });
        });

        return true;
    }

};

jQuery.noConflict();
jQuery(document).ready(function () {

    jQuery.each(fpcm, function(idx, object) {

        if (typeof object.init === 'function') {
            object.init();
        }

    });

});

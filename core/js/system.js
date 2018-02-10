/**
 * FanPress CM system javascript functions
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.system = {
    
    init: function() {
        fpcm.system.runCronsAsync();
        setInterval(fpcm.system.runMinuteIntervals, 60000);
        fpcm.system.initPasswordFieldActions();
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

        jQuery("#generatepasswd" ).click(function () {
            fpcm.system.generatePasswdString();
            return false;
        });

    },
    
    showSessionCheckDialog: function () {
        
        window.fpcmSessionCheckEnabled = false;

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
                        fpcmSessionCheckEnabled = true;
                        jQuery(this).dialog('close');
                    }
                }
            ],
            id: 'sessioncheck'
        });
    },
    
    checkSession: function() {
        
        if (!window.fpcmSessionCheckEnabled) {
            return false;
        }

        fpcm.ajax.exec('session', {
            execDone: function() {                
                if (fpcm.ajax.getResult('session') != '0') {
                    return true;
                }
                fpcm.system.showSessionCheckDialog
            },
            execFail: fpcm.system.showSessionCheckDialog,
        });
        
        return false;
    },
    
    runCronsAsync: function() {
        if (window.fpcmCronAsyncDiabled) {
            return false;
        }
        
//        fpcm.ajax.get('cronasync');
    },
    
    runMinuteIntervals: function() {
        fpcm.system.runCronsAsync();
        fpcm.system.checkSession();
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

                                var objectIDs = fpcm.ui.getCheckboxCheckedValues('.fpcm-list-selectbox');
                                if (objectIDs.length == 0) {
                                    fpcm.ui.showLoader(false);
                                    return false;
                                }

                                var mefields = jQuery('.fpcm-ui-input-massedit');
                                var params = {
                                    fields: {},
                                    ids     : fpcm.ajax.toJSON(objectIDs),
                                    pageTkn : masseditPageToken
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
                    txt  : fpcm.ui.translate('SAVE_FAILED_ARTICLES')
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
    
    openManualCheckFrame: function () {

        if (!fpcm.vars.jsvars.manualCheckUrl) {
            return false;
        }

        var size = fpcm.ui.getDialogSizes();

        fpcm.ui.dialog({
            id         : 'manualupdate-check',
            dlWidth    : size.width,
            dlHeight   : size.height,
            resizable  : true,
            title      : fpcm.ui.translate('HL_PACKAGEMGR_SYSUPDATES'),
            dlButtons  : [
                {
                    text: fpcm.ui.translate('GLOBAL_OPENNEWWIN'),
                    icon: "ui-icon-extlink",                    
                    click: function() {
                        window.open(fpcm.vars.jsvars.manualCheckUrl);
                        jQuery(this).dialog('close');
                    }
                },
                {
                    text: fpcm.ui.translate('GLOBAL_CLOSE'),
                    icon: "ui-icon-closethick",                    
                    click: function() {
                        jQuery(this).dialog('close');
                    }
                }
            ],
            dlOnOpen: function (event, ui) {
                jQuery(this).empty();
                fpcm.ui.appendHtml(this, '<iframe class="fpcm-full-width" style="height:100%;"  src="' + fpcmManualCheckUrl + '"></iframe>');
            },
            dlOnClose: function( event, ui ) {
                jQuery(this).empty();
            }
        });
    }

};

jQuery.noConflict();
jQuery(document).ready(function () {

    fpcmJs   = new fpcmJs();

    jQuery.each(fpcm, function(idx, object) {

        if (typeof object.init === 'function') {
            object.init();
        }

    });

});

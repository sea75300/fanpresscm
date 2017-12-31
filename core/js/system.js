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
                if (fpcmLang.passCheckAlert !== undefined) {
                    alert(fpcm.ui.translate('passCheckAlert'));
                } else {
                    fpcmJs.addAjaxMassage('error', 'SAVE_FAILED_PASSWORD_MATCH');                
                }
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
            content: fpcm.ui.translate('sessionCheckMsg'),
            dlButtons: buttons = [
                {
                    text: fpcm.ui.translate('yes'),
                    icon: "ui-icon-check",
                    click: function() {
                        fpcmJs.relocate(fpcmActionPath + 'system/login');
                        jQuery(this).dialog('close');
                    }
                },
                {
                    text: fpcm.ui.translate('no'),
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
        
        fpcm.ajax.get('cronasync');
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
            title    : fpcm.ui.translate('masseditHeadline'),
            dlButtons  : [
                {
                    text: fpcm.ui.translate('masseditSave'),
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
                    text: fpcm.ui.translate('close'),
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
                    fpcmJs.relocate(window.location.href);
                    return true;
                }

                fpcm.ui.addMessage({
                    type : 'error',
                    id   : 'fpcm-articles-massedit',
                    icon : 'exclamation-triangle',
                    txt  : fpcm.ui.translate('masseditSaveFailed')
                }, true);

            }
        });
    },

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

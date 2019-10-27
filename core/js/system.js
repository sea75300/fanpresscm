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

    init: function () {

        if (!fpcm.vars.jsvars.noRefresh) {
            fpcm.system.doRefresh();
            setInterval(fpcm.system.doRefresh, 60000);
        }

        fpcm.system.initPasswordFieldActions();
        fpcm.system.showHelpDialog();
    },

    initPasswordFieldActions: function () {

        fpcm.dom.fromId('password_confirm').focusout(function () {
            var password = fpcm.dom.fromId('password').val();
            var confirm = fpcm.dom.fromTag(this).val();

            if (password != confirm) {
                fpcm.ui.addMessage({
                    type: 'error',
                    txt: fpcm.ui.translate('SAVE_FAILED_PASSWORD_MATCH')
                }, true);
            }
            
            fpcm.ui.showCurrentPasswordConfirmation();

            fpcm.ajax.post('passcheck', {
                data: {
                    password: password
                },
                execDone: function (result) {

                    if (parseInt(result) === 0) {
                        return false;
                    }

                    fpcm.ui.addMessage({
                        type: 'error',
                        txt: fpcm.ui.translate('SAVE_FAILED_PASSWORD_SECURITY_PWNDPASS')
                    }, true);

                    return false;
                }
            });

            return false;
        });

        fpcm.dom.fromId('genPasswd').click(function () {
            fpcm.system.generatePasswdString();
            fpcm.ui.showCurrentPasswordConfirmation();
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
                    click: function () {
                        fpcm.ui.relocate(fpcm.vars.actionPath + 'system/login');
                        fpcm.dom.fromTag(this).dialog('close');
                    }
                },
                {
                    text: fpcm.ui.translate('GLOBAL_NO'),
                    icon: "ui-icon-closethick",
                    click: function () {
                        fpcm.vars.jsvars.sessionCheck = true;
                        fpcm.dom.fromTag(this).dialog('close');
                        fpcm.dom.fromTag(this).remove();
                    }
                }
            ],
            id: 'sessioncheck'
        });

        fpcm.vars.jsvars.sessionCheck = false;
    },

    doRefresh: function () {

        fpcm.ajax.exec('refresh', {
            data: {
                articleId: fpcm.vars.jsvars.articleId
            },
            execDone: function () {

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

    generatePasswdString: function () {
        var newPass = nkorgPassGen.getPassword(6);
        fpcm.dom.fromId('password').val(newPass);
        fpcm.dom.fromId('password_confirm').val(newPass);
        return false;
    },

    initMassEditDialog: function (func, dialogId, list) {

        var dialogIdCom = '#fpcm-dialog-' + dialogId;

        fpcm.ui.selectmenu('.fpcm-ui-input-select-massedit', {
            appendTo: dialogIdCom,
            removeCornerLeft: true
        });

        if (typeof list.initWidgets === 'function') {
            list.initWidgets(dialogIdCom);
        }

        var size = fpcm.ui.getDialogSizes();

        fpcm.ui.dialog({
            id: dialogId,
            dlWidth: size.width,
            resizable: true,
            title: fpcm.ui.translate('GLOBAL_EDIT_SELECTED'),
            dlButtons: [
                {
                    text: fpcm.ui.translate('GLOBAL_SAVE'),
                    icon: "ui-icon-check",
                    class: 'fpcm-ui-button-primary',
                    click: function () {

                        fpcm.ui.confirmDialog({
                            defaultYes: true,
                            clickYes: function () {

                                var objectIDs = fpcm.ui.getCheckboxCheckedValues('.fpcm-ui-list-checkbox');
                                if (objectIDs.length == 0) {
                                    fpcm.ui.showLoader(false);
                                    return false;
                                }

                                var params = {
                                    fields: fpcm.ui.getValuesByClass('fpcm-ui-input-massedit'),
                                    ids: fpcm.ajax.toJSON(objectIDs),
                                    pageTkn: fpcm.vars.jsvars.masseditPageToken
                                };

                                var catEl = fpcm.dom.fromId('categories');
                                if (catEl) {
                                    params.fields.categories = catEl.val();
                                }

                                fpcm.system.execMassEdit(func, params);

                                fpcm.dom.fromTag(this).dialog('close');

                            },

                            clickNo: function () {
                                fpcm.dom.fromTag(this).dialog('close');
                            }
                        });
                    }
                },
                {
                    text: fpcm.ui.translate('GLOBAL_CLOSE'),
                    icon: "ui-icon-closethick",
                    click: function () {
                        fpcm.dom.fromTag(this).dialog('close');
                    }
                }
            ],
            dlOnClose: function (event, ui) {
                
                let catEl = fpcm.dom.fromId('categories');
                if (catEl[0]) {
                    catEl[0].selectize.clear();
                }

                fpcm.ui.showLoader(false);
            }
        });

        return false;
    },

    execMassEdit: function (func, params) {
        fpcm.ajax.post(func, {
            data: params,
            dataType: 'json',
            execDone: function (res) {

                if (res !== null && res.code == 1) {
                    fpcm.ui.relocate(window.location.href + (fpcm.vars.jsvars.massEdit && fpcm.vars.jsvars.massEdit.relocateParams ? fpcm.vars.jsvars.massEdit.relocateParams : ''));
                    return true;
                }

                fpcm.ui.addMessage({
                    type: 'error',
                    id: 'fpcm-articles-massedit',
                    icon: 'exclamation-triangle',
                    txt: fpcm.vars.jsvars.massEditSaveFailed
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
                    type: data.type,
                    icon: data.icon,
                    txt: data.txt
                }, true);
            }
        });

        return false;
    },

    showHelpDialog: function () {

        fpcm.dom.fromClass('fpcm-ui-help-dialog').click(function () {
            var el = fpcm.dom.fromTag(this);

            fpcm.ajax.get('help', {
                data: {
                    ref: el.data('ref'),
                    chapter: el.data('chapter'),
                },
                execDone: function (result) {

                    var sizes = fpcm.ui.getDialogSizes(top, 0.90);

                    fpcm.ui.dialog({
                        id: 'help',
                        dlWidth: sizes.width,
                        dlMaxHeight: sizes.height,
                        resizable: false,
                        title: fpcm.ui.translate('HL_HELP'),
                        content: result,
                        dlButtons: [
                            {
                                text: fpcm.ui.translate('GLOBAL_CLOSE'),
                                icon: "ui-icon-closethick",
                                click: function () {
                                    fpcm.dom.fromTag(this).dialog('close');
                                    return false;
                                }
                            }
                        ],
                        dlOnClose: function (event, ui) {
                            fpcm.dom.fromTag(this).remove();
                        }
                    });

                    fpcm.ui.tabs('#fpcm-ui-tabs-help');

                    var headlines = fpcm.dom.fromId('tabs-help-general').find('h3');
                    if (headlines.length < 2) {
                        fpcm.dom.fromId('fpcm-ui-help-toc-headline').addClass('fpcm-ui-hidden');
                        return true;
                    }

                    var listEl = fpcm.dom.fromId('fpcm-ui-help-toc');
                    jQuery.each(headlines, function (i, val) {

                        if (!i) {
                            return true;
                        }

                        listEl.append('<li><strong>' + val.innerText + '</strong></li>');
                    });

                }
            });

            return false;
        });

    },

    checkForUpdates: function () {
        fpcm.dom.fromId('checkUpdate').click(function () {
            fpcm.ui.showLoader(true);
            fpcm.ajax.get('cronasync', {
                data: {
                    cjId: 'updateCheck'
                },
                execDone: fpcm.ui.showLoader(false)
            });
        });

        return true;
    },
    
    domFromId: function (_str) {
        
        if (!_str) {
            return false;
        }

        return fpcm.dom.fromId('' + _str);
    },
    
    domFromClass: function (_str) {
        
        if (!_str) {
            return false;
        }

        return fpcm.dom.fromClass('' + _str);
    }

};

jQuery.noConflict();
jQuery(document).ready(function () {

    jQuery.each(fpcm, function (idx, object) {

        if (!object.init || typeof object.init !== 'function') {
            return true;
        }

        object.init();
    });

    jQuery.each(fpcm, function (idx, object) {
        if (!object.initAfter || typeof object.initAfter !== 'function') {
            return true;
        }

        object.initAfter();
    });

});
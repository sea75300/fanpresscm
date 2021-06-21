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

    init: function ()
    {

        fpcm.worker.postMessage({
            namespace: 'system',
            function: 'doRefresh',
            interval: 60000,
            id: 'system.refresh'
        });

        fpcm.system.initPasswordFieldActions();
        fpcm.system.showHelpDialog();
        
        let ccEL = fpcm.dom.fromId('fpcm-clear-cache');
        ccEL.unbind('click');
        ccEL.click(function () {
            return fpcm.system.clearCache();
        });
    },

    togglePasswordField: function (_event, _callee)
    {
        let _el = _callee.parentNode.querySelector('input');
        let _map = {
            password: 'text',
            text: 'password',
        }
        
        _el.type = _map[_el.type] ? _map[_el.type] : 'password';
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
                quiet: true,
                data: {
                    password: password
                },
                execDone: function (result) {

                    if (result.code == 0) {
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

        fpcm.ui_notify.show({
            body: 'SESSION_TIMEOUT',
            timeout: -1,
            click: function (event) {
                window.focus();
            }
        });

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

        if (fpcm.vars.jsvars.noRefresh) {
            return false;
        }

        fpcm.ajax.post('refresh', {
            quiet: true,
            data: {
                articleId: fpcm.vars.jsvars.articleId
            },
            execDone: function (result) {
                
                fpcm.worker.postMessage({
                    cmd: 'remove',
                    id: 'system.refresh'
                });                
                

                if (fpcm.vars.jsvars.articleId == 1) {
                    fpcm.editor.showInEditDialog(result);
                }

                if (result.sessionCode == 0 && fpcm.vars.jsvars.sessionCheck) {
                    fpcm.system.showSessionCheckDialog();
                }

            },
        });

        return true;
    },

    generatePasswdString: function () {
        var newPass = nkorgPassGen.getPassword(6);
        fpcm.dom.fromId('password').val(newPass);
        fpcm.dom.fromId('password_confirm').val(newPass);
        return false;
    },

    initMassEditDialog: function (func, dialogId, list, _params) {

        if (_params === undefined) {
            _params = {};
        }

        var dialogIdCom = '#fpcm-dialog-' + dialogId;

        fpcm.ui.selectmenu('.fpcm-ui-input-select-massedit', {
            appendTo: dialogIdCom,
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
                    class: 'btn-primary',
                    click: function () {

                        fpcm.ui.confirmDialog({
                            defaultYes: true,
                            clickYes: function () {

                                var objectIDs = fpcm.ui.getCheckboxCheckedValues('.fpcm-ui-list-checkbox');
                                if (objectIDs.length == 0) {
                                    fpcm.ui_loader.hide();
                                    return false;
                                }

                                var params = {
                                    fields: fpcm.ui.getValuesByClass('fpcm-ui-input-massedit'),
                                    ids: fpcm.ajax.toJSON(objectIDs),
                                };

                                if (_params.multipleSelect) {
                                    params.fields[_params.multipleSelectField] = fpcm.dom.fromId(_params.multipleSelect).val();
                                }
                                else {
                                    var catEl = fpcm.dom.fromId('categories');
                                    if (catEl) {
                                        params.fields.categories = catEl.val();
                                    }                                    
                                }

                                if (_params.onSuccess) {
                                    params.onSuccess = _params.onSuccess;
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

                fpcm.ui_loader.hide();
            }
        });

        return false;
    },

    execMassEdit: function (func, params) {
        
        if (!params.onSuccess) {
            params.onSuccess = function () {
                fpcm.ui.relocate(window.location.href + (fpcm.vars.jsvars.massEdit && fpcm.vars.jsvars.massEdit.relocateParams ? fpcm.vars.jsvars.massEdit.relocateParams : ''));
            };
        }
        
        fpcm.ajax.post(func, {
            data: params,
            dataType: 'json',
            execDone: function (res) {

                if (res !== null && res.code == 1) {
                    params.onSuccess();
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

        fpcm.ajax.post('cache', {
            data: params,
            execDone: function (result) {
                fpcm.ui.addMessage(result, true);
            }
        });

        return false;
    },

    emptyTrash: function (_params) {

        fpcm.ui.confirmDialog({

            clickYes: function () {
                
                fpcm.ajax.execFunction('clearTrash', _params.fn, {
                    pageToken: 'ajax/clearTrash',
                    data: {
                        ids: _params.ids ? _params.ids : [],
                    },
                    execDone: function (result) {

                        fpcm.ui.resetSelectMenuSelection('action');

                        if (!result.msg && !result.code) {
                            fpcm.ajax.showAjaxErrorMessage();
                            fpcm.ui_loader.hide();
                            return false;
                        }

                        fpcm.ui.addMessage(result.msg, true);
                        if (result.code == 1) {
                            setTimeout(function () {
                                window.location.reload();
                            }, 2500);
                            return true;
                        }

                    }
                });

                fpcm.dom.fromTag(this).dialog("close");
            },
            clickNoDefault: true

        });

        return false;
    },

    showHelpDialog: function () {

        fpcm.dom.fromClass('fpcm-ui-help-dialog').click(function () {
            var el = fpcm.dom.fromTag(this);

            fpcm.ajax.get('help', {
                
                quiet: true,
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
                        resizable: true,
                        title: fpcm.ui.translate('HL_HELP'),
                        opener: el.attr('id'),
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

//                    fpcm.ui.tabs('#fpcm-ui-tabs-help');
//
//                    var headlines = fpcm.dom.fromId('tabs-help-general').find('h3');
//                    if (headlines.length < 2) {
//                        fpcm.dom.fromId('fpcm-ui-help-toc-headline').addClass('fpcm-ui-hidden');
//                        return true;
//                    }
//
//                    var listEl = fpcm.dom.fromId('fpcm-ui-help-toc');
//                    jQuery.each(headlines, function (i, val) {
//
//                        if (!i) {
//                            return true;
//                        }
//
//                        listEl.append('<li><strong>' + val.innerText + '</strong></li>');
//                    });

                }
            });

            return false;
        });

    },

    checkForUpdates: function () {
        fpcm.dom.fromId('checkUpdate').click(function () {
            fpcm.ajax.get('cronasync', {
                data: {
                    cjId: 'updateCheck'
                }
            });
        });

        return true;
    },
    
    mergeToVars: function (_newvalue) {

        if (!_newvalue) {
            _newvalue = [];
        }
        
        return jQuery.extend(fpcm, _newvalue);
    },
    
    parseUrlQuery: function (_url) {
        
        if (!_url) {
            return {};
        }
        
        var urlItems = _url.replace(/.*\?/, '').split('&');
        var returnValues = {};
        for (var i = 0; i < urlItems.length; i++) {

            if (urlItems[i] === undefined) {
                continue;
            }

            var current = urlItems[i].split(/([a-zA-Z0-9]+)\=(.*)/);
            returnValues[current[1]] = current[2];
        }

        return returnValues;
    }

};
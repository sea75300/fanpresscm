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
        fpcm.dom.bindClick('#fpcm-clear-cache', function () {
            return fpcm.system.clearCache();
        });
    },

    togglePasswordField: function (_event, _callee)
    {
        let _el = _callee.parentNode.parentNode.querySelector('input');
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
        
        fpcm.dom.bindClick('#genPasswd', function () {
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

        fpcm.ui_dialogs.create({
            id: 'sessioncheck',
            content: fpcm.ui.translate('SESSION_TIMEOUT'),
            dlButtons: buttons = [
                {
                    text: 'GLOBAL_YES',
                    icon: "check",
                    clickClose: true,
                    primary: true,
                    click: function () {
                        fpcm.ui.relocate(fpcm.vars.actionPath + 'system/login');
                    }
                },
                {
                    text: 'GLOBAL_NO',
                    icon: "times",
                    clickClose: true,
                    click: function () {
                        fpcm.vars.jsvars.sessionCheck = true;
                    }
                }
            ],
        });

        fpcm.vars.jsvars.sessionCheck = false;
    },

    doRefresh: function () {

        if (fpcm.vars.jsvars.noRefresh) {
            return false;
        }

        fpcm.ajax.post('refresh', {
            quiet: true,
            async: true,
            data: {
                articleId: fpcm.vars.jsvars.articleId
            },
            execDone: function (result) {
                
                fpcm.worker.postMessage({
                    cmd: 'remove',
                    id: 'system.refresh'
                });                
                

                if (fpcm.vars.jsvars.articleId > 0) {
                    fpcm.editor.showInEditDialog(result);
                }

                if (result.sessionCode == 0 && fpcm.vars.jsvars.sessionCheck) {
                    fpcm.system.showSessionCheckDialog();
                }

                if (result.notifications) {
                    fpcm.system.addAjaxNotifications(result.notifications, result.notificationCount);
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

        let _content = '';
        if (_params.fields !== undefined) {
            for (var _i in _params.fields) {
                _content += _params.fields[_i];
            }
        }

        var _ajaxParams = {
            fields: {},
            ids: fpcm.dom.getCheckboxCheckedValues('.fpcm-ui-list-checkbox')
        };

        if (!_ajaxParams.ids.length) {
            return false;
        }
        
        _ajaxParams.ids = fpcm.ajax.toJSON(_ajaxParams.ids);

        fpcm.ui_dialogs.create({
            id: dialogId,
            title: 'GLOBAL_EDIT_SELECTED',
            closeButton: true,
            content: _content ? _content : undefined,
            dlButtons: [
                {
                    text: 'GLOBAL_SAVE',
                    icon: "save",
                    primary: true,
                    clickClose: true,
                    click: function () {

                        _ajaxParams.fields = fpcm.dom.getValuesByClass('fpcm-ui-input-massedit');

                        var catEl = fpcm.dom.fromId('categories');
                        if (catEl.length) {
                            list.massEditCategories = catEl.val();
                        }  

                        if (_params.multipleSelect) {
                            _ajaxParams.fields[_params.multipleSelectField] = fpcm.dom.fromId(_params.multipleSelect).val();
                        }
                        else {
                            _ajaxParams.fields.categories = list.massEditCategories;
                            list.massEditCategories = [];
                        }

                        fpcm.ui_dialogs.confirm({
                            defaultYes: true,
                            clickYes: function () {

                                if (_params.onSuccess) {
                                    _ajaxParams.onSuccess = _params.onSuccess;
                                }

                                fpcm.system.execMassEdit(func, _ajaxParams);
                            }
                        });
                    }
                }
            ],
            dlOnOpenAfter: function() {
                if (typeof list.initWidgets === 'function') {
                    list.initWidgets();
                }
            },
            dlOnClose: function (event, ui) {

                if (typeof list.onMassEditorDialogClose === 'function') {
                    list.onMassEditorDialogClose();
                }

                fpcm.ui_loader.hide();
            }
        });

        return false;
    },

    execMassEdit: function (func, params) {
        
        if (!params.onSuccess) {
            params.onSuccess = function () {
                
                if (fpcm.vars.jsvars.massEdit === undefined || !fpcm.vars.jsvars.massEdit.relocateParams === undefined) {
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                    return;
                }

                fpcm.ui.relocate(window.location.href + fpcm.vars.jsvars.massEdit.relocateParams);
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

        fpcm.ui_dialogs.confirm({

            clickYes: function () {
                
                fpcm.ajax.execFunction('clearTrash', _params.fn, {
                    pageToken: 'ajax/clearTrash',
                    data: {
                        ids: _params.ids ? _params.ids : [],
                    },
                    execDone: function (result) {

                        fpcm.dom.resetValuesByIdsSelect('action');

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
            }
        });

        return false;
    },

    showHelpDialog: function () {

        fpcm.dom.bindClick('.fpcm.ui-help-dialog', function (_event, _ui) {

            fpcm.ajax.get('help', {
                
                quiet: true,
                data: {
                    ref: _ui.dataset.ref,
                    chapter: _ui.dataset.chapter,
                },
                execDone: function (_result) {

                    fpcm.ui_dialogs.create({
                        id: 'help',
                        title: 'HL_HELP',
                        size: 'xl',
                        content: _result,
                        closeButton: true,
                        headlines: true
                    });
                }
            });
            
            return false;
        });

    },
    
    addAjaxNotifications: function(_nstring, _count) {
        
        fpcm.dom.assignHtml('#fpcm-id-notifications', _nstring);
        fpcm.dom.assignHtml('#notificationsCount', _count);
        
        
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
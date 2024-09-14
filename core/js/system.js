/**
 * FanPress CM system javascript functions
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2024, Stefan Seehafer
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

        fpcm.dom.bindClick('#btnMinifyMenu', function (_el) {

            let _descr = [
                {
                    text: 'GLOBAL_HIDE',
                    icon: 'fa-chevron-left'
                },
                {
                    text: 'GLOBAL_SHOW',
                    icon: 'fa-chevron-right'
                }
            ];

            fpcm.dom.fromTag('a.fpcm.ui-nav-link.nav-link > span.fpcm.nav-text').toggleClass('d-lg-none');
            fpcm.dom.fromTag('a.fpcm.ui-nav-link.nav-link').toggleClass('text-center');

            _el.currentTarget.dataset.navhidden = parseInt(_el.currentTarget.dataset.navhidden) ? 0 : 1;

            let _current = _descr[_el.currentTarget.dataset.navhidden];
            if (!_current) {
                return false;
            }

            _el.currentTarget.title = fpcm.ui.translate(_current.text);
            _el.delegateTarget.childNodes[0].classList.replace(_el.delegateTarget.childNodes[0].classList[3], _current.icon);
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


                if (fpcm.vars.jsvars.articleId > 0 && fpcm.editor && fpcm.editor.showInEditDialog) {
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


            _content = '<div class="mb-5">' + _content + '</div>';
        }

        var _ajaxParams = {
            fields: {},
            ids: fpcm.dom.getCheckboxCheckedValues('.fpcm-ui-list-checkbox')
        };

        if (!_ajaxParams.ids.length) {
            fpcm.ui.addMessage({
                type: 'error',
                txt: 'SELECT_ITEMS_MSG'
            }, true);
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
                        headlines: true,
                        dlOnOpenAfter: function (_dlg) {
                            fpcm.ui_dialogs.initScrollspy(_dlg.id);
                        }
                    });
                }
            });

            return false;
        });

    },

    addAjaxNotifications: function(_nstring, _count) {

        let _idStr = '#fpcm-id-notifications';
        if (!fpcm.dom.fromId(_idStr).length) {
            return false;
        }

        fpcm.dom.assignHtml(_idStr, _nstring);
        let _el = fpcm.dom.fromId('notificationsCount').html(_count);

        if (_count) {
            _el.removeClass('d-none');
            return true;
        }

        _el.addClass('d-none');
    },

    checkForUpdates: function () {

        fpcm.dom.bindClick('#checkUpdate', function () {
            fpcm.ajax.get('crons/exec', {
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
    },

    createCopy: function (_e) {

        let _params = _e.delegateTarget.dataset.fnArg.split(':');

        fpcm.ui_dialogs.confirm({
            clickYes: function () {
                fpcm.ajax.execFunction('copy', _params[0], {
                    data: {
                        id: _params[1]
                    },
                    execDone: function(_result) {

                        if (!_result.result) {
                            fpcm.ui.addMessage(_result.message);
                            return true;
                        }

                        if (_result.destination) {
                            fpcm.ui.relocate(_result.destination);
                            return true;
                        }

                        if (_result.callback) {

                            let _fn = _result.callback.split('.');
                            if (! typeof fpcm[_fn[0]][_fn[1]] == 'function') {
                                return false;
                            }
                            
                            return fpcm[_fn[0]][_fn[1]]();

                        }

                        return true;
                    }
                });
            }
        });

    }

};
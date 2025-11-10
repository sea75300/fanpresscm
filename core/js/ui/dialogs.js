/**
 * FanPress CM UI Tabs Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.ui_dialogs = {

    create: function(_params) {

        if (_params.clickClose !== undefined) {
            console.error('_params.clickClose is an undefined param for fpcm.ui_dialogs.create()!');
        }

        if (_params.title === undefined) {
            _params.title = '';
        }

        if (_params.id === undefined) {
            _params.id = (new Date()).getTime();
        }

        let _dlgId = 'fpcm-dialog-' + _params.id;
        let _btnbase = 'fpcm-ui-dlgbtn-' + _params.id + '-';
        let _domEx = fpcm.dom.fromId(_dlgId);

        if (_domEx.length) {

            if (!_domEx.hasClass('fpcm ui-dialog-dom')) {
                _params.content = fpcm.dom.fromId(_dlgId).html();
                _params.modalClass = 'fpcm ui-dialog-dom';
                fpcm.dom.fromId(_dlgId).remove();
            }

            _params.keepDom = true;
        }

        if (_params.dlButtons === undefined) {
            _params.dlButtons = [];
        }

        if (_params.closeButton) {

            _params.dlButtons.push({
                text: 'GLOBAL_CLOSE',
                icon: 'times',
                clickClose: true,
                showLabel: false,
                class: 'btn-outline-secondary'
            });

        }

        if (_params.url) {
            _params.content = fpcm.ui.createIFrame({
                src: _params.url,
                id: _dlgId + '-frame',
                classes: 'w-100 h-100'
            });

            if (!_params.useSize) {
                _params.class = 'modal-fullscreen';
            }

            _params.modalBodyClass = 'overflow-hidden' + (_params.modalBodyClass ? ' ' + _params.modalBodyClass : '');
        }

        if (_params.image) {
            _params.content = '<img src = "' + _params.image + '" role="presentation" / >';
        }

        if (_params.class === undefined) {
            _params.class = '';
        }

        if (_params.modalClass === undefined) {
            _params.modalClass = '';
        }

        if (_params.modalBodyClass === undefined) {
            _params.modalBodyClass = '';
        }

        if (_params.opener === undefined) {
            _params.opener = '';
        }

        if (_params.size === undefined) {
            _params.size = 'lg';
        }

        if (_params.content === undefined && !_params.keepDom) {
            _params.content = '';
        }

        if (_params.scrollable === undefined || _params.scrollable === true) {
            _params.class += ' modal-dialog-scrollable';
        }

        if (!fpcm.dom.fromId(_dlgId).length) {
            let _modal = fpcm.vars.ui.dialogTpl;

            fpcm.dom.appendHtml('#fpcm-body', _modal.replace('{$title}', fpcm.ui.translate(_params.title))
                  .replace(/\{\$id\}/g, _dlgId)
                  .replace('{$opener}', _params.opener)
                  .replace('{$class}', _params.class)
                  .replace('{$modalClass}', _params.modalClass)
                  .replace('{$modalBodyClass}', _params.modalBodyClass)
                  .replace('{$size}', _params.size ? 'modal-' + _params.size : '')
                  .replace('{$icon}', _params.icon ? fpcm.ui.getIcon(_params.icon.icon, _params.icon.params) : fpcm.ui.getIcon('info') )
                  .replace('{$buttons}', ''));
        }

        let _domEl = document.getElementById(_dlgId);

        let _bodyEl = _domEl.querySelector('div.modal-body');
        if (_bodyEl) {

            if (_params.directAssignToDom) {
                if (_params.content instanceof Object) {
                    _bodyEl.appendChild(_params.content);
                }
            }
            else if (_params.content instanceof Array && _params.content.length > 0) {

                for (_element of _params.content) {

                    if (!_element instanceof Object) {
                        console.error('Dialog content element in array must be an object.');
                        console.error(_element);
                        continue;
                    }

                    if (!_element.assignToDom || !_element.assignToDom instanceof Function) {
                        console.error('Dialog content element in array must provide a method "assignToDom".');
                        console.error(_element);
                        continue;
                    }

                    _element.assignToDom(_bodyEl);
                }

            }
            else if (_params.content instanceof Object) {
                if (!_params.content.assignToDom instanceof Function) {
                    console.error('Dialog content element in array must provide a method "assignToDom".');
                    console.error(_params.content);
                }
                else {
                    _params.content.assignToDom(_bodyEl);
                }
            }
            else if (_params.content !== undefined) {
                _bodyEl.innerHTML = _params.content;
            }
        }

        let _bsObj = new bootstrap.Modal(_domEl);

        if (!_params.keepDom) {

            _domEl.addEventListener('hidden.bs.modal', function (event) {
                 _bsObj.dispose(_domEl);
                 fpcm.dom.fromId(_dlgId).remove();

                if (_params.dlOnClose) {
                    _params.dlOnClose(this, _bsObj);
                }
            }, {
                once: true
            });
        }
        else if (_params.dlOnClose) {
            _domEl.addEventListener('hidden.bs.modal', function (event) {
                _params.dlOnClose(this, _bsObj);
            }, {
                once: true
            });
        }

        if (_params.dlOnOpen) {
            _domEl.addEventListener('show.bs.modal', function (event) {
                _params.dlOnOpen(this, _bsObj);
            }, {
                once: true
            });
        }

        _bsObj.toggle(_domEl);

        let _focused = '';

        if (_params.dlButtons !== undefined) {

            let _footer = document.querySelector('#' + _dlgId + ' div.modal-footer');
            _footer.innerHTML = '';

            for (var _idx in _params.dlButtons) {

                if (_params.dlButtons[_idx] == undefined) {
                    continue;
                }

                let _obj = _params.dlButtons[_idx];

                if (_obj.showLabel === undefined) {
                    _obj.showLabel = true;
                }

                let _btn = document.createElement('button');

                let _classes = ['btn'];
                if (_obj.primary) {
                    _classes.push('btn-primary');
                }

                if (_obj.class) {
                    _classes.push(_obj.class);
                }

                if (_obj.isLeft) {
                    _classes.push('me-auto');
                }

                _btn.id = _obj.id ? _obj.id : fpcm.ui.getUniqueID('fpcm-id-dialogbtn');
                _btn.type = 'button';
                _btn.tabIndex = (_idx + 1);
                _btn.classList.add(..._classes);

                if (!_obj.showLabel) {
                    _btn.innerHTML = fpcm.ui.getIcon(_obj.icon);
                    _btn.title = fpcm.ui.translate(_obj.text);
                }
                else {
                    _btn.innerHTML = (_obj.icon ? fpcm.ui.getIcon(_obj.icon) + ' <span class="fpcm-ui-label ps-1">' : '') + fpcm.ui.translate(_obj.text) + (_obj.icon ? '</span>' : '');
                }

                if (_obj.disabled !== undefined) {
                    _btn.disabled = _obj.disabled;
                }

                if (_obj.click == undefined && _obj.clickClose == undefined) {
                    _footer.appendChild(_btn);
                    continue;
                }

                _btn.onclick = function () {

                    try {

                        if (_obj.click) {
                            _obj.click.call(this, _bsObj, _btn);
                        }

                        if (!_obj.clickClose) {
                            return false;
                        }

                        _bsObj.toggle(_domEl);
                        return false;

                    } catch (_e) {
                        fpcm.ui.addMessage({
                            type: 'error',
                            txt: _e
                        });

                    }

                };

                _footer.appendChild(_btn);
                if (!_obj.disabled && (_obj.primary || _obj.autofocus)) {
                    _focused = _btn.id;
                }
            }

        }

        if (_focused || _params.dlOnOpenAfter) {

            _domEl.addEventListener('shown.bs.modal', function (event) {

                if (_focused) {
                    document.getElementById(_focused).focus({ focusVisible: true });
                }

                if (_params.dlOnOpenAfter) {
                    _params.dlOnOpenAfter(this, _bsObj);
                }


            }, {
                once: true
            });

        }

        if (!_params.headlines) {
            return true;
        }

        let _headlines = fpcm.dom.fromTag(_domEl).find('h3');
        if (!_headlines.length) {
            return true;
        }

        let _links = [];
        for (var i = 0, max = _headlines.length; i < max; i++) {

            let _hl = _headlines[i];

            _hl.id = _dlgId + '-hl-' + i;

            _links.push('<li class="nav-item"><a class="nav-link" href="#' + _hl.id + '">' + _hl.innerText + '</a></li>');
        }

        fpcm.dom.fromId(_dlgId + '-navbar').find('ul').append(_links.join('')).removeClass('d-none');
        fpcm.dom.fromId(_dlgId + '-navbar').removeClass('d-none');
    },

    confirm: function(_params) {

        if (_params.clickNoDefault === undefined) {
            _params.clickNoDefault = true;
        }

        fpcm.ui_dialogs.create({
            title: 'GLOBAL_CONFIRM',
            content: fpcm.ui.translate('CONFIRM_MESSAGE'),
            size: '',
            icon: {
                icon: 'circle-check'
            },
            dlButtons: [
                {
                    text: 'GLOBAL_YES',
                    icon: "check",
                    click: _params.clickYes,
                    autofocus: _params.focusYes ? true : false,
                    class: 'btn-success',
                    clickClose: true
                },
                {
                    text: 'GLOBAL_NO',
                    icon: "times",
                    click: _params.clickNo,
                    primary: _params.defaultNo ? true : false,
                    autofocus: _params.focusNo ? true : false,
                    class: 'btn-danger',
                    clickClose: _params.clickNoDefault
                }
            ]
        });

    },

    insert: function(_params) {

        var _var = {
            id: _params.id,
            title: fpcm.ui.translate(_params.title),
        };

        _var.dlButtons = _params.dlButtons ? _params.dlButtons : [];

        if (_params.fileManagerAction) {
            _var.dlButtons.unshift({
                text: 'HL_FILES_MNG',
                icon: "folder-open",
                isLeft: true,
                click: _params.fileManagerAction
            });
        }

        if (_params.insertAction) {
            _var.dlButtons.push({
                text: 'GLOBAL_INSERT',
                icon: "check",
                clickClose: true,
                click: _params.insertAction,
                primary: true
            });
        }

        if (_params.dlOnOpen) {
            _var.dlOnOpen = _params.dlOnOpen;
        }

        if (_params.dlOnOpenAfter) {
            _var.dlOnOpenAfter = _params.dlOnOpenAfter;
        }

        if (_params.dlOnClose) {
            _var.dlOnClose = _params.dlOnClose;
        }

        _var.closeButton = true;

        if (_params.content) {
            _var.content = _params.content;
        }

        if (_params.icon) {
            _var.icon = _params.icon;
        }

        if (_params.size) {
            _var.size = _params.size;
        }

        if (_params.directAssignToDom) {
            _var.directAssignToDom = _params.directAssignToDom;
        }

        fpcm.ui_dialogs.create(_var);
    },

    settings: function(_id, _cfg, _callback) {

        let _settings = fpcm.ui_dialogs.fromDOM(_cfg);
        if (!_settings) {
            return;
        }

        fpcm.ui_dialogs.create({
            id: _id +'-settings',
            title: 'HL_OPTIONS',
            size: '',
            closeButton: true,
            directAssignToDom: true,
            content: _settings,
            icon: {
                icon: 'cogs',
            },
            dlOnOpenAfter: function () {
                fpcm.ui.selectmenu('select[data-user_setting]', {
                    change: function (_ev, _ui) {

                        document.getElementById('pageSelect').selectedIndex = 0;

                        fpcm.ajax.post('setconfig', {
                            data: {
                                var: _ui.dataset.user_setting,
                                value: _ui.value
                            },
                            execDone: function (_result) {
                                fpcm.vars.jsvars.dialogs[_cfg].fields[_ui.dataset.index].preSelected = _ui.value;
                                _callback(_ev, _ui, _result);
                            }
                        });
                    }
                });
            }
        });

    },

    search: function(_name, _searchCallBack, _resetCallBack) {

        if (!fpcm.search) {
            return false;
        }

        if (!fpcm.search._dlg) {
            fpcm.search._dlg = new fpcm.ui.forms.searchDialog(fpcm.ui_dialogs.getConfig('search'));
        }

        fpcm.ui_dialogs.create({
            id: _name + '-search',
            title: 'ARTICLES_SEARCH',
            closeButton: true,
            directAssignToDom: true,
            content: fpcm.search._dlg.getRendered(),
            size: 'xl',
            dlButtons: [
                {
                    text: fpcm.ui.translate('GLOBAL_ADD'),
                    icon: "plus",
                    class: 'btn-success',
                    showLabel: false,
                    isLeft: true,
                    click: function(_ui, _bsObj) {
                        fpcm.search._dlg.addNewCondition();
                    }
                },
                {
                    text: fpcm.ui.translate('ARTICLE_SEARCH_START'),
                    icon: "search",
                    clickClose: true,
                    class: 'btn-primary',
                    click: _searchCallBack
                },
                {
                    text: fpcm.ui.translate('GLOBAL_RESET'),
                    icon: "filter-circle-xmark" ,
                    clickClose: true,
                    click: _resetCallBack
                }
            ],
            dlOnOpenAfter: function () {
                fpcm.ui_dnd.initDnd({
                    destination: fpcm.search._dlg.getFullListId(),
                    group: 'shared',
                    dropCallback: function (_e) {

                        let _rows =  _e.to.children;
                        if (!_rows.length) {
                            return;
                        }

                        let _ridx = 0;
                        for (var _row of _rows) {

                            let _list = _row.querySelectorAll('[data-ridx]');
                            if (!_list.length) {
                                return;
                            }

                            for (var _el of _list) {
                                _el.dataset.ridx = _ridx;
                            }

                            _ridx++;
                        }
                    }
                });
            }
        });


    },

    close: function(_id, _parent) {

        if (!_id) {
            return false;
        }

        if (!_parent) {
            _parent = false;
        }

        var _domEl = false;
        var _bsObj = false;

        _id = 'fpcm-dialog-' + _id;

        _domEl = _parent ? window.parent.document.getElementById(_id) : document.getElementById(_id);

        if (!_domEl) {
            console.warn('Item ' + _id + ' not found!');
            return false;
        }

        _bsObj = _parent ? window.parent.bootstrap.Modal.getOrCreateInstance(_domEl) : window.bootstrap.Modal.getOrCreateInstance(_domEl);
        if (!_bsObj) {
            console.warn('Failed to create bootstrap item instance for ' + _id + '!');
            return false;
        }

        _bsObj.toggle(_domEl);
        return true;
    },

    initScrollspy: function (_id) {
        var _spylist = [].slice.call(document.querySelectorAll('#' + _id + ' [data-bs-spy="scroll"]'));
        _spylist.forEach(function (_el) {
            bootstrap.ScrollSpy.getOrCreateInstance(_el).refresh();
        });
    },

    getConfig: function(_dialogname) {

        if (!fpcm.vars.jsvars.dialogs[_dialogname]) {
            console.error(`No dialog with id ${_dialogname} defined.`);
            return false;
        }

        if (!fpcm.vars.jsvars.dialogs[_dialogname].fields) {
            console.error(`Invalid dialog ${_dialogname} definition found.`);
            return false;
        }

        return fpcm.vars.jsvars.dialogs[_dialogname];
    },

    fromDOM: function (_dialogname) {

        let _dlgCgf = fpcm.ui_dialogs.getConfig(_dialogname);
        if (_dlgCgf === false) {
            return false;
        }

        let _form = document.createElement('div');

        for (var _field of _dlgCgf.fields) {

            if (_field instanceof Array) {

                let _cols = _field.length;

                if (_cols > 5) {
                    console.warn(`A dialog row must not contain more then five elements, skipping`);
                    continue;
                }

                let _rowForm = document.createElement('div');
                _rowForm.classList.add('row', 'row-cols-1', 'row-cols-lg-' + _cols, 'my-3', 'gap-3', 'gap-lg-0', 'align-items-center');

                for (var _in of _field) {
                    fpcm.ui_dialogs.appendField(_in, _rowForm, true);
                }

                _form.appendChild(_rowForm);
                continue;
            }

            fpcm.ui_dialogs.appendField(_field, _form);
        }

        return _form;
    },

    mapItems: function (_item) {

        if (_item === 'textInput') {
            return {
                callback: 'input',
                type: 'text'
            };
        }

        if (_item === 'numberInput') {
            return {
                callback: 'input',
                type: 'number'
            };
        }

        if (_item === 'dateTimeInput') {
            return {
                callback: 'input',
                type: 'date'
            };
        }

        if (_item === 'radiobutton') {
            return {
                callback: 'radiocheck',
                type: 'radio'
            };
        }

        if (_item === 'checkbox') {
            return {
                callback: 'radiocheck',
                type: 'checkbox'
            };
        }

        if (_item === 'boolSelect') {
            return {
                callback: 'select',
                type: 'select'
            };
        }

        return _item;
    },

    appendField: function (_field, _form, _multiple, _options) {

        if (_options === undefined) {
            _options = {};
        }

        let _map = fpcm.ui_dialogs.mapItems(_field.callback);
        if (_map !== _field.callback) {
            _field.callback = _map.callback;
        }

        if (!fpcm.ui.forms[_field.callback]) {
            console.error(`No fpcm.ui.form element defined for ${_field.callback}!`);
            return false;
        }

        let _tmp = new fpcm.ui.forms[_field.callback];

        if (_options.idIndex === undefined && _options.namePattern === undefined) {
            _tmp.name = _field.name;
            _tmp.id = _field.id;
        }

        _tmp.label = _field.text;
        _tmp.class = _field.class;
        _tmp.wrapper = `${_field.labelType} ${_field.bottomSpace}`;
        if (_map.type !== undefined) {
            _tmp.type = _map.type;
        }

        if (_field.selected !== undefined) {
            _tmp.preSelected = _field.selected;
        }

        if (!_tmp.assignFormObject) {
            return false;
        }

        _tmp.assignFormObject(_field);
        if (_options.idIndex !== undefined) {
            _tmp.id = _field.id + _options.idIndex;
        }

        if (_options.namePattern !== undefined) {
            _tmp.name = _options.namePattern;
        }

        let _colDescr = document.createElement('div');

        if (_options.colClass !== undefined) {
            _colDescr.classList.add(..._options.colClass);
        }
        else {
            _colDescr.classList.add('col');
        }

        _tmp.assignToDom(_colDescr);

        if (_multiple) {
            _form.appendChild(_colDescr);
            return true;
        }

        let _row = document.createElement('div');
        _row.className = 'row mb-3';
        _row.appendChild(_colDescr);
        _form.appendChild(_row);
    }

}
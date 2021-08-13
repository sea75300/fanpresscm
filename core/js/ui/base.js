/**
 * FanPress CM UI Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.ui = {

    _intVars: {},
    _autocompletes: {},

    init: function() {

        fpcm.ui.mainToolbar = fpcm.dom.fromId('#fpcm-ui-toolbar');
        fpcm.dom.bindClick('button[data-fn]', function (_event, _callee) {

            _event.preventDefault();

            let _fn = _callee.dataset.fn.split('.');
            if (! typeof fpcm[_fn[0]][_fn[1]] == 'function') {
                return false;
            }
            
            let _args = null;
            if (_callee.dataset.fnArg) {
                _args = _callee.dataset.fnArg;
            }
            
            fpcm[_fn[0]][_fn[1]](_event, _callee, _args);
            return false;
        })

        fpcm.ui.showMessages();

        fpcm.ui.initJqUiWidgets();
        fpcm.ui.initDateTimeMasks();       

        fpcm.dom.bindEvent('#fpcm-ui-form', 'submit', function () {
            fpcm.ui_loader.show();
            return true;            
        }, false);

        if (fpcm.vars.jsvars.navigationActive) {
            fpcm.dom.fromId(fpcm.vars.jsvars.navigationActive).find('a.nav-link').addClass('active');
            
        }

        fpcm.dom.fromClass('fpcm.ui-link-fancybox').fancybox();
    },

    initJqUiWidgets: function () {

        fpcm.dom.fromClass('fpcm-ui-button-confirm').click(function() {

            fpcm.ui_loader.hide();
            if (!confirm(fpcm.ui.translate('CONFIRM_MESSAGE'))) {
                fpcm.ui_loader.hide();
                return false;
            }
            
            if (!fpcm.dom.fromTag(this).data('hidespinner')) {
                fpcm.ui_loader.show();
            }

        });

        fpcm.ui.initPager();
    },
    
    translate: function(langVar) {
        return fpcm.vars.ui.lang[langVar] === undefined ? langVar : fpcm.vars.ui.lang[langVar];
    },
    
    langvarExists: function(langVar) {
        return fpcm.vars.ui.lang[langVar] === undefined ? false : true;
    },
    
    assignCheckboxes: function() {
        
        var _cbxAll = fpcm.dom.fromId('fpcm-select-all');
        if (!_cbxAll.length) {
            return false;
        }

        fpcm.dom.bindClick(_cbxAll, function(_event, _ui) {

            fpcm.dom.fromClass('fpcm-ui-list-checkbox-sub').prop('checked', false);
            fpcm.dom.fromClass('fpcm-ui-list-checkbox').prop('checked', (                    
                _ui.checked ? true : false
            ));

        });

        var _cbxSub = fpcm.dom.fromClass('fpcm-ui-list-checkbox-sub');
        if (!_cbxSub.length) {
            return false;
        }

        fpcm.dom.bindClick(_cbxSub, function(_event, _ui) {

            fpcm.dom.fromClass('fpcm-ui-list-checkbox-subitem' + _ui.value).prop('checked', (                    
                _ui.checked ? true : false
            ));

        });

    },

    selectmenu: function(_elemClassId, _params) {

        let _el = fpcm.dom.fromTag(_elemClassId);
        if (_params.change) {

            _el.bind('change', function (_ev) {
                
                _ev.preventDefault();
                try {
                    _params.change(_ev, this);
                } catch (_e) {
                    fpcm.ui.addMessage({
                        type: 'error',
                        txt: _e
                    });
                }

            });
        }

    },
    
    progressbar: function(_cid, _params){

        if (_params === undefined) {
            _params = {};
        }

        let _el = fpcm.dom.fromId('fpcm-progress-' + _cid).find('div.progress-bar').get(0);
        if (!_el) {
            return false;
        }

        if (_params.min === undefined) {
            _params.min = 0;
        }

        if (_params.max === undefined) {
            _params.max = 0;
        }

        if (_params.value === undefined) {
            _params.value = 0;
        }

        if (_params.label === undefined) {
            _params.label = '';
        }

        _el.setAttribute('ariaValuenow', _params.value);
        _el.setAttribute('ariaValuemax', _params.max);
        _el.setAttribute('ariaValuemin', _params.min);
        _el.innerText = _params.label;
        _el.style.width = (_params.value * 100 / _params.max) + '%';
        
        if (_el.label && !_el.classList.contains('p-2')) {
            _el.classList.add('p-2');
        }

        if (_params.animated && !_el.classList.contains('progress-bar-animated')) {
            _el.classList.add('progress-bar-animated');
        }
    },
    
    dialog: function(params) {

        if (params.clickClose !== undefined) {
            console.error('params.clickClose is an undefined param for fpcm.ui.dialog()!');
        }

        if (params.title === undefined) {
            params.title = '';
        }

        if (params.id === undefined) {
            params.id = (new Date()).getTime();
        }

        let _dlgId = 'fpcm-dialog-' + params.id;
        let _btnbase = 'fpcm-ui-dlgbtn-' + params.id + '-';
        let _domEx = fpcm.dom.fromId(_dlgId);
        
        if (_domEx.length) {
            
            if (!_domEx.hasClass('fpcm ui-dialog-dom')) {
                params.content = fpcm.dom.fromId(_dlgId).html();
                params.modalClass = 'fpcm ui-dialog-dom';
                fpcm.dom.fromId(_dlgId).remove();
            }
            
            params.keepDom = true;
        }

        if (params.dlButtons === undefined) {
            params.dlButtons = [];
        }

        if (params.closeButton) {
            
            params.dlButtons.push({
                text: 'GLOBAL_CLOSE',
                icon: 'times',
                clickClose: true,
                class: 'btn-outline-secondary'
            });
            
        }

        if (params.url) {
            params.content = fpcm.ui.createIFrame({
                src: params.url,
                id: _dlgId + '-frame',
                classes: 'w-100 h-100'
            });
            
            params.class = 'modal-fullscreen';
            params.modalBodyClass = 'overflow-hidden';
        }

        if (params.image) {
            params.content = '<img src = "' + params.image + '" role="presentation" / >';
        }
        
        if (params.class === undefined) {
            params.class = '';
        }
        
        if (params.modalClass === undefined) {
            params.modalClass = '';
        }
        
        if (params.modalBodyClass === undefined) {
            params.modalBodyClass = '';
        }
        
        if (params.opener === undefined) {
            params.opener = '';
        }
        
        if (params.size === undefined) {
            params.size = 'lg';
        }
        
        if (params.content === undefined) {
            params.content = '';
        }

        if (!fpcm.dom.fromId(_dlgId).length) {            
            let _modal = fpcm.vars.ui.dialogTpl;

            fpcm.dom.appendHtml('#fpcm-body', _modal.replace('{$title}', fpcm.ui.translate(params.title))
                  .replace('{$id}', _dlgId)
                  .replace('{$opener}', params.opener)
                  .replace('{$content}', params.content)
                  .replace('{$class}', params.class)
                  .replace('{$modalClass}', params.modalClass)
                  .replace('{$modalBodyClass}', params.modalBodyClass)
                  .replace('{$size}', params.size ? 'modal-' + params.size : '')
                  .replace('{$buttons}', ''));
        }

        let _domEl = document.getElementById(_dlgId);
        let _bsObj = new bootstrap.Modal(_domEl);
        
        if (!params.keepDom) {
            
            _domEl.addEventListener('hidden.bs.modal', function (event) {
                 _bsObj.dispose(_domEl);
                 fpcm.dom.fromId(_dlgId).remove();
                 
                if (params.dlOnClose) {
                    params.dlOnClose(this, _bsObj);
                }
            }, {
                once: true
            });
        }
        else if (params.dlOnClose) {
            _domEl.addEventListener('hidden.bs.modal', function (event) {
                params.dlOnClose(this, _bsObj);                 
            }, {
                once: true
            });
        }
        
        if (params.dlOnOpen) {
            _domEl.addEventListener('show.bs.modal', function (event) {
                params.dlOnOpen(this, _bsObj);
            }, {
                once: true
            });
        }

        _bsObj.toggle(_domEl);
        if (params.dlButtons !== undefined) {
            
            let _footer = document.querySelector('#' + _dlgId + ' div.modal-footer');
            _footer.innerHTML = '';
            
            for (var _idx in params.dlButtons) {

                if (params.dlButtons[_idx] == undefined) {
                    continue;
                }

                let _obj = params.dlButtons[_idx];
                
                if (_obj.showLabel === undefined) {
                    _obj.showLabel = true;
                }                
                
                let _btn = document.createElement('button');

                _btn.type = 'button';
                _btn.className = 'btn' + (_obj.primary ? ' btn-primary' : '') + (_obj.class ? ' ' + _obj.class : '');
                
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
                            _obj.click.call(this, _bsObj);
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
                if (_obj.primary || _obj.autofocus) {
                    _btn.focus();
                }

            }

        }
    },
    
    closeDialog: function(_id, _parent) {
        
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
    
    autocomplete: function(_elemClassId, _params) {

        if (fpcm.ui._autocompletes[_elemClassId]) {
            return true;
        }

        let _opt = {};

        if (_params.source === undefined) {
            _params.source = [];
        }
        
        _opt.data = _params.source === undefined || !_params.source instanceof Array ? [] : _params.source;
        _opt.highlightTyped = false;

        let _acDdEl = document.querySelector(_elemClassId);
        if (_acDdEl === null) {
            console.warn('No DOm element found for ' + _elemClassId)
            return false;
        }

        _opt.onSelectItem = function (_el) {
            _acDdEl.value = _el.value;
        };
        
        if (_params.minLength !== undefined) {
            _opt.treshold = _params.minLength;
        }
        
        if (_params.onRenderItems !== undefined) {
            _opt.onRenderItems = _params.onRenderItems;
        }
        
        if (_params.showValue !== undefined) {
            _opt.showValue = _params.showValue;
        }

        if ( _params.source instanceof Array ) {
            fpcm.ui._autocompletes[_elemClassId] = new Autocomplete(_acDdEl, _opt);
            return true;
        }

        _opt.onInput = function (_val) {

            if ( _val.length < this.treshold || !fpcm.ui._autocompletes[_elemClassId] ) {
                fpcm.ui._autocompletes[_elemClassId].setData([]);
                return false;
            }
            
            fpcm.ui._autocompletes[_elemClassId].setData([]);

            fpcm.ajax.get(_params.source + '&term=' + _val, {
                quiet: true,
                execDone: function (_result) {
                    
                    if (!_result instanceof Array) {
                        _result = [];
                    }
                    
                    fpcm.ui._autocompletes[_elemClassId].setData(_result);
                }
            });
            
            return false;
        };

        fpcm.ui._autocompletes[_elemClassId] = new Autocomplete(_acDdEl, _opt);
        fpcm.ui._autocompletes[_elemClassId].setData([]);
    },
    
    getDialogSizes: function(el, scale_factor) {

        if (el === undefined) {
            el = top;
        }

        win_with = fpcm.dom.fromTag(el).width();
        
        if (win_with <= 700) {
            scale_factor = 0.95;
        }
        else if (scale_factor === undefined && win_with <= 1024) {            
            scale_factor = 0.65;
        }
        else if (scale_factor === undefined) {
            scale_factor = 0.5;
        }

        var ret = {
            width : fpcm.dom.fromTag(top).width() * scale_factor,
            height: fpcm.dom.fromTag(top).height() * scale_factor
        }

        return ret;
    },
    
    showMessages: function() {
        
        if (window.fpcm.vars.ui.messages === undefined || !fpcm.vars.ui.messages.length) {
            return false;
        }

        for (var _i in window.fpcm.vars.ui.messages) {

            if (fpcm.vars.ui.messages[_i] === undefined) {
                continue;
            }

            fpcm.ui.createMessageBox(fpcm.vars.ui.messages[_i])
        }

    },
    
    addMessage: function(value, _clear) {

        if (_clear === undefined) {
            _clear = true;
        }

        if (fpcm.vars.ui.messages === undefined || _clear) {
            fpcm.vars.ui.messages = [];
            fpcm.dom.fromClass('fpcm.ui-message').remove();
        }
        
        if (!value.icon) {
            switch (value.type) {                    
                case 'error' :
                    value.icon = 'exclamation-triangle';
                    break;
                case 'notice' :
                    value.icon = 'check';
                    break;
                default:
                    value.icon = 'info-circle';
                    break;
            }
        }
        
        if (!value.id) {
            value.id = fpcm.ui.getUniqueID();
        }
        
        if (fpcm.ui.langvarExists(value.txt)) {
            value.txt = fpcm.ui.translate(value.txt);
        }
        else if (value.txtComplete) {
            value.txt = value.txtComplete;
        }

        fpcm.ui.createMessageBox(value);
    },
    
    createMessageBox: function(_msg)
    {
        var _css = ' alert d-flex align-items-center alert-dismissible fade show position-fixed top-50 start-50 translate-middle';
        
        _mbxId = 'msgbox-' + _msg.id;
        if (fpcm.dom.fromId(_mbxId).length > 0) {
            return true;
        }

        if (_msg.webnotify) {
            fpcm.ui_notify.show({
                body: _msg.txt,
            });

            return true;
        }

        if (_msg.type == 'info' || _msg.type == 'notice') {
            _css += ' ui-msg-fadeout';
        }

        if (_msg.type == 'error') {
            _msg.type = 'danger';
        }

        if (_msg.type == 'neutral') {
            _msg.type = 'warning';
        }

        if (_msg.type == 'notice') {
            _msg.type = 'success';
        }

        _msgCode  = '<div class="fpcm ui-message shadow alert-' + _msg.type + _css + '" role="alert">';
        _msgCode += fpcm.ui.getIcon(_msg.icon, { size: '2x' });
        _msgCode += '<div class="mx-3">' + _msg.txt + '</div>';
        _msgCode += '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="' + fpcm.ui.translate('GLOBAL_CLOSE') + '"></button>';
        _msgCode += '</div>';

        fpcm.dom.appendHtml('#fpcm-body', _msgCode);
        return true;
    },
    
    removeLoaderClass: function(elemId) {
        fpcm.dom.fromTag(_id).removeClass('fpcm-loader');
    },
    
    createIFrame: function(params) {
      
        if (!params.src) {
            console.warn('fpcm.ui.createIFrame requires a non-empty value.');
            return '';
        }
      
        if (!params.classes) {
            params.classes = 'fpcm-ui-full-width';
        }
      
        if (!params.id) {
            params.id = fpcm.ui.getUniqueID();
        }

        params.style = params.style ? 'style="' + params.style + '"' : '';

        return '<iframe src="' + params.src + '" id="' + params.id + '" class="' + params.classes + '" ' + params.style + '></iframe>';
    },

    getIcon: function(_icon, _params) {

        if (!_icon) {
            console.warn('Invalid icon class given!');
            return '';
        }
        
        if (!_params) {
            _params = {};
        }

        if (!_params.spinner) {
            _params.spinner = '';
        }

        if (!_params.stack) {
            _params.stack = '';
        }

        if (!_params.size) {
            _params.size = '1x';
        }

        if (!_params.text) {
            _params.text = '';
        }

        if (!_params.class) {
            _params.class = '';
        }

        let iconType = 'unstacked';

        if (_params.stack && _params.stackTop) {
            iconType = 'stackedTop';
        }
        else if (_params.stack && !_params.stackTop) {
            iconType = 'stacked';
        }

        let iconStr = fpcm.vars.ui.components.icon[iconType] ? fpcm.vars.ui.components.icon[iconType] : '';
        if (!iconStr) {
            return '';
        }

        return iconStr.replace('{{icon}}', _icon)
                      .replace('{{class}}', _params.class)
                      .replace('{{spinner}}', _params.spinner)
                      .replace('{{stack}}', _params.stack)
                      .replace('{{size}}', _params.size)
                      .replace('{{text}}', _params.text)
                      .replace('{{prefix}}', ( _params.prefix ? _params.prefix : fpcm.vars.ui.components.icon.defaultPrefix ));
       
        
    },

    getTextInput: function(_params) {

        if (!_params || !_params.name) {
            console.warn('Invalid input params given!');
            return '';
        }

        if (!_params.id) {
            _params.id = _params.name;
        }

        if (_params.value === undefined) {
            _params.value = '';
        }

        if (_params.text === undefined) {
            _params.text = '';
        }

        if (_params.placeholder === undefined) {
            _params.placeholder = '';
        }

        if (_params.maxlenght === undefined) {
            _params.maxlenght = '255';
        }

        if (_params.type === undefined) {
            _params.type = 'text';
        }

        if (_params.class === undefined) {
            _params.class = '';
        }

        return fpcm.vars.ui.components.input
                    .replace('{{name}}', _params.name)
                    .replace('{{id}}', _params.id)
                    .replace('{{id}}', _params.id)
                    .replace('{{value}}', _params.value)
                    .replace('&lbrace;&lbrace;value&rcub;&rcub;', _params.value)
                    .replace('{{type}}', _params.type)
                    .replace('{{text}}', _params.text)
                    .replace('{{text}}', _params.text)
                    .replace('{{class}}', _params.class)
                    .replace('{{type}}', _params.type)
                    .replace('{{placeholder}}', _params.placeholder)
                    .replace('maxlength=\"255\" ', _params.maxlenght ? 'maxlength=\"' + _params.maxlenght + '\" ' : '');
    },
    
    initDateTimeMasks: function() {
        
        if (!fpcm.vars.jsvars.dtMasks) {
            return false;
        }
        
        if (fpcm.dom.fromId('#system_dtmask').length) {
            fpcm.ui.autocomplete('#system_dtmask', {
                source: fpcm.vars.jsvars.dtMasks,
                minLength: 1
            });
        }
        
        if (fpcm.dom.fromId('#datasystem_dtmask').length) {
            fpcm.ui.autocomplete('#datasystem_dtmask', {
                source: fpcm.vars.jsvars.dtMasks,
                minLength: 1
            });
        }
    },
    
    initTabsScroll: function(elemClassId, isResize) {
        
        var el = fpcm.dom.fromTag(elemClassId);        
        var tabNav       = el.find('ul.ui-tabs-nav');
        var tabsMaxWidth = el.find('div.fpcm-tabs-scroll').width();
        
        var liElements       = el.find('li.ui-tabs-tab');
        var tabsCurrentWidth = 0;

        jQuery.each(liElements, function(key, item) {
            tabsCurrentWidth += fpcm.dom.fromTag(item).width() + 5;
        });

        if (tabNav.data('fpcmtabsscrollinit') && !isResize) {
            return true;
        }

        tabNav.data('fpcmtabsscrollinit', 1);
        if (tabsCurrentWidth <= tabsMaxWidth) {
            tabNav.width('auto');
            return false;
        }

        tabNav.width(parseInt(tabsCurrentWidth));
        return true;

    },
    
    getCheckboxCheckedValues: function(id) {

        var data = [];
        fpcm.dom.fromTag(id + ':checked').map(function (idx, item) {
            data.push(item.value);
        });

        return data;

    },

    getValuesByClass: function(_class, _indexed) {
        
        var _fields = fpcm.dom.fromClass(_class);
        if (!_fields.length) {
            return {};
        }

        _data = _indexed ? [] : {};
        _fields.map(function (idx, item) {

            var el = fpcm.dom.fromTag(item);
            if (_indexed) {
                _data.push(el.val());
                return true;
            }

            _data[el.attr('name')] = el.val();
            return true;
        });     

        return _data;
    },
    
    confirmDialog: function(params) {

        var size  = fpcm.ui.getDialogSizes(top, 0.35);
        
        if (params.defaultYes === undefined && params.defaultNo === undefined) {
            params.defaultYes = true;
        }
        
        if (params.clickNoDefault === undefined) {
            params.clickNoDefault = true;
        }

        fpcm.ui.dialog({
            title: 'GLOBAL_CONFIRM',
            content: fpcm.ui.translate('CONFIRM_MESSAGE'),
            size: '',
            dlButtons: [
                {
                    text: 'GLOBAL_YES',
                    icon: "check",
                    click: params.clickYes,
                    primary: params.defaultYes ? true : false,
                    clickClose: true
                },
                {
                    text: 'GLOBAL_NO',
                    icon: "times",
                    click: params.clickNo,
                    primary: params.defaultNo ? true : false,
                    clickClose: params.clickNoDefault
                }
            ]
        });

    },

    insertDialog: function(params) {

        var dialogParams = {
            id: params.id,
            title: fpcm.ui.translate(params.title),
        };

        dialogParams.dlButtons = params.dlButtons ? params.dlButtons : [];

        if (params.fileManagerAction) {
            dialogParams.dlButtons.push({
                text: 'HL_FILES_MNG',
                icon: "folder-open",
                click: params.fileManagerAction
            });
        }

        if (params.insertAction) {
            dialogParams.dlButtons.push({
                text: 'GLOBAL_INSERT',
                icon: "check",
                clickClose: true,
                click: params.insertAction,
                primary: true
            });
        }
         
        if (params.dlOnOpen) {
            dialogParams.dlOnOpen = params.dlOnOpen;
        }
        
        if (params.dlOnClose) {
            dialogParams.dlOnClose = params.dlOnClose;
        }
        
        dialogParams.closeButton = true;
        
        if (params.content) {
            dialogParams.content = params.content;
        }

        return fpcm.ui.dialog(dialogParams);
    },
    
    relocate: function (url) {

        if (url === 'self') {
            url = window.location.href;
        }

        window.location.href = url;
    },

    openWindow: function (url) {
        return window.open(url);
    },
    
    updateMainToolbar: function (ui) {
        
        var tabEl = ui.newTab ? ui.newTab : ui.tab;
        
        var hideButtons = ui.oldTab ? fpcm.dom.fromTag(ui.oldTab).data('toolbar-buttons') : 1;
        var showButtons = fpcm.dom.fromTag(tabEl).data('toolbar-buttons');

        fpcm.ui.mainToolbar.find('.fpcm-ui-maintoolbarbuttons-tab'+ hideButtons).addClass('fpcm-ui-hidden');
        fpcm.ui.mainToolbar.find('.fpcm-ui-maintoolbarbuttons-tab'+ showButtons).removeClass('fpcm-ui-hidden');

        fpcm.ui.controlgroup(fpcm.ui.mainToolbar, 'refresh');
    },

    resetSelectMenuSelection: function (elId) {
        fpcm.dom.resetValuesByIdsSelect([elId]);
        return true;
    },
    
    showCurrentPasswordConfirmation: function () {
        var el = fpcm.dom.fromId('fpcm-ui-currentpass-box');
        if (!el.length) {
            return false;
        }

        el.removeClass('fpcm-ui-hidden');
        return true;
    },

    isHidden: function (element) {

        if (!(element instanceof Object)) {
            element = fpcm.dom.fromTag(element);
        }

        return element.hasClass('fpcm-ui-hidden') ? true : false;
    },
    
    getUniqueID: function (descr) {
        return (new Date()).getMilliseconds() + Math.random().toString(36).substr(2, 9) + (descr ? descr : '');
    },

};
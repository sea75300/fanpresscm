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
        }, false, true);

        fpcm.ui.initShorthelpTooltips();
        fpcm.ui.initLightbox();
        
        fpcm.ui.tabs('.fpcm-ui-tabs-general');
    },

    initShorthelpTooltips: function(_) {

        let _domEl = document.getElementsByClassName('fpcm ui-button-shorthelp');
        if (!_domEl.length) {
            return true;
        }
        
        for (var i = 0; i < _domEl.length; i++) {
            new bootstrap.Tooltip(_domEl[i], {
                placement: 'auto'
            });
        }    

    },

    initJqUiWidgets: function () {

        fpcm.dom.bindClick('.fpcm.ui-button-confirm', function() {

            fpcm.ui_loader.hide();
            if (!confirm(fpcm.ui.translate('CONFIRM_MESSAGE'))) {
                fpcm.ui_loader.hide();
                return false;
            }
            
            if (!fpcm.dom.fromTag(this).data('hidespinner')) {
                fpcm.ui_loader.show();
            }
            
            return true;

        }, false, true);

        fpcm.ui.initPager();
    },
    
    initLightbox: function() {

        fpcm.dom.fromClass('fpcm.ui-link-fancybox').fancybox({
            buttons: [
                "zoom",
                "fullScreen",
                "download",
                "close"
              ]
        });

    },
    
    showMessages: function() {
        
        if (window.fpcm.vars.ui.messages === undefined || !fpcm.vars.ui.messages.length) {
            return false;
        }

        _boxes = '';
        for (var _i in window.fpcm.vars.ui.messages) {

            if (fpcm.vars.ui.messages[_i] === undefined) {
                continue;
            }

            _boxes += fpcm.ui.createMessageBox(fpcm.vars.ui.messages[_i]);
        }

        fpcm.ui.appendMessageToBody(_boxes);
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

        fpcm.ui.appendMessageToBody(fpcm.ui.createMessageBox(value));
    },
    
    createMessageBox: function(_msg)
    {
        var _css = ' toast';
        
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
        
        _msg.cbtn = '';
        _msg.bstm = 'light';

        if (_msg.type == 'error') {
            _msg.type = 'danger';
        }

        if (_msg.type == 'neutral') {
            _msg.type = 'warning';
        }

        if (_msg.type == 'notice') {
            _msg.type = 'success';
            _msg.cbtn = 'btn-close-white';
        }

        _msgCode = '   <div class="fpcm ui-message shadow ' + _css + '" role="alert" aria-live="assertive" aria-atomic="true">';
        _msgCode += '   <div class="toast-header text-white bg-'  + _msg.type +'">';
        _msgCode += fpcm.ui.getIcon(_msg.icon);
        _msgCode += '   <span class="d-inline-block w-100"></span>';
        _msgCode += '   <button type="button" class="btn-close '+_msg.cbtn+'" data-bs-dismiss="toast" data-bs-theme="'+_msg.bstm + '"  aria-label="' + fpcm.ui.translate('GLOBAL_CLOSE') + '"></button>';
        _msgCode += '   </div>';
        _msgCode += '       <div class="toast-body">';
        _msgCode += '           <div class="mx-3">' + _msg.txt + '</div>';
        _msgCode += '       </div>';
        _msgCode += '   </div>';

        return _msgCode;
    },
    
    appendMessageToBody: function(_boxes)
    {
        fpcm.dom.appendHtml('#fpcm-body', '<div class="toast-container position-fixed top-0 end-0 p-3">' + _boxes + '</div>');

        let _el = document.getElementsByClassName('fpcm ui-message');
        for (var i = 0; i < _el.length; i++) {
            var toast = new bootstrap.Toast(_el[i]);
            toast.show();        
        }
    },
    
    translate: function(langVar) {
        return fpcm.vars.ui.lang[langVar] === undefined ? langVar : fpcm.vars.ui.lang[langVar];
    },
    
    langvarExists: function(langVar) {
        return fpcm.vars.ui.lang[langVar] === undefined ? false : true;
    },
    
    assignCheckboxes: function() {

        if (!fpcm.dom.fromClass('fpcm-select-all').length) {
            return false;
        }

        fpcm.dom.bindEvent('.fpcm-select-all', 'change', function(_event, _ui) {
            fpcm.dom.fromClass('fpcm-ui-list-checkbox-sub').prop('checked', false);
            fpcm.dom.fromClass('fpcm-ui-list-checkbox').prop('checked', function (_i, _old) {
                return !_old;
            });
        });

        if (!fpcm.dom.fromClass('fpcm-ui-list-checkbox-sub').length) {
            return false;
        }

        fpcm.dom.bindEvent('.fpcm-ui-list-checkbox-sub', 'change', function(_event, _ui) {
            fpcm.dom.fromClass('fpcm-ui-list-checkbox-subitem' + _ui.value).prop('checked', function (_i, _old) {
                return !_old;
            });

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

                if (_params.resetAfter) {
                    this.selectedIndex = 0;
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
    
    multiselect: function(_id, _params) {

        if (TomSelect === undefined) {
            alert('Multiselect ui init error, "TomSelect" not defined. Check if you included the library.');
            return null;
        }

        if (!_params) {
            _params = {};
        }
        
        if (!_params.searchField) {
            _params.searchField = ['text', 'value'];
        }
        
        if (!_params.plugins) {
            _params.plugins = [];
        }
        
        if (!_params.placeholder) {
            _params.placeholder = 'EDITOR_CATEGORIES_SEARCH';
        }
        
        if (!_params.hidePlaceholder) {
            _params.hidePlaceholder = true;
        }

        _params.placeholder = fpcm.ui.translate(_params.placeholder);
        _params.plugins.push('remove_button');


        if (!document.getElementById(_id)) {
            return false;
        }

        return new TomSelect('#' + _id, _params);
    },

    createIFrame: function(params) {
      
        if (!params.src) {
            console.warn('fpcm.ui.createIFrame requires a non-empty value.');
            return '';
        }
      
        if (!params.classes) {
            params.classes = 'w-100';
        }
      
        if (!params.id) {
            params.id = fpcm.ui.getUniqueID();
        }
      
        if (!params.options) {
            params.options = [];
        }

        params.style = params.style ? ' style="' + params.style + '"' : '';
        params.options = params.options.length ? ' ' + params.options.join(' ') : '';

        return '<iframe src="' + params.src + '" id="' + params.id + '" class="' + params.classes + '"' + params.style + params.options + '></iframe>';
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
        
        if (fpcm.dom.fromId('system_dtmask').length) {
            fpcm.ui.autocomplete('#system_dtmask', {
                source: fpcm.vars.jsvars.dtMasks,
                minLength: 1
            });
        }
        
        if (fpcm.dom.fromId('datasystem_dtmask').length) {
            fpcm.ui.autocomplete('#datasystem_dtmask', {
                source: fpcm.vars.jsvars.dtMasks,
                minLength: 1
            });
        }
        
        if (fpcm.dom.fromId('usermetasystem_dtmask').length) {
            fpcm.ui.autocomplete('#usermetasystem_dtmask', {
                source: fpcm.vars.jsvars.dtMasks,
                minLength: 1
            });
        }
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
        
        if (element.hasClass('fpcm ui-hidden')) {
            return true;
        }

        return element.hasClass('fpcm-ui-hidden') ? true : false;
    },
    
    getUniqueID: function (_descr) {
        return (new Date()).getMilliseconds() + Math.random().toString(36).substr(2, 9) + (_descr ? _descr : '');
    },
    
    replaceIcon: function (_id, _haystack, _needle) {
        fpcm.dom.fromId(_id).find('span.fpcm-ui-icon').removeClass('fa-' + _haystack).addClass('fa-' + _needle);
    }

};
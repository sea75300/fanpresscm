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

    init: function() {

        fpcm.ui._intVars.msgEl = fpcm.dom.fromClass('fpcm.ui-message');
        fpcm.dom.fromTag("button[data-fn]").click(function (_e) {

            _e.preventDefault();

            let _fn = this.dataset['fn'].split('.');
            if (! typeof fpcm[_fn[0]][_fn[1]] == 'function') {
                return false;
            }
            
            let _args = null;
            if (this.dataset['fn-arg']) {
                _args = this.dataset['fn-arg'];
            }
            
            fpcm[_fn[0]][_fn[1]](_e, this, _args);
            return false;
        });
        
        
//
        fpcm.ui.showMessages();
//        fpcm.ui.messagesInitClose();

//        fpcm.ui.tabs('.fpcm-ui-tabs-general');
//        fpcm.ui.initJqUiWidgets();
//        fpcm.ui.spinner('input.fpcm-ui-spinner');
//        fpcm.ui.datepicker('input.fpcm-ui-datetime-picker');
//        fpcm.ui.accordion('.fpcm-tabs-accordion');
//        fpcm.ui.initDateTimeMasks();
//
//        fpcm.dom.fromClass('fpcm-navigation-noclick').click(function () {
//            fpcm.ui_loader.hide();
//            return false;
//        });
//

//
//        fpcm.dom.fromClass('fpcm-loader').click(function () {
//
//            var el = fpcm.dom.fromTag(this);
//            if (el.hasClass('fpcm-navigation-noclick') || el.data('hidespinner')) {
//                return false;
//            }
//
//            fpcm.ui_loader.show();
//        });
//        
//        fpcm.dom.fromId('fpcm-ui-form').submit(function () {
//            fpcm.ui_loader.show();
//            return true;
//        });
//        
//        if (fpcm.vars.jsvars.fieldAutoFocus) {
//            fpcm.dom.setFocus(fpcm.vars.jsvars.fieldAutoFocus);
//        }

    },

    initJqUiWidgets: function () {

        fpcm.dom.fromClass('fpcm-ui-button.fpcm-ui-button-confirm').unbind('click');

        fpcm.ui.mainToolbar = fpcm.ui.controlgroup('#fpcm-ui-toolbar div.fpcm-ui-toolbar', {
            onlyVisible: true
        });

        fpcm.ui.assignControlgroups();

        fpcm.dom.fromClass('fpcm-ui-button.fpcm-ui-button-confirm').click(function() {

            fpcm.ui_loader.hide();
            if (!confirm(fpcm.ui.translate('CONFIRM_MESSAGE'))) {
                fpcm.ui_loader.hide();
                return false;
            }
            
            if (!fpcm.dom.fromTag(this).data('hidespinner')) {
                fpcm.ui_loader.show();
            }

        });

        fpcm.ui.selectmenu('.fpcm-ui-input-select');

        fpcm.ui.assignCheckboxes();
        fpcm.ui.articleActionsOkButton();
        fpcm.ui.initPager();
    },
    
    translate: function(langVar) {
        return fpcm.vars.ui.lang[langVar] === undefined ? langVar : fpcm.vars.ui.lang[langVar];
    },
    
    langvarExists: function(langVar) {
        return fpcm.vars.ui.lang[langVar] === undefined ? false : true;
    },
    
    articleActionsOkButton: function () {

        var el = fpcm.dom.fromClass('fpcm-ui-articleactions-ok');

        el.unbind('click');
        el.click(function () {

            for (var object in fpcm) {
                if (typeof fpcm[object].assignActions === 'function' && fpcm[object].assignActions() === -1) {
                    return false;
                }
            }

            if (!confirm(fpcm.ui.translate('CONFIRM_MESSAGE'))) {
                return false;
            }

        });

    },
    
    assignControlgroups: function() {
        fpcm.ui.controlgroup('div.fpcm-ui-controlgroup', {
            onlyVisible: false
        });
    },
    
    assignCheckboxes: function() {
        
        var checkboxAll = fpcm.dom.fromId('fpcm-select-all');
        if (!checkboxAll.length) {
            return false;
        }

        checkboxAll.unbind('click');
        checkboxAll.click(function() {
            
            var el0 = fpcm.dom.fromClass('fpcm-ui-list-checkbox-sub');
            el0.prop('checked', false);
            if (fpcm.vars.jsvars.checkboxRefresh) {
               el0.checkboxradio('refresh');
            }
            
            var el1 = fpcm.dom.fromClass('fpcm-ui-list-checkbox');
            el1.prop('checked', (
                fpcm.dom.fromTag(this).prop('checked') ? true : false
            ));

            if (fpcm.vars.jsvars.checkboxRefresh) {
                el1.checkboxradio('refresh');
            }
        });

        var checkboxSub = fpcm.dom.fromClass('fpcm-ui-list-checkbox-sub');
        if (!checkboxSub.length) {
            return false;
        }

        checkboxSub.unbind('click');
        checkboxSub.click(function() {
            var el2 = fpcm.dom.fromClass('fpcm-ui-list-checkbox-subitem' + fpcm.dom.fromTag(this).val());
            el2.prop('checked', ( fpcm.dom.fromTag(this).prop('checked') ? true : false ));
            
            if (fpcm.vars.jsvars.checkboxRefresh) {
                el2.checkboxradio('refresh');
            }
        });
    },
    
    assignSelectmenu: function() {
        fpcm.ui.selectmenu('.fpcm-ui-input-select');
    },
    
    accordion: function(elemClassId, params) {
        
        var el = fpcm.dom.fromTag(elemClassId);
        if (!el.length) {
            return;
        }

        if (params === undefined) {
            params = {
                header: "h2",
                heightStyle: "content"
            };
        }

        return el.accordion(params);
    },
    
    tabs: function(_elemClassId, params) {

        fpcm.ui_tabs.render(_elemClassId, params);

    },

    spinner: function(elemClassId, params) {
        
        var el = fpcm.dom.fromTag(elemClassId);
        if (!el.length) {
            return;
        }

        if (params === undefined) params = {};

        el.spinner(params);
    },

    datepicker: function(elemClassId, params) {

        var el = fpcm.dom.fromTag(elemClassId);
        if (!el.length) {
            return;
        }

        if (params === undefined) {
            params = {};
        }

        params.showButtonPanel   = true,
        params.showOtherMonths   = true,
        params.selectOtherMonths = true,
        params.monthNames        = fpcm.vars.ui.lang.calendar.months;
        params.dayNames          = fpcm.vars.ui.lang.calendar.days;
        params.dayNamesShort     = fpcm.vars.ui.lang.calendar.daysShort;
        params.dayNamesMin       = fpcm.vars.ui.lang.calendar.daysShort;
        params.firstDay          = 1;
        params.dateFormat        = "yy-mm-dd";

        var elData = el.data();
        if (elData.mindate) {
            params.minDate = elData.mindate;
        }

        if (elData.maxdate) {
            params.maxDate = elData.maxdate;
        }

        el.datepicker(params);
    },

    selectmenu: function(elemClassId, params) {

//        var el = fpcm.dom.fromTag(elemClassId);
//        if (!el.length) {
//            return false;
//        }
//    
//        if (params === undefined) {
//            params = {};
//        }
//
//        if (elemClassId.substr(0,1) === '#') {
//            var dataWidth = fpcm.dom.fromTag(elemClassId).data('width');
//            if (dataWidth) {
//                params.width = dataWidth;
//            }
//        }
//
//        params.width = false;
//
//        var el = fpcm.dom.fromTag(elemClassId).selectmenu(params);
//        if (params.doRefresh) {
//            el.selectmenu('refresh');
//        }
//
//        return el;
        
    },
    
    checkboxradio: function(elemClassId, params, onClick) {

        var el = fpcm.dom.fromTag(elemClassId);
        if (!el.length) {
            return;
        }

        if (params === undefined) {
            params = {};
        }
        
        if (params.icon === undefined) {
            params.icon = true;
        }

        el.checkboxradio(params);

        if (onClick === undefined) {
            return;
        }
        
        el.click(onClick);
    },
    
    controlgroup: function(elemClassId, params) {
      
//        if (params === undefined) {
//            params = {};
//        }
//
//        var el = fpcm.dom.fromTag(elemClassId);
//        if (!el.length) {
//            return false;
//        }
//
//        var elResult = el.controlgroup(params);
//        if (params.removeLeftBorderRadius) {
//            elResult.find('.ui-controlgroup-item').first().removeClass('ui-corner-left');
//        }
//
//        if (params.removeRightBorderRadius) {
//            elResult.find('.ui-controlgroup-item').first().removeClass('ui-corner-right');
//        }
//        
//        return elResult;
    },
    
    button: function(elemClassId, params, onClick) {

        if (params === undefined) {
            params = {};
        }

        fpcm.dom.fromTag(elemClassId).button(params);
        
        if (onClick === undefined) {
            return;
        }
        
        fpcm.dom.fromTag(elemClassId).click(onClick);
    },
    
    progressbar: function(elemClassId, params){

        if (params === undefined) {
            params = {};
        }

        return fpcm.dom.fromTag(elemClassId).progressbar(params);
    },
    
    dialog: function(params) {

//        if (params.title === undefined) {
//            params.title = '';
//        }
//
//        if (params.id === undefined) {
//            params.id = (new Date()).getTime();
//        }
//
//        if (params.dlWidth === undefined) {
//            var size = fpcm.ui.getDialogSizes();
//            params.dlWidth = size.width;
//        }
//
//        if (params.modal === undefined) {
//            params.modal = true;
//        }
//
//        if (params.resizable === undefined) {
//            params.resizable = false;
//        }
//        
//        var dialogId = 'fpcm-dialog-'+  params.id;
//        if (params.content !== undefined) {
//            fpcm.dom.appendHtml('#fpcm-body', '<div class="fpcm-ui-dialog-layer fpcm-editor-dialog" id="' + dialogId + '">' +  params.content + '</div>');
//        }
//
//        var el = fpcm.dom.fromId(dialogId);
//        if (!el.length) {
//            return false;
//        }
//
//        if (params.defaultCloseEmpty) {
//            params.dlOnClose = function () {
//                fpcm.dom.fromTag(this).dialog("close");
//                fpcm.dom.fromTag(this).empty();
//                return false;
//            }
//        }
//        else if (params.defaultClose) {
//            params.dlOnClose = function () {
//                fpcm.dom.fromTag(this).dialog("close");
//                return false;
//            }
//        }
//
//        var dlParams = {};
//        dlParams.width    = params.dlWidth;        
//        dlParams.modal    = params.modal;
//        dlParams.resizable= params.resizable;
//        dlParams.title    = params.title;
//        dlParams.buttons  = params.dlButtons;
//        dlParams.open     = params.dlOnOpen;
//        dlParams.close    = params.dlOnClose;
//        
//        if (params.dlHeight !== undefined) {
//            dlParams.height   = params.dlHeight;            
//        }
//        
//        if (params.dlMinWidth !== undefined) {
//            dlParams.minWidth   = params.dlMinWidth;
//        }
//        
//        if (params.dlMinHeight !== undefined) {
//            dlParams.minHeight   = params.dlMinHeight;            
//        }
//        
//        if (params.dlMaxWidth !== undefined) {
//            dlParams.maxWidth   = params.dlMaxWidth;            
//        }
//        
//        if (params.dlMaxHeight !== undefined) {
//            dlParams.maxHeight   = params.dlMaxHeight;            
//        }
//        
//        if (params.dlPosition !== undefined) {
//            dlParams.position   = params.dlPosition; 
//        }
//        
//        if (params.onCreate) {
//            dlParams.create = params.onCreate;
//        }
//        
//        if (params.onResize !== undefined) {
//            dlParams.resize   = params.onResize; 
//        }
//
//        dlParams.show = true;
//        dlParams.hide = true;

        params.id = 'fpcm-dialog-' + params.id;

        let _buttons = '';
        if (params.dlButtons !== undefined) {
            
            for (var _idx in params.dlButtons) {
                
                let _bid = 'fpcm-ui-dlgbtn-' + Date.now();
                let _obj = params.dlButtons[_idx];
                let _str = '<button type="button" id="' + _bid + '" class="btn' + (_obj.primary ? ' btn-primary' : '') + (_obj.class ? ' ' + _obj.class : '') + '">' + (_obj.icon ? fpcm.ui.getIcon(_obj.icon) : '') + ' <span class="fpcm-ui-label ps-1">' + fpcm.ui.translate(_obj.text) + '</span> </button>'

                if (_obj.click) {
//                    _str.addEventListener('onclick', params.click);
                    
                    document.getElementById(_bid).addEventListener('onclick', _obj.click);
                    
//                    fpcm.dom.fromId(_str).unbind('click');
//                    fpcm.dom.fromId(_str).click(_obj.click);
                }

                _buttons += _str;
            }
            
            
        }


        let _modal = fpcm.vars.ui.dialogTpl;

        fpcm.dom.appendHtml('#fpcm-body', _modal.replace('{$title}', fpcm.ui.translate(params.title))
              .replace('{$id}', params.id)
              .replace('{$opener}', params.opener)
              .replace('{$content}', params.content)
              .replace('{$buttons}', _buttons));
        
        
        
        let _domEl = document.getElementById(params.id);
        let _bsObj = new bootstrap.Modal(_domEl);
        _bsObj.show(_domEl);
        _domEl.addEventListener('hidden.bs.modal', function (event) {
             _bsObj.dispose(_domEl);
             fpcm.dom.fromId(params.id).remove();
        });




//        return el.dialog(dlParams);
    },
    
    autocomplete: function(elemClassId, params) {

        var el = fpcm.dom.fromTag(elemClassId);
        if (!el.length) {
            return false;
        }

        if (params.minLength === undefined) {
            params.minLength = 0;
        }

        return el.autocomplete(params);
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

        var msg = null;
        var msgBoxid = null;
        var _css = ' alert d-flex align-items-center alert-dismissible fade show position-absolute top-50 start-50 translate-middle';

        for (var i = 0; i < window.fpcm.vars.ui.messages.length; i++) {

            msg = fpcm.vars.ui.messages[i];
            msgBoxid = 'msgbox-' + msg.id;
            if (fpcm.dom.fromId(msgBoxid).length > 0) {
                continue;
            }

            if (msg.webnotify) {
                fpcm.ui_notify.show({
                    body: msg.txt,
                });
                
                continue;
            }

            if (msg.type == 'info' || msg.type == 'notice') {
                _css += ' ui-msg-fadeout';
            }

            if (msg.type == 'error') {
                msg.type = 'danger';
            }

            if (msg.type == 'neutral') {
                msg.type = 'warning';
            }

            if (msg.type == 'notice') {
                msg.type = 'success';
            }

            msgCode  = '<div class="fpcm ui-message alert-' + msg.type + _css + '" role="alert">';
            msgCode += fpcm.ui.getIcon(msg.icon, { size: '2x' });
            msgCode += '<div class="mx-3">' + msg.txt + '</div>';
            msgCode += '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Schließen"></button>';
            msgCode += '</div>';
            fpcm.dom.appendHtml('#fpcm-body', msgCode);
        }

    },
    
    addMessage: function(value, clear) {

        if (fpcm.vars.ui.messages === undefined || clear) {
            fpcm.vars.ui.messages = [];
            fpcm.ui._intVars.msgEl.empty();
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

        fpcm.vars.ui.messages.push(value);
        fpcm.ui.showMessages();
        fpcm.ui.messagesInitClose();

    },
    
    messagesInitClose: function() {
        
        fpcm.ui._intVars.msgEl.find('.fpcm-ui-messages-close').unbind('click');
        fpcm.ui._intVars.msgEl.find('.fpcm-ui-messages-close').click(function (event, ui) {

            let _data = fpcm.dom.fromTag(this).data();

            let _el = fpcm.dom.fromId('msgbox-' + _data.msgid);
            _el.fadeOut('slow');
            _el.remove();

            if (fpcm.vars.ui.messages[_data.msgidx] === undefined) {
                return true;
            }

            delete fpcm.vars.ui.messages.splice(_data.msgidx, 1);
            return true;
        }).mouseover(function (event, ui) {
            fpcm.dom.fromTag(this).find('.fa.fa-square').removeClass('fa-inverse');
            fpcm.dom.fromTag(this).find('.fa.fa-times').addClass('fa-inverse');
        }).mouseout(function (event, ui) {
            fpcm.dom.fromTag(this).find('.fa.fa-square').addClass('fa-inverse');
            fpcm.dom.fromTag(this).find('.fa.fa-times').removeClass('fa-inverse'); 
        });
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
        
        fpcm.ui.autocomplete('#system_dtmask', {
            source: fpcm.vars.jsvars.dtMasks
        });

        fpcm.ui.autocomplete('#usermetasystem_dtmask', {
            source: fpcm.vars.jsvars.dtMasks
        });
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
        
        if (params.clickNoDefault) {
            params.clickNo = function () {
                fpcm.dom.fromTag(this).dialog("close");
                return false;
            }
        }
        
        if (params.defaultYes === undefined && params.defaultNo === undefined) {
            params.defaultYes = true;
        }

        fpcm.ui.dialog({
            title: 'GLOBAL_CONFIRM',
            content: fpcm.ui.translate('CONFIRM_MESSAGE'),
            dlWidth: size.width,
            dlButtons: [
                {
                    text: 'GLOBAL_YES',
                    icon: "check",
                    click: params.clickYes,
                    primary: params.defaultYes ? true : false
                },
                {
                    text: 'GLOBAL_NO',
                    icon: "times",
                    click: params.clickNo,
                    primary: params.defaultNo ? true : false
                }
            ]
        });

    },

    insertDialog: function(params) {

        var dialogParams = {
            id: params.id,
            title: fpcm.ui.translate(params.title),
            dlWidth: params.dlWidth,
        };

        if (params.dlHeight !== undefined) {
            dialogParams.dlHeight = params.dlHeight;
        }
        
        if (params.resizable !== undefined) {
            dialogParams.resizable = params.resizable;
        }

        dialogParams.dlButtons = params.dlButtons ? params.dlButtons : [];

        if (params.insertAction) {
            dialogParams.dlButtons.push({
                text: fpcm.ui.translate('GLOBAL_INSERT'),
                icon: "ui-icon-check",
                class: "btn-primary",
                click: params.insertAction
            });
        }

        if (params.fileManagerAction) {
            dialogParams.dlButtons.push({
                text: fpcm.ui.translate('HL_FILES_MNG'),
                icon: "ui-icon-folder-open",
                click: params.fileManagerAction
            });
        }
 
        if (!params.closeAction) {
            params.closeAction = function () {
                fpcm.dom.fromTag(this).dialog("close");
            }
        }
        
        dialogParams.dlButtons.push({
            text: fpcm.ui.translate('GLOBAL_CLOSE'),
            icon: "ui-icon-closethick", 
            click: params.closeAction
        });
        
        if (params.dlOnOpen) {
            dialogParams.dlOnOpen = params.dlOnOpen;
        }
        
        if (params.dlOnClose) {
            dialogParams.dlOnClose = params.dlOnClose;
        }
        
        if (params.onCreate) {
            dialogParams.onCreate = params.onCreate;
        }

        return fpcm.ui.dialog(dialogParams);
    },
    
    initPager: function(params) {

        if (params === undefined) {
            params = {};
        }
        
        if (!fpcm.vars.jsvars.pager) {
            return false;
        }

        var backEl = fpcm.dom.fromId('pagerBack');
        var nextEl = fpcm.dom.fromId('pagerNext');

        backEl.unbind('click');
        nextEl.unbind('click');

        if (!params.backAction) {
            params.backAction = function () {
                fpcm.dom.fromTag(this).attr('href', fpcm.vars.jsvars.pager.linkString.replace('__page__', fpcm.vars.jsvars.pager.showBackButton) );
            };
        }

        if (!params.nextAction) {
            params.nextAction = function () {
                fpcm.dom.fromTag(this).attr('href', fpcm.vars.jsvars.pager.linkString.replace('__page__', fpcm.vars.jsvars.pager.showNextButton) );
            };
        }

        if (fpcm.vars.jsvars.pager.showBackButton) {
            backEl.removeClass('fpcm-ui-readonly');
            backEl.click(params.backAction);
        }
        else if (!fpcm.vars.jsvars.pager.showBackButton && backEl && !backEl.hasClass('fpcm-ui-readonly')) {
            backEl.addClass('fpcm-ui-readonly');
        }
        
        if (fpcm.vars.jsvars.pager.showNextButton) {
            nextEl.removeClass('fpcm-ui-readonly');
            nextEl.click(params.nextAction);
        }
        else if (!fpcm.vars.jsvars.pager.showNextButton && nextEl && !nextEl.hasClass('fpcm-ui-readonly')) {
            nextEl.addClass('fpcm-ui-readonly');
        }

        var selectId    = 'pageSelect';
        var selectEl    = fpcm.dom.fromId(selectId);
        selectEl.unbind('select');
        
        if (!params.keepSelect) {
            selectEl.empty();
        }
        
        if (fpcm.vars.jsvars.pager.maxPages) {
            for(i=1; i<= fpcm.vars.jsvars.pager.maxPages; i++) {
                selectEl.append( '<option ' + (fpcm.vars.jsvars.pager.currentPage === i ? 'selected' : '') + ' value="' + i + '">' + fpcm.ui.translate('GLOBAL_PAGER').replace('{{current}}', i).replace('{{total}}', fpcm.vars.jsvars.pager.maxPages) + '</option>');
            }
        }
        
        if (!params.selectAction) {
            params.selectAction = function( event, ui ) {

                if (ui.item.value == fpcm.vars.jsvars.pager.currentPage) {
                    return false;
                }

                if (ui.item.value == '1') {
                    window.location.href = fpcm.vars.actionPath + fpcm.vars.jsvars.currentModule;
                    return true;
                }
                window.location.href = fpcm.vars.actionPath + fpcm.vars.jsvars.currentModule + '&page=' + ui.item.value;
            };
        }

        var selectParams = {
            width : 'auto',
            select: params.selectAction,
            classes: {
                'ui-selectmenu-button': 'fpcm-ui-pager-element'
            },
            doRefresh: true
        };

        fpcm.ui.selectmenu('#' + selectId, selectParams);
        
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
    
    /* deprecated about to remove in fpcm 4.6 or later! */
    setFocus: function(elemId) {
        console.warn('fpcm.ui.setFocus is deprecated and will be removed shortly. Use fpcm.dom.setFocus instead!');
        fpcm.dom.setFocus(elemId);
    },

    assignHtml: function(elemId, data) {
        console.warn('fpcm.ui.assignHtml is deprecated and will be removed shortly. Use fpcm.dom.assignHtml instead!');
        fpcm.dom.assignHtml(elemId, data);
    },
    
    assignText: function(elemId, data) {
        console.warn('fpcm.ui.assignText is deprecated and will be removed shortly. Use fpcm.dom.assignText instead!');
        fpcm.dom.assignText(elemId, data);
    },
    
    appendHtml: function(elemId, data) {
        console.warn('fpcm.ui.appendHtml is deprecated and will be removed shortly. Use fpcm.dom.appendHtml instead!');
        fpcm.dom.appendHtml(elemId, data);
    },
    
    prependHtml: function(elemId, data) {
        console.warn('fpcm.ui.prependHtml is deprecated and will be removed shortly. Use fpcm.dom.prependHtml instead!');
        fpcm.dom.prependHtml(elemId, data);
    },
    
    isReadonly: function(elemId, state) {
        console.warn('fpcm.ui.isReadonly is deprecated and will be removed shortly. Use fpcm.dom.isReadonly instead!');
        fpcm.dom.isReadonly(elemId, state);
    }

};
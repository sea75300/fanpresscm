/**
 * FanPress CM UI Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.ui = {

    _intVars: {},

    init: function() {

        console.log('ui.init');

        fpcm.ui._intVars.msgEl = fpcm.dom.fromId('fpcm-messages');

        fpcm.ui.showMessages();
        fpcm.ui.messagesInitClose();

        fpcm.ui.tabs('.fpcm-ui-tabs-general');
        fpcm.ui.initJqUiWidgets();
        fpcm.ui.spinner('input.fpcm-ui-spinner');
        fpcm.ui.datepicker('input.fpcm-ui-datetime-picker');
        fpcm.ui.accordion('.fpcm-tabs-accordion');
        fpcm.ui.initDateTimeMasks();

        fpcm.dom.fromClass('fpcm-navigation-noclick').click(function () {
            fpcm.ui_loader.hide();
            return false;
        });

        fpcm.dom.fromId('fpcm-clear-cache').click(function () {
            return fpcm.system.clearCache();
        });

        fpcm.dom.fromClass('fpcm-loader').click(function () {

            var el = fpcm.dom.fromTag(this);
            if (el.hasClass('fpcm-navigation-noclick') || el.data('hidespinner')) {
                return false;
            }

            fpcm.ui_loader.show();
        });
        
        fpcm.dom.fromId('fpcm-ui-form').submit(function () {
            fpcm.ui_loader.show();
            return true;
        });
        
        if (fpcm.vars.jsvars.fieldAutoFocus) {
            fpcm.ui.setFocus(fpcm.vars.jsvars.fieldAutoFocus);
        }

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
    
    tabs: function(elemClassId, params) {
    
        if (params === undefined) params = {};
        
        var el = fpcm.dom.fromTag(elemClassId);
        if (!el.length) {
            return;
        }
        
        if (params.addMainToobarToggle) {
            params.beforeActivate = function( event, ui ) {
                fpcm.ui.updateMainToolbar(ui);
                if (params.addMainToobarToggleAfter) {
                    params.addMainToobarToggleAfter(event, ui);
                }

            }            
        }
        
        if (params.saveActiveTab) {
            params.activate = function(event, ui) {
                fpcm.vars.jsvars.activeTab = fpcm.dom.fromTag(this).tabs('option', 'active');
                fpcm.dom.fromId('activeTab').val(fpcm.vars.jsvars.activeTab);
                fpcm.ui.updateMainToolbar(ui);
                if (params.saveActiveTabAfter) {
                    params.saveActiveTabAfter(event, ui);
                }
            };

            params.create = function(event, ui) {
                fpcm.ui.updateMainToolbar(ui);
                if (params.saveActiveTabAfterInit) {
                    params.saveActiveTabAfterInit(event, ui);
                }
            }
        }

        if (params.initDataViewJson) {

            params.beforeLoad = function(event, ui) {

                fpcm.ui_loader.show();   
                
                tabList = ui.tab.data('dataview-list');                
                if (!tabList) {
                    return true;
                }

                if (params.initDataViewJsonBeforeLoad) {
                    params.initDataViewJsonBefore(event, ui);
                }

                if (!params.dataFilterParams) {
                    params.dataFilterParams = function( response ) {
                        this.dataTypes = ['html', 'text'];
                        return response;
                    };
                    
                }

                ui.ajaxSettings.dataTypes = ['json'];
                ui.ajaxSettings.accepts = 'application/json';
                ui.ajaxSettings.dataFilter = params.dataFilterParams;

                ui.jqXHR.done(function(jqXHR) {

                    if (typeof jqXHR !== 'object' || !jqXHR.dataViewVars) {
                        return true;
                    }

                    fpcm.vars.jsvars.dataviews[tabList] = jqXHR.dataViewVars;
                    if (params.initbeforeLoadDone) {
                        params.initbeforeLoadDone(jqXHR);
                    }
                    
                    return true;
                });

                ui.jqXHR.fail(function(jqXHR, textStatus, errorThrown) {
                    console.error(fpcm.ui.translate('AJAX_RESPONSE_ERROR'));
                    console.error('STATUS MESSAGE: ' + textStatus);
                    console.error('ERROR MESSAGE: ' + errorThrown);
                    fpcm.ajax.showAjaxErrorMessage();
                    fpcm.ui_loader.hide();
                });
            };

            params.load = function(event, ui) {

                var tabList = ui.tab.data('dataview-list');                
                if (!tabList) {
                    return true;
                }

                if (!fpcm.vars.jsvars.dataviews[tabList]) {
                    return false;
                }

                if (params.initDataViewJsonBefore) {
                    params.initDataViewJsonBefore(event, ui);
                }

                ui.panel.append(fpcm.dataview.getDataViewWrapper(tabList, params.dataViewWrapperClass ? params.dataViewWrapperClass : ''));

                fpcm.dataview.updateAndRender(
                    tabList,
                    {
                        onRenderAfter: params.initDataViewOnRenderAfter
                });

                if (params.initDataViewJsonAfter) {
                    params.initDataViewJsonAfter(event, ui);
                }

                fpcm.ui_loader.hide();
                return false;
            };
        }
        
        var tabEl = el.tabs(params);
        
        if (params.addTabScroll) {

            el.find('ul.ui-tabs-nav').wrap('<div class="fpcm-tabs-scroll"></div>');
            fpcm.ui.initTabsScroll(elemClassId);
            
            fpcm.dom.fromWindow().resize(function() {
                fpcm.ui.initTabsScroll(elemClassId, true);
            });

        }

        return tabEl;

    },

    spinner: function(elemClassId, params) {
        
        var el = fpcm.dom.fromTag(elemClassId);
        if (!el.length) {
            return;
        }

        if (params === undefined) params = {};

        if (params.removeCornerLeft) {
            params.classes = {
                'ui-spinner': "fpcm-ui-border-radius-right"
            }
        }

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

        var el = fpcm.dom.fromTag(elemClassId);
        if (!el.length) {
            return false;
        }
    
        if (params === undefined) {
            params = {};
        }

        if (elemClassId.substr(1,1) === '#') {
            var dataWidth = fpcm.dom.fromTag(elemClassId).data('width');
            if (dataWidth) {
                params.width = dataWidth;
            }
        }
        
        if (params.width === undefined) {
            params.width = 300;
        }

        var el = fpcm.dom.fromTag(elemClassId).selectmenu(params);
        if (params.removeCornerLeft || (elemClassId.substr(1,1) === '#' && el.data('remove_corner_left'))) {
            el.selectmenu( "option", "classes.ui-selectmenu-button", "fpcm-ui-border-radius-right" );
        }

        if (params.doRefresh) {
            el.selectmenu('refresh');
        }

        return el;
        
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
      
        if (params === undefined) {
            params = {};
        }

        var el = fpcm.dom.fromTag(elemClassId);
        if (!el.length) {
            return false;
        }

        var elResult = el.controlgroup(params);
        if (params.removeLeftBorderRadius) {
            elResult.find('.ui-controlgroup-item').first().removeClass('ui-corner-left');
        }

        if (params.removeRightBorderRadius) {
            elResult.find('.ui-controlgroup-item').first().removeClass('ui-corner-right');
        }
        
        return elResult;
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

        if (params.title === undefined) {
            params.title = '';
        }

        if (params.id === undefined) {
            params.id = (new Date()).getTime();
        }

        if (params.dlWidth === undefined) {
            var size = fpcm.ui.getDialogSizes();
            params.dlWidth = size.width;
        }

        if (params.modal === undefined) {
            params.modal = true;
        }

        if (params.resizable === undefined) {
            params.resizable = false;
        }
        
        var dialogId = 'fpcm-dialog-'+  params.id;
        if (params.content !== undefined) {
            fpcm.dom.appendHtml('#fpcm-body', '<div class="fpcm-ui-dialog-layer fpcm-editor-dialog" id="' + dialogId + '">' +  params.content + '</div>');
        }

        var el = fpcm.dom.fromId(dialogId);
        if (!el.length) {
            return false;
        }

        if (params.defaultCloseEmpty) {
            params.dlOnClose = function () {
                fpcm.dom.fromTag(this).dialog("close");
                fpcm.dom.fromTag(this).empty();
                return false;
            }
        }
        else if (params.defaultClose) {
            params.dlOnClose = function () {
                fpcm.dom.fromTag(this).dialog("close");
                return false;
            }
        }

        var dlParams = {};
        dlParams.width    = params.dlWidth;        
        dlParams.modal    = params.modal;
        dlParams.resizable= params.resizable;
        dlParams.title    = params.title;
        dlParams.buttons  = params.dlButtons;
        dlParams.open     = params.dlOnOpen;
        dlParams.close    = params.dlOnClose;
        
        if (params.dlHeight !== undefined) {
            dlParams.height   = params.dlHeight;            
        }
        
        if (params.dlMinWidth !== undefined) {
            dlParams.minWidth   = params.dlMinWidth;
        }
        
        if (params.dlMinHeight !== undefined) {
            dlParams.minHeight   = params.dlMinHeight;            
        }
        
        if (params.dlMaxWidth !== undefined) {
            dlParams.maxWidth   = params.dlMaxWidth;            
        }
        
        if (params.dlMaxHeight !== undefined) {
            dlParams.maxHeight   = params.dlMaxHeight;            
        }
        
        if (params.onCreate !== undefined) {
            dlParams.create   = params.onCreate; 
        }

        dlParams.show = true;
        dlParams.hide = true;

        return el.dialog(dlParams);
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

        for (var i = 0; i < window.fpcm.vars.ui.messages.length; i++) {

            msg = fpcm.vars.ui.messages[i];
            msgBoxid = 'msgbox-' + msg.id;
            if (fpcm.dom.fromId(msgBoxid).length > 0) {
                continue;
            }

            msgCode  = '<div class="row fpcm-message-box fpcm-message-' + msg.type + '" id="' + msgBoxid + '">';
            msgCode += '    <div class="col-12 col-sm-11 fpcm-ui-padding-none-lr">';
            msgCode += '        <div class="row">';
            msgCode += '            <div class="col-12 col-sm-auto align-self-center fpcm-ui-center fpcm-ui-padding-none-lr">';
            msgCode += '                <span class="fa-stack fa-2x">';
            msgCode += '                    <span class="fa fa-square fa-stack-2x fa-inverse"></span>';                    
            msgCode += '                    <span class="fa fa-' + msg.icon + ' fa-stack-1x"></span>';
            msgCode += '                </span>';
            msgCode += '            </div>';
            msgCode += '            <div class="col-12 col-sm-10 align-self-center fpcm-ui-ellipsis">' + msg.txt +  '</div>';
            msgCode += '        </div>';
            msgCode += '    </div>';
            msgCode += '    <div class="col-12 col-sm-1 fpcm-ui-padding-none-lr fpcm-ui-messages-close fpcm-ui-align-right" id="msgclose-' + msg.id + '">';
            msgCode += '        <span class="fa-stack"><span class="fa fa-square fa-stack-2x fa-inverse"></span><span class="fa fa-times fa-stack-1x"></span></span>';
            msgCode += '    </div>';
            msgCode += '</div>';
            fpcm.dom.appendHtml('#fpcm-messages', msgCode);
        }

        fpcm.dom.fromTag('div.fpcm-message-box.fpcm-message-notice').delay(1000).fadeOut('slow');
        fpcm.dom.fromTag('div.fpcm-message-box.fpcm-message-neutral').delay(1000).fadeOut('slow');        
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
        fpcm.ui._intVars.msgEl.find('.fpcm-ui-messages-close').click(function () {
            var closeId = fpcm.dom.fromTag(this).attr('id');
            fpcm.dom.fromId('msgbox-' + closeId.substring(9)).fadeOut('slow');
        }).mouseover(function (event, ui) {
            fpcm.dom.fromTag(this).find('.fa.fa-square').removeClass('fa-inverse');
            fpcm.dom.fromTag(this).find('.fa.fa-times').addClass('fa-inverse');
        }).mouseout(function (event, ui) {
            fpcm.dom.fromTag(this).find('.fa.fa-square').addClass('fa-inverse');
            fpcm.dom.fromTag(this).find('.fa.fa-times').removeClass('fa-inverse');
        });
    },
    
    setFocus: function(elemId) {
        fpcm.dom.setFocus(elemId);
    },
    
    assignHtml: function(elemId, data) {
        fpcm.dom.assignHtml(elemId, data);
    },
    
    assignText: function(elemId, data) {
        fpcm.dom.assignText(elemId, data);
    },
    
    appendHtml: function(elemId, data) {
        fpcm.dom.appendHtml(elemId, data);
    },
    
    prependHtml: function(elemId, data) {
        fpcm.dom.prependHtml(elemId, data);
    },
    
    removeLoaderClass: function(elemId) {
        fpcm.dom.fromTag(_id).removeClass('fpcm-loader');
    },
    
    isReadonly: function(elemId, state) {
        fpcm.dom.isReadonly(elemId, state);
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
    
    showLoader: function(show, addtext) {

        if (!show) {
            console.warn('fpcm.ui.showLoader is deprecated as of FPCM 4.3. use "fpcm.ui_loader.hide() instead."');
            return fpcm.ui_loader.hide();
        }

        console.warn('fpcm.ui.showLoader is deprecated as of FPCM 4.3. use "fpcm.ui_loader.show(Your messaage) instead."');
        return fpcm.ui_loader.show(addtext ? addtext : null);
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

        fpcm.ui.dialog({
            title: fpcm.ui.translate('GLOBAL_CONFIRM'),
            content: fpcm.ui.translate('CONFIRM_MESSAGE'),
            dlWidth: size.width,
            dlButtons: [
                {
                    text: fpcm.ui.translate('GLOBAL_YES'),
                    icon: "ui-icon-check",
                    class: (params.defaultYes ? 'fpcm-ui-button-primary' : ''),
                    click: params.clickYes
                },
                {
                    text: fpcm.ui.translate('GLOBAL_NO'),
                    icon: "ui-icon-closethick",
                    class: (params.defaultNo ? 'fpcm-ui-button-primary' : ''),
                    click: params.clickNo
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
                class: "fpcm-ui-button-primary",
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
            backEl.click(params.backAction);
        }
        
        if (fpcm.vars.jsvars.pager.showNextButton) {
            nextEl.click(params.nextAction);
        }

        var selectId    = 'pageSelect';
        var selectEl    = fpcm.dom.fromId(selectId);
        selectEl.unbind('select');
        
        if (fpcm.vars.jsvars.pager.maxPages) {
            for(i=1; i<= fpcm.vars.jsvars.pager.maxPages; i++) {
                selectEl.append( '<option ' + (fpcm.vars.jsvars.pager.currentPage === i ? 'selected' : '') + ' value="' + i + '">' + fpcm.ui.translate('GLOBAL_PAGER').replace('{{current}}', i).replace('{{total}}', fpcm.vars.jsvars.pager.maxPages) + '</option>');
            }
        }
        
        if (!params.selectAction) {
            params.selectAction = function( event, ui ) {
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
        var selectEl = fpcm.dom.fromTag(elId);
        selectEl.prop('selectedIndex', 0);
        selectEl.val('');
        selectEl.selectmenu("refresh");
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

    testing: function (_params) {
        
        if (!_params) {
            _params = {};
        }
        
        if (!_params.step) {
            _params.step = 1;
        }
        
        fpcm.ajax.get('testing', {
            data: {
                timestamp: (new Date()).getTime(),
                step: _params.step
            },
            cache: false,
            dataType: 'json',
            execDone: function (result) {
                console.log(result);
                
                if (result.res == 2) {
                    return false;
                }
                
                
                fpcm.ui.testing({
                    step: result.step
                });
            }
        })
        
        
    }

};
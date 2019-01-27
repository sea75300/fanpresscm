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

    init: function() {

        fpcm.ui.highlightModule();
        fpcm.ui.showMessages();
        fpcm.ui.messagesInitClose();

        fpcm.ui.tabs('.fpcm-ui-tabs-general');
        fpcm.ui.initJqUiWidgets();
        fpcm.ui.initInputShadow();
        fpcm.ui.spinner('input.fpcm-ui-spinner');
        fpcm.ui.accordion('.fpcm-tabs-accordion');
        fpcm.ui.initDateTimeMasks();
        
        jQuery('#itemshowMenu').find('a').click(function () {
            fpcm.ui.showLoader(false);
            jQuery('div.fpcm-navigation ul.fpcm-menu li.fpcm-menu-level1-show').toggle();
            return false;
        });

        jQuery('.fpcm-navigation-noclick').click(function () {
            fpcm.ui.showLoader(false);
            return false;
        });
        
        jQuery('.fpcm-menu-level1-hassubmenu').hover(function () {
            
            if (jQuery(window).width() < 768) {
                return true;
            }
            
            jQuery(this).find('ul.fpcm-submenu').css('left', jQuery('#fpcm-navigation-ul').width());
        });

        jQuery('#fpcm-clear-cache').click(function () {
            return fpcm.system.clearCache();
        });

        jQuery('.fpcm-loader').click(function () {
            if (jQuery(this).hasClass('fpcm-noloader') || jQuery(this).hasClass('fpcm-navigation-noclick')) return false;
            fpcm.ui.showLoader(true);
        });
        
        if (fpcm.vars.jsvars.fieldAutoFocus) {
            fpcm.ui.setFocus(fpcm.vars.jsvars.fieldAutoFocus);
        }

    },

    initJqUiWidgets: function () {

        fpcm.ui.mainToolbar = fpcm.ui.controlgroup('#fpcm-ui-toolbar div.fpcm-ui-toolbar', {
            onlyVisible: true
        });

        fpcm.ui.assignControlgroups();

        jQuery('.fpcm-ui-button.fpcm-ui-button-confirm').click(function() {
            fpcm.ui.showLoader(false);
            if (!confirm(fpcm.ui.translate('CONFIRM_MESSAGE'))) {
                fpcm.ui.showLoader(false);
                return false;
            }
            fpcm.ui.showLoader(true);
        });

        fpcm.ui.selectmenu('.fpcm-ui-input-select');

        fpcm.ui.assignCheckboxes();
        fpcm.ui.articleActionsOkButton();
        fpcm.ui.initPager();
        
        noActionButtonAssign = false;
    },
    
    translate: function(langVar) {
        return fpcm.vars.ui.lang[langVar] === undefined ? langVar : fpcm.vars.ui.lang[langVar];
    },
    
    langvarExists: function(langVar) {
        return fpcm.vars.ui.lang[langVar] === undefined ? false : true;
    },
    
    articleActionsOkButton: function () {

        if (window.noActionButtonAssign) return false;

        jQuery('.fpcm-ui-articleactions-ok').click(function () {

            for (var object in fpcm) {
                if (typeof fpcm[object].assignActions === 'function' && fpcm[object].assignActions() === -1) {
                    return false;
                }
            }

            fpcm.ui.removeLoaderClass(this);
            if (!confirm(fpcm.ui.translate('CONFIRM_MESSAGE'))) {
                jQuery(this).addClass('fpcm-noloader');
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
        
        var checkboxAll = jQuery('#fpcm-select-all');
        if (!checkboxAll.length) {
            return false;
        }

        checkboxAll.click(function() {
            
            var el0 = jQuery('.fpcm-ui-list-checkbox-sub');
            el0.prop('checked', false);
            if (fpcm.vars.jsvars.checkboxRefresh) {
               el0.checkboxradio('refresh');
            }
            
            var el1 = jQuery('.fpcm-ui-list-checkbox');
            el1.prop('checked', (
                jQuery(this).prop('checked') ? true : false
            ));

            if (fpcm.vars.jsvars.checkboxRefresh) {
                el1.checkboxradio('refresh');
            }
        });

        var checkboxSub = jQuery('.fpcm-ui-list-checkbox-sub');
        if (!checkboxSub.length) {
            return false;
        }

        checkboxSub.click(function() {
            var el2 = jQuery('.fpcm-ui-list-checkbox-subitem' + jQuery(this).val());
            el2.prop('checked', ( jQuery(this).prop('checked') ? true : false ));
            
            if (fpcm.vars.jsvars.checkboxRefresh) {
                el2.checkboxradio('refresh');
            }
        });
    },
    
    assignSelectmenu: function() {
        fpcm.ui.selectmenu('.fpcm-ui-input-select');
    },

    
    initInputShadow: function() {
        jQuery('.fpcm-ui-input-wrapper input[type=text]').focus(function () {
            jQuery(this).parent().parent().addClass('fpcm-ui-input-wrapper-hover');
        }).blur(function () {
            jQuery(this).parent().parent().removeClass('fpcm-ui-input-wrapper-hover');
        });

        jQuery('.fpcm-ui-input-wrapper input[type=password]').focus(function () {
            jQuery(this).parent().parent().addClass('fpcm-ui-input-wrapper-hover');
        }).blur(function () {
            jQuery(this).parent().parent().removeClass('fpcm-ui-input-wrapper-hover');
        });

        jQuery('.fpcm-ui-input-wrapper textarea').focus(function () {
            jQuery(this).parent().parent().addClass('fpcm-ui-input-wrapper-hover');
        }).blur(function () {
            jQuery(this).parent().parent().removeClass('fpcm-ui-input-wrapper-hover');
        });  
    },
    
    accordion: function(elemClassId, params) {
        
        var el = jQuery(elemClassId);
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
        
        var el = jQuery(elemClassId);
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
                fpcm.vars.jsvars.activeTab = jQuery(this).tabs('option', 'active');
                jQuery('#activeTab').val(fpcm.vars.jsvars.activeTab);
                fpcm.ui.updateMainToolbar(ui);
                if (params.saveActiveTabAfter) {
                    params.saveActiveTabAfter(event, ui);
                }
            }
        }
        
        var tabEl = el.tabs(params);
        
        if (params.addTabScroll) {

            el.find('ul.ui-tabs-nav').wrap('<div class="fpcm-tabs-scroll"></div>');
            fpcm.ui.initTabsScroll(elemClassId);
            
            jQuery(window).resize(function() {
                fpcm.ui.initTabsScroll(elemClassId, true);
            });

        }

        return tabEl;

    },

    spinner: function(elemClassId, params) {
        
        var el = jQuery(elemClassId);
        if (!el.length) {
            return;
        }

        if (params === undefined) params = {};        
        el.spinner(params);

    },

    datepicker: function(elemClassId, params) {

        var el = jQuery(elemClassId);
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

        el.datepicker(params);
    },

    selectmenu: function(elemClassId, params) {

        var el = jQuery(elemClassId);
        if (!el.length) {
            return false;
        }
    
        if (params === undefined) {
            params = {};
        }

        if (elemClassId.substr(1,1) === '#') {
            var dataWidth = jQuery(elemClassId).attr('data-width');
            if (dataWidth) {
                params.width = dataWidth;
            }
        }
        
        if (params.width === undefined) {
            params.width = 200;
        }

        var el = jQuery(elemClassId).selectmenu(params);
        if (params.doRefresh) {
            el.selectmenu('refresh');
        }

        return el;
        
    },
    
    checkboxradio: function(elemClassId, params, onClick) {

        var el = jQuery(elemClassId);
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

        var el = jQuery(elemClassId);
        if (!el.length) {
            return false;
        }

        return el.controlgroup(params);

    },
    
    button: function(elemClassId, params, onClick) {

        if (params === undefined) {
            params = {};
        }

        jQuery(elemClassId).button(params);
        
        if (onClick === undefined) {
            return;
        }
        
        jQuery(elemClassId).click(onClick);
    },
    
    progressbar: function(elemClassId, params){

        if (params === undefined) {
            params = {};
        }

        jQuery(elemClassId).progressbar(params);
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
            fpcm.ui.appendHtml('#fpcm-body', '<div class="fpcm-ui-dialog-layer fpcm-editor-dialog" id="' + dialogId + '">' +  params.content + '</div>');
        }

        var el = jQuery('#' + dialogId);
        if (!el.length) {
            return false;
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

        var el = jQuery(elemClassId);
        if (!el.length) {
            return false;
        }

        if (params.minLength === undefined) {
            params.minLength = 0;
        }

        return el.autocomplete(params);
    },
    
    highlightModule: function() {

        if (fpcm.vars.jsvars.navigationActive !== undefined) {
            jQuery('#' + fpcm.vars.jsvars.navigationActive).addClass('fpcm-menu-active');
        }

        var active_submenu_items = jQuery('#fpcm-navigation-ul ul.fpcm-submenu').find('li.fpcm-menu-active');
        if (active_submenu_items.length !== undefined && active_submenu_items.length) {
            jQuery(active_submenu_items[0]).parent().parent().addClass('fpcm-menu-active');
        }

    },
    
    getDialogSizes: function(el, scale_factor) {

        if (el === undefined) {
            el = top;
        }

        win_with = jQuery(el).width();
        
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
            width : jQuery(top).width() * scale_factor,
            height: jQuery(top).height() * scale_factor
        }

        return ret;
    },
    
    showMessages: function() {
        
        if (window.fpcm.vars.ui.messages === undefined || !fpcm.vars.ui.messages.length) {
            return false;
        }

        var msg = null;
        for (var i = 0; i < window.fpcm.vars.ui.messages.length; i++) {
            msg = fpcm.vars.ui.messages[i];
            msgCode  = '<div class="row fpcm-message-box fpcm-message-' + msg.type + '" id="msgbox-' + msg.id + '">';
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
            msgCode += '    <div class="col-12 col-sm-1 fpcm-ui-padding-none-lr fpcm-ui-messages-close" id="msgclose-' + msg.id + '">';
            msgCode += '        <span class="fa-stack"><span class="fa fa-square fa-stack-2x fa-inverse"></span><span class="fa fa-times fa-stack-1x"></span></span>';
            msgCode += '    </div>';
            msgCode += '</div>';
            fpcm.ui.appendHtml('#fpcm-messages', msgCode);
        }
        
        jQuery('div.fpcm-message-box.fpcm-message-notice').delay(1000).fadeOut('slow');
        jQuery('div.fpcm-message-box.fpcm-message-neutral').delay(1000).fadeOut('slow');        
    },
    
    addMessage: function(value, clear) {
        if (fpcm.vars.ui.messages === undefined || clear) {
            fpcm.vars.ui.messages = [];
            jQuery('#fpcm-messages').empty();
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
            value.id = (new Date()).getTime();
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
        var msgEl = jQuery('#fpcm-messages');
        var closeEl = msgEl.find('.fpcm-ui-messages-close');
        
        closeEl.click(function () {
            var closeId = jQuery(this).attr('id');
            jQuery('#msgbox-' + closeId.substring(9)).fadeOut('slow');
        }).mouseover(function () {
            jQuery(this).find('.fa.fa-square').removeClass('fa-inverse');
            jQuery(this).find('.fa.fa-times').addClass('fa-inverse');
        }).mouseout(function () {
            jQuery(this).find('.fa.fa-square').addClass('fa-inverse');
            jQuery(this).find('.fa.fa-times').removeClass('fa-inverse');
        });
    },
    
    setFocus: function(elemId) {
        jQuery('#' + elemId).focus();
    },
    
    assignHtml: function(elemId, data) {
        jQuery(elemId).html(data);
    },
    
    assignText: function(elemId, data) {
        jQuery(elemId).text(data);
    },
    
    appendHtml: function(elemId, data) {
        jQuery(elemId).append(data);
    },
    
    prependHtml: function(elemId, data) {
        jQuery(elemId).prepend(data);
    },
    
    removeLoaderClass: function(elemId) {
        jQuery(elemId).removeClass('fpcm-loader');
    },
    
    isReadonly: function(elemId, state) {
        jQuery(elemId).prop('readonly', state);
    },
    
    createIFrame: function(params) {
      
        if (!params.src) {
            console.warn('fpcm.ui.createIFrame requires a set and non-empty value.');
            return '';
        }
      
        if (!params.classes) {
            params.classes = 'fpcm-ui-full-width';
        }
      
        if (!params.id) {
            params.id = jQuery.uniqueId();
        }

        return '<iframe ' + params.src + ' id="' + params.id + '" class="' + params.classes + '"></iframe>';
        
    },
    
    showLoader: function(show, addtext) {

        if (!show) {
            jQuery('#fpcm-loader').fadeOut('fast', function(){
                jQuery(this).remove();
            });
            return false;
        }

        fpcm.ui.appendHtml('#fpcm-body', '<div class="fpcm-loader" id="fpcm-loader" style="' + window.spinnerParams + '"><span class="fa-stack fa-fw ' + (addtext ? 'fa-lg' : 'fa-2x') + '"><span class="fa fa-circle fa-stack-2x fpcm-ui-status-075"></span><span class="fa fa-spinner fa-pulse fa-stack-1x fa-inverse fa-fw"></span></span> ' + (addtext ? '<span>' + addtext + '</span>' : '') + '</div>');

        jQuery('#fpcm-loader').css('top',  ( parseInt( (jQuery(window).height() * 0.5) - (jQuery('#fpcm-loader').height() / 2) ) + 'px' ) )
                              .css('left', ( parseInt( (jQuery(window).width() * 0.5) - (jQuery('#fpcm-loader').width() / 2) ) + 'px' ) )
                              .fadeIn(100);

        return true;
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
        
        var el = jQuery(elemClassId);        
        var tabNav       = el.find('ul.ui-tabs-nav');
        var tabsMaxWidth = el.find('div.fpcm-tabs-scroll').width();
        
        var liElements       = el.find('li.ui-tabs-tab');
        var tabsCurrentWidth = 0;

        jQuery.each(liElements, function(key, item) {
            tabsCurrentWidth += jQuery(item).width() + 5;
        });

        if (tabNav.attr('data-fpcmtabsscrollinit') && !isResize) {
            return true;
        }

        tabNav.attr('data-fpcmtabsscrollinit', 1);
        if (tabsCurrentWidth <= tabsMaxWidth) {
            tabNav.width('auto');
            return false;
        }

        tabNav.width(parseInt(tabsCurrentWidth));
        return true;

    },
    
    getCheckboxCheckedValues: function(id) {
        
        var data = [];
        jQuery(id + ':checked').map(function (idx, item) {
            data.push(jQuery(item).val());
        });

        return data;

    },
    
    confirmDialog: function(params) {

        var size  = fpcm.ui.getDialogSizes(top, 0.35);

        fpcm.ui.dialog({
            title: fpcm.ui.translate('GLOBAL_CONFIRM'),
            content: fpcm.ui.translate('CONFIRM_MESSAGE'),
            dlWidth: size.width,
            dlButtons: [
                {
                    text: fpcm.ui.translate('GLOBAL_YES'),
                    icon: "ui-icon-check",                    
                    click: params.clickYes
                },
                {
                    text: fpcm.ui.translate('GLOBAL_NO'),
                    icon: "ui-icon-closethick",
                    click: params.clickNo
                }
            ]
        });

    },
    
    initPager: function(params) {

        if (params === undefined) {
            params = {};
        }

        if (!fpcm.vars.jsvars.pager) {
            return false;
        }

        var backEl = jQuery('#pagerBack');
        var nextEl = jQuery('#pagerNext');

        if (!params.backAction) {
            params.backAction = function () {
                jQuery(this).attr('href', fpcm.vars.jsvars.pager.linkString.replace('__page__', fpcm.vars.jsvars.pager.showBackButton) );
            };
        }

        if (!params.nextAction) {
            params.nextAction = function () {
                jQuery(this).attr('href', fpcm.vars.jsvars.pager.linkString.replace('__page__', fpcm.vars.jsvars.pager.showNextButton) );
            };
        }

        if (fpcm.vars.jsvars.pager.showBackButton) {
            backEl.click(params.backAction);
        }
        
        if (fpcm.vars.jsvars.pager.showNextButton) {
            nextEl.click(params.nextAction);
        }

        var selectId    = '#pageSelect';
        var selectEl    = jQuery(selectId);
        
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

        fpcm.ui.selectmenu(selectId, selectParams);
        
    },
    
    relocate: function (url) {
        window.location.href = url;
    },

    openWindow: function (url) {
        return window.open(url);
    },
    
    updateMainToolbar: function (ui) {
        var hideButtons = jQuery(ui.oldTab).attr('data-toolbar-buttons');
        var showButtons = jQuery(ui.newTab).attr('data-toolbar-buttons');

        fpcm.ui.mainToolbar.find('.fpcm-ui-maintoolbarbuttons-tab'+ hideButtons).addClass('fpcm-ui-hidden');
        fpcm.ui.mainToolbar.find('.fpcm-ui-maintoolbarbuttons-tab'+ showButtons).removeClass('fpcm-ui-hidden');

        fpcm.ui.controlgroup(fpcm.ui.mainToolbar, 'refresh');
    }
    
};
/**
 * FanPress CM UI Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.ui = {

    init: function() {

        jQuery(document).tooltip();

        fpcmJs.assignButtons();
        this.assignSelectmenu();
        this.initInputShadow();
        this.spinner('input.fpcm-ui-spinner');
        this.tabs('.fpcm-tabs-general');
        this.accordion('.fpcm-tabs-accordion');
        this.highlightModule();
        this.showMessages();
        this.messagesInitClose();
        this.initDateTimeMasks();

        jQuery('.fpcm-navigation-noclick').click(function () {
            fpcm.ui.showLoader(false);
            return false;
        });
    
    
        jQuery('#fpcm-ui-showmenu-li').click(function () {
            jQuery('li.fpcm-menu-level1.fpcm-menu-level1-show').fadeToggle();
        });

        jQuery('#fpcm-logo').click(function () {
            jQuery('li.fpcm-menu-level1.fpcm-menu-level1-show').fadeOut();
        });

        jQuery('#fpcm-clear-cache').click(function () {
            return fpcmJs.clearCache();
        });

        jQuery('.fpcm-loader').click(function () {
            if (jQuery(this).hasClass('fpcm-noloader') || jQuery(this).hasClass('fpcm-navigation-noclick')) return false;
            fpcm.ui.showLoader(true);
        });
        
        if (window.fpcmFieldSetAutoFocus) {
            fpcm.ui.setFocus(window.fpcmFieldSetAutoFocus);
        }

        fpcm.ui.resize();
        jQuery(window).resize(function() {
            fpcm.ui.resize();
        });
    },
    
    translate: function(langVar) {
        
        return fpcmLang[langVar] === undefined ? langVar : fpcmLang[langVar];

    },

    actionButtonsGenreal: function() {
        jQuery('.fpcm-ui-actions-genreal').click(function () {
            if (jQuery(this).hasClass('fpcm-noloader')) jQuery(this).removeClass('fpcm-noloader');
            if (!confirm(fpcm.ui.translate('confirmMessage'))) {
                jQuery(this).addClass('fpcm-noloader');
                return false;
            }            
        });
    },
    
    assignBlankIconButton: function() {        
        this.button('.fpcm-ui-button-blank', {
            icon: "ui-icon-blank",
            showLabel: false
        });        
    },
    
    assignCheckboxes: function() {
        jQuery('#fpcmselectall').click(function(){
            jQuery('.fpcm-select-allsub').prop('checked', false);
            if (jQuery(this).prop('checked'))        
                jQuery('.fpcm-list-selectbox').prop('checked', true);
            else
                jQuery('.fpcm-list-selectbox').prop('checked', false);
        });
        jQuery('#fpcmselectalldraft').click(function(){
            jQuery('.fpcm-select-allsub-draft').prop('checked', false);
            if (jQuery(this).prop('checked'))        
                jQuery('.fpcm-list-selectbox-draft').prop('checked', true);
            else
                jQuery('.fpcm-list-selectbox-draft').prop('checked', false);
        });
        jQuery('#fpcmselectalltrash').click(function(){
            jQuery('.fpcm-select-allsub-trash').prop('checked', false);
            if (jQuery(this).prop('checked'))        
                jQuery('.fpcm-list-selectbox-trash').prop('checked', true);
            else
                jQuery('.fpcm-list-selectbox-trash').prop('checked', false);
        });
        jQuery('#fpcmselectallrevisions').click(function(){
            if (jQuery(this).prop('checked'))        
                jQuery('.fpcm-list-selectboxrevisions').prop('checked', true);
            else
                jQuery('.fpcm-list-selectboxrevisions').prop('checked', false);
        });
    },
    
    assignCheckboxesSub: function() {
        jQuery('.fpcm-select-allsub').click(function(){
            var subValue = jQuery(this).val();
            if (jQuery(this).prop('checked'))        
                jQuery('.fpcm-list-selectbox-sub' + subValue).prop('checked', true);
            else
                jQuery('.fpcm-list-selectbox-sub' + subValue).prop('checked', false);
        });
    },
    
    assignSelectmenu: function() {
        
        this.selectmenu('.fpcm-ui-input-select');
        this.selectmenu(
            '.fpcm-ui-input-select-articleactions', {
            position: {
                my: 'left top',
                at: 'left bottom+5',
                offset: null
            }
        });    

        this.selectmenu(
            '.fpcm-ui-input-select-moduleactions', {
            position: {
                my: 'left top',
                at: 'left bottom+5',
                offset: null
            }
        });

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
        
        if (params === undefined) {
            params = {
                header: "h2",
                heightStyle: "content"
            };
        }
        
        if (params.activate === undefined) {

            params.activate = function(event, ui) {
                fpcm.ui.resize();
            };

        }

        jQuery(elemClassId).accordion(params);

    },
    
    tabs: function(elemClassId, params) {
    
        if (params === undefined) params = {};
        var el = jQuery(elemClassId);
        
        el.tabs(params);
        
        if (params.addTabScroll) {

            el.find('ul.ui-tabs-nav').wrap('<div class="fpcm-tabs-scroll"></div>');
            fpcm.ui.initTabsScroll(elemClassId);
            
            jQuery(window).resize(function() {
                fpcm.ui.initTabsScroll(elemClassId, true);
            });

        }

    },

    spinner: function(elemClassId, params) {

        if (params === undefined) params = {};        
        jQuery(elemClassId).spinner(params);

    },

    datepicker: function(elemClassId, params) {

        if (params === undefined) {
            params = {};
        }

        params.showButtonPanel   = true,
        params.showOtherMonths   = true,
        params.selectOtherMonths = true,
        params.monthNames        = this.translate('jquiDateMonths'),
        params.dayNames          = this.translate('jquiDateDays'),
        params.dayNamesShort     = this.translate('jquiDateDaysShort'),
        params.dayNamesMin       = this.translate('jquiDateDaysShort')
        params.firstDay          = 1;
        params.dateFormat        = "yy-mm-dd";

        jQuery(elemClassId).datepicker(params);

    },

    selectmenu: function(elemClassId, params) {

        if (params === undefined) {
            params = {};
        }

        if (params.width === undefined) {
            params.width = 200;
        }

        return jQuery(elemClassId).selectmenu(params);

    },
    
    checkboxradio: function(elemClassId, params, onClick) {

        if (params === undefined) {
            params = {};
        }

        jQuery(elemClassId).checkboxradio(params);

        if (onClick === undefined) {
            return;
        }
        
        jQuery(elemClassId).click(onClick);
    },
    
    controlgroup: function(elemClassId, params) {
      
        if (params === undefined) {
            params = {};
        }

        jQuery(elemClassId).controlgroup(params);

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
        
        jQuery('#' + dialogId).dialog(dlParams);

        return true;
    },
    
    autocomplete: function(elemClassId, params) {
        
        if (params.minLength === undefined) {
            params.minLength = 0;
        }

        return jQuery(elemClassId).autocomplete(params);
    },
    
    highlightModule: function() {

        if (window.fpcmNavigationActiveItemId !== undefined) {
            jQuery('#' + window.fpcmNavigationActiveItemId).addClass('fpcm-menu-active');
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
        
        if (window.fpcmMsg === undefined || !fpcmMsg.length) {
            return false;
        }
        
        var msg = null;
        for (var i = 0; i < window.fpcmMsg.length; i++) {

            msg = fpcmMsg[i];
            msgCode  = '<div class="fpcm-message-box fpcm-message-' + msg.type + '" id="msgbox-' + msg.id + '">';
            msgCode += '    <div class="fpcm-msg-icon">';
            msgCode += '        <span class="fa-stack fa-lg">';
            msgCode += '            <span class="fa fa-square fa-stack-2x fa-inverse"></span>';                    
            msgCode += '            <span class="fa fa-' + msg.icon + ' fa-stack-1x"></span>';
            msgCode += '        </span>';
            msgCode += '    </div>';
            msgCode += '    <div class="fpcm-msg-text">' + msg.txt + '</div>';
            msgCode += '    <div class="fpcm-msg-close" id="msgclose-' + msg.id + '">';
            msgCode += '        <span class="fa-stack fa-lg"><span class="fa fa-square fa-stack-2x fa-inverse"></span><span class="fa fa-times fa-stack-1x"></span></span>';
            msgCode += '    </div>';
            msgCode += '</div>';
            msgCode += '<div class="fpcm-clear"></div>';
            fpcm.ui.appendHtml('#fpcm-messages', msgCode);

        }
        
    },
    
    prepareMessages: function () {
        jQuery('div.fpcm-message-box.fpcm-message-notice').delay(2000).fadeOut('slow');
        jQuery('div.fpcm-message-box.fpcm-message-neutral').delay(2000).fadeOut('slow');
        jQuery('.fpcm-messages div.fpcm-message-box').draggable({
            opacity: 0.5,
            cursor: 'move'
        });
    },
    
    appendMessage: function(value) {

        if (window.fpcmMsg === undefined) {
            window.fpcmMsg = [];
        }

        window.fpcmMsg = fpcm.ajax.fromJSON(value).data;
        this.showMessages();
        this.prepareMessages();
        this.messagesInitClose();

    },
    
    addMessage: function(value, clear) {

        if (window.fpcmMsg === undefined || clear) {
            window.fpcmMsg = [];
        }

        window.fpcmMsg.push(value);
        this.showMessages();
        this.prepareMessages();
        this.messagesInitClose();

    },
    
    messagesInitClose: function() {
        jQuery('#fpcm-messages').find('.fpcm-msg-close').click(function () {
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
    
    resize: function () {
        fpcm.ui.prepareMessages();
        jQuery('#fpcm-ui-errorbox').css('top', jQuery(window).height() / 2 - jQuery('#fpcm-ui-errorbox').height() / 2);


        var wrpl        = jQuery('#fpcm-wrapper-left');
        wrpl.css('min-height', jQuery(window).height());

        var wrpl_height = jQuery('body').height() < jQuery(window).height() ? jQuery(window).height() : jQuery('body').height();

        wrpl.css('min-height', '');
        if (jQuery(window).width() > 800) {
            jQuery('li.fpcm-menu-level1.fpcm-menu-level1-show').show();
            wrpl.css('min-height', wrpl_height);
        }
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
            params.classes = 'fpcm-full-width';
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

        fpcm.ui.appendHtml('#fpcm-body', '<div class="fpcm-loader" id="fpcm-loader" style="' + window.spinnerParams + '"><span class="fa-stack fa-fw ' + (addtext ? 'fa-lg' : 'fa-2x') + '"><span class="fa fa-circle fa-stack-2x"></span><span class="fa fa-spinner fa-pulse fa-stack-1x fa-inverse fa-fw"></span></span> ' + (addtext ? '<span>' + addtext + '</span>' : '') + '</div>');

        jQuery('#fpcm-loader').css('top',  ( parseInt( (jQuery(window).height() * 0.5) - (jQuery('#fpcm-loader').height() / 2) ) + 'px' ) )
                              .css('left', ( parseInt( (jQuery(window).width() * 0.5) - (jQuery('#fpcm-loader').width() / 2) ) + 'px' ) )
                              .fadeIn(100);

        return true;
    },
    
    initDateTimeMasks: function() {
        
        if (window.fpcmDtMasks === undefined) {
            return false;
        }
        
        fpcm.ui.autocomplete('#system_dtmask', {
            source: fpcmDtMasks
        });

        fpcm.ui.autocomplete('#usermetasystem_dtmask', {
            source: fpcmDtMasks
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
            title: fpcm.ui.translate('confirmHL'),
            content: fpcm.ui.translate('confirmMessage'),
            dlWidth: size.width,
            dlButtons: [
                {
                    text: fpcm.ui.translate('yes'),
                    icon: "ui-icon-check",                    
                    click: params.clickYes
                },
                {
                    text: fpcm.ui.translate('no'),
                    icon: "ui-icon-closethick",
                    click: params.clickNo
                }
            ]
        });

    }
    
};
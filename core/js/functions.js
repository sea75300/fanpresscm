/**
 * FanPress CM javascript functions
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

var noActionButtonAssign = false;
var noDeleteButtonAssign = false;

var fpcmJs = function () {
    
    var self = this;
    
    this.clearCache = function (params) {
        
        if (!params) {
            params = {};
        }

        fpcm.ui.showLoader(true);

        fpcm.ajax.get('cache', {
            data: params,
            execDone: function () {
                fpcm.ui.showLoader(false);
                fpcm.ui.appendMessage(fpcm.ajax.getResult('cache'));
            }
        });
        
        return false;        
    };
    
    this.relocate = function (url) {
        window.location.href = url;
    };
    
    this.assignButtons = function () {

        fpcm.ui.controlgroup('.fpcm-ui-buttonset');
        fpcm.ui.controlgroup('.fpcm-buttons.fpcm-ui-list-buttons', {
            onlyVisible: true
        });

        fpcm.ui.controlgroup('.fpcm-buttons div.fpcm-ui-margin-center', {
            onlyVisible: true
        });

        fpcm.ui.button('.fpcm-ui-button');
        fpcm.ui.actionButtonsGenreal();
        fpcm.ui.assignBlankIconButton();
        fpcm.ui.assignCheckboxes();
        fpcm.ui.assignCheckboxesSub();
        self.articleActionsOkButton();
        self.moduleActionButtons();
        self.assignDeleteButton();
        self.pagerButtons();
        
        noActionButtonAssign = false;
    };

    this.assignDeleteButton = function () {
        
        if (noDeleteButtonAssign) return false;
        
        jQuery('.fpcm-delete-btn').click(function () {
            if (jQuery(this).hasClass('fpcm-noloader')) jQuery(this).removeClass('fpcm-noloader');
            if (!confirm(fpcm.ui.translate('confirmMessage'))) {
                jQuery(this).addClass('fpcm-noloader');
                return false;
            }
        });
        
        noDeleteButtonAssign = true;
    };
    
    this.articleActionsOkButton = function () {

        if (window.noActionButtonAssign) return false;

        jQuery('.fpcm-ui-articleactions-ok').click(function () {

            for (var object in fpcm) {
                if (typeof fpcm[object].assignActions === 'function' && fpcm[object].assignActions() === -1) {
                    return false;
                }
            }

            fpcm.ui.removeLoaderClass(this);
            if (!confirm(fpcm.ui.translate('confirmMessage'))) {
                jQuery(this).addClass('fpcm-noloader');
                return false;
            }

        });

    };
    
    this.moduleActionButtons = function () {        
        if (typeof fpcmModulelist == 'undefined') return false;
        return fpcmModulelist.actionButtons();
    };
    
    this.startSearch = function (sParams) {
        if (((new Date()).getTime() - fpcmArticlesLastSearch) < 10000) {
            self.addAjaxMassage('error', fpcm.ui.translate('searchWaitMsg'));
            return false;
        }

        fpcm.ui.showLoader(true);

        fpcm.ajax.post('articles/search', {
            data: sParams,
            execDone: function () {
                fpcm.ui.showLoader(false);
                fpcm.ui.assignHtml('#tabs-article-list', fpcm.ajax.getResult('articles/search'));
                window.noActionButtonAssign = true;
                fpcmJs.assignButtons();
                fpcm.articlelist.clearArticleCache();
                fpcm.ui.resize();
            }
        });

        fpcmArticlesLastSearch = (new Date()).getTime();
    };
    
    this.addAjaxMassage = function (type, message) {

        jQuery('.fpcm-messages').empty();

        fpcm.ajax.post('addmsg', {
            data: {
                type  : type,
                msgtxt: message
            },
            execDone: function () {
                fpcm.ui.showLoader(false);
                fpcm.ui.appendMessage(fpcm.ajax.getResult('addmsg'));
            }
        });

    };
    
    this.systemCheck = function () {
        fpcm.ui.showLoader(true);
        fpcm.ajax.get('syscheck', {
            execDone: function () {
                fpcm.ui.showLoader(false);
                fpcm.ui.assignHtml("#tabs-options-check", fpcm.ajax.getResult('syscheck'));
                fpcmJs.assignButtons();
                fpcm.ui.resize();
            }
        });
        
    };
    
    this.openManualCheckFrame = function () {

        var size = fpcm.ui.getDialogSizes();

        fpcm.ui.dialog({
            id         : 'manualupdate-check',
            dlWidth    : size.width,
            dlHeight   : size.height,
            resizable  : true,
            title      : fpcmManualCheckHeadline,
            dlButtons  : [
                {
                    text: fpcm.ui.translate('newWindow'),
                    icon: "ui-icon-extlink",                    
                    click: function() {
                        window.open(fpcmManualCheckUrl);
                        jQuery(this).dialog('close');
                    }
                },
                {
                    text: fpcm.ui.translate('close'),
                    icon: "ui-icon-closethick",                    
                    click: function() {
                        jQuery(this).dialog('close');
                    }
                }
            ],
            dlOnOpen: function (event, ui) {
                jQuery(this).empty();
                fpcm.ui.appendHtml(this, '<iframe class="fpcm-full-width" style="height:100%;"  src="' + fpcmManualCheckUrl + '"></iframe>');
            },
            dlOnClose: function( event, ui ) {
                jQuery(this).empty();
            }
        });
    };

    this.pagerButtons = function() {

        fpcm.ui.selectmenu('#pageSelect', {
            select: function( event, ui ) {
                if (ui.item.value == '1') {
                    window.location.href = fpcmActionPath + fpcmCurrentModule;
                    return true;
                }
                window.location.href = fpcmActionPath + fpcmCurrentModule + '&page=' + ui.item.value;
            },
            position: {
                my: "left bottom",
                at: "left top"
            }
        });
        
    };

}
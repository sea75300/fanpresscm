
/**
 * FanPress CM Editor Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.editor = {

    init: function() {

        fpcm.editor.initToolbar();

        fpcm.editor.editorTabs = fpcm.ui.tabs('#fpcm-editor-tabs',
        {
            dataViewWrapperClass: 'fpcm-ui-editor-editlist',
            initDataViewJson: true,
            addMainToobarToggle: true,
            addTabScroll: true,
            saveActiveTab: true,
            initDataViewOnRenderAfter: function () {
                fpcm.ui.assignCheckboxes();
                fpcm.ui.assignControlgroups(),
                fpcm.editor.initCommentListActions();
            },
            active: fpcm.vars.jsvars.activeTab !== undefined ? fpcm.vars.jsvars.activeTab : 0
        });

        if (!fpcm.vars.jsvars.isRevision) {
            fpcm.editor[fpcm.vars.jsvars.editorInitFunction].call();
        }
        else {
            fpcm.dom.fromClass('fpcm-ui-editor-categories-revisiondiff .fpcm-ui-input-checkbox').click(function() {
                return false;
            });
        }

        /**
         * Keycodes
         * http://www.brain4.de/programmierecke/js/tastatur.php
         */
        fpcm.dom.fromTag(document).keydown(function(thekey) {

            if (thekey.ctrlKey && thekey.which == 83) {
                
                var _saveBtnEl = fpcm.dom.fromId('btnArticleSave');
                if(_saveBtnEl) {
                    _saveBtnEl.click();
                    return false;
                }
            }

        });

    },
    
    initAfter: function() {

        fpcm.ui.setFocus('articletitle');
        fpcm.dom.fromClass('fpcm-editor-articleimage').fancybox();

        fpcm.ui.spinner('input.fpcm-ui-spinner-hour', {
            min: 0,
            max: 23
        });

        fpcm.ui.spinner('input.fpcm-ui-spinner-minutes', {
            min: 0,
            max: 59
        });

        fpcm.dom.fromId('insertarticleimg').click(function () {
            fpcm.editor.showFileManager(3);
            return false;
        });

        fpcm.ui.autocomplete('#articleimagepath', {
            source: fpcm.vars.ajaxActionPath + 'autocomplete&src=editorfiles',
            minLength: 3,
            position: {
                my: "left bottom",
                at: "left top"
            }
        });

        fpcm.ui.autocomplete('#articlesources', {
            source: fpcm.vars.ajaxActionPath + 'autocomplete&src=articlesources',
            minLength: 3
        });

        fpcm.editor.tweetTextInput = fpcm.dom.fromId('articletweettxt');
        fpcm.ui.selectmenu('#twitterReplacements', {
            change: function( event, ui ) {

                if (ui.item.value) {
                    var currentText = fpcm.editor.tweetTextInput.val();
                    var currentpos = fpcm.dom.fromTag(fpcm.editor.tweetTextInput).prop('selectionStart');
                    fpcm.editor.tweetTextInput.val(currentText.substring(0, currentpos) + ui.item.value +  currentText.substring(currentpos));
                }

                this.selectedIndex = 0;
                fpcm.dom.fromTag(this).selectmenu('refresh');
                return false;
            }
        });
        
        fpcm.dom.fromId('articlecategories').selectize({
            placeholder: fpcm.ui.translate('EDITOR_CATEGORIES_SEARCH'),
            searchField: ['text', 'value'],
            plugins: ['remove_button']
        });

        if (!fpcm.vars.jsvars.articleId) {
            return true;
        }

        fpcm.dom.fromId('btnShortlink').click(function (event, handler) {

            fpcm.ajax.get('editor/editorlist', {
                dataType: 'json',
                data: {
                    id: fpcm.dom.fromTag(this).data().article,
                    view: 'shortlink'
                },
                execDone: function (result) {

                    fpcm.ui.dialog({
                        id: 'editor-shortlink',
                        dlWidth: fpcm.ui.getDialogSizes().width,
                        title: fpcm.ui.translate('EDITOR_ARTICLE_SHORTLINK'),
                        resizable: true,
                        dlButtons: [
                            {
                                text: fpcm.ui.translate('GLOBAL_CLOSE'),
                                icon: "ui-icon-closethick",                        
                                click: function() {
                                    fpcm.dom.fromTag(this).dialog( "close" );
                                }
                            }
                        ],
                        dlOnOpen: function (event, ui) {                
                            fpcm.dom.appendHtml(
                                this, 
                                result.permalink
                                    ? '<div class="fpcm-ui-input-wrapper"><div class="fpcm-ui-input-wrapper-inner"><input type="text" value="' + result.shortend + '"></div></div>'
                                    : '<iframe class="fpcm-ui-full-width" src="https://is.gd/create.php?format=simple&url= '+ result.shortend + '"></iframe>'
                            );
                        },
                        dlOnClose: function( event, ui ) {
                            fpcm.dom.fromTag(this).empty();
                        }
                     });

                }
            });

             return false;
        });

        fpcm.dom.fromTag('input.fpcm-ui-editor-metainfo-checkbox').click(function () {
            fpcm.dom.fromTag('span.fpcm-ui-editor-metainfo-' + fpcm.dom.fromTag(this).data('icon')).toggleClass('fpcm-ui-status-1 fpcm-ui-status-0');
            return true;
        });
    },
    
    showCommentLayer: function(layerUrl) {
        
        var size = fpcm.ui.getDialogSizes();
        
        fpcm.dom.appendHtml('#fpcm-dialog-editor-comments', '<iframe id="fpcm-editor-comment-frame" name="fpcmeditorcommentframe" class="fpcm-ui-full-width" src="' + layerUrl + '"></iframe>');
        fpcm.dom.fromClass('fpcm-ui-commentaction-buttons').fadeOut();

        var size = fpcm.ui.getDialogSizes(top, 0.75);

        fpcm.ui.dialog({
            id       : 'editor-comments',
            dlWidth    : size.width,
            dlHeight   : size.height,
            resizable: true,
            title    : fpcm.ui.translate('COMMENTS_EDIT'),
            defaultCloseEmpty: true,
            dlButtons  : [
                {
                    text: fpcm.ui.translate('GLOBAL_SAVE'),
                    icon: "ui-icon-disk",
                    class: 'fpcm-ui-button-primary',
                    click: function() {
                        fpcm.dom.fromTag(this).children('#fpcm-editor-comment-frame').contents().find('#btnCommentSave').trigger('click');
                        fpcm.editor.editorTabs.tabs('load', 2);
                    }
                },
                {
                    text: fpcm.ui.translate('COMMMENT_LOCKIP'),
                    icon: "ui-icon-locked",
                    disabled: fpcm.vars.jsvars.lkIp ? false : true,
                    click: function() {
                        fpcm.dom.fromTag(this).children('#fpcm-editor-comment-frame').contents().find('#btnLockIp').trigger('click');
                    }
                },
                {
                    text: fpcm.ui.translate('Whois'),
                    icon: "ui-icon-home",
                    click: function() {
                        window.open(fpcm.dom.fromTag(this).children('#fpcm-editor-comment-frame').contents().find('#whoisIp').attr('href'), '_blank', 'width=700,height=500,scrollbars=yes,resizable=yes,');
                    }
                },
                {
                    text: fpcm.ui.translate('GLOBAL_CLOSE'),
                    icon: "ui-icon-closethick",                    
                    click: function() {
                        fpcm.dom.fromTag(this).dialog('close');
                        fpcm.dom.fromClass('fpcm-ui-commentaction-buttons').fadeIn();
                    }
                }                            
            ]
        });
        
        fpcm.ui_loader.hide();
        return false;
    },
    
    initCodeMirrorAutosave: function() {

        fpcm.vars.jsvars.autoSaveStorage = localStorage.getItem(fpcm.vars.jsvars.editorConfig.autosavePref);

        setInterval(function() {

            var editorValue = fpcm.editor.cmInstance.getValue();
            if (!editorValue) {
                return false;
            }
            
            if (editorValue === localStorage.getItem(fpcm.vars.jsvars.editorConfig.autosavePref)) {
                return true;
            }

            localStorage.setItem(fpcm.vars.jsvars.editorConfig.autosavePref, editorValue);
            fpcm.ui.button('#editor-html-buttonrestore', {
                disabled: false
            });
            
        }, 30000);

    },
    
    initCodeMirror: function() {

        fpcm.editor.cmInstance = fpcm.editor_codemirror.create({
           editorId  : 'htmleditor',
           elementId : 'articlecontent',
           extraKeys : fpcm.editor_codemirror.defaultShortKeys
        });
        
        fpcm.editor.cmInstance.on('paste', function(instance, event) {
                
            if (event.clipboardData === undefined) {
                return true;
            }

            var orgText = event.clipboardData.getData('Text');            
            var chgText = fpcm.editor_videolinks.replace(orgText);

            if (orgText === chgText) {
                return false;
            }

            fpcm.ui_loader.show();
            event.preventDefault();
            fpcm.editor_videolinks.createFrame(chgText, false);
            fpcm.ui_loader.hide();
            return true;

        });

        fpcm.editor.initCodeMirrorAutosave();
        
        var sizeSmall = fpcm.ui.getDialogSizes(top, 0.35);
        var sizeLarge = fpcm.ui.getDialogSizes();

    },

    showInEditDialog: function(result){

        if (fpcm.vars.jsvars.checkLastState == 1 && result.articleCode == 0 && !result.articleUser) {

            fpcm.ui.addMessage({
                type : 'notice',
                id   : 'fpcm-editor-notinedit',
                icon : 'check',
                txt  : fpcm.ui.translate('EDITOR_STATUS_NOTINEDIT')
            }, true);
        }

        if (fpcm.vars.jsvars.checkLastState == 0 && result.articleCode == 1 && result.articleUser) {
            var msg = fpcm.ui.translate('EDITOR_STATUS_INEDIT');
            fpcm.ui.addMessage({
                type : 'neutral',
                id   : 'fpcm-editor-inedit',
                icon : 'pencil-square',
                txt  : msg.replace('{{username}}', result.username)
            }, true);
        }

        fpcm.vars.jsvars.checkLastState = result.articleCode;
    },
    
    initCommentListActions: function () {

        if (!fpcm.comments) {
            return true;
        }

        fpcm.comments.assignActions();
        
        fpcm.dom.fromClass('fpcm-ui-commentlist-link').click(function () {
            fpcm.ui_loader.hide();
            fpcm.editor.showCommentLayer(fpcm.dom.fromTag(this).attr('href'));
            return false;
        });

    },

    insertIFrame: function(url, params, returnOnly) {
        
        if (url === undefined) {
            url = 'http://';
        }
        
        if (params === undefined) {
            params = [];
        }

        var code = '<iframe src="' + url + '" class="fpcm-articletext-iframe" ' + params.join(' ') + '></iframe>';
        if (!returnOnly) {
            fpcm.editor.insert(code, '');
            return true;
        }

        return code;
    }

};
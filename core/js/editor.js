
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

        fpcm.ui.setFocus('articletitle');
        fpcm.ui.checkboxradio('.fpcm-ui-editor-categories .fpcm-ui-input-checkbox');

        if (!fpcm.vars.jsvars.isRevision) {
            fpcm.editor[fpcm.vars.jsvars.editorInitFunction].call();
        }
        else {
            jQuery('.fpcm-ui-editor-categories-revisiondiff .fpcm-ui-input-checkbox').click(function() {
                return false;
            });
        }

        fpcm.editor.initToolbar();

        fpcm.ui.spinner('input.fpcm-ui-spinner-hour', {
            min: 0,
            max: 23,
            classes: {
                "ui-spinner": 'ui-corner-all fpcm-ui-margin-md-left col-6 col-md-1'
            }
        });

        fpcm.ui.spinner('input.fpcm-ui-spinner-minutes', {
            min: 0,
            max: 59,
            classes: {
                "ui-spinner": 'ui-corner-all fpcm-ui-margin-md-left col-6 col-md-1'
            }
        });

        fpcm.ui.datepicker('input.fpcm-ui-datepicker', {
            maxDate: "+2m",
            minDate: "-0d"
        });   

        jQuery('#btnShortlink').click(function (event, handler) {

            fpcm.ui.showLoader(true);

            fpcm.ajax.get('editor/editorlist',{
                data: {
                    id: jQuery(this).data().article,
                    view: 'shortlink'
                },
                execDone: function (result) {

                    result = fpcm.ajax.fromJSON(result);

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
                                    jQuery( this ).dialog( "close" );
                                }
                            }
                        ],
                        dlOnOpen: function (event, ui) {                
                            fpcm.ui.appendHtml(
                                this, 
                                result.permalink
                                    ? '<div class="fpcm-ui-input-wrapper"><div class="fpcm-ui-input-wrapper-inner"><input type="text" value="' + result.shortend + '"></div></div>'
                                    : '<iframe class="fpcm-ui-full-width" src="https://is.gd/create.php?format=simple&url= '+ result.shortend + '"></iframe>'
                            );
                        },
                        dlOnClose: function( event, ui ) {
                            jQuery(this).empty();
                        }
                     });
                     
                     fpcm.ui.showLoader(false);
                }
            });

             return false;
        });

        jQuery('.fpcm-editor-articleimage').fancybox();

        jQuery('#insertarticleimg').click(function () {
            fpcm.vars.jsvars.filemanagerMode = 3;
            fpcm.editor.showFileManager();
            fpcm.vars.jsvars.filemanagerMode = 2;
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
            minLength: 3,
            position: {
                my: "left bottom",
                at: "left top"
            }
        });

        fpcm.editor.tweetTextInput = jQuery('#articletweettxt');
        fpcm.ui.selectmenu('#twitterReplacements', {
            change: function( event, ui ) {
                fpcm.editor.tweetTextInput.val(fpcm.editor.tweetTextInput.val() + ' ' + ui.item.value);
                this.selectedIndex = 0;
                jQuery(this).selectmenu('refresh');
                return false;
            }
        });

        fpcm.ui.checkboxradio('.fpcm-ui-input-checkbox');
        
        fpcm.editor.editorTabs = fpcm.ui.tabs('#fpcm-editor-tabs',
        {
            dataViewWrapperClass: 'fpcm-ui-editor-editlist',
            initDataViewJson: true,
            addMainToobarToggle: true,
            addTabScroll: true,
            saveActiveTab: true,
            initDataViewJsonBefore:function(event, ui) {
                jQuery('.fpcm-ui-editor-editlist').remove();
            },            
            initDataViewOnRenderAfter: function () {
                fpcm.ui.assignCheckboxes();
                fpcm.ui.assignControlgroups(),
                fpcm.editor.initCommentListActions();
            },
            active: fpcm.vars.jsvars.activeTab !== undefined ? fpcm.vars.jsvars.activeTab : 0
        });

        jQuery('#fpcm-editor-tabs-editorregister').click(function() {
            fpcm.ui.initJqUiWidgets();
        });

        /**
         * Keycodes
         * http://www.brain4.de/programmierecke/js/tastatur.php
         */
        jQuery(document).keypress(function(thekey) {

            if (thekey.ctrlKey && thekey.which == 115) {
                if(jQuery("#btnArticleSave")) {
                    jQuery("#btnArticleSave").click();
                    return false;
                }
            }

        });

    },
    
    showCommentLayer: function(layerUrl) {
        
        var size = fpcm.ui.getDialogSizes();
        
        fpcm.ui.appendHtml('#fpcm-dialog-editor-comments', '<iframe id="fpcm-editor-comment-frame" name="fpcmeditorcommentframe" class="fpcm-ui-full-width" src="' + layerUrl + '"></iframe>');
        jQuery('.fpcm-ui-commentaction-buttons').fadeOut();

        var size = fpcm.ui.getDialogSizes(top, 0.75);

        fpcm.ui.dialog({
            id       : 'editor-comments',
            dlWidth    : size.width,
            dlHeight   : size.height,
            resizable: true,
            title    : fpcm.ui.translate('COMMENTS_EDIT'),
            dlButtons  : [
                {
                    text: fpcm.ui.translate('GLOBAL_SAVE'),
                    icon: "ui-icon-disk",
                    click: function() {
                        jQuery(this).children('#fpcm-editor-comment-frame').contents().find('#btnCommentSave').trigger('click');
                        fpcm.editor.editorTabs.tabs('load', 2);
                        fpcm.ui.showLoader(false);
                    }
                },
                {
                    text: fpcm.ui.translate('COMMMENT_LOCKIP'),
                    icon: "ui-icon-locked",
                    disabled: fpcm.vars.jsvars.lkIp ? false : true,
                    click: function() {
                        jQuery(this).children('#fpcm-editor-comment-frame').contents().find('#btnLockIp').trigger('click');
                    }
                },
                {
                    text: fpcm.ui.translate('Whois'),
                    icon: "ui-icon-home",
                    click: function() {
                        window.open(jQuery(this).children('#fpcm-editor-comment-frame').contents().find('#whoisIp').attr('href'), '_blank', 'width=700,height=500,scrollbars=yes,resizable=yes,');
                    }
                },
                {
                    text: fpcm.ui.translate('GLOBAL_CLOSE'),
                    icon: "ui-icon-closethick",                    
                    click: function() {
                        jQuery(this).dialog('close');
                        fpcm.ui.showLoader(false);
                        jQuery('.fpcm-ui-commentaction-buttons').fadeIn();
                    }
                }                            
            ],
            dlOnClose: function( event, ui ) {
                jQuery(this).empty();
            }
        });
        fpcm.ui.showLoader(false);
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

            fpcm.ui.showLoader(true);
            event.preventDefault();
            fpcm.editor_videolinks.createFrame(chgText, false);
            fpcm.ui.showLoader(false);
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
        
        fpcm.comments.assignActions();
        
        jQuery('.fpcm-ui-commentlist-link').click(function () {
            fpcm.ui.showLoader(false);
            fpcm.editor.showCommentLayer(jQuery(this).attr('href'));
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
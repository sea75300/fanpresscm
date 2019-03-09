
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

        jQuery('#shortlink').click(function () {
            var text = jQuery(this).text();
            var link = jQuery(this).attr('href');

            var size = fpcm.ui.getDialogSizes();

            fpcm.ui.dialog({
                id: 'editor-shortlink',
                dlWidth: size.width,
                title: text,
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
                    var appendCode  = fpcm.vars.jsvars.canConnect
                                    ? '<div class="fpcm-ui-input-wrapper"><div class="fpcm-ui-input-wrapper-inner"><input type="text" value="' + link + '"></div></div>'
                                    : '<iframe class="fpcm-ui-full-width"  src="' + link + '"></iframe>';

                    fpcm.ui.appendHtml(this, appendCode);
                },
                dlOnClose: function( event, ui ) {
                    jQuery(this).empty();
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
    
    showFileManager: function() {
        
        var size = fpcm.ui.getDialogSizes(top, 0.75);
        
        fpcm.ui.appendHtml('#fpcm-dialog-editor-html-filemanager', '<iframe id="fpcm-dialog-editor-html-filemanager-frame" class="fpcm-ui-full-width" src="' + fpcm.vars.jsvars.filemanagerUrl + fpcm.vars.jsvars.filemanagerMode + '"></iframe>');
        fpcm.ui.dialog({
            id       : 'editor-html-filemanager',
            dlMinWidth : size.width,
            dlMinHeight: size.height,
            modal    : true,
            resizable: true,
            title    : fpcm.ui.translate('HL_FILES_MNG'),
            dlButtons  : [
                {
                    text: fpcm.ui.translate('ARTICLES_SEARCH'),
                    icon: "ui-icon-search",
                    click: function() {
                        jQuery(this).children('#fpcm-dialog-editor-html-filemanager-frame').contents().find('#opensearch').click();
                    }
                },
                {
                    text: fpcm.ui.translate('FILE_LIST_NEWTHUMBS'),
                    icon: "ui-icon-image",
                    click: function() {
                        jQuery(this).children('#fpcm-dialog-editor-html-filemanager-frame').contents().find('#createThumbs').click();
                    }
                },
                {
                    text: fpcm.ui.translate('GLOBAL_DELETE'),
                    icon: "ui-icon-trash",
                    click: function() {
                        jQuery(this).children('#fpcm-dialog-editor-html-filemanager-frame').contents().find('#deleteFiles').click();
                    }
                },
                {
                    text: fpcm.ui.translate('GLOBAL_CLOSE'),
                    icon: "ui-icon-closethick",                    
                    click: function() {
                        jQuery(this).dialog('close');
                    }
                }                            
            ],
            dlOnClose: function( event, ui ) {
                jQuery(this).empty();
            }
        });   
    },
    
    setSelectToDialog: function(obj) {
        jQuery(obj).find('.fpcm-ui-input-select').selectmenu({
            appendTo: "#" + jQuery(obj).attr('id')
        });
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

        var colorsEl = jQuery('#fpcm-dialog-editor-html-insertcolor').find('div.fpcm-dialog-editor-colors');
        for (var i = 0;i < fpcm.vars.jsvars.editorConfig.colors.length; i++) {
            colorsEl.append('<span class="fpcm-ui-padding-md-tb fas fa-square fa-fw fa-lg" style="color:' + fpcm.vars.jsvars.editorConfig.colors[i] + '" data-color="' + fpcm.vars.jsvars.editorConfig.colors[i] + '"></span>');
            if ((i+1) % 20 == 0) {
                colorsEl.append('<br>');
            }
        }

        jQuery('div.fpcm-dialog-editor-colors span').click(function() {
            jQuery('#colorhexcode').val(jQuery(this).attr('data-color'));
        });

        fpcm.editor.cmInstance = fpcm.editor_codemirror.create({
           editorId  : 'htmleditor',
           elementId : 'articlecontent',
           extraKeys : {
                "Enter"    : function() {
                    fpcm.editor.insertBr();
                },
                "Ctrl-B"    : function() {
                    jQuery('#fpcm-editor-html-bold-btn').click();
                },
                "Ctrl-I"    : function() {
                    jQuery('#fpcm-editor-html-italic-btn').click();
                },
                "Ctrl-U"    : function() {
                    jQuery('#fpcm-editor-html-underline-btn').click();
                },
                "Ctrl-O"    : function() {
                    jQuery('#fpcm-editor-html-strike-btn').click();
                },
                "Shift-Ctrl-F"    : function() {
                    jQuery('#fpcm-dialog-editor-html-insertcolor-btn').click();
                },
                "Ctrl-Y"    : function() {
                    jQuery('#fpcm-editor-html-sup-btn').click();
                },
                "Shift-Ctrl-Y"    : function() {
                    jQuery('#fpcm-editor-html-sub-btn').click();
                },
                "Shift-Ctrl-L"    : function() {
                    jQuery('#fpcm-editor-html-aleft-btn').click();
                },
                "Shift-Ctrl-C"    : function() {
                    jQuery('#fpcm-editor-html-acenter-btn').click();
                },
                "Shift-Ctrl-R"    : function() {
                    jQuery('#fpcm-editor-html-aright-btn').click();
                },
                "Shift-Ctrl-J"    : function() {
                    jQuery('#fpcm-editor-html-ajustify-btn').click();
                },
                "Ctrl-Alt-N"    : function() {
                    jQuery('#fpcm-editor-html-insertlist-btn').click();
                },
                "Shift-Ctrl-N"    : function() {
                    jQuery('#fpcm-editor-html-insertlistnum-btn').click();
                },
                "Ctrl-Q"    : function() {
                    jQuery('#fpcm-editor-html-quote-btn').click();
                },
                "Ctrl-L"    : function() {
                    jQuery('#fpcm-dialog-editor-html-insertlink-btn').click();
                },
                "Ctrl-P"    : function() {
                    jQuery('#fpcm-dialog-editor-html-insertimage-btn').click();
                },
                "Shift-Ctrl-Z"    : function() {
                    jQuery('#fpcm-dialog-editor-html-insertmedia-btn').click();
                },
                "Ctrl-F"    : function() {
                    jQuery('#fpcm-editor-html-insertiframe-btn').click();
                },
                "Ctrl-M"    : function() {
                    jQuery('#fpcm-editor-html-insertmore-btn').click();
                },
                "Shift-Ctrl-T"    : function() {
                    jQuery('#fpcm-dialog-editor-html-inserttable-btn').click();
                },
                "Shift-Ctrl-E"    : function() {
                    jQuery('#fpcm-dialog-editor-html-insertsmiley-btn').click();
                },
                "Shift-Ctrl-D"    : function() {
                    jQuery('#fpcm-dialog-editor-html-insertdraft-btn').click();
                },
                "Shift-Ctrl-I"    : function() {
                    jQuery('#fpcm-dialog-editor-html-insertsymbol-btn').click();
                },
                "Shift-Ctrl-S"    : function() {
                    jQuery('#fpcm-editor-html-removetags-btn').click();
                    return false;
                }
            }
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
    
    initTinyMce: function() {

        fpcm.vars.jsvars.editorConfig.file_picker = function(callback, value, meta) {
            var fmSize = fpcm.ui.getDialogSizes(top, 0.75);

            tinymce.activeEditor.windowManager.open({
                file            : fpcm.vars.jsvars.filemanagerUrl + fpcm.vars.jsvars.filemanagerMode,
                title           : fpcm.ui.translate('HL_FILES_MNG'),
                width           : fmSize.width,
                height          : fmSize.height,
                close_previous  : false,
                buttons  : [
                    {
                        text: fpcm.ui.translate('ARTICLES_SEARCH'),
                        onclick: function() {
                            var tinyMceWins = top.tinymce.activeEditor.windowManager.getWindows();
                            jQuery('#'+ tinyMceWins[1]._id).find('iframe').contents().find('#opensearch').click();
                        }
                    },
                    {
                        text: fpcm.ui.translate('FILE_LIST_NEWTHUMBS'),
                        onclick: function() {
                            var tinyMceWins = top.tinymce.activeEditor.windowManager.getWindows();
                            jQuery('#'+ tinyMceWins[1]._id).find('iframe').contents().find('#createThumbs').click();
                        }
                    },
                    {
                        text: fpcm.ui.translate('GLOBAL_DELETE'),
                        onclick: function() {
                            var tinyMceWins = top.tinymce.activeEditor.windowManager.getWindows();
                            jQuery('#'+ tinyMceWins[1]._id).find('iframe').contents().find('#deleteFiles').click();
                        }
                    },
                    {
                        text: fpcm.ui.translate('GLOBAL_CLOSE'),
                        onclick: function() {
                            top.tinymce.activeEditor.windowManager.close();
                        }
                    }                            
                ]
            },
            {
                oninsert: function (url, objVals) {
                    callback(url, objVals);
                }
            });
        };

        fpcm.vars.jsvars.editorConfig.onPaste = function(plugin, args) {
            var content = fpcm.editor_videolinks.replace(args.content);
            if (content === args.content) {
                return true;
            }

            fpcm.ui.showLoader(true);
            args.content = fpcm.editor_videolinks.createFrame(content, true);
            fpcm.ui.showLoader(false);
        };

        fpcm.editor_tinymce.create(fpcm.vars.jsvars.editorConfig);
   
    },
    
    initTinyMce5: function() {

        fpcm.vars.jsvars.editorConfig.file_picker = function(callback, value, meta) {

            tinymce.activeEditor.windowManager.open({
                title: fpcm.ui.translate('HL_FILES_MNG') + ' - dummy dialog!',
                size: 'large',
                body: {
                    type: 'panel',
                    items: [{
                        type: 'htmlpanel',
                        html: fpcm.ui.createIFrame({
                            src: fpcm.vars.jsvars.filemanagerUrl + fpcm.vars.jsvars.filemanagerMode,
                            style: 'width:100%;height:100%;'
                        })
                    }]
                },
                buttons: [
//                    {
//                        type:  'custom',
//                        name: 'fmSearch',
//                        text: fpcm.ui.translate('ARTICLES_SEARCH'),
//                        disabled: false,
//                        primary: false,
//                        onAction: function() {
//                            var tinyMceWins = top.tinymce.activeEditor.windowManager.getWindows();
//                            jQuery('#'+ tinyMceWins[1]._id).find('iframe').contents().find('#deleteFiles').click();
//                        }
//                    },
//                    {
//                        type:  'custom',
//                        name: 'fmNewThumbs',
//                        text: fpcm.ui.translate('FILE_LIST_NEWTHUMBS'),
//                        disabled: false,
//                        primary: false,
//                        onAction: function() {
//                            var tinyMceWins = top.tinymce.activeEditor.windowManager.getWindows();
//                            jQuery('#'+ tinyMceWins[1]._id).find('iframe').contents().find('#createThumbs').click();
//                        }
//                    },
//                    {
//                        type:  'custom',
//                        name: 'fmDelete',
//                        text: fpcm.ui.translate('GLOBAL_DELETE'),
//                        disabled: false,
//                        primary: false,
//                        onAction: function() {
//                            var tinyMceWins = top.tinymce.activeEditor.windowManager.getWindows();
//                            jQuery('#'+ tinyMceWins[1]._id).find('iframe').contents().find('#deleteFiles').click();
//                        }
//                    },
                    {
                        type:  'cancel',
                        name: 'fmClose',
                        text: fpcm.ui.translate('GLOBAL_CLOSE'),
                        disabled: false,
                        primary: true
                    },                          
                ]
            });

            return true;
        }

        fpcm.vars.jsvars.editorConfig.onPaste = function(plugin, args) {
            var content = fpcm.editor_videolinks.replace(args.content);
            if (content === args.content) {
                return true;
            }

            fpcm.ui.showLoader(true);
            args.content = fpcm.editor_videolinks.createFrame(content, true);
            fpcm.ui.showLoader(false);
        };

        fpcm.editor_tinymce.create(fpcm.vars.jsvars.editorConfig);
   
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
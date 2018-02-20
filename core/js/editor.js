
/**
 * FanPress CM Editor Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.editor = {

    init: function() {

        fpcm.ui.setFocus('articletitle');

        if (fpcm.vars.jsvars.articleId) {
            fpcm.editor.setInEdit();
            setInterval(fpcm.editor.setInEdit, fpcm.vars.jsvars.checkTimeout);
        }

        fpcm.ui.checkboxradio('.fpcm-ui-editor-categories .fpcm-ui-input-checkbox');

        jQuery('.fpcm-ui-editor-categories-revisiondiff .fpcm-ui-input-checkbox').click(function() {
            return false;
        });
        
        if (!fpcm.vars.jsvars.isRevision) {
            this[fpcm.vars.jsvars.editorInitFunction].call();
        }

        fpcm.ui.selectmenu('#fpcm-editor-paragraphs', {
            select: function( event, ui ) {
                if (!ui.item.value) {
                    return false;
                }

                fpcm.editor.insert('<' + ui.item.value + '>', '</' + ui.item.value + '>');
            },
            change: function( event, ui ) {            
                this.selectedIndex = 0;
                this.value = '';
                jQuery(this).selectmenu("refresh");
            }
        });

        fpcm.ui.selectmenu('#fpcm-editor-styles', {
            select: function( event, ui ) {
                if (!ui.item.value) {
                    return false;
                }

                fpcm.editor.insert(' class="' + ui.item.value + '"', '');
            },
            change: function( event, ui ) {            
                this.selectedIndex = 0;
                this.value = '';
                jQuery(this).selectmenu("refresh");
            }
        });

        fpcm.ui.selectmenu('#fpcm-editor-fontsizes', {
            select: function( event, ui ) {
                if (!ui.item.value) {
                    return false;
                }

                fpcm.editor.insertFontsize(ui.item.value);
            },
            change: function( event, ui ) {            
                this.selectedIndex = 0;
                this.value = '';
                jQuery(this).selectmenu("refresh");
            }
        });

        jQuery('.fpcm-editor-html-click').click(function() {
            
            var el      = jQuery(this);
            var tag     = el.attr('data-htmltag');
            var action  = el.attr('data-action');

            if (tag && !action) {
                fpcm.editor.insert('<' + tag + '>', '</' + tag + '>');
            }
            else if (action && tag) {
                fpcm.editor[action].call(this, tag);
            }
            else if (action) {
                fpcm.editor[action].call();
            }
                
            
            return false;
        });

        jQuery('#editorextended').click(function() {

            var size = fpcm.ui.getDialogSizes(top, 0.75);

            fpcm.ui.dialog({
                id: 'editor-extended',
                dlWidth: size.width,
                title: fpcm.ui.translate('GLOBAL_EXTENDED'),
                resizable: true,
                dlButtons: [
                    {
                        text: fpcm.ui.translate('GLOBAL_CLOSE'),
                        icon: "ui-icon-closethick",
                        click: function() {
                            jQuery(this).dialog('close');
                        }
                    }
                ],
                dlOnOpen: function (event, ui) {
                    fpcm.editor.setSelectToDialog(this);
                },
                dlOnClose: function (event, ui) {
                    jQuery(this).dialog('destroy');
                }
            });

            jQuery('#btnArticleSave').click(function() {
                jQuery('#fpcm-dialog-editor-extended').dialog('close');
            });

            return false;
        });

        fpcm.ui.spinner('input.fpcm-ui-spinner-hour', {
            min: 0,
            max: 23
        });

        fpcm.ui.spinner('input.fpcm-ui-spinner-minutes', {
            min: 0,
            max: 59
        });

        fpcm.ui.datepicker('input.fpcm-ui-datepicker', {
            maxDate: "+2m",
            minDate: "-0d"
        });   

//
//        jQuery(window).click(function() {
//            jQuery('.fpcm-editor-select').fadeOut();
//        });

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
                                    : '<iframe class="fpcm-full-width"  src="' + link + '"></iframe>';

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


        fpcm.ui.checkboxradio('#fpcm-dialog-editor-extended .fpcm-ui-input-checkbox');
        
        fpcm.ui.tabs('#fpcm-editor-tabs', {
            beforeLoad: function(event, ui) {
                fpcm.ui.showLoader(true);
            },
            load: function(event, ui) {
                fpcm.editor.initCommentListActions();
                fpcm.ui.resize();
                fpcm.ui.initJqUiWidgets();
                fpcm.ui.showLoader(false);
            },
            addMainToobarToggle: true,
            addTabScroll: true
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
    
    insert: function(aTag, eTag) {    

        if(fpcm.editor.cmInstance.doc.somethingSelected()) {
            fpcm.editor.cmInstance.doc.replaceSelection(aTag + fpcm.editor.cmInstance.doc.getSelection() + eTag);
        }
        else {
            var cursorPos       = fpcm.editor.cmInstance.doc.getCursor();
            fpcm.editor.cmInstance.doc.replaceRange(aTag + '' + eTag, cursorPos, cursorPos);        

            if(eTag != '') {
                cursorPos.ch = (eTag.length > cursorPos.ch)
                             ? cursorPos.ch + aTag.length
                             : cursorPos.ch - eTag.length;

                fpcm.editor.cmInstance.doc.setCursor(cursorPos);            
            }

            fpcm.editor.cmInstance.focus();
        }

        return false;  
    },

    insertFontsize: function(fs) {
        aTag = '<span style=\"font-size:' + fs + 'pt;\">';
        fpcm.editor.insert(aTag, '</span>');
    },

    insertAlignTags: function(aligndes) {
        aTag = '<p style=\"text-align:' + aligndes + ';\">';
        fpcm.editor.insert(aTag, '</p>');
    },
    
    insertFrame: function(url, params, returnOnly) {
        
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
    },
    
    showFileManager: function() {
        
        var size = fpcm.ui.getDialogSizes(top, 0.75);
        
        fpcm.ui.appendHtml('#fpcm-dialog-editor-html-filemanager', '<iframe id="fpcm-dialog-editor-html-filemanager-frame" class="fpcm-full-width" src="' + fpcm.vars.jsvars.filemanagerUrl + fpcm.vars.jsvars.filemanagerMode + '"></iframe>');
        fpcm.ui.dialog({
            id       : 'editor-html-filemanager',
            dlMinWidth : size.width,
            dlMinHeight: size.height,
            modal    : true,
            resizable: true,
            title    : fpcm.ui.translate('HL_FILES_MNG'),
            dlButtons  : [
                {
                    text: fpcm.ui.translate('GLOBAL_EXTENDED'),
                    icon: "ui-icon-wrench",                    
                    click: function() {
                        jQuery(this).children('#fpcm-dialog-editor-html-filemanager-frame').contents().find('.fpcm-ui-filemanager-buttons').fadeToggle();
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
    
    showCommentLayer: function(layerUrl) {
        
        var size = fpcm.ui.getDialogSizes();
        
        fpcm.ui.appendHtml('#fpcm-dialog-editor-comments', '<iframe id="fpcm-editor-comment-frame" name="fpcmeditorcommentframe" class="fpcm-full-width" src="' + layerUrl + '"></iframe>');
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
                        fpcm.ui.showLoader(false);
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
        jQuery('#fpcm-dialog-editor-html-colorhexcode').colorPicker({
            rows        : 5,
            cols        : 8,
            showCode    : 0,
            cellWidth   : 15,
            cellHeight  : 15,
            top         : 27,
            left        : 0,
            colorData   : fpcm.vars.jsvars.editorConfig.colors,            
            onSelect    : function(colorCode) {
                jQuery('#fpcm-dialog-editor-html-colorhexcode').val(colorCode);
            }
        });
        
        fpcm.editor.cmInstance = fpcm.editor_codemirror.create({
           editorId  : 'htmleditor',
           elementId : 'articlecontent',
           extraKeys : {
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
                        text: fpcm.ui.translate('GLOBAL_EXTENDED'),                   
                        onclick: function() {
                            var tinyMceWins = top.tinymce.activeEditor.windowManager.getWindows();
                            jQuery('#'+ tinyMceWins[1]._id).find('iframe').contents().find('.fpcm-ui-filemanager-buttons').fadeToggle();
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

    setInEdit: function(){
        
        if (!fpcm.vars.jsvars.sessionCheck) {
            return false;
        }
        
        fpcm.ajax.post('editor/inedit', {
            data: {
                id: fpcm.vars.jsvars.articleId
            },
            execDone: function () {

                var res = fpcm.ajax.fromJSON(fpcm.ajax.getResult('editor/inedit'));
                if (fpcm.vars.jsvars.checkLastState == 1 && res.code == 0) {

                    fpcm.ui.addMessage({
                        type : 'notice',
                        id   : 'fpcm-editor-notinedit',
                        icon : 'check',
                        txt  : fpcm.ui.translate('EDITOR_STATUS_NOTINEDIT')
                    }, true);
                }

                if (fpcm.vars.jsvars.checkLastState == 0 && res.code == 1 && res.username) {

                    var msg = fpcm.ui.translate('EDITOR_STATUS_INEDIT');
                    fpcm.ui.addMessage({
                        type : 'neutral',
                        id   : 'fpcm-editor-inedit',
                        icon : 'pencil-square',
                        txt  : msg.replace('{{username}}', res.username)
                    }, true);
                }

                fpcm.vars.jsvars.checkLastState = res.code;
            }
        });            

    },
    
    initCommentListActions: function () {
        
        fpcm.comments.assignActions();
        
        jQuery('.fpcm-ui-commentlist-link').click(function () {
            fpcm.ui.showLoader(false);
            fpcm.editor.showCommentLayer(jQuery(this).attr('href'));
            return false;
        });

    },
    
    insertList: function (listtype) {

        fpcm.ui.spinner('#listrows', {
            min: 1
        });

        fpcm.ui.dialog({
            id: 'editor-html-insertlist',
            dlWidth: fpcm.ui.getDialogSizes().width,
            title: fpcm.ui.translate('EDITOR_HTML_BUTTONS_LISTOL'),
            dlButtons: [
                {
                    text: fpcm.ui.translate('GLOBAL_INSERT'),
                    icon: "ui-icon-check",                        
                    click: function() {

                        var tablerows = jQuery('#listrows').val();

                        aTag = '<' + listtype + '>\n';
                        for (i=0;i<tablerows;i++) {
                            aTag += '<li></li>\n';
                        }

                        fpcm.editor.insert(aTag, '</' + listtype + '>');

                        jQuery( this ).dialog( "close" );
                    }
                },
                {
                    text: fpcm.ui.translate('GLOBAL_CLOSE'),
                    icon: "ui-icon-closethick",                
                    click: function() {
                        jQuery( this ).dialog( "close" );
                    }
                }
            ],
            dlOnOpen: function () {
                fpcm.editor.setSelectToDialog(this);
            },
            dlOnClose: function() {
                jQuery('#listrows').val('1');
            }
        });
    },
    
    insertSmilies: function () {
        fpcm.ui.dialog({
            id: 'editor-html-insertsmileys',
            dlWidth: fpcm.ui.getDialogSizes().width,
            title: fpcm.ui.translate('EDITOR_INSERTSMILEY'),
            dlButtons: [
                {
                    text: fpcm.ui.translate('GLOBAL_CLOSE'),
                    icon: "ui-icon-closethick",                        
                    click: function() {
                        jQuery( this ).dialog( "close" );
                    }
                }
            ],
            dlOnOpen: function () {

                fpcm.ajax.exec('editor/smileys', {
                    async: false,
                    execDone: function () {
                        jQuery('#fpcm-dialog-editor-html-insertsmileys').append(fpcm.ajax.getResult('editor/smileys'));
                        jQuery('.fpcm-editor-htmlsmiley').click(function() {
                            fpcm.editor.insert(' ' + jQuery(this).attr('smileycode') + ' ', '');
                        });
                    }
                });

            },
            dlOnClose: function() {
                jQuery(this).empty();
            }
        });
    },
    
    insertSymbol: function () {
        
        var sizeLarge = fpcm.ui.getDialogSizes();
        
        fpcm.ui.dialog({
            id: 'editor-html-insertsymbol',
            dlWidth: sizeLarge.width,
            dlHeight: sizeLarge.height,
            title: fpcm.ui.translate('EDITOR_INSERTSYMBOL'),
            dlButtons: [
                {
                    text: fpcm.ui.translate('GLOBAL_CLOSE'),
                    icon: "ui-icon-closethick",                        
                    click: function() {
                        jQuery( this ).dialog( "close" );
                    }
                }
            ]
        });

        jQuery('.fpcm-editor-htmlsymbol').click(function() {
            fpcm.editor.insert(jQuery(this).attr('symbolcode'), '');
            return false;
        });
    },
    
    insertColor: function () {
        fpcm.ui.dialog({
            id: 'editor-html-insertcolor',
            dlWidth: fpcm.ui.getDialogSizes().width,
            title: fpcm.ui.translate('EDITOR_INSERTCOLOR'),
            dlButtons: [
                {
                    text: fpcm.ui.translate('GLOBAL_INSERT'),
                    icon: "ui-icon-check",                        
                    click: function() {
                        
                        var mode    = jQuery('.color_mode:checked').val();
                        var color   = jQuery('#fpcm-dialog-editor-html-colorhexcode').val();
                        fpcm.editor.insert('<span style="' + (mode === undefined ? 'color' : mode) + ':' + (color == '' ? '#000000' : color) + ';">', '</span>');

                        jQuery('#fpcm-dialog-editor-html-colorhexcode').val('');
                        jQuery('.color_mode:checked').removeAttr('checked');    
                        jQuery('#color_mode1').prop( "checked", true );

                        jQuery( this ).dialog( "close" );
                    }
                },
                {
                    text: fpcm.ui.translate('GLOBAL_CLOSE'),
                    icon: "ui-icon-closethick",                
                    click: function() {
                        jQuery( this ).dialog( "close" );
                    }
                }
            ]        
        });
    },
    
    insertDrafts: function () {
        
        var sizeLarge = fpcm.ui.getDialogSizes();
        
        fpcm.ui.dialog({
            id       : 'editor-html-insertdraft',
            dlWidth  : sizeLarge.width,
            dlHeight : sizeLarge.height,
            title    : fpcm.ui.translate('EDITOR_HTML_BUTTONS_ARTICLETPL'),
            resizable: true,
            dlButtons: [
                {
                    text: fpcm.ui.translate('GLOBAL_INSERT'),
                    icon: "ui-icon-copy",                        
                    click: function() {

                        var item = jQuery('#tpldraft').val();
                        if (!item) {
                            jQuery( this ).dialog( "close" );
                            return false;
                        }

                        fpcm.ajax.exec('editor/draft', {
                            data    : {
                                path: item
                            },
                            execDone: function () {                                    
                                var responseData = fpcm.ajax.fromJSON(fpcm.ajax.getResult('editor/draft'));
                                fpcm.editor.cmInstance.doc.setValue(responseData.data);
                                jQuery('#fpcm-dialog-editor-html-insertdraft').dialog('close');
                            }
                        });

                    }
                },
                {
                    text: fpcm.ui.translate('GLOBAL_CLOSE'),
                    icon: "ui-icon-closethick",                        
                    click: function() {
                        jQuery( this ).dialog( "close" );
                    }
                }
            ],
            dlOnOpen: function () {

                fpcm.ui.selectmenu('#tpldraft',{

                    appendTo: '#fpcm-dialog-editor-html-insertdraft',
                    change: function( event, ui ) {

                        var item = jQuery(this).val();
                        if (!item) {
                            jQuery('#fpcm-dialog-editor-html-insertdraft-preview').empty();
                            return false;
                        }

                        fpcm.ajax.exec('editor/draft', {
                            data    : {
                                path: item
                            },
                            execDone: function () {

                                var responseData = fpcm.ajax.fromJSON(fpcm.ajax.getResult('editor/draft'));
                                fpcm.editor_codemirror.highlight({
                                    input   : responseData.data,
                                    ouputId : 'fpcm-dialog-editor-html-insertdraft-preview'
                                });

                            }
                        });

                        return false;

                    }

                });

            },
            dlOnClose: function() {
                jQuery('#fpcm-dialog-editor-html-insertdraft-preview').empty();
                var selectEl = jQuery('#tpldraft');
                selectEl.prop('selectedIndex', 0);
                selectEl.val('');
                selectEl.selectmenu("refresh");
            }
        });
    },
    
    insertMedia: function () {
        fpcm.ui.dialog({
            id: 'editor-html-insertmedia',
            dlWidth: fpcm.ui.getDialogSizes(top, 0.35).width,
            title: fpcm.ui.translate('EDITOR_INSERTMEDIA'),
            dlButtons: [
                {
                    text: fpcm.ui.translate('GLOBAL_INSERT'),
                    icon: "ui-icon-check",                        
                    click: function() {
                        
                        var tagName = jQuery('#mediatype:checked').val();
                        
                        aTag        = '<' + tagName + '>';
                        aTag       += '<source src="' + jQuery('#mediapath').val() + '">';        
                        fpcm.editor.insert(aTag, '</' + tagName + '>');

                        jQuery('#mediapath').val('');
                        jQuery('.fpcm-editor-mediatype').removeAttr('checked');    
                        jQuery('#mediatype_a').prop( "checked", true );

                        jQuery( this ).dialog( "close" );
                    }
                },
                {
                    text: fpcm.ui.translate('GLOBAL_CLOSE'),
                    icon: "ui-icon-closethick",                
                    click: function() {
                        jQuery( this ).dialog( "close" );
                    }
                }
            ]        
        });
    },
    
    insertTable: function () {
        fpcm.ui.spinner('#tablerows', {
            min: 1
        });

        fpcm.ui.spinner('#tablecols', {
            min: 1
        });

        fpcm.ui.dialog({
            id: 'editor-html-inserttable',
            dlWidth: fpcm.ui.getDialogSizes(top, 0.35).width,
            title: fpcm.ui.translate('EDITOR_INSERTTABLE'),
            dlButtons: [
                {
                    text: fpcm.ui.translate('GLOBAL_INSERT'),
                    icon: "ui-icon-check",                        
                    click: function() {

                        var tablerows = jQuery('#tablerows').val();
                        var tablecols = jQuery('#tablecols').val();
                        var aTag = '<table>\n'

                        for (i=0;i<tablerows;i++) {        
                            aTag += '<tr>\n';        
                            for (x=0;x<tablecols;x++) { aTag += '<td></td>\n'; }        
                            aTag += '</tr>\n';        
                        }
                        fpcm.editor.insert(aTag + '</table>', '');

                        jQuery( this ).dialog( "close" );
                    }
                },
                {
                    text: fpcm.ui.translate('GLOBAL_CLOSE'),
                    icon: "ui-icon-closethick",                
                    click: function() {
                        jQuery( this ).dialog( "close" );
                    }
                }
            ],
            dlOnOpen: function () {
                fpcm.editor.setSelectToDialog(this);
            },
            dlOnClose: function() {
                jQuery('#tablerows').val('1');
                jQuery('#tablecols').val('1');
            }
        });
    },
    
    insertPicture: function () {
        fpcm.ui.dialog({
            id: 'editor-html-insertimage',
            dlWidth: fpcm.ui.getDialogSizes().width,
            title: fpcm.ui.translate('EDITOR_INSERTPIC'),
            dlButtons: [
                {
                    text: fpcm.ui.translate('GLOBAL_INSERT'),
                    icon: "ui-icon-check",                        
                    click: function() {

                        var pic_path = jQuery('#imagespath').val();
                        var pic_align = jQuery('#imagesalign').val();
                        var pic_atxt = jQuery('#imagesalt').val();

                        if(jQuery('#imagescss') != null) {
                            var pic_css = jQuery('#imagescss').value;
                        }

                        if (pic_align == "right" || pic_align == "left") {
                            aTag = '<img src=\"' + pic_path + '\" alt=\"' + pic_atxt + '\" style=\"float:' + pic_align + ';margin:3px;\"';
                            if(pic_css) { aTag = aTag + ' class=\"'+ pic_css +'\"'; }
                            fpcm.editor.insert(aTag + '/>', ' ');
                        } else if (pic_align == "center") {
                            aTag = '<div style=\"text-align:' + pic_align + ';\"><img src=\"' + pic_path + '\" alt=\"' + pic_atxt + '\"';
                            if(pic_css) { aTag = aTag + ' class=\"'+ pic_css +'\"'; }
                            fpcm.editor.insert(aTag + '/></div>', ' ');
                        } else {
                            aTag = '<img src=\"' + pic_path + '\" alt=\"' + pic_atxt + '\"';
                            if(pic_css) { aTag = aTag + ' class=\"'+ pic_css +'\"'; }
                            fpcm.editor.insert(aTag + ' />', ' ');
                        }

                        jQuery( this ).dialog( "close" );
                    }
                },
                {
                    text: fpcm.ui.translate('HL_FILES_MNG'),
                    icon: "ui-icon-folder-open" ,                
                    click: function() {
                        window.fileOpenMode = 2;
                        fpcm.editor.showFileManager();
                    }
                },
                {
                    text: fpcm.ui.translate('GLOBAL_CLOSE'),
                    icon: "ui-icon-closethick",                
                    click: function() {
                        jQuery( this ).dialog( "close" );
                    }
                }
            ],
            dlOnOpen: function () {
                fpcm.ajax.exec('autocomplete&src=editorfiles', {
                    execDone: function () {
                        fpcm.ui.autocomplete('#imagespath', {
                            source: fpcm.ajax.fromJSON(fpcm.ajax.getResult('autocomplete&src=editorfiles')),
                            minLength: 2,
                            appendTo: "#fpcm-dialog-editor-html-insertimage",
                            select: function( event, ui ) {
                                jQuery('#imagesalt').val(ui.item.label);
                            }
                        });
                    }
                });
                fpcm.editor.setSelectToDialog(this);
            },
            dlOnClose: function() {
                jQuery('#imagespath').val('http://');
                jQuery('#imagesalign').val('');
                jQuery('#imagesalt').val('');
                jQuery('#imagescss').val('');
                fileOpenMode = 0;
            }
        });
    },
    
    insertLink: function() {
        fpcm.ui.dialog({
            id: 'editor-html-insertlink',
            dlWidth: fpcm.ui.getDialogSizes().width,
            title: fpcm.ui.translate('EDITOR_INSERTLINK'),
            dlButtons: [
                {
                    text: fpcm.ui.translate('GLOBAL_INSERT'),
                    icon: "ui-icon-check",
                    click: function() {
                        var lnk_url = jQuery('#linksurl').val();
                        var lnk_txt = jQuery('#linkstext').val();
                        var lnk_tgt = jQuery('#linkstarget').val();

                        if(jQuery('#linkscss') != null) {
                            var lnk_css = jQuery('#linkscss').value;
                        }

                        if (lnk_tgt != "") {
                            aTag = '<a href=\"' + lnk_url + '"\ target=\"' + lnk_tgt + '\"';
                            if(lnk_css) { aTag = aTag + ' class=\"'+ lnk_css +'\"'; }
                            aTag = aTag + '>' + lnk_txt ;
                        }
                        else {
                            aTag = '<a href=\"' + lnk_url + '\"';
                            if(lnk_css) { aTag = aTag + ' class=\"'+ lnk_css +'\"'; }
                            aTag = aTag + '>' + lnk_txt ;
                        }

                        fpcm.editor.insert(aTag, '</a>');
                        jQuery(this).dialog( "close" );
                    }
                },
                {
                    text: fpcm.ui.translate('HL_FILES_MNG'),
                    icon: "ui-icon-folder-open",
                    click: function() {
                        window.fileOpenMode = 1;
                        fpcm.editor.showFileManager();
                    }
                },
                {
                    text: fpcm.ui.translate('GLOBAL_CLOSE'),
                    icon: "ui-icon-closethick",
                    click: function() {
                        jQuery( this ).dialog( "close" );
                    }
                }
            ],
            dlOnOpen: function () {
                fpcm.ajax.exec('autocomplete&src=editorlinks', {
                    execDone: function () {
                        fpcm.ui.autocomplete('#linksurl', {
                            source: fpcm.ajax.fromJSON(fpcm.ajax.getResult('autocomplete&src=editorlinks')),
                            minLength: 2,
                            appendTo: "#fpcm-dialog-editor-html-insertlink",
                            select: function( event, ui ) {
                                jQuery('#linkstext').val(ui.item.label);
                            }
                        });
                    }
                });
                fpcm.editor.setSelectToDialog(this);
            },
            dlOnClose: function () {
                jQuery('#linksurl').val('http://');
                jQuery('#linkstext').val('');
                jQuery('#linkstarget').val('');
                jQuery('#linkscss').val('');
                fileOpenMode = 0;
            }
        });
    },
    
    insertIframe: function () {
        fpcm.editor.insertFrame();
    },
    
    insertReadMore: function () {
        fpcm.editor.insert('<readmore>', '</readmore>');
    },
    
    removeTags: function () {
        fpcm.ajax.post('editor/cleartags', {
            data: {
                text: fpcm.editor.cmInstance.doc.getValue()
            },
            execDone: function () {
                fpcm.editor.cmInstance.doc.setValue(fpcm.ajax.getResult('editor/cleartags'));
            }
        });
    },
    
    restoreSave: function () {
        if (!confirm(fpcm.ui.translate('CONFIRM_MESSAGE'))) {
            return false;
        }
        
        var isDisabled = (fpcm.vars.jsvars.autoSaveStorage === null ? true : false);

        fpcm.ui.button('#editor-html-buttonrestore',
        {
            disabled: isDisabled
        },
        function () {

            fpcm.vars.jsvars.autoSaveStorage = localStorage.getItem(fpcm.vars.jsvars.editorConfig.autosavePref);
            fpcm.editor.cmInstance.setValue(fpcm.vars.jsvars.autoSaveStorage);

            return false;
        });
    }

};
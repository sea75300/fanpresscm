/**
 * FanPress CM CodeMirror Editor Wrapper Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.editor_codemirror = {

    defaultShortKeys: {
        "Enter"    : function() {
            fpcm.editor.insertBr();
        },
        "Ctrl-B"    : function() {
            fpcm.dom.fromId('btnEditor-html-buttonbold').click();
        },
        "Ctrl-I"    : function() {
            fpcm.dom.fromId('btnEditor-html-buttonitalic').click();
        },
        "Ctrl-U"    : function() {
            fpcm.dom.fromId('btnEditor-html-buttonunderline').click();
        },
        "Ctrl-O"    : function() {
            fpcm.dom.fromId('btnEditor-html-buttonstrike').click();
        },
        "Shift-Ctrl-F"    : function() {
            fpcm.editor.insertColor();
        },
        "Ctrl-Y"    : function() {
            fpcm.dom.fromId('btnEditor-html-buttonsup').click();
        },
        "Shift-Ctrl-Y"    : function() {
            fpcm.dom.fromId('btnEditor-html-buttonsub').click();
        },
        "Shift-Ctrl-L"    : function() {
            fpcm.dom.fromId('btnEditor-html-buttonaleft').click();
        },
        "Shift-Ctrl-C"    : function() {
            fpcm.dom.fromId('btnEditor-html-buttonacenter').click();
        },
        "Shift-Ctrl-R"    : function() {
            fpcm.dom.fromId('btnEditor-html-buttonaright').click();
        },
        "Shift-Ctrl-J"    : function() {
            fpcm.dom.fromId('btnEditor-html-buttonajustify').click();
        },
        "Ctrl-Alt-N"    : function() {
            fpcm.dom.fromId('btnEditor-html-buttoninsertlist').click();
        },
        "Shift-Ctrl-N"    : function() {
            fpcm.dom.fromId('btnEditor-html-buttoninsertlistnum').click();
        },
        "Shift-Ctrl-Q"    : function() {
            fpcm.editor.insertQuote();
        },
        "Ctrl-L"    : function() {
            fpcm.editor.insertLink();
        },
        "Ctrl-P"    : function() {
            fpcm.editor.insertPicture();
        },
        "Shift-Ctrl-V"    : function() {
            fpcm.editor.insertMedia();
        },
        "Shift-Ctrl-T"    : function() {
            fpcm.editor.insertTable();
        },
        "Ctrl-Alt-E"    : function() {
            fpcm.editor.insertSmilies();
        },
        "Shift-Ctrl-D"    : function() {
            fpcm.editor.insertDrafts();
        },
        "Shift-Ctrl-I"    : function() {
            fpcm.editor.insertSymbol();
        },
        "Shift-Ctrl-M"    : function() {
            fpcm.editor.insertPageBreak()();
        },
        "Shift-Ctrl-B"    : function() {
            fpcm.editor.insertIFrame();
        },
        "Shift-Ctrl-S"    : function() {
            fpcm.editor.removeTags();
            return false;
        }
    },

    create: function(config) {

        params = {
            lineNumbers      : true,
            lineWrapping     : true,
            matchBrackets    : true,
            autoCloseTags    : true,
            id               : config.editorId,
            value            : document.documentElement.innerHTML,
            mode             : "text/html",            
            theme            : 'fpcm',
            matchTags        : {
                bothTags     : true
            },
            extraKeys        : {
                "Ctrl-Space" : "autocomplete",
            }
        };

        if (config.extraKeys !== undefined) {
            jQuery.extend(params.extraKeys, config.extraKeys);
        }

        return CodeMirror.fromTextArea(document.getElementById(config.elementId), params);         

    },

    highlight: function(config) {
        return CodeMirror.runMode(config.input, 'text/html', document.getElementById(config.outputId));
    },

    initToInstance: function (cmEditinstance, aTag, eTag) {

        if(cmEditinstance.doc.somethingSelected()) {
            cmEditinstance.doc.replaceSelection(aTag + cmEditinstance.doc.getSelection() + eTag);
            return true;
        }

        var cursorPos       = cmEditinstance.doc.getCursor();
        cmEditinstance.doc.replaceRange(aTag + '' + eTag, cursorPos, cursorPos);        

        if(eTag != '') {
            cursorPos.ch = (eTag.length > cursorPos.ch)
                         ? cursorPos.ch + aTag.length
                         : cursorPos.ch - eTag.length;

            cmEditinstance.doc.setCursor(cursorPos);            
        }

        cmEditinstance.focus();
        return true;  
    }

};

if (fpcm.editor) {
    
    fpcm.editor.initToolbar = function () {

        fpcm.ui.selectmenu('#fpcm-editor-paragraphs', {
            width: 'auto',
            select: function( event, ui ) {
                if (!ui.item.value) {
                    return false;
                }

                fpcm.editor.insert('<' + ui.item.value + '>', '</' + ui.item.value + '>');
            },
            change: function( event, ui ) {            
                this.selectedIndex = 0;
                this.value = '';
                fpcm.dom.fromTag(this).selectmenu("refresh");
            }
        });

        fpcm.ui.selectmenu('#fpcm-editor-styles', {
            width: 'auto',
            select: function( event, ui ) {
                if (!ui.item.value) {
                    return false;
                }

                fpcm.editor.insert(' class="' + ui.item.value + '"', '');
            },
            change: function( event, ui ) {            
                this.selectedIndex = 0;
                this.value = '';
                fpcm.dom.fromTag(this).selectmenu("refresh");
            }
        });

        fpcm.ui.selectmenu('#fpcm-editor-fontsizes', {
            width: 'auto',
            select: function( event, ui ) {
                if (!ui.item.value) {
                    return false;
                }

                fpcm.editor.insertFontsize(ui.item.value);
            },
            change: function( event, ui ) {            
                this.selectedIndex = 0;
                this.value = '';
                fpcm.dom.fromTag(this).selectmenu("refresh");
            }
        });

        fpcm.dom.fromClass('fpcm-editor-html-click').click(function() {

            var el      = fpcm.dom.fromTag(this);
            var tag     = el.data('htmltag');
            var action  = el.data('action');

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

        var colorsEl = fpcm.dom.fromId('fpcm-dialog-editor-html-insertcolor').find('div.fpcm-dialog-editor-colors');
        if (colorsEl.length) {

            for (var i = 0;i < fpcm.vars.jsvars.editorConfig.colors.length; i++) {
                colorsEl.append('<span class="fpcm-ui-padding-md-tb fas fa-square fa-fw fa-2x" style="color:' + fpcm.vars.jsvars.editorConfig.colors[i] + '" data-color="' + fpcm.vars.jsvars.editorConfig.colors[i] + '"></span>');
                if ((i+1) % 10 == 0) {
                    colorsEl.append('<br>');
                }
            }

            fpcm.dom.fromTag('div.fpcm-dialog-editor-colors span').click(function() {
                fpcm.dom.fromId('colorhexcode').val(fpcm.dom.fromTag(this).data('color'));
            });

        }

        return true;
    };

    fpcm.editor.insertThumbByEditor = function (url, title) {
        fpcm.editor._insertToFields(url, title);
        return false;
    };

    fpcm.editor.insertFullByEditor = function (url, title) {
        fpcm.editor._insertToFields(url, title);
        return false;
    };

    fpcm.editor.insertGalleryDisabled = function (_mode) {
        
        if (_mode === undefined) {
            _mode = fpcm.vars.jsvars.filemanagerMode;
        }        

        if (_mode !== 2) {
            return true;
        }
        
        if (top.fpcm.editor.cmInstance === undefined) {
            return true;
        }
        
        if (top.fpcm.editor.cmInstance.doc.getValue() && fpcm.editor.cmInstance.doc.getValue().search('/gallery') != -1 ) {
            return true;
        }

        return false;
    };

    fpcm.editor.insertGalleryByEditor = function (_values) {

        if (!_values.length) {
            return false;
        }

        fpcm.editor.insert(fpcm.editor.getGalleryReplacement(_values), fpcm.vars.jsvars.editorGalleryTagEnd);
        
        var dialogEl = window.parent.fpcm.dom.fromId("fpcm-dialog-editor-html-filemanager");
        dialogEl.dialog('close');
        dialogEl.empty();

        window.parent.fpcm.dom.fromId("fpcm-dialog-editor-html-insertimage").dialog('close');
        return false;
    };

    fpcm.editor._insertToFields = function (url, title) {
        
        if (!url || !title) {
            self.fpcm.dom.fromId("fpcm-dialog-editor-html-filemanager").dialog('close').empty();
            return false;
        }

        switch (self.fileOpenMode) {
            case 1 :
                var urlField = 'linksurl';
                var titleField = 'linkstext';
                break;                
            case 2 :
                var urlField = 'imagespath';
                var titleField = 'imagesalt';
                break;
        }

        if (urlField && titleField) {
            self.document.getElementById(urlField).value = url;
            self.document.getElementById(titleField).value  = title;
        }

        self.fpcm.dom.fromId("fpcm-dialog-editor-html-filemanager").dialog('close').empty();
        return true;
    };

    fpcm.editor.insert = function(aTag, eTag) {    

        return fpcm.editor_codemirror.initToInstance(fpcm.editor.cmInstance, aTag, eTag);
    };

    fpcm.editor.insertBr = function() {    

        if(fpcm.editor.cmInstance.doc.somethingSelected()) {
            fpcm.editor.cmInstance.doc.replaceSelection('<p>' + fpcm.editor.cmInstance.doc.getSelection() + '</p>\n');
        }
        else {
            var cursorPos = fpcm.editor.cmInstance.doc.getCursor();
            var eTag      = '<br>\n';

            fpcm.editor.cmInstance.doc.replaceRange(eTag, cursorPos, cursorPos);
            fpcm.editor.cmInstance.focus();
        }

        return false;  
    };

    fpcm.editor.insertFontsize = function(fs) {
        aTag = '<span style=\"font-size:' + fs + 'pt;\">';
        fpcm.editor.insert(aTag, '</span>');
    };

    fpcm.editor.insertAlignTags = function(aligndes) {
        aTag = '<p style=\"text-align:' + aligndes + ';\">';
        fpcm.editor.insert(aTag, '</p>');
    };
    
    fpcm.editor.insertList = function (listtype) {

        fpcm.ui.spinner('#listrows', {
            min: 1,
            classes: {
                "ui-spinner": "ui-corner-tr ui-corner-br col-6 col-md-2"
            }
        });

        fpcm.ui.autocomplete('#listtype', {
            source: (listtype === 'ol' ? ['decimal', 'decimal-leading-zero', 'lower-roman', 'upper-roman', 'lower-latin', 'upper-latin'] : ['disc', 'circle', 'square']),
            appendTo: "#fpcm-dialog-editor-html-insertlist"
        });

        fpcm.ui.insertDialog({
            id: 'editor-html-insertlist',
            dlWidth: fpcm.ui.getDialogSizes().width,
            title: 'EDITOR_HTML_BUTTONS_LIST' + listtype.toUpperCase(),
            resizable: true,
            dlOnOpen: function () {
                fpcm.editor.setSelectToDialog(this);
            },
            dlOnClose: function() {
                fpcm.dom.fromId('listrows').val('1');
                fpcm.dom.fromId('listtype').val('');
            },
            insertAction: function() {
                var rowCount = fpcm.dom.fromId('listrows').val();
                var cssType = fpcm.dom.fromId('listtype').val();

                aTag = '<' + listtype + (cssType ? ' style="list-style-type:' + cssType + '"' : '') + '>\n';
                for (i=0;i<rowCount;i++) {
                    aTag += '<li></li>\n';
                }

                fpcm.editor.insert(aTag, '</' + listtype + '>');

                fpcm.dom.fromTag(this).dialog( "close" );
            }
        });
    };
    
    fpcm.editor.insertSmilies = function () {
        
        fpcm.ui.insertDialog({
            id: 'editor-html-insertsmileys',
            dlWidth: fpcm.ui.getDialogSizes().width,
            title: 'EDITOR_INSERTSMILEY',
            dlOnOpen: function () {
                fpcm.ajax.exec('editor/smileys', {
                    quiet: true,
                    execDone: function (result) {
                        fpcm.dom.fromId('fpcm-dialog-editor-html-insertsmileys').append(result);
                        fpcm.dom.fromClass('fpcm-editor-htmlsmiley').click(function() {
                            fpcm.editor.insert(' ' + fpcm.dom.fromTag(this).data('smileycode') + ' ', '');
                        });
                    }
                });
            },
            dlOnClose: function() {
                fpcm.dom.fromTag(this).empty();
            }
        });        

    };
    
    fpcm.editor.insertSymbol = function () {
        
        var sizeLarge = fpcm.ui.getDialogSizes();

        var el = fpcm.ui.insertDialog({
            id: 'editor-html-insertsymbol',
            dlWidth: sizeLarge.width,
            dlHeight: sizeLarge.height,
            title: 'EDITOR_INSERTSYMBOL'
        });

        nkorgJSCharMap.createList('#' + el.attr('id'));
        nkorgJSCharMap.addClickEvent(function() {
            fpcm.editor.insert(fpcm.dom.fromTag(this).data('code'), '');
            return false;
        });

    };
    
    fpcm.editor.insertColor = function () {

        fpcm.ui.insertDialog({
            id: 'editor-html-insertcolor',
            dlWidth: 'auto',
            dlMaxWidth: 550,
            title: 'EDITOR_INSERTCOLOR',
            resizable: true,
            onCreate: function (event, ui) {
                fpcm.ui.controlgroup('#fpcm-ui-editor-color-controlgroup', {
                    onlyVisible: false
                });
            },
            insertAction: function() {
                var mode    = fpcm.dom.fromClass('fpcm-ui-editor-colormode:checked').val();
                var color   = fpcm.dom.fromId('colorhexcode').val();
                fpcm.editor.insert('<span style="' + (mode === undefined ? 'color' : mode) + ':' + (color == '' ? '#000000' : color) + ';">', '</span>');

                fpcm.dom.fromId('colorhexcode').val('');
                fpcm.dom.fromId('color_mode1').prop( "checked", true ).checkboxradio('refresh');
                fpcm.dom.fromId('color_mode2').prop( "checked", false ).checkboxradio('refresh');

                fpcm.dom.fromTag(this).dialog( "close" );
            }
        });
    };
    
    fpcm.editor.insertDrafts = function () {
        
        var sizeLarge = fpcm.ui.getDialogSizes();
        
        fpcm.ui.insertDialog({
            id: 'editor-html-insertdraft',
            dlWidth  : sizeLarge.width,
            dlHeight : sizeLarge.height,
            title: 'EDITOR_HTML_BUTTONS_ARTICLETPL',
            resizable: true,
            onCreate: function () {
                fpcm.ui.selectmenu('#tpldraft',{
                    appendTo: '#fpcm-dialog-editor-html-insertdraft',
                    change: function( event, ui ) {

                        var item = fpcm.dom.fromTag(this).val();
                        if (!item) {
                            fpcm.dom.fromId('fpcm-dialog-editor-html-insertdraft-preview').empty();
                            return false;
                        }

                        fpcm.ajax.exec('editor/draft', {
                            dataType: 'json',
                            data    : {
                                path: item
                            },
                            execDone: function (result) {
                                fpcm.editor_codemirror.highlight({
                                    input   : result.data,
                                    outputId : 'fpcm-dialog-editor-html-insertdraft-preview'
                                });
                            }
                        });

                        return false;
                    }
                });
            },
            dlOnClose: function() {
                fpcm.dom.fromId('fpcm-dialog-editor-html-insertdraft-preview').empty();
                fpcm.ui.resetSelectMenuSelection('#tpldraft');
            },
            insertAction: function() {
                var item = fpcm.dom.fromId('tpldraft').val();
                if (!item) {
                    fpcm.dom.fromTag(this).dialog( "close" );
                    return false;
                }

                fpcm.ajax.exec('editor/draft', {
                    dataType: 'json',
                    data    : {
                        path: item
                    },
                    execDone: function (result) {
                        fpcm.editor.cmInstance.doc.setValue(result.data);
                        fpcm.dom.fromId('fpcm-dialog-editor-html-insertdraft').dialog('close');
                    }
                });
            }
        });
    };
    
    fpcm.editor.insertMedia = function () {
        
        fpcm.ui.insertDialog({
            id: 'editor-html-insertmedia',
            dlWidth: fpcm.ui.getDialogSizes().width,
            title: 'EDITOR_INSERTMEDIA',
            resizable: true,
            dlButtons: [{
                text: fpcm.ui.translate('GLOBAL_PREVIEW'),
                icon: "ui-icon-video",
                click: function () {
                    var data = fpcm.editor.getMediaData(true);
                    fpcm.dom.assignHtml('#fpcm-dialog-editor-html-insertmedia-preview', '<div class="col-12 col-md-10 my-3">' + data.aTag + data.eTag +'</div>');
                }
            }],
            onCreate: function (event, ui) {
                fpcm.ui.controlgroup('#fpcm-ui-editor-media-controlgroup', {
                    onlyVisible: false
                });
            },
            dlOnOpen: function () {

                fpcm.ui.selectmenu('.fpcm-editor-mediaformat',{
                    appendTo: '#fpcm-dialog-editor-html-insertmedia',
                    width: '100%',
                    removeCornerLeft: true
                });
            },
            dlOnClose: function() {
                fpcm.dom.fromId('autoplay').prop('checked', false).checkboxradio('refresh');
                fpcm.dom.fromId('controls').prop('checked', true).checkboxradio('refresh');
                fpcm.dom.fromId('mediatypea').prop('checked', true).checkboxradio('refresh');
                fpcm.dom.fromId('mediatypev').prop('checked', false).checkboxradio('refresh');
                fpcm.dom.fromId('mediapath').val('');
                fpcm.dom.fromId('mediapath2').val('');
                fpcm.dom.fromId('mediaformat').val('').selectmenu('refresh');
                fpcm.dom.fromId('mediaformat2').val('').selectmenu('refresh');
                fpcm.dom.fromId('fpcm-dialog-editor-html-insertmedia-preview').empty();
            },
            insertAction: function() {
                var data = fpcm.editor.getMediaData();
                fpcm.editor.insert(data.aTag, data.eTag);
                fpcm.dom.fromTag(this).dialog( "close" );
            }
        });
    };
    
    fpcm.editor.insertTable = function () {

        fpcm.ui.spinner('#tablerows', {
            min: 1,
            classes: {
                "ui-spinner": "ui-corner-tr ui-corner-br col-6 col-md-2"
            }
        });

        fpcm.ui.spinner('#tablecols', {
            min: 1,
            classes: {
                "ui-spinner": "ui-corner-tr ui-corner-br col-6 col-md-2"
            }
        });
        
        fpcm.ui.insertDialog({
            id: 'editor-html-inserttable',
            dlWidth: fpcm.ui.getDialogSizes(top, 0.35).width,
            title: 'EDITOR_INSERTTABLE',
            dlOnOpen: function () {
                fpcm.editor.setSelectToDialog(this);
            },
            dlOnClose: function() {
                fpcm.dom.fromId('tablerows').val('1');
                fpcm.dom.fromId('tablecols').val('1');
            },
            insertAction: function() {
                var tablerows = fpcm.dom.fromId('tablerows').val();
                var tablecols = fpcm.dom.fromId('tablecols').val();
                var aTag = '<table>\n'

                for (i=0;i<tablerows;i++) {        
                    aTag += '<tr>\n';        
                    for (x=0;x<tablecols;x++) { aTag += '<td></td>\n'; }        
                    aTag += '</tr>\n';        
                }
                fpcm.editor.insert(aTag + '</table>', '');

                fpcm.dom.fromTag(this).dialog( "close" );
            }
        });
    };
    
    fpcm.editor.insertPicture = function () {

        fpcm.ui.selectmenu('#imagesalign',{
            removeCornerLeft: true
        });

        fpcm.ui.insertDialog({
            id: 'editor-html-insertimage',
            dlWidth: fpcm.ui.getDialogSizes(top, 0.35).width,
            title: 'EDITOR_INSERTPIC',
            resizable: true,
            dlButtons: [{
                text: fpcm.ui.translate('EDITOR_INSERTPIC_ASLINK'),
                icon: "ui-icon-link",
                click: function () {
                    var data = fpcm.editor.getImageData(true);
                    fpcm.editor.insert(data.aTag, data.eTag);
                    fpcm.dom.fromTag(this).dialog( "close" );
                }
            }],
            dlOnOpen: function () {
                
                fpcm.ajax.get('autocomplete', {
                    dataType: 'json',
                    data: {
                        src: 'editorfiles'
                    },
                    execDone: function (result) {
                        fpcm.ui.autocomplete('#imagespath', {
                            source: result,
                            minLength: 2,
                            appendTo: "#fpcm-dialog-editor-html-insertimage",
                            select: function( event, ui ) {
                                fpcm.dom.fromId('imagesalt').val(ui.item.label);
                            }
                        });
                    }
                });
                fpcm.editor.setSelectToDialog(this);
            },
            dlOnClose: function() {
                fpcm.dom.fromId('imagespath').val('');
                fpcm.dom.fromId('imagesalign').val('');
                fpcm.dom.fromId('imagesalt').val('');
                fpcm.dom.fromId('imagescss').val('');
            },
            insertAction: function() {
                var data = fpcm.editor.getImageData();

                fpcm.editor.insert(data.aTag, data.eTag);
                fpcm.dom.fromTag(this).dialog( "close" );
            },
            fileManagerAction: function () {
                fileOpenMode = 2;
                fpcm.editor.showFileManager(2);
            }
        });
    };
    
    fpcm.editor.insertLink = function() {

        fpcm.ui.selectmenu('#linkstarget',{
            removeCornerLeft: true
        });

        fpcm.ui.insertDialog({
            id: 'editor-html-insertlink',
            dlWidth: fpcm.ui.getDialogSizes().width,
            title: 'EDITOR_INSERTLINK',
            resizable: true,
            dlOnOpen: function () {
                
                fpcm.ajax.get('autocomplete', {
                    dataType: 'json',
                    data: {
                        src: 'editorlinks'
                    },
                    execDone: function (result) {
                        fpcm.ui.autocomplete('#linksurl', {
                            source: result,
                            minLength: 2,
                            appendTo: "#fpcm-dialog-editor-html-insertlink",
                            select: function( event, ui ) {
                                fpcm.dom.fromId('linkstext').val(ui.item.label);
                            }
                        });
                    }
                });
                fpcm.editor.setSelectToDialog(this);
            },
            dlOnClose: function () {
                fpcm.dom.fromId('linksurl').val('');
                fpcm.dom.fromId('linkstext').val('');
                fpcm.dom.fromId('linkstarget').val('');
                fpcm.dom.fromId('linkscss').val('');
            },
            insertAction: function() {
                var linkEl = fpcm.editor.getLinkData(
                    fpcm.dom.fromId('linksurl').val(),
                    fpcm.dom.fromId('linkstext').val(),
                    fpcm.dom.fromId('linkstarget').val(),
                    fpcm.dom.fromId('linkscss').length ? fpcm.dom.fromId('linkscss').val() : ''
                );

                fpcm.editor.insert(linkEl.aTag, linkEl.eTag);
                fpcm.dom.fromTag(this).dialog( "close" );
            },
            fileManagerAction: function () {
                fileOpenMode = 1;
                fpcm.editor.showFileManager(2);
            }
        });
    };
    
    fpcm.editor.insertReadMore = function () {
        console.warn('fpcm.editor.insertReadMore is deprecated as of FPCM 4.4, use fpcm.editor.insertPageBreak instead');
        fpcm.editor.insert('<readmore>', '</readmore>');
    };
    
    fpcm.editor.insertPageBreak = function () {
        fpcm.editor.insert('<p>' + fpcm.vars.jsvars.editorConfig.pageBreakVar, '</p>');
    };

    fpcm.editor.insertQuote = function () {

        fpcm.ui.insertDialog({
            id: 'editor-html-insertquote',
            dlWidth: fpcm.ui.getDialogSizes().width,
            title: 'EDITOR_HTML_BUTTONS_QUOTE',
            resizable: true,
            onCreate: function (event, ui) {
                fpcm.ui.controlgroup('#fpcm-ui-editor-quote-controlgroup', {
                    onlyVisible: false
                });
            },
            dlOnClose: function () {
                fpcm.dom.fromId('quotetext').val('');
                fpcm.dom.fromId('quotesrc').val('');
                fpcm.dom.fromId('quotetype2').prop('checked', false).checkboxradio('refresh');
                fpcm.dom.fromId('quotetype1').prop('checked', true ).checkboxradio('refresh');
            },
            insertAction: function() {
                var values = {
                    text: fpcm.dom.fromId('quotetext').val(),
                    sources: fpcm.dom.fromId('quotesrc').val(),
                    type: fpcm.dom.fromTag('input.fpcm-ui-editor-quotemode:checked').val()
                };

                if (values.type === 'blockquote') {
                    values.text = '\n<p>' + values.text + '</p>\n';
                }

                fpcm.editor.insert('<' + values.type + ' class="fpcm-articletext-quote"' + (values.sources ? ' cite="' + values.sources + '"' : '') + '>' + values.text, '</' + values.type + '>');
                fpcm.dom.fromTag(this).dialog( "close" );
            }
        });
    };
    
    fpcm.editor.removeTags = function () {

        fpcm.ajax.post('editor/cleartags', {
            data: {
                text: fpcm.editor.cmInstance.doc.getValue()
            },
            execDone: function (result) {
                fpcm.editor.cmInstance.doc.setValue(result ? result : '');
            }
        });

    };
    
    fpcm.editor.restoreSave = function () {
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
    };
    
    fpcm.editor.getMediaData = function (_addWidth) {

        var tagName = fpcm.dom.fromClass('fpcm-editor-mediatype:checked').val();

        var elPath = fpcm.dom.fromId('mediapath');
        var elPathAlt = fpcm.dom.fromId('mediapath2');
        var elFormatVal = fpcm.dom.fromId('mediaformat').val();
        var elFormatAltVal = fpcm.dom.fromId('mediaformat2').val();
        var elAutoplay = fpcm.dom.fromId('autoplay:checked');
        var elControls = fpcm.dom.fromId('controls:checked');

        var aTag = '<' + tagName + (_addWidth ? ' class="fpcm ui-full-width"' : '') + (elControls.length && elControls.val() ? ' controls' : '') + '>';
        aTag += '<source src="' + elPath.val() + '"' + (elFormatVal ? ' type="' + elFormatVal + '"' : '') + (elAutoplay.length && elAutoplay.val() ? ' autoplay' : '') + '>';

        if (elPathAlt.val()) {
            aTag += '<source src="' + elPathAlt.val() + '"' + (elFormatAltVal ? ' type="' + elFormatAltVal + '"' : '') + '>';
        }

        return {
            aTag: aTag,
            eTag: '</' + tagName + '>'
        }
    }
    
    fpcm.editor.getLinkData = function (url, text, target, cssClass) {

        if (target != "") {
            aTag = '<a href=\"' + url + '"\ target=\"' + target + '\"';
            if(cssClass) { aTag = aTag + ' class=\"'+ cssClass +'\"'; }
            aTag = aTag + '>' + text ;
        }
        else {
            aTag = '<a href=\"' + url + '\"';
            if(cssClass) { aTag = aTag + ' class=\"'+ cssClass +'\"'; }
            aTag = aTag + '>' + text ;
        }

        return {
            aTag: aTag,
            eTag: '</a>'
        }
    };
    
    fpcm.editor.getImageData = function (_asLink) {

        var pic_path = fpcm.dom.fromId('imagespath').val();
        var pic_align = fpcm.dom.fromId('imagesalign').val();
        var pic_atxt = fpcm.dom.fromId('imagesalt').val();
        var pic_css = fpcm.dom.fromId('imagescss').val();

        var elCss = fpcm.dom.fromId('imagescss');
        if(elCss) {
            var pic_css = elCss.val();
        }

        var aTag = '';

        if (pic_align == "right" || pic_align == "left") {

            aTag = '<img src=\"' + pic_path + '\" alt=\"' + pic_atxt + '\" style=\"float:' + pic_align + ';margin:3px;\"';
            if(pic_css && !_asLink) {
                aTag += ' class=\"'+ pic_css +'\"';
            }

            aTag += ' />';
            
            if (_asLink) {
                var linkData = fpcm.editor.getLinkData(pic_path, aTag, '', pic_css);
                aTag = linkData.aTag + linkData.eTag;
            }

        } else if (pic_align == "center") {
            var wrapper = '<div style=\"text-align:' + pic_align + ';\">';

            aTag = '<img src=\"' + pic_path + '\" alt=\"' + pic_atxt + '\"';
            
            if(pic_css && !_asLink) {
                aTag += ' class=\"'+ pic_css +'\"';
            }

            aTag += '/>';
            
            if (_asLink) {
                var linkData = fpcm.editor.getLinkData(pic_path, aTag, '', pic_css);
                aTag = linkData.aTag + linkData.eTag;
            }

            aTag = wrapper + aTag + '</div>';
            
        } else {
            aTag = '<img src=\"' + pic_path + '\" alt=\"' + pic_atxt + '\"';
            if(pic_css && !_asLink) {
                aTag += ' class=\"'+ pic_css +'\"';
            }
            
            aTag += ' />';
            
            if (_asLink) {
                var linkData = fpcm.editor.getLinkData(pic_path, aTag, '', pic_css);
                aTag = linkData.aTag + linkData.eTag;
            }
        }

        return {
            aTag: aTag,
            eTag: ''
        }
    };
}

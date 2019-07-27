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
            jQuery('#btnEditor-html-buttonbold').click();
        },
        "Ctrl-I"    : function() {
            jQuery('#btnEditor-html-buttonitalic').click();
        },
        "Ctrl-U"    : function() {
            jQuery('#btnEditor-html-buttonunderline').click();
        },
        "Ctrl-O"    : function() {
            jQuery('#btnEditor-html-buttonstrike').click();
        },
        "Shift-Ctrl-F"    : function() {
            fpcm.editor.insertColor();
        },
        "Ctrl-Y"    : function() {
            jQuery('#btnEditor-html-buttonsup').click();
        },
        "Shift-Ctrl-Y"    : function() {
            jQuery('#btnEditor-html-buttonsub').click();
        },
        "Shift-Ctrl-L"    : function() {
            jQuery('#btnEditor-html-buttonaleft').click();
        },
        "Shift-Ctrl-C"    : function() {
            jQuery('#btnEditor-html-buttonacenter').click();
        },
        "Shift-Ctrl-R"    : function() {
            jQuery('#btnEditor-html-buttonaright').click();
        },
        "Shift-Ctrl-J"    : function() {
            jQuery('#btnEditor-html-buttonajustify').click();
        },
        "Ctrl-Alt-N"    : function() {
            jQuery('#btnEditor-html-buttoninsertlist').click();
        },
        "Shift-Ctrl-N"    : function() {
            jQuery('#btnEditor-html-buttoninsertlistnum').click();
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
            fpcm.editor.insertReadMore();
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

        var colorsEl = jQuery('#fpcm-dialog-editor-html-insertcolor').find('div.fpcm-dialog-editor-colors');
        if (colorsEl.length) {

            for (var i = 0;i < fpcm.vars.jsvars.editorConfig.colors.length; i++) {
                colorsEl.append('<span class="fpcm-ui-padding-md-tb fas fa-square fa-fw fa-2x" style="color:' + fpcm.vars.jsvars.editorConfig.colors[i] + '" data-color="' + fpcm.vars.jsvars.editorConfig.colors[i] + '"></span>');
                if ((i+1) % 10 == 0) {
                    colorsEl.append('<br>');
                }
            }

            jQuery('div.fpcm-dialog-editor-colors span').click(function() {
                jQuery('#colorhexcode').val(jQuery(this).data('color'));
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

    fpcm.editor._insertToFields = function (url, title) {
        
        if (!url || !title) {
            self.jQuery("#fpcm-dialog-editor-html-filemanager").dialog('close').empty();
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

        self.jQuery("#fpcm-dialog-editor-html-filemanager").dialog('close').empty();
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
            dlOnOpen: function () {
                fpcm.editor.setSelectToDialog(this);
            },
            dlOnClose: function() {
                jQuery('#listrows').val('1');
                jQuery('#listtype').val('');
            },
            insertAction: function() {
                var rowCount = jQuery('#listrows').val();
                var cssType = jQuery('#listtype').val();

                aTag = '<' + listtype + (cssType ? ' style="list-style-type:' + cssType + '"' : '') + '>\n';
                for (i=0;i<rowCount;i++) {
                    aTag += '<li></li>\n';
                }

                fpcm.editor.insert(aTag, '</' + listtype + '>');

                jQuery( this ).dialog( "close" );
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
                    execDone: function (result) {
                        jQuery('#fpcm-dialog-editor-html-insertsmileys').append(result);
                        jQuery('.fpcm-editor-htmlsmiley').click(function() {
                            fpcm.editor.insert(' ' + jQuery(this).data('smileycode') + ' ', '');
                        });
                    }
                });
            },
            dlOnClose: function() {
                jQuery(this).empty();
            }
        });        

    };
    
    fpcm.editor.insertSymbol = function () {
        
        var sizeLarge = fpcm.ui.getDialogSizes();
        
        fpcm.ui.insertDialog({
            id: 'editor-html-insertsymbol',
            dlWidth: sizeLarge.width,
            dlHeight: sizeLarge.height,
            title: 'EDITOR_INSERTSYMBOL'
        });

        jQuery('.fpcm-editor-htmlsymbol').click(function() {
            fpcm.editor.insert(jQuery(this).data('symbolcode'), '');
            return false;
        });
    };
    
    fpcm.editor.insertColor = function () {

        fpcm.ui.insertDialog({
            id: 'editor-html-insertcolor',
            dlWidth: 'auto',
            dlMaxWidth: 550,
            title: 'EDITOR_INSERTCOLOR',
            onCreate: function (event, ui) {
                fpcm.ui.controlgroup('#fpcm-ui-editor-color-controlgroup', {
                    onlyVisible: false
                });
            },
            insertAction: function() {
                var mode    = jQuery('.fpcm-ui-editor-colormode:checked').val();
                var color   = jQuery('#colorhexcode').val();
                fpcm.editor.insert('<span style="' + (mode === undefined ? 'color' : mode) + ':' + (color == '' ? '#000000' : color) + ';">', '</span>');

                jQuery('#colorhexcode').val('');
                jQuery('#color_mode1').prop( "checked", true ).checkboxradio('refresh');
                jQuery('#color_mode2').prop( "checked", false ).checkboxradio('refresh');

                jQuery( this ).dialog( "close" );
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

                        var item = jQuery(this).val();
                        if (!item) {
                            jQuery('#fpcm-dialog-editor-html-insertdraft-preview').empty();
                            return false;
                        }

                        fpcm.ajax.exec('editor/draft', {
                            data    : {
                                path: item
                            },
                            execDone: function (result) {
                                var responseData = fpcm.ajax.fromJSON(result);
                                fpcm.editor_codemirror.highlight({
                                    input   : responseData.data,
                                    outputId : 'fpcm-dialog-editor-html-insertdraft-preview'
                                });
                            }
                        });

                        return false;
                    }
                });
            },
            dlOnClose: function() {
                jQuery('#fpcm-dialog-editor-html-insertdraft-preview').empty();
                fpcm.ui.resetSelectMenuSelection('#tpldraft');
            },
            insertAction: function() {
                var item = jQuery('#tpldraft').val();
                if (!item) {
                    jQuery( this ).dialog( "close" );
                    return false;
                }

                fpcm.ajax.exec('editor/draft', {
                    data    : {
                        path: item
                    },
                    execDone: function (result) {                                    
                        var responseData = fpcm.ajax.fromJSON(result);
                        fpcm.editor.cmInstance.doc.setValue(responseData.data);
                        jQuery('#fpcm-dialog-editor-html-insertdraft').dialog('close');
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
            onCreate: function (event, ui) {
                fpcm.ui.controlgroup('#fpcm-ui-editor-media-controlgroup', {
                    onlyVisible: false
                });
            },
            dlOnOpen: function () {

                fpcm.ui.selectmenu('.fpcm-editor-mediaformat',{
                    appendTo: '#fpcm-dialog-editor-html-insertmedia',
                    width: '100%'
                });
                
                jQuery( "#mediaformat" ).selectmenu( "option", "classes.ui-selectmenu-button", "fpcm-ui-border-radius-right" );
                jQuery( "#mediaformat2" ).selectmenu( "option", "classes.ui-selectmenu-button", "fpcm-ui-border-radius-right" );

            },
            dlOnClose: function() {
                jQuery('.fpcm-editor-mediatype').removeAttr('checked');    
                jQuery('#autoplay').prop('checked', false).checkboxradio('refresh');
                jQuery('#mediapath').val('');
                jQuery('#mediapath2').val('');
                jQuery('#mediatypea').prop('checked', true );
                jQuery('#mediaformat').val('').selectmenu('refresh');
                jQuery('#mediaformat2').val('').selectmenu('refresh');
            },
            insertAction: function() {
                var tagName = jQuery('.fpcm-editor-mediatype:checked').val();

                var elPath = jQuery('#mediapath');
                var elPathAlt = jQuery('#mediapath2');
                var elFormatVal = jQuery('#mediaformat').val();
                var elFormatAltVal = jQuery('#mediaformat2').val();
                var elAutoplay = jQuery('#autoplay:checked');

                var aTag = '<' + tagName + '>';
                aTag += '<source src="' + elPath.val() + '"' + (elFormatVal ? ' type="' + elFormatVal + '"' : '') + (elAutoplay.length && elAutoplay.val() ? ' autoplay' : '') + '>';

                if (elPathAlt.val()) {
                    aTag += '<source src="' + elPathAlt.val() + '"' + (elFormatAltVal ? ' type="' + elFormatAltVal + '"' : '') + '>';
                }

                fpcm.editor.insert(aTag, '</' + tagName + '>');
                jQuery( this ).dialog( "close" );
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
                jQuery('#tablerows').val('1');
                jQuery('#tablecols').val('1');
            },
            insertAction: function() {
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
        });
    };
    
    fpcm.editor.insertPicture = function () {
        
        jQuery( "#imagesalign" ).selectmenu( "option", "classes.ui-selectmenu-button", "fpcm-ui-border-radius-right" );
        jQuery( "#imagescss" ).selectmenu( "option", "classes.ui-selectmenu-button", "fpcm-ui-border-radius-right" );        

        fpcm.ui.insertDialog({
            id: 'editor-html-insertimage',
            dlWidth: fpcm.ui.getDialogSizes(top, 0.35).width,
            title: 'EDITOR_INSERTPIC',
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
                jQuery('#imagespath').val('');
                jQuery('#imagesalign').val('');
                jQuery('#imagesalt').val('');
                jQuery('#imagescss').val('');
            },
            insertAction: function() {
                var pic_path = jQuery('#imagespath').val();
                var pic_align = jQuery('#imagesalign').val();
                var pic_atxt = jQuery('#imagesalt').val();
                var pic_css = jQuery('#imagescss').val();

                var elCss = jQuery('#imagescss');
                if(elCss) {
                    var pic_css = elCss.val();
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
            },
            fileManagerAction: function () {
                fileOpenMode = 2;
                fpcm.editor.showFileManager(2);
            }
        });
    };
    
    fpcm.editor.insertLink = function() {

        jQuery( "#linkstarget" ).selectmenu( "option", "classes.ui-selectmenu-button", "fpcm-ui-border-radius-right" );
        jQuery( "#linkscss" ).selectmenu( "option", "classes.ui-selectmenu-button", "fpcm-ui-border-radius-right" );
        
        fpcm.ui.insertDialog({
            id: 'editor-html-insertlink',
            dlWidth: fpcm.ui.getDialogSizes().width,
            title: 'EDITOR_INSERTLINK',
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
                jQuery('#linksurl').val('');
                jQuery('#linkstext').val('');
                jQuery('#linkstarget').val('');
                jQuery('#linkscss').val('');
            },
            insertAction: function() {
                var lnk_url = jQuery('#linksurl').val();
                var lnk_txt = jQuery('#linkstext').val();
                var lnk_tgt = jQuery('#linkstarget').val();

                var cssInputEl = jQuery('#linkscss');
                var lnk_css = cssInputEl.length ? jQuery('#linkscss').val() : '';

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
            },
            fileManagerAction: function () {
                fileOpenMode = 1;
                fpcm.editor.showFileManager(2);
            }
        });
    };
    
    fpcm.editor.insertReadMore = function () {
        fpcm.editor.insert('<readmore>', '</readmore>');
    };

    fpcm.editor.insertQuote = function () {
        jQuery( "#linkstarget" ).selectmenu( "option", "classes.ui-selectmenu-button", "fpcm-ui-border-radius-right" );
        jQuery( "#linkscss" ).selectmenu( "option", "classes.ui-selectmenu-button", "fpcm-ui-border-radius-right" );
        
        fpcm.ui.insertDialog({
            id: 'editor-html-insertquote',
            dlWidth: fpcm.ui.getDialogSizes().width,
            title: 'EDITOR_HTML_BUTTONS_QUOTE',
            onCreate: function (event, ui) {
                fpcm.ui.controlgroup('#fpcm-ui-editor-quote-controlgroup', {
                    onlyVisible: false
                });
            },
            dlOnClose: function () {
                jQuery('#quotetext').val('');
                jQuery('#quotesrc').val('');
                jQuery('#quotetype2').prop('checked', false).checkboxradio('refresh');
                jQuery('#quotetype1').prop('checked', true ).checkboxradio('refresh');
            },
            insertAction: function() {
                var values = {
                    text: jQuery('#quotetext').val(),
                    sources: jQuery('#quotesrc').val(),
                    type: jQuery('input.fpcm-ui-editor-quotemode:checked').val()
                };

                if (values.type === 'blockquote') {
                    values.text = '\n<p>' + values.text + '</p>\n';
                }

                fpcm.editor.insert('<' + values.type + ' class="fpcm-articletext-quote"' + (values.sources ? ' cite="' + values.sources + '"' : '') + '>' + values.text, '</' + values.type + '>');
                jQuery(this).dialog( "close" );
            },
            fileManagerAction: function () {
                fpcm.editor.showFileManager(2);
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
}

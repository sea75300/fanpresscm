/**
 * FanPress CM CodeMirror Editor Wrapper Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.editor_ace = {

    _instance: {},

    defaultShortKeys: { },

    create: function(_config) {
        fpcm.editor_ace._instance = ace.edit(_config.elementId, fpcm.vars.jsvars.editorConfig.ace);

        fpcm.editor_ace._instance.getSession().on('change', function (_delta) {
            document.getElementById('articlecontent').value = fpcm.editor_ace._instance.getSession().getValue();
        });


    },

    highlight: function(config) {
        /*return CodeMirror.runMode(config.input, 'text/html', document.getElementById(config.outputId));*/
    },

    initToInstance: function (aTag, eTag) {
        fpcm.editor_ace._instance.insertSnippet(aTag + "${1:$SELECTION}" + eTag);
        fpcm.editor_ace._instance.renderer.scrollCursorIntoView();
    }

};

if (fpcm.editor) {

    fpcm.editor.initToolbar = function () {
        fpcm.dom.bindClick('.fpcm-editor-html-click', function(_ui) {

            let _btn =_ui.currentTarget;

            if (_btn.dataset.htmltag && !_btn.dataset.action) {
                fpcm.editor.insert('<' + _btn.dataset.htmltag + '>', '</' + _btn.dataset.htmltag + '>');
            }
            else if (_btn.dataset.action && _btn.dataset.htmltag) {
                fpcm.editor[_btn.dataset.action].call(_btn, _btn.dataset.htmltag);
            }
            else if (_btn.dataset.action) {
                fpcm.editor[_btn.dataset.action].call();
            }

            return false;
        });

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

        fpcm.ui_dialogs.close('editor-html-filemanager', true);
        fpcm.ui_dialogs.close('editor-html-insertimage');
        return false;
    };

    fpcm.editor._insertToFields = function (_url, _title, _rel) {

        if (!_url || !_title) {
            fpcm.ui_dialogs.close('editor-html-filemanager');
            return false;
        }

        switch (self.fileOpenMode) {
            case 1 :
                var urlField = 'linksurl';
                var titleField = 'linkstext';
                var relField = 'linksrel';
                break;
            case 2 :
                var urlField = 'imagespath';
                var titleField = 'imagesalt';
                var relField = false;
                break;
        }

        if (urlField && titleField) {
            self.document.getElementById(urlField).value = _url;
            self.document.getElementById(titleField).value  = _title;
        }

        if (relField && _rel) {
            self.document.getElementById(relField).value  = _rel;
        }

        fpcm.ui_dialogs.close('editor-html-filemanager');
        return true;
    };

    fpcm.editor.insert = function(aTag, eTag) {
        return fpcm.editor_ace.initToInstance(aTag, eTag);
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

    fpcm.editor.insertStyle = function(_vaö) {
        fpcm.editor.insert(' class="' + _vaö + '"', '');
    };

    fpcm.editor.insertList = function (listtype) {

        if (!listtype) {
            console.warn('Empty listtype parameter given');
            return false;
        }

        var _inRows = new fpcm.ui.forms.input();
        _inRows.name = 'list-rows';
        _inRows.type = 'number';
        _inRows.label = fpcm.ui.translate('EDITOR_INSERTTABLE_ROWS');
        _inRows.value = 1;
        _inRows.min = 1;
        _inRows.labelIcon = new fpcm.ui.forms.icon();
        _inRows.labelIcon.icon = 'keyboard';

        var _inType = new fpcm.ui.forms.input();
        _inType.name = 'list-type';
        _inType.label = fpcm.ui.translate('EDITOR_INSERTLIST_TYPESIGN');
        _inType.value = '';
        _inType.placehodler = '-';
        _inType.labelIcon = new fpcm.ui.forms.icon();
        _inType.labelIcon.icon = 'list-ul';

        let _tId = fpcm.ui.prepareId('list-type');

        fpcm.ui_dialogs.insert({
            id: 'editor-html-insertlist',
            title: 'EDITOR_HTML_BUTTONS_LIST' + listtype.toUpperCase(),
            icon: {
                icon: 'list'
            },
            content: [
                _inRows,
                _inType
            ],
            dlOnClose: function() {
                delete(fpcm.ui._autocompletes[_tId]);
            },
            dlOnOpenAfter: function() {

                var _src = [];

                if (listtype == 'ol') {

                    _src = [
                        {
                            value: 'decimal',
                            label: 'decimal'
                        },
                        {
                            value: 'decimal-leading-zero',
                            label: 'decimal-leading-zero'
                        },
                        {
                            value: 'lower-roman',
                            label: 'lower-roman'
                        },
                        {
                            value: 'upper-roman',
                            label: 'upper-roman'
                        },
                        {
                            value: 'lower-latin',
                            label: 'lower-latin'
                        },
                        {
                            value: 'upper-latin',
                            label: 'upper-latin'
                        },
                    ];
                }
                else {
                    _src = [
                        {
                            value: 'disc',
                            label: 'disc'
                        },
                        {
                            value: 'circle',
                            label: 'circle'
                        },
                        {
                            value: 'square',
                            label: 'square'
                        },
                    ];
                }

                if (fpcm.ui._autocompletes[_tId] !== undefined) {
                    fpcm.ui._autocompletes[_tId].setData(_src);
                    return false;
                }

                fpcm.ui.autocomplete(_tId, {
                    source: _src
                });
            },
            insertAction: function() {

                var rowCount = document.getElementById(fpcm.ui.prepareId('list-rows', true)).value;
                var cssType = document.getElementById(fpcm.ui.prepareId('list-type', true)).value;

                aTag = '<' + listtype + (cssType ? ' style="list-style-type:' + cssType + '"' : '') + '>\n';
                for (i=0;i<rowCount;i++) {
                    aTag += '   <li></li>\n';
                }

                fpcm.editor.insert(aTag, '</' + listtype + '>');
            }
        });

        delete(_inRows, _inType);
    };

    fpcm.editor.insertSmilies = function () {

        fpcm.ui_dialogs.insert({
            id: 'editor-html-insertsmileys',
            title: 'EDITOR_INSERTSMILEY',
            icon: {
                icon: 'face-smile'
            },
            dlOnOpen: function (_ui, _bso) {
                fpcm.ajax.exec('editor/smileys', {
                    quiet: true,
                    execDone: function (_result) {
                        _ui.querySelector('.modal-body').innerHTML = _result;
                        fpcm.dom.fromClass('fpcm-editor-htmlsmiley').unbind('click');
                        fpcm.dom.fromClass('fpcm-editor-htmlsmiley').click(function() {
                            fpcm.editor.insert(' ' + fpcm.dom.fromTag(this).data('smileycode') + ' ', '');
                        });
                    }
                });
            }
        });

    };

    fpcm.editor.insertSymbol = function () {

        fpcm.ui_dialogs.insert({
            id: 'editor-html-insertsymbol',
            title: 'EDITOR_INSERTSYMBOL',
            content: nkorgJSCharMap.createList(),
            icon: {
                icon: 'font'
            }
        });

        nkorgJSCharMap.addClickEvent(function() {
            fpcm.editor.insert(fpcm.dom.fromTag(this).data('code'), '');
            return false;
        });

    };

    fpcm.editor.insertColor = function () {

        fpcm.ui_dialogs.insert({
            id: 'editor-html-insertcolor',
            title: 'EDITOR_INSERTCOLOR',
            icon: {
                icon: 'palette'
            },
            insertAction: function() {
                var mode    = fpcm.dom.fromClass('fpcm-ui-editor-colormode:checked').val();
                var color   = fpcm.dom.fromId('colorhexcode').val();
                fpcm.editor.insert('<span style="' + (mode === undefined ? 'color' : mode) + ':' + (color == '' ? '#000000' : color) + ';">', '</span>');

                fpcm.dom.fromId('colorhexcode').val('');
                fpcm.dom.fromId('color_mode1').prop( "checked", true );
                fpcm.dom.fromId('color_mode2').prop( "checked", false );
            },
            dlOnOpen: function () {

                var colorsEl = fpcm.dom.fromId('fpcm-dialog-editor-html-insertcolor').find('div.fpcm-dialog-editor-colors');
                colorsEl.empty();

                if (colorsEl.length) {

                    let icon = fpcm.ui.getIcon('square', {
                        prefix: 'fas',
                        size: '2x',
                        class: 'pb-2'
                    });

                    for (var i = 0;i < fpcm.vars.jsvars.editorConfig.colors.length; i++) {
                        colorsEl.append(fpcm.dom.fromTag(icon).css('color', fpcm.vars.jsvars.editorConfig.colors[i]).data('color', fpcm.vars.jsvars.editorConfig.colors[i]));
                    }

                    fpcm.dom.fromTag('div.fpcm-dialog-editor-colors span').unbind('click');
                    fpcm.dom.fromTag('div.fpcm-dialog-editor-colors span').click(function() {
                        fpcm.dom.fromId('colorhexcode').val(fpcm.dom.fromTag(this).data('color'));
                    });

                }
            }
        });
    };

    fpcm.editor.insertDrafts = function () {

        fpcm.ui_dialogs.insert({
            id: 'editor-html-insertdraft',
            title: 'EDITOR_HTML_BUTTONS_ARTICLETPL',
            icon: {
                icon: 'file-alt'
            },
            dlOnOpen: function() {

                fpcm.ui.selectmenu('#tpldraft',{
                    change: function( event, ui ) {

                        if (!ui.value) {
                            fpcm.dom.fromId('fpcm-dialog-editor-html-insertdraft-preview').empty();
                            return false;
                        }

                        fpcm.ajax.exec('editor/draft', {
                            dataType: 'json',
                            data    : {
                                path: ui.value
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
                fpcm.dom.resetValuesByIdsSelect(['tpldraft']);
            },
            insertAction: function() {
                fpcm.ajax.exec('editor/draft', {
                    dataType: 'json',
                    quiet: false,
                    data: {
                        path: fpcm.dom.fromId('tpldraft').val()
                    },
                    execDone: function (result) {
                        fpcm.editor.cmInstance.doc.setValue(result.data);
                    }
                });
            }
        });
    };

    fpcm.editor.insertMedia = function () {

        fpcm.ui_dialogs.insert({
            id: 'editor-html-insertmedia',
            title: 'EDITOR_INSERTMEDIA',
            icon: {
                icon: 'play'
            },
            dlButtons: [{
                text: fpcm.ui.translate('GLOBAL_PREVIEW'),
                icon: "film",
                click: function () {
                    var data = fpcm.editor.getMediaData(true);
                    fpcm.dom.assignHtml('#fpcm-dialog-editor-html-insertmedia-preview', '<div class="col-12 col-md-10 my-3">' + data.aTag + data.eTag +'</div>');
                }
            }],
            dlOnOpen: function() {

                fpcm.dom.fromId('insertposterimg').click(function () {
                    fpcm.editor.showFileManager(4);
                    return false;
                });

            },
            dlOnClose: function() {
                fpcm.dom.resetValuesByIdsString(['mediapath', 'mediapath2', 'mediaposter']);
                fpcm.dom.resetValuesByIdsChecked(['autoplay', 'mediatypev']);
                fpcm.dom.resetValuesByIdsChecked(['controls', 'mediatypea'], true);
                fpcm.dom.resetValuesByIdsSelect(['mediaformat', 'mediaformat2']);
                fpcm.dom.fromId('fpcm-dialog-editor-html-insertmedia-preview').empty();
            },
            insertAction: function() {
                var data = fpcm.editor.getMediaData();
                fpcm.editor.insert(data.aTag, data.eTag);
            }
        });
    };

    fpcm.editor.insertTable = function () {

        var _inRows = new fpcm.ui.forms.input();
        _inRows.name = 'table-rows';
        _inRows.label = fpcm.ui.translate('EDITOR_INSERTTABLE_ROWS');
        _inRows.type = 'number';
        _inRows.value = 1;
        _inRows.min = 1;
        _inRows.labelIcon = new fpcm.ui.forms.icon();
        _inRows.labelIcon.icon = 'arrow-down';

        var _inCols = new fpcm.ui.forms.input();
        _inCols.name = 'table-cols';
        _inCols.label = fpcm.ui.translate('EDITOR_INSERTTABLE_COLS');
        _inCols.type = 'number';
        _inCols.value = 1;
        _inCols.min = 1;
        _inCols.labelIcon = new fpcm.ui.forms.icon();
        _inCols.labelIcon.icon = 'arrow-right';

        fpcm.ui_dialogs.insert({
            id: 'editor-html-inserttable',
            title: 'EDITOR_INSERTTABLE',
            icon: {
                icon: 'table'
            },
            content: [
                _inRows,
                _inCols
            ],
            insertAction: function() {

                let _tablerows = parseInt(document.getElementById(fpcm.ui.prepareId('table-rows', true)).value);
                let _tablecols = parseInt(document.getElementById(fpcm.ui.prepareId('table-cols', true)).value);

                let aTag = '<table>\n';

                for (i=0;i<_tablerows ;i++) {
                    aTag += '    <tr>\n';
                    for (x=0;x < _tablecols;x++) {
                        aTag += '        <td></td>\n';
                    }
                    aTag += '    </tr>\n';
                }

                fpcm.editor.insert(aTag, '</table>');
            }
        });

        delete(_inRows, _inCols);
    };

    fpcm.editor.insertPicture = function () {

        fpcm.ui_dialogs.insert({
            id: 'editor-html-insertimage',
            title: 'EDITOR_INSERTPIC',
            icon: {
                icon: 'images'
            },
            dlButtons: [{
                text: 'EDITOR_INSERTPIC_ASLINK',
                icon: "link",
                clickClose: true,
                click: function () {
                    var data = fpcm.editor.getImageData(true);
                    fpcm.editor.insert(data.aTag, data.eTag);
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
                            select: function( event, ui ) {
                                fpcm.dom.fromId('imagesalt').val(ui.item.label);
                            }
                        });
                    }
                });
            },
            dlOnClose: function() {
                fpcm.dom.resetValuesByIdsString(['imagespath', 'imagesalt']);
                fpcm.dom.resetValuesByIdsSelect(['imagesalign', 'imagescss']);
            },
            insertAction: function() {
                let data = fpcm.editor.getImageData();
                fpcm.editor.insert(data.aTag, data.eTag);
            },
            fileManagerAction: function () {
                fileOpenMode = 2;
                fpcm.editor.showFileManager(2);
            }
        });
    };

    fpcm.editor.insertLink = function() {

        fpcm.ui_dialogs.insert({
            id: 'editor-html-insert-link',
            title: 'EDITOR_INSERTLINK',
            content: fpcm.ui_dialogs.fromDOM('insertLink'),
            directAssignToDom: true,
            icon: {
                icon: 'link'
            },
            dlOnClose: function() {
                fpcm.ui._autocompletes[fpcm.ui.prepareId('linksrel')] = '';
                fpcm.ui._autocompletes[fpcm.ui.prepareId('linksurl')] = '';
            },
            dlOnOpen: function () {

                fpcm.ajax.get('autocomplete', {
                    dataType: 'json',
                    data: {
                        src: 'editorlinks'
                    },
                    execDone: function (result) {
                        fpcm.ui.autocomplete(fpcm.ui.prepareId('linksurl'), {
                            source: result,
                            minLength: 2,
                            select: function( event, ui ) {
                                fpcm.dom.fromId(fpcm.ui.prepareId('linkstext', true)).val(ui.item.label);
                            }
                        });
                    }
                });

                fpcm.ui.autocomplete(fpcm.ui.prepareId('linksrel'), {
                    source: [{
                        value: 'external',
                        label: 'external',
                    },
                    {
                        value: 'nofollow',
                        label: 'nofollow',
                    },
                    {
                        value: 'noopener',
                        label: 'noopener',
                    },
                    {
                        value: 'noreferrer',
                        label: 'noreferrer',
                    }],
                    minLength: 2,
                    select: function( event, ui ) {
                        fpcm.dom.fromId(fpcm.ui.prepareId('linkstext', true)).val(ui.item.label);
                    }
                });

            },
            insertAction: function() {

                let _formData = fpcm.dom.getValuesFromIds([
                    fpcm.ui.prepareId('linksurl', true),
                    fpcm.ui.prepareId('linkstext', true),
                    fpcm.ui.prepareId('linkstarget', true),
                    fpcm.ui.prepareId('linkscss', true),
                    fpcm.ui.prepareId('linksrel', true)
                ]);

                var linkEl = fpcm.editor.getLinkData(
                    _formData.linksurl,
                    _formData.linkstext,
                    _formData.linkstarget,
                    _formData.linkscss,
                    _formData.linksrel
                );

                fpcm.editor.insert(linkEl.aTag, linkEl.eTag);
            },
            fileManagerAction: function () {
                fileOpenMode = 1;
                fpcm.editor.showFileManager(2);
            }
        });
    };

    fpcm.editor.insertPageBreak = function () {
        fpcm.editor.insert('<p>' + fpcm.vars.jsvars.editorConfig.pageBreakVar, '</p>');
    };

    fpcm.editor.insertQuote = function () {

        fpcm.ui_dialogs.insert({
            id: 'editor-html-insertquote',
            title: 'EDITOR_HTML_BUTTONS_QUOTE',
            content: fpcm.ui_dialogs.fromDOM('insertQuote'),
            directAssignToDom: true,
            icon: {
                icon: 'quote-left'
            },
            insertAction: function() {
                var values = {
                    text: fpcm.dom.fromId(fpcm.ui.prepareId('quotetext', true)).val(),
                    sources: fpcm.dom.fromId(fpcm.ui.prepareId('quotesrc', true)).val(),
                    type: fpcm.dom.fromTag('input.fpcm-ui-editor-quotemode:checked').val()
                };

                if (values.type === 'blockquote') {
                    values.text = '\n<p>' + values.text + '</p>\n';
                }

                fpcm.editor.insert('<' + values.type + ' class="fpcm-articletext-quote"' + (values.sources ? ' cite="' + values.sources + '"' : '') + '>' + values.text, '</' + values.type + '>');
            }
        });
    };

    fpcm.editor.removeTags = function () {
        fpcm.ajax.post('editor/cleartags', {
            data: {
                text: fpcm.editor_ace._instance.getSession().getValue()
            },
            execDone: function (result) {
                fpcm.editor_ace._instance.getSession().setValue(result ? result : '');
            }
        });ś
    };

    fpcm.editor.restoreSave = function () {

        if (!confirm(fpcm.ui.translate('CONFIRM_MESSAGE'))) {
            return false;
        }

        let _disabled = (fpcm.vars.jsvars.autoSaveStorage === null ? true : false);
        fpcm.dom.fromId('editor-html-buttonrestore').prop(_disabled)-click(function () {

            fpcm.vars.jsvars.autoSaveStorage = localStorage.getItem(fpcm.vars.jsvars.editorConfig.autosavePref);
            fpcm.editor.cmInstance.setValue(fpcm.vars.jsvars.autoSaveStorage);

            return false;
        });
    };

    fpcm.editor.getMediaData = function (_addWidth) {

        var tagName = fpcm.dom.fromClass('fpcm-editor-mediatype:checked').val().replace(/[^a-z]/, '');
        let _formData = fpcm.dom.getValuesFromIds(['mediapath', 'mediaposter', 'mediapath2', 'mediaformat', 'mediaformat2', 'autoplay:checked', 'controls:checked']);

        var aTag  = '<' + tagName + (_addWidth ? ' class="fpcm-full-width"' : '') + (_formData.controls_checked ? ' controls' : '');
            aTag +=  (_formData.mediaposter ? ' poster="' + _formData.mediaposter + '"' : '') + '>';
            aTag += '<source src="' + _formData.mediapath + '"' + (_formData.mediaformat ? ' type="' + _formData.mediaformat.match(/[a-z]{5}\/{1}[a-z0-9]{3,}/) + '"' : '') + (_formData.autoplay_checked ? ' autoplay' : '') + '>';

        if (_formData.mediapath2) {
            aTag += '<source src="' + _formData.mediapath2 + '"' + (_formData.mediaformat2 ? ' type="' + _formData.mediaformat2.match(/[a-z]{5}\/{1}[a-z0-9]{3,}/) + '"' : '') + '>';
        }

        return {
            aTag: aTag,
            eTag: '</' + tagName + '>'
        }
    }

    fpcm.editor.getLinkData = function (_url, _text, _target, _cssClass, _rel) {

        aTag = '<a href=\"' + _url + '\"';

        if(_target) {
            aTag = aTag + ' target=\"'+ _target +'\"';
        }

        if(_cssClass) {
            aTag = aTag + ' class=\"'+ _cssClass +'\"';
        }

        if(_rel) {
            aTag = aTag + ' rel=\"'+ _rel +'\"';
        }

        return {
            aTag: aTag + '>' + _text,
            eTag: '</a>'
        }
    };

    fpcm.editor.getImageData = function (_asLink) {

        let _formData = fpcm.dom.getValuesFromIds(['imagespath', 'imagesalign', 'imagesalt', 'imagescss']);
        let _res = {
            aTag: '',
            eTag: ''
        };

        if (_formData.imagesalign == "right" || _formData.imagesalign == "left") {

            _res.aTag = '<img src=\"' + _formData.imagespath + '\" alt=\"' + _formData.imagesalt + '\" style=\"float:' + _formData.imagesalign + ';margin:3px;\"';
            if(_formData.imagescss && !_asLink) {
                _res.aTag += ' class=\"'+ _formData.imagescss +'\"';
            }

            _res.aTag += ' />';

            if (_asLink) {
                var linkData = fpcm.editor.getLinkData(_formData.imagespath, _res.aTag, '', _formData.imagescss);
                _res.aTag = linkData._res.aTag + linkData.eTag;
            }

        } else if (_formData.imagesalign == "center") {
            var wrapper = '<div style=\"text-align:' + _formData.imagesalign + ';\">';

            _res.aTag = '<img src=\"' + _formData.imagespath + '\" alt=\"' + _formData.imagesalt + '\"';

            if(_formData.imagescss && !_asLink) {
                _res.aTag += ' class=\"'+ _formData.imagescss +'\"';
            }

            _res.aTag += '/>';

            if (_asLink) {
                var linkData = fpcm.editor.getLinkData(_formData.imagespath, _res.aTag, '', _formData.imagescss);
                _res.aTag = linkData._res.aTag + linkData.eTag;
            }

            _res.aTag = wrapper + _res.aTag + '</div>';

        } else {
            _res.aTag = '<img src=\"' + _formData.imagespath + '\" alt=\"' + _formData.imagesalt + '\"';
            if(_formData.imagescss && !_asLink) {
                _res.aTag += ' class=\"'+ _formData.imagescss +'\"';
            }

            _res.aTag += ' />';

            if (_asLink) {
                var linkData = fpcm.editor.getLinkData(_formData.imagespath, _res.aTag, '', _formData.imagescss);
                _res.aTag = linkData.aTag + linkData.eTag;
            }
        }

        return _res;
    };

    fpcm.editor.insertIFrame = function() {

        var _input = new fpcm.ui.forms.input();
        _input.name = 'frame-url';
        _input.type = 'url';
        _input.label = fpcm.ui.translate('EDITOR_LINKURL');
        _input.value = '';
        _input.placeholder = 'https://';
        _input.labelIcon = new fpcm.ui.forms.icon();
        _input.labelIcon.icon = 'external-link-alt';

        fpcm.ui_dialogs.insert({
            id: 'editor-html-insertiframe',
            title: 'EDITOR_HTML_BUTTONS_IFRAME',
            closeButton: true,
            content: _input,
            icon: {
                icon: 'puzzle-piece'
            },
            insertAction: function () {

                let _url = fpcm.dom.fromId('fpcm-id-frame-url').val();
                if (!_url) {
                    return false;
                }

                let _code = fpcm.ui.createIFrame({
                    src: _url,
                    classes: 'fpcm-articletext-iframe',
                    id: 'fpcm-articletext-iframe-' + fpcm.ui.getUniqueID()
                });

                fpcm.editor.insert(_code, '');

            }
        });

        delete(_input);

    };
}

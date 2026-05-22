/**
 * FanPress CM Templates Namespace
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.templates = {

    _tplId: '',
    _cssFilePath: '',

    init: function() {

        if (fpcm.dataview.exists('draftfiles')) {
            fpcm.dataview.render('draftfiles');
        }

        fpcm.ui_tabs.render('#fpcm-tabs-templates', {

            onRenderHtmlBefore: (_ui) => {

                if (!fpcm.templates.editorInstance ||
                    !_ui.relatedTarget.dataset.tplid ) {

                    if (_ui.relatedTarget && _ui.relatedTarget.dataset.tplid) {
                        fpcm.templates._saveCss(_ui.relatedTarget.dataset.tplid);
                    }


                    return true;
                }

                if (fpcm.editor_ace._instancePreview) {
                    fpcm.editor_ace._instance.destroy();
                }

                fpcm.dom.fromTag(_ui.relatedTarget.dataset.bsTarget).empty();
                return true;
            },

            onRenderHtmlAfter: (_ui) => {
                fpcm.templates._restoreCss();
            }
        });

        fpcm.dom.bindClick('#btnShowpreview', fpcm.templates.saveTemplatePreview);

        fpcm.dom.bindClick('.fpcm-articletemplates-edit', function(_ev, _ui) {
            fpcm.ui_loader.hide();
            fpcm.ui_dialogs.create({
                id: 'draft-editor',
                title: 'TEMPLATE_HL_DRAFTS_EDIT',
                url: _ui.attributes.href.value,
                icon: {
                    icon: 'file-code'
                },
                closeButton: true,
                dlButtons : [
                    {
                        text: 'GLOBAL_SAVE',
                        icon: "save",
                        primary: true,
                        click: function(_ui) {
                            fpcm.dom.appendAndClickButtonInDialogFrame(_ui, 'btnSaveTemplate');
                            fpcm.ui_loader.hide();
                        }
                    }
                ]
            });

            return false;

        });

        fpcm.dom.bindClick('#btnSaveTemplates', function () {
            fpcm.ui_dialogs.confirm({
                clickYes: function () {
                    fpcm.ajax.post('templates/save', {
                        data: {
                            content: fpcm.editor_ace.getValue(),
                            tplid  : fpcm.templates._tplId
                        },
                        execDone: function (_result) {

                            if (_result instanceof Object && _result.txt) {
                                fpcm.ui.addMessage(_result);
                                return true;
                            }

                        }
                    });
                }
            });
        });

    },

    saveTemplatePreview: function() {
        fpcm.ajax.post('templates/savetemp', {
            quiet: true,
            data: {
                content: fpcm.editor_ace.getValue(),
                tplid  : fpcm.templates._tplId
            },
            execDone: function() {
                fpcm.ui_dialogs.create({
                    id: 'templatepreview-layer',
                    closeButton: true,
                    url: fpcm.vars.actionPath + 'templates/preview&tid=' + fpcm.templates._tplId,
                    dlOnOpenAfter: (_ui) => {

                        let _head = _ui.children.item(0).querySelector('iframe').contentDocument.head;

                        let _link = document.createElement('link');
                        _link.type = 'text/css';
                        _link.rel = 'stylesheet';
                        _link.href = document.getElementById('css-file-' + fpcm.templates._tplId).value;

                        fpcm.templates._saveCss();

                        _head.appendChild(_link);
                    }
                });
            }
        });

    },

    createEditorInstance: function (_tplid) {

        fpcm.templates._tplId = _tplid;

        try {

            fpcm.editor_ace.create({
                elementId: fpcm.ui.prepareId('content-ace-' + _tplid, true),
                textareaId: 'content-' + _tplid,
                type: 'template-' + _tplid
            });

            fpcm.dom.bindClick('a[data-tag]', function(_ev, _ui) {
                fpcm.editor_ace.initToInstance(_ui.dataset.tag, '');
            });

            fpcm.dom.bindClick('a[data-htmltag]', function(_ev, _ui) {
                fpcm.editor_ace.initToInstance('<' + _ui.dataset.htmltag + '>', '</' + _ui.dataset.htmltag + '>');
            });

            fpcm.editor.initToolbar();
            fpcm.templates._restoreCss();

        } catch (_e) {
            return false;
        }

        return true;
    },

    _saveCss: function(_id) {

        if (_id === undefined) {
            _id = fpcm.templates._tplId;
        }

        let _fid = 'css-file-' + _id;

        let _cff = document.getElementById(_fid);
        if (!_cff) {
            return false;
        }

        fpcm.templates._cssFilePath = _cff.value;

    },

    _restoreCss: function (_id) {
        
        if (!fpcm.templates._cssFilePath) {
            return;
        }

        if (_id === undefined) {
            _id = fpcm.templates._tplId;
        }

        let _fid = 'css-file-' + _id;

        document.getElementById(_fid).value = fpcm.templates._cssFilePath;
    }

};

fpcm.filemanager = {

    runFileIndexUpdate: function (_params) {
        fpcm.ui.relocate('?module=templates/templates&rg=6');
    },

    getAcceptTypes: function ()
    {
        return /(\.|\/)(htm|html|txt)$/i;
    },

    getAcceptTypesArr: function ()
    {
        return ['html', 'htm', 'txt', 'application/xhtml+xml', 'text/html', 'text/plain'];
    }

};

fpcm.editor = { }
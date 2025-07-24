/**
 * FanPress CM Templates Namespace
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.templates = {

    init: function() {

        if (fpcm.dataview.exists('draftfiles')) {
            fpcm.dataview.render('draftfiles');
        }

        fpcm.ui_tabs.render('#fpcm-tabs-templates', {

            onRenderHtmlBefore: function(_ui) {

                if (!fpcm.templates.editorInstance ||
                    !_ui.relatedTarget.dataset.tplid ) {
                    return true;
                }

                if (fpcm.editor_ace._instancePreview) {
                    fpcm.editor_ace._instance.destroy();
                }

                fpcm.dom.fromTag(_ui.relatedTarget.dataset.bsTarget).empty();
                return true;
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

                            let _body = _ui._element.getElementsByTagName('iframe').item(0).contentDocument.body;
                            let _form = _body.getElementsByTagName('form').item(0);
                            let _btn = document.createElement('button');
                            _btn.name = 'btnSaveTemplate';
                            _form.appendChild(_btn);
                            _btn.click();
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
                            tplid  : document.getElementById('templateid').value
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
                tplid  : document.getElementById('templateid').value
            },
            execDone: function() {
                fpcm.ui_dialogs.create({
                    id: 'templatepreview-layer',
                    closeButton: true,
                    url: fpcm.vars.actionPath + 'templates/preview&tid=' + document.getElementById('templateid').value
                });
            }
        });

    },

    createEditorInstance: function (_tplid) {

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

        } catch (_e) {
            return false;
        }

        return true;
    }

};

fpcm.filemanager = {

    runFileIndexUpdate: function (_params) {

        if (!_params.files || !_params.files) {
            fpcm.ui.relocate('?module=templates/templates&rg=7');
            return false;
        }

        let _err = 0;
        for (var i = 0; i < _params.result.files.length; i++) {

            if (!_params.result.files[i].error) {
                continue;
            }

            _err++;
        }

        if (_err) {
            return false;
        }

        fpcm.ui.relocate('?module=templates/templates&rg=7');
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
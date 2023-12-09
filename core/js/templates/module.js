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

                fpcm.templates.editorInstance.toTextArea();
                fpcm.templates.editorInstance = null;                
                fpcm.dom.fromTag(_ui.relatedTarget.dataset.bsTarget).empty();
                return true;
            }
        });

        fpcm.dom.bindClick('#showpreview', fpcm.templates.saveTemplatePreview);

        fpcm.dom.bindClick('.fpcm-articletemplates-edit', function(_ev, _ui) {

            fpcm.ui_loader.hide();
            fpcm.ui_dialogs.create({
                title: 'TEMPLATE_HL_DRAFTS_EDIT',
                url: _ui.attributes.href.value,
                closeButton: true,
                dlButtons : [
                    {
                        text: 'GLOBAL_SAVE',
                        icon: "save",
                        primary: true,
                        click: function(_ui) {
                            fpcm.dom.findElementInDialogFrame(_ui, '#btnSaveTemplate').click();
                            fpcm.ui_loader.hide();
                        }
                    }
                ]
            });
            
            return false;
            
        });
        
        fpcm.dom.bindClick('#save1', function () {
            fpcm.ui_dialogs.confirm({
                clickYes: function () {
                    fpcm.ajax.post('templates/save', {
                        data: {
                            content: fpcm.templates.editorInstance.getValue(),
                            tplid  : fpcm.dom.fromId('templateid').val()
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
                content: fpcm.templates.editorInstance.getValue(),
                tplid  : fpcm.dom.fromId('templateid').val()
            },
            execDone: function() {
                fpcm.ui_dialogs.create({
                    id: 'templatepreview-layer',
                    closeButton: true,
                    url: fpcm.vars.actionPath + 'templates/preview&tid=' + fpcm.dom.fromId('templateid').val()
                });
            }
        });
        
    },
    
    createEditorInstance: function (_tplid) {

        try {

            fpcm.templates.editorInstance = fpcm.editor_codemirror.create({
               editorId  : 'tpleditor' + _tplid,
               elementId : 'content_' + _tplid
            });

            fpcm.templates.editorInstance.setSize('100%', '100vh');

            fpcm.dom.fromTag('a.fpcm-ui-template-tags').click(function() {

                var tag = fpcm.dom.fromTag(this).attr('data-tag');
                var doc = fpcm.templates.editorInstance.doc;
                var cursorPos = doc.getCursor();

                doc.replaceRange(tag, cursorPos, cursorPos);
                fpcm.templates.editorInstance.focus();

                return false;
            });

            fpcm.dom.fromClass('fpcm-editor-html-click').click(function() {

                var tag     = fpcm.dom.fromTag(this).data('htmltag');
                fpcm.editor_codemirror.initToInstance(
                    fpcm.templates.editorInstance,
                    '<' + tag + '>',
                    '</' + tag + '>'
                );

                return false;
            });


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
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

                if (fpcm.templates.editorInstance === undefined ||
                    !_ui.relatedTarget.dataset.tplid ) {
                    return true;
                }

                fpcm.templates.editorInstance.toTextArea();
                fpcm.templates.editorInstance = null;                
                fpcm.dom.fromTag(_ui.relatedTarget.dataset.bsTarget).empty();
                return true;
            },

            onTabShowAfter: function( _ui ) {

                if (!_ui.target.dataset.tplid) {
                    return true;
                }

                try {
                    fpcm.templates.editorInstance = fpcm.editor_codemirror.create({
                       editorId  : 'tpleditor' + _ui.target.dataset.tplid,
                       elementId : 'content_' + _ui.target.dataset.tplid
                    });
                    
                    fpcm.templates.editorInstance.setSize('100%', '100vh');

                } catch (_e) {
                    fpcm.dom.assignHtml(_ev.target.dataset.bsTarget, 'Error init error\r\n' + _e);
                    return false;
                }


                fpcm.dom.fromTag('button.fpcm-ui-template-tags').click(function() {

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
        
    }

};

fpcm.filemanager = {

    runFileIndexUpdate: function (_params) {

        if (!_params.files || !_params.files) {
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

        fpcm.ui.relocate('self');
    },
    
    getAcceptTypes: function ()
    {
        return /(\.|\/)(htm|html|txt)$/i;
    }

};
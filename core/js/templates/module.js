/**
 * FanPress CM Templates Namespace
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.templates = {
    
    init: function() {

        fpcm.dataview.render('draftfiles', {
            onRenderAfter: fpcm.ui.assignControlgroups
        });

        fpcm.ui.tabs('#fpcm-tabs-templates', {
            beforeLoad: function(event, ui) {
                fpcm.ui_loader.show();
                ui.jqXHR.done(function(result) {
                    fpcm.ui_loader.hide();
                    return true;
                });
            },
            load: function( event, ui ) {
                fpcm.templates.editorInstance = fpcm.editor_codemirror.create({
                   editorId  : 'tpleditor',
                   elementId : 'content_' + ui.tab.attr('data-tplId')
                });
                
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

                
                fpcm.ui.assignControlgroups();

                return true;
            },
            addMainToobarToggleAfter: function( event, ui ) {
                if (ui.oldTab.attr('data-noempty')) {
                    return true;
                }

                ui.oldPanel.empty();
            },
            addTabScroll: true,
            addMainToobarToggle: true,
            saveActiveTab: true,
            active: fpcm.vars.jsvars.activeTab
        });

        fpcm.dom.fromId('showpreview').click(function () {
            fpcm.templates.saveTemplatePreview();
            return false;
        });
        
        fpcm.dom.fromClass('fpcm-articletemplates-edit').click(function() {

            fpcm.ui_loader.hide();

            var sizes       = fpcm.ui.getDialogSizes(top, 0.75);
            fpcm.ui.dialog({
                title     : fpcm.ui.translate('TEMPLATE_HL_DRAFTS_EDIT'),
                content   : fpcm.ui.createIFrame({
                    src: fpcm.dom.fromTag(this).attr('href'),
                    id: 'fpcm-articletemplates-editor-frame'
                }),
                dlWidth   : parseInt(sizes.width),
                dlHeight  : parseInt(sizes.height),
                resizable : true,
                defaultCloseEmpty: true,
                dlButtons : [
                    {
                        text: fpcm.ui.translate('GLOBAL_SAVE'),
                        icon: "ui-icon-disk",
                        class: 'fpcm-ui-button-primary',
                        click: function() {
                            fpcm.dom.fromTag(this).children('#fpcm-articletemplates-editor-frame').contents().find('#btnSaveTemplate').trigger('click');
                            fpcm.ui_loader.hide();
                        }
                    },
                    {
                        text: fpcm.ui.translate('GLOBAL_CLOSE'),
                        icon: "ui-icon-closethick",                    
                        click: function() {
                            fpcm.dom.fromTag(this).dialog('close');
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
                fpcm.dom.appendHtml('#fpcm-dialog-templatepreview-layer', '<iframe id="fpcm-dialog-templatepreview-layer-frame" class="fpcm-ui-full-width" src="' + fpcm.vars.actionPath + 'templates/preview&tid=' + fpcm.dom.fromId('templateid').val() + '"></iframe>');
                fpcm.ui.dialog({
                    id         : 'templatepreview-layer',
                    dlWidth    : fpcm.ui.getDialogSizes(top, 0.75).width,
                    dlHeight   : fpcm.ui.getDialogSizes(top, 0.75).height,
                    title      : fpcm.ui.translate('HL_TEMPLATE_PREVIEW'),
                    resizable  : true,
                    defaultCloseEmpty: true,
                    dlButtons  : [
                        {
                            text: fpcm.ui.translate('GLOBAL_CLOSE'),
                            icon: "ui-icon-closethick",                    
                            click: function() {
                                fpcm.dom.fromTag(this).dialog('close');
                                fpcm.dom.fromClass('fpcm-dialog-templatepreview-layer-frame').remove();
                            }
                        }                            
                    ]
                });
            }
        });
        
    }

};

fpcm.filemanager = {

    runFileIndexUpdate: function () {
        fpcm.ui.relocate('self');
    }

};
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
                fpcm.ui.showLoader(true);
                ui.jqXHR.done(function(result) {
                    fpcm.ui.showLoader();
                    return true;
                });
            },
            load: function( event, ui ) {
                fpcm.templates.editorInstance = fpcm.editor_codemirror.create({
                   editorId  : 'tpleditor',
                   elementId : 'content_' + ui.tab.attr('data-tplId')
                });
                
                jQuery('a.fpcm-ui-template-tags').click(function() {

                    var tag = jQuery(this).attr('data-tag');
                    var doc = fpcm.templates.editorInstance.doc;
                    var cursorPos = doc.getCursor();

                    doc.replaceRange(tag, cursorPos, cursorPos);
                    fpcm.templates.editorInstance.focus();

                    return false;
                });

                return true;
            },
            addMainToobarToggleAfter: function( event, ui ) {
                if (ui.oldTab.attr('data-noempty')) {
                    return true;
                }

                ui.oldPanel.empty();
            },
            addTabScroll: true,
            addMainToobarToggle: true
        });

        jQuery('#showpreview').click(function () {
            fpcm.templates.saveTemplatePreview();
            return false;
        });
        
        jQuery('.fpcm-articletemplates-edit').click(function() {

            fpcm.ui.showLoader();

            var sizes       = fpcm.ui.getDialogSizes(top, 0.75);
            fpcm.ui.dialog({
                title     : fpcm.ui.translate('TEMPLATE_HL_DRAFTS_EDIT'),
                content   : '<iframe id="fpcm-articletemplates-editor-frame" src="' + jQuery(this).attr('href') + '" class="fpcm-ui-full-width"></iframe>',
                dlWidth   : parseInt(sizes.width),
                dlHeight  : parseInt(sizes.height),
                resizable : true,
                dlButtons : [
                    {
                        text: fpcm.ui.translate('GLOBAL_SAVE'),
                        icon: "ui-icon-disk",                        
                        click: function() {
                            jQuery(this).children('#fpcm-articletemplates-editor-frame').contents().find('#btnSaveTemplate').trigger('click');
                            fpcm.ui.showLoader(false);
                        }
                    },
                    {
                        text: fpcm.ui.translate('GLOBAL_CLOSE'),
                        icon: "ui-icon-closethick",                    
                        click: function() {
                            jQuery(this).dialog('close');
                            fpcm.ui.showLoader(false);
                        }
                    }
                ],
                dlOnClose: function() {
                    jQuery(this).remove();
                }
            });
            
            return false;
            
        });
        
    },

    saveTemplatePreview: function() {

        fpcm.ajax.post('templates/savetemp', {
            data    : {
                content: fpcm.templates.editorInstance.getValue(),
                tplid  : jQuery('#templateid').val()
            },
            execDone: function() {
                fpcm.ui.appendHtml('#fpcm-dialog-templatepreview-layer', '<iframe id="fpcm-dialog-templatepreview-layer-frame" class="fpcm-ui-full-width" src="' + fpcm.vars.actionPath + 'templates/preview&tid=' + jQuery('#templateid').val() + '"></iframe>');
                fpcm.ui.dialog({
                    id         : 'templatepreview-layer',
                    dlWidth    : fpcm.ui.getDialogSizes(top, 0.75).width,
                    dlHeight   : fpcm.ui.getDialogSizes(top, 0.75).height,
                    resizable  : true,
                    title      : fpcm.ui.translate('HL_TEMPLATE_PREVIEW'),
                    dlButtons  : [
                        {
                            text: fpcm.ui.translate('GLOBAL_CLOSE'),
                            icon: "ui-icon-closethick",                    
                            click: function() {
                                jQuery(this).dialog('close');
                                jQuery('.fpcm-dialog-templatepreview-layer-frame').remove();
                            }
                        }                            
                    ],
                    dlOnClose: function( event, ui ) {
                        jQuery(this).empty();
                    }
                });
            }
        });
        
    }

};
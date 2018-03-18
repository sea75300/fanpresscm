/**
 * FanPress CM Templates Namespace
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.templates = {
    
    init: function() {

        fpcm.templates.initCodeMirror('article');
        fpcm.templates.initTemplatePreview();
        fpcm.dataview.render('draftfiles', {
            onRenderAfter: fpcm.ui.assignControlgroups
        });

        fpcm.ui.tabs('.fpcm-tabs-general', {
            addTabScroll: true,
            addMainToobarToggle: true
        });
        
        jQuery('.fpcm-articletemplates-edit').click(function() {

            fpcm.ui.showLoader();

            var sizes       = fpcm.ui.getDialogSizes(top, 0.75);
            fpcm.ui.dialog({
                title     : fpcm.ui.translate('TEMPLATE_HL_DRAFTS_EDIT'),
                content   : '<iframe id="fpcm-articletemplates-editor-frame" src="' + jQuery(this).attr('href') + '" class="fpcm-full-width"></iframe>',
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

    enabledEditors: {
        ed2: false,
        ed3: false,
        ed4: false,
        ed5: false,
        ed6: false,
    },

    getIdClass: function(id) {

        switch (id) {
            case 1:
                return 'article';
                break;
            case 2:
                return 'articleSingle';
                break;
            case 3:
                return 'comment';
                break;
            case 4:
                return 'commentForm';
                break;
            case 5:
                return 'latestNews';
                break;
            case 6:
                return 'tweet';
                break;
        }
        
        return false;
    },

    initTemplatePreview: function() {
        
        jQuery('.fpcm-template-tab').click(function () {

            fpcm.vars.jsvars.templateId = jQuery(this).data('tpl');

            var idClass = fpcm.templates.getIdClass(fpcm.vars.jsvars.templateId);
            if (idClass !== false && !fpcm.templates.enabledEditors['ed' + fpcm.vars.jsvars.templateId]) {
                fpcm.templates.initCodeMirror(idClass);
            }

            if (fpcm.vars.jsvars.templateId == 7) {
                return false;
            }

            return false;
        });
        
        jQuery('#showpreview').click(function () {
            fpcm.templates.saveTemplatePreview();
            return false;
        });
        
    },

    saveTemplatePreview: function() {

        fpcm.ajax.post('templates/savetemp', {
            data    : {
                content: fpcm.templates.enabledEditors['ed' + fpcm.vars.jsvars.templateId].getValue(),
                tplid  : fpcm.vars.jsvars.templateId
            },
            workData: fpcm.vars.jsvars.templateId,
            execDone: function() {

                tplId = fpcm.ajax.getWorkData('templates/savetemp');

                fpcm.ui.appendHtml('#fpcm-dialog-templatepreview-layer', '<iframe id="fpcm-dialog-templatepreview-layer-frame" class="fpcm-full-width" src="' + fpcm.vars.actionPath + 'templates/preview&tid=' + tplId + '"></iframe>');
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
        
    },

    initCodeMirror: function(id) {

        fpcm.templates.enabledEditors['ed' + fpcm.vars.jsvars.templateId] = fpcm.editor_codemirror.create({
           editorId  : 'tpleditor-' + id,
           elementId : id,
        });

    }

};
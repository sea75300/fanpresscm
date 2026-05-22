/**
 * FanPress CM Templates Namespace
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.templates_articles = {
    
    init: function() {
        fpcm.editor_ace.create({
           elementId: fpcm.ui.prepareId('content-ace', true),
           textareaId : 'templatecode',
           type: 'draft'
        });
        
        fpcm.editor.initToolbar();
    }

};

fpcm.editor = { }
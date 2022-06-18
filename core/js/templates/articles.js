/**
 * FanPress CM Templates Namespace
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.templates_articles = {
    
    init: function() {
        var cm = fpcm.editor_codemirror.create({           
           editorId  : 'templatecode',
           elementId : 'templatecode'
        });

        cm.setSize('100%', '100%');
    }

};
/**
 * FanPress CM CodeMirror Editor Wrapper Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.editor_codemirror = {

    create: function(config) {

        params = {
            lineNumbers      : true,
            matchBrackets    : true,
            lineWrapping     : true,
            autoCloseTags    : true,
            id               : config.editorId,
            value            : document.documentElement.innerHTML,
            mode             : "text/html",            
            theme            : 'fpcm',
            matchTags        : {
                bothTags     : true
            },            
            extraKeys        : {
                "Ctrl-Space" : "autocomplete",
            }
        };
        
        if (config.extraKeys !== undefined) {
            jQuery.extend(params.extraKeys, config.extraKeys);
        }

        return CodeMirror.fromTextArea(document.getElementById(config.elementId), params);         

    },

    highlight: function(config) {
        return CodeMirror.runMode(config.input, 'text/html', document.getElementById(config.ouputId));
    }

};
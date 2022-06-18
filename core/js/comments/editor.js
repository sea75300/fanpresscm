/**
 * FanPress CM Comments Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.editor = {

    init: function () {
        fpcm.editor[fpcm.vars.jsvars.editorInitFunction].call();
        fpcm.editor.initToolbar();

        fpcm.ui.autocomplete('#commentarticle', {
            source: fpcm.vars.ajaxActionPath + 'autocomplete&src=articles',
            minLength: 3
        });

        fpcm.dom.fromId('btnLockIp').unbind('click');
        fpcm.dom.fromId('btnLockIp').click(function (event, ui) {

            var cid = fpcm.dom.fromTag(event.currentTarget).data('commentid');

            fpcm.ui_dialogs.confirm({
                clickYes: function () {
                    fpcm.ajax.post('comments/lockip', {
                        dataType: 'json',
                        data: {
                            cid: cid
                        },
                        execDone: function (result) {
                            fpcm.ui.addMessage(result);
                        }
                    });
                }
            });

            return false;
        });
    },

    initCodeMirror: function() {

        fpcm.editor.cmInstance = fpcm.editor_codemirror.create({
           editorId  : 'htmleditor',
           elementId : 'commenttext',
           extraKeys : fpcm.editor_codemirror.defaultShortKeys
        });

    },
    
    initTinyMce: function() {
        fpcm.editor_tinymce.create(fpcm.vars.jsvars.editorConfig);
    },
    
    getGalleryReplacement: function (_values) {
        return fpcm.vars.jsvars.editorGalleryTagStart.replace(
            '{{IMAGES}}',
            fpcm.vars.jsvars.editorGalleryTagThumb + _values.join(fpcm.vars.jsvars.editorGalleryTagLink + '|' + fpcm.vars.jsvars.editorGalleryTagThumb) + fpcm.vars.jsvars.editorGalleryTagLink
        );
    }

};
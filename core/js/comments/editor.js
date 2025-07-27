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

        if (!fpcm.editor.initToolbar) {
            fpcm.ui.addMessage({
                txt: 'Failed to init editor toolbar, fpcm.editor.initToolbar not defined',
                type: 'error'
            });

            return false;
        }

        if (!fpcm.editor.initEditor) {
            fpcm.ui.addMessage({
                txt: 'Failed to init editor toolbar, fpcm.editor.initEditor not defined',
                type: 'error'
            });

            return false;
        }

        fpcm.editor.initToolbar();
        fpcm.editor.initEditor({
           elementId: fpcm.ui.prepareId('content-ace', true),
           textareaId: 'commenttext',
           type: 'commenttext'
        });

    },

    initAfter: function() {

        fpcm.ui.autocomplete('#commentarticle', {
            source: fpcm.vars.ajaxActionPath + 'autocomplete&src=articles',
            minLength: 3
        });

        fpcm.dom.bindClick('#btnLockIp', function (event, ui) {

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

    initTinyMce: function() {
        fpcm.editor_tinymce.create(fpcm.vars.jsvars.editorConfig);
    },

    initAce: function() {

        fpcm.editor_ace.create({
           elementId: fpcm.ui.prepareId('content-ace', true),
           textareaId: 'commenttext',
           type: 'commenttext'
        });

    },

    getGalleryReplacement: function (_values) {
        return fpcm.vars.jsvars.editorGalleryTagStart.replace(
            '{{IMAGES}}',
            fpcm.vars.jsvars.editorGalleryTagThumb + _values.join(fpcm.vars.jsvars.editorGalleryTagLink + '|' + fpcm.vars.jsvars.editorGalleryTagThumb) + fpcm.vars.jsvars.editorGalleryTagLink
        );
    }

};
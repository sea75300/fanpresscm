/**
 * FanPress CM Filemanager Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.filemanager.listActions = {

    init: function() {

        fpcm.dom.bindClick('a.btn[data-insert-type]', function (_e, _ui) {

            let _search = new URLSearchParams(window.location.search);
            let _isMedia = _search.get('m') === 'media';
            let _isFile = _search.get('m') === 'file';

            if (!_isFile && !_isMedia && _ui.dataset.insertType === 'video') {
                fpcm.ui.addMessage({
                    txt: 'FILE_LIST_INSERT_FAILED_IMAGE',
                    type: 'neutral'
                });
                return;
            }

            if (!_isFile && _isMedia && _ui.dataset.insertType === 'image') {
                fpcm.ui.addMessage({
                    txt: 'FILE_LIST_INSERT_FAILED_VIDEO',
                    type: 'neutral'
                });
                return;
            }

            switch (_ui.dataset.insertFn) {
                case 'thumb' :
                    parent.fpcm.editor.insertThumbByEditor(_ui.href, _ui.dataset.imgtext);
                    break;
                case 'full' :
                    parent.fpcm.editor.insertFullByEditor(_ui.href, _ui.dataset.imgtext);
                    break;
                case 'articleimg' :
                    parent.document.getElementById('articleimagepath').value  = _ui.href;
                    break;
                case 'poster-thumb' :
                    parent.document.getElementById(fpcm.ui.prepareId('mediaposter', true)).value  = _ui.href;
                    break;
                case 'poster-full' :
                    parent.document.getElementById(fpcm.ui.prepareId('mediaposter', true)).value  = _ui.href;
                    break;
                case 'video' :
                    parent.fpcm.editor.insertFullByEditor(_ui.href, _ui.href);
                    break;
            }

            fpcm.ui_dialogs.close('editor-html-filemanager', true);
        });

        fpcm.dom.bindClick('#btnInsertGallery', function () {

            let _items = document.querySelectorAll('.fpcm-ui-list-checkbox[data-type=image]:checked');
            if (!_items || !_items.length) {
                return false;
            }

            parent.fpcm.editor.insertGalleryByEditor(_items);
            return false;
        });
    },

    initListActions: function () {
        fpcm.filemanager.initInsertButtons();
    }

};

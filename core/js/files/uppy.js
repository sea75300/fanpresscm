/**
 * FanPress CM File Uppy Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.fileuploader = {

    init: function() {

        var _uppy = new window.Uppy({
            locale: window.Uppy.locales.de_DE,
            restrictions: {
                allowedFileTypes: fpcm.filemanager.getAcceptTypesArr
                                ? fpcm.filemanager.getAcceptTypesArr()
                                : ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', '.jpeg', '.jpg', '.png', '.gif', '.webp']
            }
        });

        _uppy
            .use(window.Uppy.XHRUpload, { endpoint:
                fpcm.vars.ajaxActionPath + 'upload/uppy&dest=' + fpcm.vars.jsvars.uploadDest
            })
            .use(window.Uppy.FileInput, {
                target: '#fpcm-id-uppy-select'
            })
            .use(window.Uppy.DragDrop, {
                target: '#fpcm-id-uppy-drop-area'
            })
            .use(window.Uppy.StatusBar, {
                target: '#fpcm-id-uppy-progress',
                hideAfterFinish: false,
                showProgressDetails: true
            })
            .use(window.Uppy.Informer, {
                target: '#fpcm-id-uppy-informer',
            })
            .on('complete', function (_file, _response) {
                /*fpcm.filemanager.runFileIndexUpdate(_file);*/
                _uppy.cancelAll();
            })
            .on('upload-start', function (_file) {
                fpcm.fileuploader._toggleUi(false, true);
            })
            .on('pause-all', function (_file) {
                fpcm.fileuploader._toggleUi(true, false);
            })
            .on('cancel-all', function (_file) {
                fpcm.fileuploader._toggleUi(true, true);
            });

        fpcm.dom.bindClick('#btnCancel', function () {
           _uppy.cancelAll();
        });

        fpcm.dom.bindClick('#btnPause', function () {
           _uppy.pauseAll()
        });

        fpcm.dom.bindClick('#btnResume', function () {
           _uppy.resumeAll()
        });

    },

    _toggleUi: function(_pause, _resume) {
        document.getElementById('btnPause').disabled = _pause;
        document.getElementById('btnResume').disabled = _resume;
    }

};
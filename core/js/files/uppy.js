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

        var _uppy = new window.Uppy.Core({
            locale: window.Uppy.locales.de_DE,
            restrictions: {
                allowedFileTypes: fpcm.filemanager.getAcceptTypesArr
                                ? fpcm.filemanager.getAcceptTypesArr()
                                : ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', '.jpeg', '.jpg', '.png', '.gif']
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
                fpcm.filemanager.runFileIndexUpdate(_file);
                _uppy.cancelAll();
            });

        fpcm.dom.bindClick('#btnUpload', function () {
           _uppy.upload(); 
        });

        fpcm.dom.bindClick('#btnCancel', function () {
           _uppy.cancelAll(); 
        });

    },
    
    initAfter: function () {
        fpcm.dom.fromClass('uppy-FileInput-btn').addClass('w-100');
    }
    
};
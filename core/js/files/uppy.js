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

        var _uppy = new Uppy.Core({
            locale: Uppy.locales.de_DE,
            restrictions: {
                allowedFileTypes: fpcm.filemanager.getAcceptTypesArr
                                ? fpcm.filemanager.getAcceptTypesArr()
                                : ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', '.jpeg', '.jpg', '.png', '.gif']
            }
        });

        _uppy
            .use(Uppy.XHRUpload, { endpoint: 
                fpcm.vars.ajaxActionPath + 'upload/uppy&dest=' + fpcm.vars.jsvars.uploadDest
            })
            .use(Uppy.FileInput, {
                target: '#fpcm-uppy-select'
            })
            .use(Uppy.DragDrop, {
                target: '#fpcm-uppy-drop-area'
            })
            .use(Uppy.StatusBar, {
                target: '#fpcm-uppy-progress',
                hideAfterFinish: true,
                showProgressDetails: true,
                hideUploadButton: true
            })
            .on('complete', function (_file, _response) {
                fpcm.filemanager.runFileIndexUpdate(_file);
                _uppy.reset();
            });

        fpcm.dom.bindClick('#btnUpload', function () {
           _uppy.upload(); 
        });

        fpcm.dom.bindClick('#btnCancel', function () {
           _uppy.cancelAll(); 
        });

    }
    
};
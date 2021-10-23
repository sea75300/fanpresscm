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

        var _uppy = new Uppy.Core()

        _uppy.use(Uppy.FileInput, { target: '.UppyInput', pretty: true })
             .use(Uppy.XHRUpload, { endpoint: 
                fpcm.vars.ajaxActionPath + 'testing&dest=' + fpcm.vars.jsvars.uploadDest
              })
              .use(Uppy.StatusBar, {
                target: '.UppyInput-Progress',
                hideUploadButton: true,
                hideAfterFinish: false
            });

        fpcm.dom.bindClick('#btnUpload', function () {
           _uppy.upload(); 
        });

    }
    
};
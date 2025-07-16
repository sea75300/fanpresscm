import Cropper from './../../lib/cropperjs2/dist/cropper.esm.min.js';

export class cropper2 {

    _instance = null;

    constructor(_id) {

        this._instance = new Cropper(fpcm.ui.prepareId(_id), {
            template: this._getTemplate().join('')
        });

    }

    _getTemplate() {

        return new Array(
            '<cropper-canvas background class="w-100 h-100">' +
            '   <cropper-image rotatable scalable skewable translatable></cropper-image>' +
            '   <cropper-handle action="select" plain></cropper-handle>' +
            '   <cropper-shade hidden></cropper-shade>' +
            '   <cropper-selection initial-coverage="0" dynamic movable resizable zoomable>',
            '       <cropper-grid role="grid" bordered covered></cropper-grid>',
            '       <cropper-crosshair centered></cropper-crosshair>',
            '       <cropper-handle action="move" theme-color="rgba(255, 255, 255, 0.35)"></cropper-handle>',
            '       <cropper-handle action="n-resize"></cropper-handle>',
            '       <cropper-handle action="e-resize"></cropper-handle>',
            '       <cropper-handle action="s-resize"></cropper-handle>',
            '       <cropper-handle action="w-resize"></cropper-handle>',
            '       <cropper-handle action="ne-resize"></cropper-handle>',
            '       <cropper-handle action="nw-resize"></cropper-handle>',
            '       <cropper-handle action="se-resize"></cropper-handle>',
            '       <cropper-handle action="sw-resize"></cropper-handle>',
            '   </cropper-selection>',
            '</cropper-canvas>'
        );

    }

    rotate(_value) {
        this._instance.getCropperImage().$rotate(_value)
    }

    zoom(_value) {
        this._instance.getCropperImage().$zoom(_value)
    }

    save(_param) {

        this._instance.getCropperSelection().$toCanvas()
        .then(canvas => {

            canvas.toBlob((_blob) => {
                
                
                console.log(_blob);
                
                const formData = new FormData();
                formData.append('file', _blob, _param.data.filename);
                fpcm.ajax.post('editor/imgupload', {
                    data: formData,
                    processData: false,
                    contentType: false,
                    execDone: function (result) {
                        _param.afterUpload(result);
                        fpcm.vars.jsvars.cropperSizes = {};
                    }
                });
            });


        })
        .catch(error => {
            console.error("Fehler! ", error);
        })
        /*.finally(data => {
            console.log(data);
        });/*/
    }

    reset() {

        this._instance.getCropperImage().$resetTransform();
        this._instance.getCropperSelection().$reset();
        this._instance.getCropperImage().$center('contain');

    }

}

fpcm.cropper = {

    getInstance: function (_id) {
        return new cropper2(_id);
    }

};
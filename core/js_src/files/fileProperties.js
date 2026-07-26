/**
 * FanPress CM Filemanager Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.fileProperties = {

    setImageResolution: function(_url, _wEl, _hEl) {

        let _dataObj = new Image();
        _dataObj.src = _url;
        _dataObj.onload = function () {

            if (_wEl instanceof HTMLInputElement) {
                _wEl.value = _dataObj.naturalWidth;
            }
            else {
                _wEl.innerHTML = _dataObj.naturalWidth;
            }

            if (_wEl instanceof HTMLInputElement) {
                _hEl.value  = _dataObj.naturalHeight;
            }
            else {
                _hEl.innerHTML = _dataObj.naturalWidth;
            }

        };

    },

    setVideoResolution: function (_url, _wEl, _hEl) {

        let _dataObj = document.createElement('video');
        _dataObj.src = _url;
        _dataObj.addEventListener('loadedmetadata', function(e){

            if (_wEl instanceof HTMLInputElement) {
                _wEl.value = _dataObj.videoWidth;
            }
            else if ( typeof _wEl === 'string') {
                _wEl = _dataObj.videoWidth;
            }
            else {
                _wEl.innerHTML = _dataObj.naturalWidth;
            }

            if (_wEl instanceof HTMLInputElement) {
                _hEl.value  = _dataObj.videoHeight;
            }
            else if ( typeof _hEl === 'string') {
                _hEl = _dataObj.videoHeight;
            }
            else {
                _hEl.innerHTML = _dataObj.videoHeight;
            }


        });
    }
};

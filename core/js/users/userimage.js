/**
 * FanPress CM user image namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 5.0.0-b4
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.filemanager = {

    runFileIndexUpdate: function (_params)
    {
        if (!fpcm.vars.jsvars.userImgRedir) {
            return false;
        }

        if (!_params.successful && !_params.successful.length !== 1) {
            fpcm.ui.relocate(fpcm.vars.jsvars.userImgRedir);
            return false;
        }
        

        let _first = _params.successful.shift();
        if (_first === undefined) {
            return false;
        }
        
        let _el = document.getElementById('fpcm-ui-avatar');        
        _el.src = _first.uploadURL;
        _el.className = 'img-thumbnail';
        
        setTimeout(function () {
            fpcm.ui_loader.show();
            fpcm.ui.relocate(fpcm.vars.jsvars.userImgRedir);
        }, 1500);        
        
        return true;
    },
    
    getAcceptTypes: function ()
    {
        return /(\.|\/)(jp?g|png|gif)$/i;
    }

};
/**
 * FanPress CM UI Loader Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.ui_loader = {
    
    show: function(_message) {
        
        if (fpcm.dom.fromId('fpcm-loader').length != 0) {
            return true;
        }
        
        var html = [
            '<div id="fpcm-loader">',
            '   <div class="position-fixed top-50 start-50 translate-middle d-flex justify-content-center">',
            '       <div class="spinner-grow text-warning" role="status"><span class="visually-hidden">',
            '           <span class="visually-hidden">' + fpcm.ui.translate('GLOBAL_PLEASEWAIT') + '...</span>',
            '       </div>',   
            (_message ? '       <div class="text-light fpcm ui-label ms-3 align-self-center">' + _message + '</div>' : ''),
            '   </div>',
            '   <div class="modal-backdrop show ui-blurring"></div>',
            '</div>'            
        ];

        fpcm.dom.appendHtml('#fpcm-body', html.join(''));
        fpcm.dom.fromId('fpcm-loader').fadeIn(100);
        return true;
    },
    
    hide: function () {
        
        var _el = fpcm.dom.fromId('fpcm-loader');
        if (fpcm.dom.fromId('fpcm-loader').length == 0) {
            return true;
        }
        
        fpcm.dom.fromId('fpcm-loader').remove();
        return true;   
    }

};
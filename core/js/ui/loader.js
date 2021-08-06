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
            '   <div class="position-fixed top-50 start-50 translate-middle fpcm-loader-icon">\n\n',
                fpcm.ui.getIcon('spinner', {
                    stack: 'circle',
                    spinner: 'spin fa-inverse',
                    size: _message ? 'lg' : '2x',
                    class: 'text-primary'
                }),
                (_message ? '<span class="fpcm ui-label ms-3">' + _message + '</span>' : ''),
            '   </div>',
            '   <div class="modal-backdrop show ui-blurring"></div>',
            '</div>'            
        ];

        fpcm.dom.appendHtml('#fpcm-body', html.join(''));
        fpcm.dom.fromId('fpcm-loader').fadeIn(100);
        return true;
    },
    
    hide: function () {
        
        var el = fpcm.dom.fromId('fpcm-loader');
        if (el.length == 0) {
            return true;
        }

        el.fadeOut('fast', function(){
            fpcm.dom.fromTag(this).fadeOut(100).remove();
        });

        return true;   
    }

};
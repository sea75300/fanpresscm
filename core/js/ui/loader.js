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
            '<div id="fpcm-loader" class="row g-0 fpcm-ui-position-fixed fpcm-ui-position-left-0 fpcm-ui-position-right-0 fpcm-ui-position-bottom-0 fpcm-ui-position-top-0 align-self-center">',
            '   <div class="fpcm fpcm-ui-position-absolute fpcm-ui-position-top-0 ui-background-black-75p ui-blurring fpcm-ui-full-width fpcm-ui-full-height"></div>',
            '   <div class="fpcm-ui-position-relative fpcm-ui-align-center fpcm-loader-icon">\n\n',
                fpcm.ui.getIcon('spinner', {
                    stack: 'circle',
                    spinner: 'spinner fa-inverse',
                    size: _message ? 'lg' : '2x',
                }),
                (_message ? '<span class="fpcm ui-label">' + _message + '</span>' : ''),
            '   </div>',
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
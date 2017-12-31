/**
 * FanPress CM Login Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.login = {

    init: function() {
        this.moveToCenter();
        fpcm.ui.setFocus('loginusername');
    },

    moveToCenter: function() {
        
        if (jQuery(window).width() < 800) {
            return;
        }

        var loginTopPos = (jQuery(window).height() / 2 - jQuery('.fpcm-login-form').height() * 0.5);
        jQuery('.fpcm-login-form').css('margin-top', loginTopPos);

    },

};
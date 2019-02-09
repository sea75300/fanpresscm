/**
 * FanPress CM Login Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.login = {

    init: function() {

        var _passCodeField = jQuery('#loginpassword');
        var _authCodeField = jQuery('#loginauthcode');

        if (!_authCodeField.length || _authCodeField.hasClass('fpcm-ui-hidden')) {
            return true;
        }

        _passCodeField.focusout(function () {
            fpcm.login.showAuthCodeField();
        });

        jQuery('#btnLogin').click(function () {
            if (!_authCodeField.hasClass('fpcm-ui-hidden')) {
                return true;
            }

            fpcm.login.showAuthCodeField();
            return false;
        });

        return true;
    },
    
    showAuthCodeField: function () {
        jQuery('#fpcm-loginauthcode-box').fadeIn();
        fpcm.ui.setFocus('loginauthcode');
        return true;
    }

};
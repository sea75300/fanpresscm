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

        var _authCodeFieldBox = jQuery('#fpcm-loginauthcode-box');
        if (!_authCodeFieldBox.length || !fpcm.ui.isHidden(_authCodeFieldBox)) {
            return true;
        }

        jQuery('form').keydown(function(event) {
            if (event.which === 13 && fpcm.ui.isHidden('#fpcm-loginauthcode-box')) {
                fpcm.login.showAuthCodeField();
                return false;
            }

            return true;
        });

        jQuery('#loginpassword').focusout(function () {
            if (!fpcm.ui.isHidden('#fpcm-loginauthcode-box')) {
                return true;
            }

            fpcm.login.showAuthCodeField();
            return false;
        });

        jQuery('#btnLogin').click(function () {
            if (!fpcm.ui.isHidden('#fpcm-loginauthcode-box')) {
                return true;
            }

            fpcm.login.showAuthCodeField();
            return false;
        });

        return true;
    },
    
    showAuthCodeField: function () {
        jQuery('#fpcm-loginauthcode-box').removeClass('fpcm-ui-hidden');
        fpcm.ui.setFocus('loginauthcode');
        return true;
    }

};
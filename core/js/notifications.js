/**
 * FanPress CM Notifications Callback Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.notifications = {

    init: function () {

        jQuery('li.fpcm-notification-item a').click(function() {
            
            var callback = jQuery(this).parent('li').attr('data-callback');
            if (!callback) {
                return true;
            }

            if (!fpcm.notifications[callback]) {
                console.log('callback ' + callback + ' not found');
                return false;
            }

            fpcm.notifications[callback].call();
        });

    }

};
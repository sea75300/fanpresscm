/**
 * FanPress CM Notifications Callback Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.notifications = {

    init: function () {

        fpcm.dom.bindClick('li.fpcm-notification-item a', function() {
            
            var callback = fpcm.dom.fromTag(this).parent('li').data('callback');
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
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

    },
    
    onRefresh: function (_result) {
        fpcm.notifications.addFromAjax(_result.notifications, _result.notificationCount);
    },
    
    addFromAjax: function (_nstring, _count) {

        let _idStr = '#fpcm-id-notifications';
        if (!fpcm.dom.fromId(_idStr).length) {
            return false;
        }

        fpcm.dom.assignHtml(_idStr, _nstring);
        let _el = fpcm.dom.fromId('notificationsCount').html(_count);

        if (_count) {

            fpcm.dom.bindClick('button[data-set-read-notify]', function (_ui) {

                fpcm.ui.replaceIcon(_ui.currentTarget.id, 'fa-envelope-circle-check', 'circle-notch fa-spin-pulse');

                fpcm.reminders.delete(
                    _ui.currentTarget.dataset.setReadType,
                    _ui.currentTarget.dataset.setReadNotify,
                    function () {
                        _ui.currentTarget.parentElement.parentElement.remove();
                    }
                );

            });
            
            fpcm.system.openUpdateDialog('btnStartUpdateNotify');

            _el.removeClass('d-none');
            return true;
        }

        _el.addClass('d-none');        
    }
    
};
/**
 * FanPress CM Users Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 5.3.0-b2
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.ips_actions = {

    init: function() {

        fpcm.dom.bindClick('#btnSettings', function (ev, _ui, _result) {
            fpcm.ui_dialogs.settings('comments', 'settings', function () {
                fpcm.ui.relocate('self');
            });
        });

        fpcm.dom.bindEvent('#sortlist', 'change', function () {
            fpcm.dom.fromId('fpcm-ui-form').submit();
        });
    },

};
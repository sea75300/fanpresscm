/**
 * FanPress CM Options Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.options = {

    init: function () {

        fpcm.ui_tabs.render('#options');

        fpcm.dom.fromId('syschecksubmitstats').click(function () {
            fpcm.ajax.post('syscheck', {
                data: {
                    sendstats: 1
                }
            });
        });

        fpcm.ui.selectmenu('#smtp_enabled', {
            change: function( event, _item ) {
                var status = (_item.value == 1 ? false : true);
                fpcm.dom.isReadonly('input.fpcm-ui-options-smtp-input', status);
                fpcm.dom.fromId('smtp_settingsencr').prop('disabled', status);
                fpcm.dom.fromId('btnSmtp_settingspass-toggle').prop('disabled', status);
                return true;
            }
        });

        fpcm.dom.bindEvent('#file_thumb_size', 'change', function (_ev) {
            let _par = fpcm.dom.fromId('fpcm-thumb-preview');
            _par.find('img').width(_ev.target.value).height(_ev.target.value);
            _par.find('figcaption > span').text(_ev.target.value);
        });

        fpcm.system.checkForUpdates();

    }
};
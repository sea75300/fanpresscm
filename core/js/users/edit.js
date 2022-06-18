/**
 * FanPress CM user editing Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.useredit = {

    init: function () {

        fpcm.dom.bindClick('#openQr', function(_ui) {

            fpcm.ui_dialogs.create({
                image: _ui.delegateTarget.href,
                title: _ui.delegateTarget.innerText,
                class: 'text-center',
                size: 'sm',
                closeButton: true
            });
            
            return false;
        });

    }

};
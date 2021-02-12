/**
 * FanPress CM Module info view namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 4.5.1
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.moduleinfo = {

    init: function() {

        fpcm.dom.fromId('btnInstall').unbind('click');
        fpcm.dom.fromId('btnInstall').click(function () {

            let _hash = fpcm.dom.fromTag(this).data('hash');
            if (!_hash) {
                return false;
            }
            
            let el = parent.fpcm.dom.fromId('install' + _hash);
            if (!el.length) {
                return false;
            }

            parent.fpcm.ui.relocate(el.attr('href'));
        });

    }
};
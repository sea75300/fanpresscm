/**
 * FanPress CM dev tools namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.dev = {

    init: function ()
    {
        fpcm.dom.bindClick('button[data-toolbar-name]', function (_el) {

            if (!_el.delegateTarget) {
                return false;
            }

            if (!_el.delegateTarget.dataset) {
                return false;
            }

            if (!_el.delegateTarget.dataset.toolbarName) {
                return false;
            }

            let _input = document.createElement('input');
            _input.value = _el.delegateTarget.dataset.toolbarName;
            _input.id = 'tmp' + _el.delegateTarget.dataset.toolbarName;
            document.body.appendChild(_input);
            document.getElementById(_input.id).select();
            document.execCommand('copy');
            document.body.removeChild(_input);

            fpcm.ui.addMessage(new fpcm.ui.message(
                'info',
                `Area ID copied: ${_input.value}`
            ));
        });

    }
};
/**
 * FanPress CM javascript bootstrapper
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020-2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

jQuery.noConflict();

(function () {

    fpcm.ui.init();
    fpcm.ui_notify.init();

    delete fpcm.ui.init;
    delete fpcm.ui_notify.init;

    fpcm.worker = new Worker('core/js/worker.js');
    fpcm.worker.onmessage = function(_event) {

        if (typeof _event.data.cmd) {

            if (!fpcm[_event.data.ns]) {
                console.warn('Invalid namespace given fpcm.' + _event.data.ns);
                return false;
            }

            if (!fpcm[_event.data.ns][_event.data.func]) {
                console.warn('Invalid function call fpcm.' + _event.data.ns + '.' + _event.data.func);
                return false;
            }

            let _func = fpcm[_event.data.ns][_event.data.func];
            if (_event.data.intvl) {
                setInterval(function () {
                    _func(_event.data.param);
                }, _event.data.intvl);

                _func(_event.data.param);
                return true;
            }

            _func(_event.data.param);
            return true;
        }
    }

})();


jQuery(document).ready(function () {

    for (var _mod in fpcm) {

        if (!fpcm[_mod].init || typeof fpcm[_mod].init !== 'function') {
            continue;
        }

        try {
            fpcm[_mod].init();
        } catch (_e) {
            console.log(_e);
            alert('UI init error!\n>> Code: ' + _e.message);
        }

    }

    for (var _mod2 in fpcm) {

        if (!fpcm[_mod2].init || typeof fpcm[_mod2].initAfter !== 'function') {
            continue;
        }

        try {
            fpcm[_mod2].initAfter();
        } catch (_e) {
            console.log(_e);
            alert('UI initAfter error!\n>> Code: ' + _e.message);
        }

    }

});
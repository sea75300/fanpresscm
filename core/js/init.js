/**
 * FanPress CM javascript bootstrapper
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

jQuery.noConflict();
(function () {
    fpcm.ui.init();
    fpcm.ui_navigation.init();
    fpcm.ui_notify.init();

    delete fpcm.ui.init;
    delete fpcm.ui_navigation.init;
    delete fpcm.ui_notify.init;
})();


jQuery(document).ready(function () {
    jQuery.each(fpcm, function (idx, object) {

        if (!object.init || typeof object.init !== 'function') {
            return true;
        }

        object.init();
    });

    jQuery.each(fpcm, function (idx, object) {
        if (!object.initAfter || typeof object.initAfter !== 'function') {
            return true;
        }

        object.initAfter();
    });

});
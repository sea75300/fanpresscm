/**
 * FanPress CM Updater Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.pkgManager.execModulerequest = function(_params) {

    fpcm.ajax.post('packagemgr/processUpdate', {
        quiet: true,
        data: {
            step : fpcm.pkgManager.currentEl.step
        },
        execDone: function (res) {

            if (!res.code) {

                if (res.message) {
                    fpcm.pkgManager.showMessage('danger', fpcm.pkgManager.currentEl.icon + fpcm.pkgManager.currentEl.label, res.message.icon + res.message.txt);

                    fpcm.ui_notify.show({
                        body: res.message.txt
                    });
                    
                    document.getElementById('protobtn').classList.replace('btn-light', 'btn-primary');
                }
                else {
                    fpcm.pkgManager.errorMsg();
                }

                fpcm.pkgManager.assignTimerStopTime('danger');
                fpcm.pkgManager.currentEl = {};
                return false;
            }

            if (res.pkgdata instanceof Object) {
                jQuery.extend(fpcm.vars.jsvars.pkgdata.update, res.pkgdata);
            }

            var afterFunc = fpcm.pkgManager.currentEl.after;
            if (afterFunc && typeof fpcm.pkgManager[afterFunc] === 'function') {
                fpcm.pkgManager[afterFunc].call();
            }

            if (!fpcm.vars.jsvars.pkgdata.update.steps[fpcm.pkgManager.currentIdx]) {
                return false;
            }

            fpcm.pkgManager.execRequest(fpcm.vars.jsvars.pkgdata.update.steps[fpcm.pkgManager.currentIdx]);
        },
        execFail: function () {

            fpcm.ui_notify.show({
                body: fpcm.pkgManager.errorMsg()
            });

            fpcm.pkgManager.currentEl = {};
            return false;
        }
    });

    return true;
};

fpcm.pkgManager.stopTimerMessage = function() {

    let _icon = new fpcm.ui.forms.icon('circle-check', 'lg');

    fpcm.pkgManager.showMessage(
        'success',
        '<span class="me-2">' + _icon.getString() + '</span>' + fpcm.ui.translate('PACKAGEMANAGER_NEWVERSION') + ' ' + fpcm.vars.jsvars.pkgdata.update.version,
        fpcm.ui.translate('PACKAGEMANAGER_SUCCESS')
    );

    fpcm.ui_notify.show({
        body: fpcm.ui.translate('PACKAGEMANAGER_SUCCESS') + ' ' + fpcm.ui.translate('PACKAGEMANAGER_NEWVERSION') + ' ' + fpcm.vars.jsvars.pkgdata.update.version
    });

};
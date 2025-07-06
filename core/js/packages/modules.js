/**
 * FanPress CM module installer Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.pkgManager.beforeExecRequest = function () {
    document.getElementById('fpcm-id-modul-pkg-name').innerHTML = fpcm.vars.jsvars.pkgdata.pkgname;
    document.getElementById('fpcm-id-modul-pkg-size').innerHTML = fpcm.vars.jsvars.pkgdata.pkgsize;
};

fpcm.pkgManager.execModulerequest = function () {

    fpcm.ajax.post('packagemgr/moduleInstaller', {
        quiet: true,
        data: {
            step: fpcm.pkgManager.currentEl.step,
            key : fpcm.vars.jsvars.pkgdata.pkgKey,
            mode: fpcm.vars.jsvars.action
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
                jQuery.extend(fpcm.vars.jsvars.pkgdata, res.pkgdata);
            }

            var afterFunc = fpcm.pkgManager.currentEl.after;
            if (afterFunc && typeof fpcm.pkgManager[afterFunc] === 'function') {
                fpcm.pkgManager[afterFunc].call();
            }

            if (!fpcm.vars.jsvars.pkgdata.steps[fpcm.pkgManager.currentIdx]) {
                return false;
            }

            fpcm.pkgManager.execRequest(fpcm.vars.jsvars.pkgdata.steps[fpcm.pkgManager.currentIdx]);

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
        '<span class="me-2">' + _icon.getString() + '</span>' + fpcm.ui.translate('PACKAGEMANAGER_NEWVERSION') + ' ' + fpcm.vars.jsvars.pkgdata.version,
        fpcm.ui.translate('PACKAGEMANAGER_SUCCESS')
    );

    fpcm.ui_notify.show({
        body: fpcm.ui.translate('PACKAGEMANAGER_SUCCESS') + ' ' + fpcm.ui.translate('PACKAGEMANAGER_NEWVERSION') + ' ' + fpcm.vars.jsvars.pkgdata.version
    });
};
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

fpcm.pkgManager.execModulerequest = function () {

    fpcm.ajax.post('packagemgr/moduleInstaller', {
        quiet: true,
        data: {
            step: fpcm.pkgManager.currentEl.step,
            key : fpcm.vars.jsvars.pkgdata.key,
            mode: fpcm.vars.jsvars.pkgdata.action
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

/*fpcm.moduleinstaller = {

    startTime    : 0,
    stopTime     : 0,
    elCount      : [],
    elements     : [],
    currentEl    : {},
    currentIdx   : 0,

    init: function () {

        fpcm.moduleinstaller.elements = fpcm.dom.fromClass('fpcm-ui-update-icons');
        fpcm.moduleinstaller.elCount  = fpcm.moduleinstaller.elements.length;

        var start = fpcm.moduleinstaller.elements.first();
        fpcm.moduleinstaller.execRequest(start);
    },

    execRequest: function(el) {


    },

    startTimer: function() {
        fpcm.moduleinstaller.startTime = (new Date().getTime());
    },

    stopTimer: function() {
        fpcm.moduleinstaller.stopTime = (new Date().getTime());
        fpcm.dom.assignHtml('#fpcm-ui-update-timer', (fpcm.moduleinstaller.stopTime - fpcm.moduleinstaller.startTime) / 1000 + ' sec');

        let _res = fpcm.dom.fromId('fpcm-ui-update-result-1');
        _res.removeClass('disabled d-none').addClass('list-group-item-success');

        fpcm.ui_notify.show({
            body: _res.find('div.fpcm-ui-updater-descr').text().trim()
        });

        fpcm.dom.fromId('runUpdateNext').removeClass('fpcm ui-hidden');
    },

    errorMsg: function () {
        let _res = fpcm.dom.fromId('fpcm-ui-update-result-0');
        _res.removeClass('disabled d-none').addClass('list-group-item-danger');
        fpcm.dom.fromId('fpcm-ui-update-timer').addClass('d-none');
        fpcm.dom.fromId('fpcm-ui-update-version').addClass('d-none');
        return _res.find('div.fpcm-ui-updater-descr').text().trim();
    },

    _spinner: function (_listItem, _status) {
        _listItem.find('div.fpcm.ui-updater-spinner').html(_status ? '<div class="spinner-border spinner-border-sm text-secondary" role="status"></div>' : '');
    }

};*/
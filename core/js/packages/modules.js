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

fpcm.pkgManager._rootEL = null;
_messageDiv = null;
fpcm.pkgManager._currentKeyIndex = 0;

fpcm.pkgManager.initBefore = function () {
    return !fpcm.vars.jsvars.pkgdata.pkgKey === 'all';
};

fpcm.pkgManager.beforeExecRequest = function () {

    if (fpcm.pkgManager._rootEL) {
        return;
    }

    let _pbId = 'fpcm-progress-package';
    if (fpcm.vars.jsvars.pkgdata.pkgHashes && fpcm.vars.jsvars.pkgdata.pkgHashes.length > 1) {
        let _hash = fpcm.vars.jsvars.pkgdata.pkgHashes[fpcm.pkgManager._currentKeyIndex];
        _pbId += fpcm.vars.jsvars.pkgdata.pkgHashes[fpcm.pkgManager._currentKeyIndex];

        fpcm.pkgManager.progressBarLabelId += _hash;
        fpcm.pkgManager.timerDivId += _hash;
        fpcm.pkgManager.messagesDivId += _hash;
    }

    fpcm.pkgManager._rootEL = document.getElementById('fpcm-id-prozess-list');

    let _groupline = document.createElement('div');
    _groupline.classList.add('list-group', 'list-group-horizontal-md', 'shadow', 'row-cols-md-3');

    let _icon1 = new fpcm.ui.forms.icon('plus-circle');
    let _grouplineCol1 = document.createElement('div');
    _grouplineCol1.classList.add('list-group-item', 'py-3', 'list-group-item-light');
    _grouplineCol1.innerHTML = `${_icon1.getString()} ${fpcm.ui.translate('FILE_LIST_FILENAME')}: ${fpcm.vars.jsvars.pkgdata.pkgname}`;

    let _icon2 = new fpcm.ui.forms.icon('weight');
    let _grouplineCol2 = document.createElement('div');
    _grouplineCol2.classList.add('list-group-item', 'py-3', 'list-group-item-light');
    _grouplineCol2.innerHTML = `${_icon2.getString()} ${fpcm.ui.translate('FILE_LIST_FILESIZE')}: ${fpcm.vars.jsvars.pkgdata.pkgsize}`;

    let _icon3 = new fpcm.ui.forms.icon('clock fa-far');
    let _grouplineCol3 = document.createElement('div');
    _grouplineCol3.classList.add('list-group-item', 'py-3', 'list-group-item-light');
    _grouplineCol3.innerHTML = `${_icon3.getString()} ${fpcm.ui.translate('PACKAGEMANAGER_TIMER')} `;

    let _spinner = document.createElement('span');
    _spinner.classList.add('spinner-border', 'spinner-border-sm', 'text-secondary');
    _spinner.setAttribute('role', 'status');

    let _timerDiv = document.createElement('span');
    _timerDiv.id = fpcm.pkgManager.timerDivId;
    _timerDiv.appendChild(_spinner);

    _grouplineCol3.appendChild(_timerDiv);

    _groupline.appendChild(_grouplineCol1);
    _groupline.appendChild(_grouplineCol2);
    _groupline.appendChild(_grouplineCol3);

    let _progressParent = document.createElement('div');
    _progressParent.classList.add('my-3');

    let _progressDiv = document.createElement('div');
    _progressDiv.id = _pbId;
    _progressDiv.classList.add('progress', 'fpcm', 'ui-progressbar-lg', 'position-relative', 'shadow');
    _progressDiv.setAttribute('role', 'progressbar');
    _progressDiv.setAttribute('aria-label', fpcm.ui.translate('MODULES_LIST_INSTALL'));
    _progressDiv.setAttribute('aria-valuenow', 1);
    _progressDiv.setAttribute('aria-valuemin', 1);
    _progressDiv.setAttribute('aria-valuemax', fpcm.vars.jsvars.pkgdata.steps.length);

    let _progressBar = document.createElement('div');
    _progressBar.classList.add('progress-bar', 'overflow-visible', 'text-start');

    let _progressText = document.createElement('div');
    _progressText.id = fpcm.pkgManager.progressBarLabelId;
    _progressText.classList.add('position-absolute', 'top-50', 'start-50', 'translate-middle', 'p-1', 'rounded');
    if (!fpcm.ui.darkModeEnabled()) {
        _progressText.classList.add('bg-light', 'bg-opacity-75<');
    }

    _progressDiv.appendChild(_progressBar);
    _progressDiv.appendChild(_progressText);
    _progressParent.appendChild(_progressDiv);

    let _messageDiv = document.createElement('div');
    _messageDiv.id = fpcm.pkgManager.messagesDivId;

    fpcm.pkgManager._rootEL.appendChild(_groupline);
    fpcm.pkgManager._rootEL.appendChild(_progressParent);
    fpcm.pkgManager._rootEL.appendChild(_messageDiv);

    fpcm.pkgManager._currentKeyIndex++;
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
        '<span class="me-2">' + _icon.getString() + '</span>' + ' ' + fpcm.vars.jsvars.pkgdata.pkgname,
        fpcm.ui.translate('PACKAGEMANAGER_SUCCESS')
    );

    fpcm.ui_notify.show({
        body: fpcm.ui.translate('PACKAGEMANAGER_SUCCESS') + ' ' + fpcm.vars.jsvars.pkgdata.pkgname
    });
};
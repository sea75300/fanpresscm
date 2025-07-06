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

fpcm.pkgManager = {

    startTime    : 0,
    stopTime     : 0,
    elCount      : [],
    elements     : [],
    currentEl    : {},
    currentIdx   : 0,

    init: function () {
        fpcm.pkgManager.execRequest(fpcm.vars.jsvars.pkgdata.steps[0]);
    },

    execRequest: function(el) {

        if (!fpcm.pkgManager.execModulerequest) {
            fpcm.pkgManager.showMessage('danger', 'Package manager request executable not found!');
            return false;
        }

        fpcm.pkgManager.currentIdx++;
        fpcm.pkgManager.currentEl = el;
        
        if (fpcm.pkgManager.beforeExecRequest) {
            fpcm.pkgManager.beforeExecRequest();
        }

        var params = {
            step: el.step,
            func: el.func,
            var: el.var
        };

        let _progress = fpcm.ui.progressbar(
            'package', {
                value: fpcm.pkgManager.currentIdx,
                max: fpcm.vars.jsvars.stepcount,
                min: 1,
                label: el.icon + el.label,
                hasHtmlLabel: 'progress-bar-label'
            }
        );

        if (params.func && typeof fpcm.pkgManager[params.func] === 'function') {
            fpcm.pkgManager[params.func].call();
        }

        if (!params.step) {
            return false;
        }


        fpcm.pkgManager.execModulerequest();

        return true;
    },

    startTimer: function() {
        fpcm.pkgManager.startTime = (new Date().getTime());
    },

    stopTimer: function() {

        fpcm.pkgManager.assignTimerStopTime('success');

        if (!fpcm.pkgManager.stopTimerMessage) {
            return false;
        }

        fpcm.pkgManager.stopTimerMessage();
    },

    errorMsg: function () {

        let _icon = new fpcm.ui.forms.icon('times', 'lg');

        fpcm.pkgManager.assignTimerStopTime('danger');
        fpcm.pkgManager.showMessage('danger', '<span class="me-2">' + _icon.getString() + '</span>' + fpcm.pkgManager.currentEl.label.trim(), fpcm.ui.translate('PACKAGEMANAGER_FAILED'));
        
        document.getElementById('protobtn').classList.replace('btn-light', 'btn-primary');

        return fpcm.ui.translate('PACKAGEMANAGER_FAILED') + ' ' + fpcm.pkgManager.currentEl.label.trim();
    },

    assignTimerStopTime: function (_class) {
        fpcm.pkgManager.stopTime = (new Date().getTime());
        
        let _el = document.getElementById('fpcm-id-update-timer');
        _el.innerText = (fpcm.pkgManager.stopTime - fpcm.pkgManager.startTime) / 1000 + ' sec';
        _el.parentElement.classList.add(`bg-${_class}-subtle`);
    },

    showMessage: function (_type, _headline, _text) {

        let _msg = document.createElement('div');
        _msg.classList.add('alert');
        _msg.classList.add('alert-' + _type);
        _msg.classList.add('shadow-sm');
        _msg.setAttribute('role', 'alert');

        let _msgHead = document.createElement('h4');
        _msgHead.innerHTML = _headline;
        _msgHead.classList.add('alert-heading');
        _msgHead.classList.add('mb-3');
        _msgHead.classList.add('text-break');
        _msg.appendChild(_msgHead);

        _msg.appendChild(document.createElement('hr'));

        let _msgText = document.createElement('p');
        _msgText.innerHTML = _text;
        _msgText.classList.add('my-0');
        _msgText.classList.add('text-break');
        _msg.appendChild(_msgText);

        document.getElementById('fpcm-id-package-messages').appendChild(_msg);
    }

};
/**
 * FanPress CM Updater Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.updater = {

    startTime    : 0,
    stopTime     : 0,
    elCount      : [],
    elements     : [],
    currentEl    : {},
    currentIdx   : 0,

    init: function () {
        fpcm.updater.execRequest(fpcm.vars.jsvars.pkgdata.update.steps[0]);
    },

    execRequest: function(el) {

        fpcm.updater.currentIdx++;

        var params = {
            step: el.step,
            func: el.func,
            var: el.var
        };

        let _progress = fpcm.ui.progressbar(
            'package', {
                value: fpcm.updater.currentIdx,
                max: fpcm.vars.jsvars.stepcount,
                min: 1,
                label: el.icon + el.label,
                hasHtmlLabel: 'progress-bar-label'
            }
        );

        if (params.func && typeof fpcm.updater[params.func] === 'function') {
            fpcm.updater[params.func].call();
        }

        if (!params.step) {
            return false;
        }

        fpcm.updater.currentEl = el;

        fpcm.ajax.post('packagemgr/processUpdate', {
            quiet: true,
            data: {
                step : params.step
            },
            execDone: function (res) {

                if (!res.code) {

                    fpcm.updater.errorMsg();
                    
                    if (res.message) {
                        fpcm.ui.addMessage(res.message);
                        
                        fpcm.ui_notify.show({
                            body: res.message.txt
                        });
                    }

                    fpcm.updater.currentEl = {};
                    return false;
                }

                if (res.pkgdata instanceof Object) {
                    jQuery.extend(fpcm.vars.jsvars.pkgdata.update, res.pkgdata);
                }

                var afterFunc = fpcm.updater.currentEl.after;
                if (afterFunc && typeof fpcm.updater[afterFunc] === 'function') {
                    fpcm.updater[afterFunc].call();
                }

                if (!fpcm.vars.jsvars.pkgdata.update.steps[fpcm.updater.currentIdx]) {
                    return false;
                }
                
                console.log('fpcm.updater.currentIdx = ' + fpcm.updater.currentIdx);

                fpcm.updater.execRequest(fpcm.vars.jsvars.pkgdata.update.steps[fpcm.updater.currentIdx]);
            },
            execFail: function () {

                fpcm.ui_notify.show({
                    body: fpcm.updater.errorMsg()
                });                   
                
                fpcm.updater.currentEl = {};
                return false;
            }
        });

        return true;
    },
    
    startTimer: function() {
        fpcm.updater.startTime = (new Date().getTime());
    },
    
    stopTimer: function() {

        fpcm.updater.assignTimerStopTime();
        
        let _icon = new fpcm.ui.forms.icon('check', 'lg');

        let _msg = document.createElement('div');
        _msg.classList.add('alert');
        _msg.classList.add('alert-success');
        _msg.classList.add('shadow-sm');
        _msg.setAttribute('role', 'alert');
        _msg.innerHTML = _icon.getString() + 
                fpcm.ui.translate('PACKAGEMANAGER_SUCCESS') + ' ' + 
                fpcm.ui.translate('PACKAGEMANAGER_NEWVERSION') + ' ' + fpcm.vars.jsvars.pkgdata.update.version;

        document.getElementById('fpcm-id-package-messages').appendChild(_msg);
        
        fpcm.ui_notify.show({
            body: fpcm.ui.translate('PACKAGEMANAGER_SUCCESS') + ' ' + fpcm.ui.translate('PACKAGEMANAGER_NEWVERSION') + ' ' + fpcm.vars.jsvars.pkgdata.update.version
        });        

    },
    
    errorMsg: function () {
        
        let _icon = new fpcm.ui.forms.icon('times', 'lg');

        let _msg = document.createElement('div');
        _msg.classList.add('alert');
        _msg.classList.add('alert-danger');
        _msg.classList.add('shadow-sm');
        _msg.setAttribute('role', 'alert');
        _msg.innerHTML = _icon.getString() + fpcm.ui.translate('PACKAGEMANAGER_FAILED');

        document.getElementById('fpcm-id-package-messages').appendChild(_msg);      
        
        fpcm.updater.assignTimerStopTime();
        
        fpcm.dom.assignHtml('#fpcm-id-update-timer', (fpcm.updater.stopTime - fpcm.updater.startTime) / 1000 + ' sec');

        return fpcm.ui.translate('PACKAGEMANAGER_FAILED') + ' ' + fpcm.updater.currentEl.label.trim();
    },
    
    assignTimerStopTime: function () {
        fpcm.updater.stopTime = (new Date().getTime());        
        fpcm.dom.assignHtml('#fpcm-id-update-timer', (fpcm.updater.stopTime - fpcm.updater.startTime) / 1000 + ' sec');
    }
    
};
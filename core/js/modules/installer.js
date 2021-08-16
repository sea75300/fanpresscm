/**
 * FanPress CM module installer Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.moduleinstaller = {

    startTime    : 0,
    stopTime     : 0,
    elCount      : [],
    elements     : [],
    currentEl    : {},
    currentIdx   : 0,
    statusSpinner: 'fa-spinner fa-pulse fpcm-ui-update-iconstatus-spinner',

    init: function () {

        fpcm.moduleinstaller.elements = fpcm.dom.fromClass('fpcm-ui-update-icons');
        fpcm.moduleinstaller.elCount  = fpcm.moduleinstaller.elements.length;

        var start = fpcm.moduleinstaller.elements.first();
        fpcm.moduleinstaller.execRequest(start);
    },

    execRequest: function(el) {

        fpcm.moduleinstaller.currentIdx++;

        var params = {
            step: el.attr('data-step'),
            func: el.attr('data-func'),
            var: el.attr('data-var'),
        };

        el.parent().parent().removeClass('fpcm-ui-status-0').addClass('fpcm-ui-status-1');
        var descrEl = el.parent().parent().find('.fpcm-ui-updater-descr');
        
        if (params.var && fpcm.vars.jsvars.pkgdata.module[params.var]) {
            descrEl.html(descrEl.html().replace('{{var}}', fpcm.vars.jsvars.pkgdata.module[params.var]));
        }

        if (params.func && typeof fpcm.moduleinstaller[params.func] === 'function') {
            fpcm.moduleinstaller[params.func].call();
        }

        if (!params.step) {
            return false;
        }

        el.find('.fpcm-ui-update-iconstatus').removeClass('fa-square fpcm-ui-update-iconstatus-0').addClass(fpcm.moduleinstaller.statusSpinner);
        fpcm.moduleinstaller.currentEl = el;

        fpcm.ajax.post('packagemgr/moduleInstaller', {
            quiet: true,
            data: {
                step: params.step,
                key : fpcm.vars.jsvars.pkgdata.key,
                mode: fpcm.vars.jsvars.pkgdata.action
            },
            execDone: function (res) {

                var statusEl = fpcm.moduleinstaller.currentEl.find('.fpcm-ui-update-iconstatus');
                statusEl.removeClass(fpcm.moduleinstaller.statusSpinner);

                if (!res.code) {
                    statusEl.addClass('fa-ban text-danger');
                    fpcm.dom.fromId('fpcm-ui-update-result-0').removeClass('fpcm-ui-hidden');
                    
                    if (res.pkgdata.errorMsg) {
                        fpcm.ui.addMessage({
                            txt: res.pkgdata.errorMsg,
                            type: 'error'
                        });

                        fpcm.ui_notify.show({
                            body: res.pkgdata.errorMsg
                        });
                    }

                    fpcm.moduleinstaller.currentEl = {};
                    return false;
                }

                if (res.pkgdata instanceof Object) {
                    jQuery.extend(fpcm.vars.jsvars.pkgdata.module, res.pkgdata);
                }

                var afterFunc = fpcm.moduleinstaller.currentEl.attr('data-after');
                if (afterFunc && typeof fpcm.moduleinstaller[afterFunc] === 'function') {
                    fpcm.moduleinstaller[afterFunc].call();
                }

                statusEl.addClass('fa-check fpcm-ui-editor-metainfo fpcm ui-status-075');
                if (!fpcm.moduleinstaller.elements[fpcm.moduleinstaller.currentIdx]) {
                    return false;
                }

                fpcm.moduleinstaller.execRequest(jQuery(fpcm.moduleinstaller.elements[fpcm.moduleinstaller.currentIdx]));
            },
            execFail: function () {
                fpcm.moduleinstaller.currentEl.find('.fpcm-ui-update-iconstatus').removeClass(fpcm.moduleinstaller.statusSpinner).addClass('fa-ban text-danger');

                var resultEl = fpcm.dom.fromId('fpcm-ui-update-result-0');
                resultEl.removeClass('fpcm-ui-hidden');

                fpcm.ui_notify.show({
                    body: resultEl.find('div.fpcm-ui-updater-descr').text()
                });

                fpcm.moduleinstaller.currentEl = {};
                return false;
            }
        });

        return true;
    },
    
    startTimer: function() {
        fpcm.moduleinstaller.startTime = (new Date().getTime());
    },
    
    stopTimer: function() {
        fpcm.moduleinstaller.stopTime = (new Date().getTime());
        fpcm.dom.appendHtml('#fpcm-ui-update-timer', ': ' + (fpcm.moduleinstaller.stopTime - fpcm.moduleinstaller.startTime) / 1000 + ' sec');
        fpcm.dom.fromId('fpcm-ui-update-timer').parent().removeClass('fpcm-ui-hidden');

        var resultEl = fpcm.dom.fromId('fpcm-ui-update-result-1');
        resultEl.removeClass('fpcm-ui-hidden');

        fpcm.ui_notify.show({
            body: resultEl.find('div.fpcm-ui-updater-descr').text()
        }); 
        
        fpcm.dom.fromId('runUpdateNext').removeClass('fpcm ui-hidden');
    }
};
/**
 * FanPress CM Updater Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
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
    statusSpinner: 'fa-spinner fa-pulse fpcm-ui-update-iconstatus-spinner',

    init: function () {

        fpcm.updater.elements = fpcm.dom.fromClass('fpcm-ui-update-icons');
        fpcm.updater.elCount  = fpcm.updater.elements.length;

        var start = fpcm.updater.elements.first();
        fpcm.updater.execRequest(start);
    },

    execRequest: function(el) {

        fpcm.updater.currentIdx++;

        var params = {
            step: el.data('step'),
            func: el.data('func'),
            var: el.data('var'),
        };

        el.parent().parent().removeClass('fpcm-ui-status-0').addClass('fpcm-ui-status-1');
        var descrEl = el.parent().parent().find('.fpcm-ui-updater-descr');
        
        if (params.var && fpcm.vars.jsvars.pkgdata.update[params.var]) {
            descrEl.html(descrEl.html().replace('{{var}}', fpcm.vars.jsvars.pkgdata.update[params.var]));
        }

        if (params.func && typeof fpcm.updater[params.func] === 'function') {
            fpcm.updater[params.func].call();
        }

        if (!params.step) {
            return false;
        }

        el.find('.fpcm-ui-update-iconstatus').removeClass('fa-square fpcm-ui-update-iconstatus-0').addClass(fpcm.updater.statusSpinner);
        fpcm.updater.currentEl = el;

        fpcm.ajax.post('packagemgr/processUpdate', {
            quiet: true,
            data: {
                step : params.step
            },
            execDone: function (res) {

                var statusEl = fpcm.updater.currentEl.find('.fpcm-ui-update-iconstatus');
                statusEl.removeClass(fpcm.updater.statusSpinner);

                if (!res.code) {
                    statusEl.addClass('fa-ban text-danger');
                    fpcm.dom.fromId('fpcm-ui-update-result-0').removeClass('fpcm-ui-hidden');
                    
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

                var afterFunc = fpcm.updater.currentEl.data('after');
                if (afterFunc && typeof fpcm.updater[afterFunc] === 'function') {
                    fpcm.updater[afterFunc].call();
                }

                statusEl.addClass('fa-check fpcm-ui-editor-metainfo opacity-75');
                if (!fpcm.updater.elements[fpcm.updater.currentIdx]) {
                    return false;
                }

                fpcm.updater.execRequest(fpcm.dom.fromTag(fpcm.updater.elements[fpcm.updater.currentIdx]));
            },
            execFail: function () {
                fpcm.updater.currentEl.find('.fpcm-ui-update-iconstatus').removeClass(fpcm.updater.statusSpinner).addClass('fa-ban text-danger');
                
                var resultEl = fpcm.dom.fromId('fpcm-ui-update-result-0');
                resultEl.removeClass('fpcm-ui-hidden');

                fpcm.ui_notify.show({
                    body: resultEl.find('div.fpcm-ui-updater-descr').text()
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
        fpcm.updater.stopTime = (new Date().getTime());
        fpcm.dom.appendHtml('#fpcm-ui-update-timer', ': ' + (fpcm.updater.stopTime - fpcm.updater.startTime) / 1000 + ' sec');
        fpcm.dom.appendHtml('#fpcm-ui-update-newver-descr', ': ' + fpcm.vars.jsvars.pkgdata.update.version);
        fpcm.dom.fromId('fpcm-ui-update-timer').parent().removeClass('fpcm-ui-hidden');
        
        var resultEl = fpcm.dom.fromId('fpcm-ui-update-result-1');
        resultEl.removeClass('fpcm-ui-hidden');

        fpcm.ui_notify.show({
            body: resultEl.find('div.fpcm-ui-updater-descr').text()
        });        

    }
};
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

        fpcm.updater.elements = jQuery('.fpcm-ui-update-icons');
        fpcm.updater.elCount  = fpcm.updater.elements.length;

        var start = fpcm.updater.elements.first();
        fpcm.updater.execRequest(start);

    },

    execRequest: function(el) {

        fpcm.updater.currentIdx++;

        var params = {
            step: el.attr('data-step'),
            func: el.attr('data-func')
        };

        el.parent().parent().removeClass('fpcm-ui-status-0').addClass('fpcm-ui-status-1');

        if (params.func && typeof fpcm.updater[params.func] === 'function') {
            fpcm.updater[params.func].call();
        }

        if (!params.step) {
            return false;
        }
        
        el.find('.fpcm-ui-update-iconstatus').removeClass('fa-square-o fpcm-ui-update-iconstatus-0').addClass(fpcm.updater.statusSpinner);
        fpcm.updater.currentEl = el;

        fpcm.ajax.post('packagemgr/sysupdater', {
            data: {
                step : params.step
            },
            execDone: function () {

                var res = parseInt(fpcm.ajax.getResult('packagemgr/sysupdater'));

                var statusEl = fpcm.updater.currentEl.find('.fpcm-ui-update-iconstatus');
                statusEl.removeClass(fpcm.updater.statusSpinner);

                if (!res) {
                    statusEl.addClass('fa-ban fpcm-ui-important-text');
                    jQuery('#fpcm-ui-update-result-0').removeClass('fpcm-ui-hidden');
                    fpcm.updater.currentEl = {};
                    return false;
                }

                var afterFunc = fpcm.updater.currentEl.attr('data-after');
                console.log(afterFunc);
                if (afterFunc && typeof fpcm.updater[afterFunc] === 'function') {
                    fpcm.updater[afterFunc].call();
                }

                statusEl.addClass('fa-check fpcm-ui-editor-metainfo').css('opacity', '0.75');
                if (!fpcm.updater.elements[fpcm.updater.currentIdx]) {
                    return false;
                }

                fpcm.updater.execRequest(jQuery(fpcm.updater.elements[fpcm.updater.currentIdx]));
            }
        });

        return true;
    },
    
    startTimer: function() {
        fpcm.updater.startTime = (new Date().getTime());
    },
    
    stopTimer: function() {
        fpcm.updater.stopTime = (new Date().getTime());
        fpcm.ui.appendHtml('#fpcm-ui-update-timer', ': <strong>' + (fpcm.updater.stopTime - fpcm.updater.startTime) / 1000 + ' sec</strong>');
        jQuery('#fpcm-ui-update-timer').parent().removeClass('fpcm-ui-hidden');
        jQuery('#fpcm-ui-update-result-1').removeClass('fpcm-ui-hidden');
    }
};
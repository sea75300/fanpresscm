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

    startTime   : 0,
    responseData: '',

    init: function () {

        fpcm.updater.startTime   = (new Date().getTime());
        
        fpcm.ui.showLoader(true);
        
        if (!fpcm.vars.jsvars.fpcmUpdaterProgressbar) {
            jQuery('.fpcm-updater-progressbar').remove();
        }

        fpcm.updater.progressbar(0);
        fpcm.updater.execRequest(fpcm.vars.jsvars.fpcmUpdaterStartStep);

    },

    execRequest: function(stepName) {

        if (fpcm.vars.jsvars.fpcmUpdaterStepMap[stepName] === undefined) {
            return false;
        }

        var idx = fpcm.vars.jsvars.fpcmUpdaterStepMap[stepName];        
        if (idx > fpcm.vars.jsvars.fpcmUpdaterMaxStep) {
            return false;
        }

        fpcm.ui.assignHtml('div.fpcm-updater-progressbar div.fpcm-ui-progressbar-label', fpcm.vars.jsvars.fpcmUpdaterMessages[stepName + '_START']);
        fpcm.ajax.post('packagemgr/sysupdater', {
            data: {
                step : stepName,
                force: fpcm.vars.jsvars.fpcmUpdaterForce,
            },
            execDone: function () {
                fpcm.updater.responseData = fpcm.ajax.fromJSON(fpcm.ajax.getResult('packagemgr/sysupdater'));
                if (fpcm.updater.responseData.data === undefined) {
                    alert(fpcm.ui.translate('AJAX_RESPONSE_ERROR'));
                    return false;
                }

                fpcm.updater.progressbar(fpcm.updater.responseData.data.current);

                var currentIdx = fpcm.vars.jsvars.fpcmUpdaterStepMap[fpcm.updater.responseData.data.current];
                if (currentIdx < fpcm.vars.jsvars.fpcmUpdaterMaxStep &&
                    fpcm.updater.responseData.code != fpcm.updater.responseData.data.current + '_' + 1) {
                    fpcm.ui.showLoader(false);
                    fpcm.ui.appendHtml('.fpcm-updater-list', '<p class="fpcm-ui-important-text">' + fpcm.vars.jsvars.fpcmUpdaterMessages[fpcm.updater.responseData.code] + '</p>');
                    return false;
                }
                else if (currentIdx === fpcm.vars.jsvars.fpcmUpdaterMaxStep) {
                    fpcm.ui.appendHtml('.fpcm-updater-list', '<p>' + fpcm.vars.jsvars.fpcmUpdaterMessages[fpcm.updater.responseData.code] + ': ' + fpcm.updater.responseData.data.newver + '</p>');
                    fpcm.updater.ajaxCallbackFinal(fpcm.updater.responseDataresponseData);
                }
                else {
                    fpcm.ui.appendHtml('.fpcm-updater-list', '<p>' + fpcm.vars.jsvars.fpcmUpdaterMessages[fpcm.updater.responseData.code] + '</p>');
                }

                if (currentIdx < fpcm.vars.jsvars.fpcmUpdaterMaxStep) {
                    fpcm.updater.execRequest(fpcm.updater.responseData.data.nextstep);
                }

                if (currentIdx == fpcm.vars.jsvars.fpcmUpdaterMaxStep) {
                    fpcm.ui.assignText('div.fpcm-updater-progressbar div.fpcm-ui-progressbar-label', '');
                }
            }
        });
        
        return true;
    },

    ajaxCallbackFinal: function() {
        jQuery('#fpcm-ui-headspinner').removeClass('fa-spin');
        fpcm.ui.addMessage({
            type: 'notice',
            txt : fpcm.vars.jsvars.fpcmUpdaterMessages['EXIT_1']
        });
        fpcm.ui.appendHtml('.fpcm-updater-list', '<p>' + '<span class="fa fa-check-square fa-fw fa-lg fpcm-ui-booltext-yes"></span>'  + fpcm.vars.jsvars.fpcmUpdaterMessages['EXIT_1'] + '</p>');
        fpcm.ui.showLoader(false);
        fpcm.updater.addTimer();
        jQuery('#updaterButtons').show();
        return true;
    },

    addTimer: function() {
        var updateTimer = ((new Date().getTime()) - fpcm.updater.startTime) / 1000;
        fpcm.ui.appendHtml('.fpcm-updater-list', '<p>' + fpcmUpdaterProcessTime + ': ' + updateTimer + 'sec</p>');
        fpcm.ui.showLoader(false);
        return true;
    },

    progressbar: function (pgValue) {

        if (!window.fpcmUpdaterProgressbar) return false;  

        fpcm.ui.progressbar('.fpcm-updater-progressbar', {
            max: parseInt(fpcmUpdaterMaxStep),
            value: parseInt(fpcmUpdaterStepMap[pgValue])
        });

    }
};
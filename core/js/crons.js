/**
 * FanPress CM Crons Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.crons = {

    init: function () {

        jQuery('.fpcm-cronjoblist-exec').click(function () {
            var cjId = jQuery(this).attr('id');
            fpcm.crons.execCronjobDemand(cjId);
            return false;
        });

        jQuery(".fpcm-cronjoblist-intervals" ).on("selectmenuchange", function(event, ui) {

            var cronjob  = jQuery(this).attr('id').split('_');
            var interval = jQuery(this).val();

            fpcm.crons.setCronjobInterval(cronjob[1], interval);
            return false;
        });

    },

    execCronjobDemand : function(cronjobId) {
        fpcm.ui.showLoader(true);
        fpcm.ajax.get('cronasync', {
            data    : {
                cjId: cronjobId
            },
            execDone: 'fpcm.ui.showLoader(false);'
        });
    },
    
    setCronjobInterval : function(cronjobId, cronjobInterval) {
        fpcm.ui.showLoader(true);
        fpcm.ajax.get('croninterval', {
            data    : {
                cjId:cronjobId,
                interval:cronjobInterval
            },
            execDone: 'fpcm.ui.showLoader(false);'
        });
    }

};
/**
 * FanPress CM Crons Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.crons = {

    init: function () {

        fpcm.dataview.render('cronlist', {
            onRenderAfter: function () {

                fpcm.dom.fromClass('fpcm-cronjoblist-exec').click(function () {
                    var data = fpcm.dom.fromTag(this).data();
                    fpcm.crons.execCronjobDemand(data.cjid, data.cjmod, data.cjdescr);
                    return false;
                });
                
                fpcm.ui.selectmenu(".fpcm-cronjoblist-intervals", {
                    change: function(event, ui) {
                        var cronjob  = fpcm.dom.fromTag(this).attr('id').split('_');
                        fpcm.crons.setCronjobInterval(cronjob[1], this.value, jQuery(this).data('cjmod'));
                        return false;
                    }
                });

            }
        });

    },

    execCronjobDemand : function(cronjobId, modulekey, descr) {
        fpcm.ajax.get('cronasync', {
            data    : {
                cjId: cronjobId,
                cjmod: modulekey
            },
            loaderMsg: fpcm.ui.translate('CRONJOB_ECEDUTING').replace('{{cjname}}', descr)
        });
    },
    
    setCronjobInterval : function(cronjobId, cronjobInterval, modulekey) {
        fpcm.ajax.get('croninterval', {
            data    : {
                cjId:cronjobId,
                interval:cronjobInterval,
                cjmod: modulekey
            }
        });
    }

};
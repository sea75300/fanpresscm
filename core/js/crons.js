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
                    let _data = fpcm.dom.fromTag(this).data();
                    
                    fpcm.worker.postMessage({
                        namespace: 'crons',
                        function: 'execCronjobDemand',
                        id: 'crons.execCronjobDemand',
                        param: _data
                    });

                    return false;
                });
                
                fpcm.ui.selectmenu('select', {
                    change: function(_event, _ui) {
                        fpcm.crons.setCronjobInterval(
                            _ui.attributes.id.value.replace(/^intervals_/i, ''),
                            _ui.value,
                            _ui.dataset.cjmod
                        );
                        return false;
                    }
                });

            }
        });

    },

    execCronjobDemand : function(_data) {
        fpcm.ajax.get('cronasync', {
            data    : {
                cjId: _data.cjid,
                cjmod: _data.cjmod
            },
            loaderMsg: fpcm.ui.translate('CRONJOB_ECEDUTING').replace('{{cjname}}', _data.cjdescr),
            execDone: function (result) {
                
                fpcm.worker.postMessage({
                    cmd: 'remove',
                    id: 'crons.execCronjobDemand'
                });                

            },
        });
    },
    
    setCronjobInterval : function(cronjobId, cronjobInterval, modulekey) {
        fpcm.ajax.post('croninterval', {
            data    : {
                cjId:cronjobId,
                interval:cronjobInterval,
                cjmod: modulekey
            }
        });
    }

};
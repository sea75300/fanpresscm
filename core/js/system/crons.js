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

                fpcm.dom.bindClick('.fpcm-cronjoblist-exec', function (_e, _ui) {
                    fpcm.worker.postMessage({
                        namespace: 'crons',
                        function: 'execCronjobDemand',
                        id: 'crons.execCronjobDemand',
                        param: {
                            cjdescr: _ui.dataset.cjdescr,
                            cjid: _ui.dataset.cjid,
                            cjmod: _ui.dataset.cjmod,
                            dest: _ui.name
                        }
                    });

                    _ui.disabled = true;
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

                fpcm.dom.bindClick('.fpcm-cronjoblist-release', function (_e, _ui) {
                    
                    fpcm.worker.postMessage({
                        namespace: 'crons',
                        function: 'releaseCronjob',
                        id: 'crons.releaseCronjob',
                        param: {
                            cjid: _ui.dataset.cjid,
                            cjmod: _ui.dataset.cjmod
                        }
                    });                    
                    
                });

            }
        });

    },

    execCronjobDemand : function(_data)
    {
        fpcm.ajax.get('crons/exec', {
            data    : {
                cjId: _data.cjid,
                cjmod: _data.cjmod
            },
            loaderMsg: fpcm.ui.translate('CRONJOB_ECEDUTING').replace('{{cjname}}', _data.cjdescr),
            execDone: function (result) {

                fpcm.dom.fromId(_data.dest).prop('disabled', false);
                fpcm.worker.postMessage({
                    cmd: 'remove',
                    id: 'crons.execCronjobDemand'
                });

            }
        });
    },
    
    setCronjobInterval : function(cronjobId, cronjobInterval, modulekey)
    {
        fpcm.ajax.post('crons/interval', {
            data    : {
                cjId:cronjobId,
                interval:cronjobInterval,
                cjmod: modulekey
            }
        });
    },
    
    releaseCronjob: function (_data)
    {
        fpcm.ajax.post('crons/interval', {
            data: {
                cjId: _data.cjid,
                cjmod: _data.cjmod
            },
            execDone: function (result) {
                window.location.reload();
            }
        });
    }

};
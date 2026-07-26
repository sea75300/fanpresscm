/**
 * FanPress CM refresh javascript functions
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.refresh = {

    init: function ()
    {

        fpcm.worker.postMessage({
            namespace: 'refresh',
            function: 'exec',
            interval: 60000,
            id: 'refresh.exec'
        });

    },

    exec: function () {

        if (fpcm.vars.jsvars.noRefresh) {
            return false;
        }

        fpcm.ajax.post('refresh', {
            quiet: true,
            async: true,
            data: {
                articleId: fpcm.vars.jsvars.articleId
            },
            execDone: function (_result) {

                fpcm.worker.postMessage({
                    cmd: 'remove',
                    id: 'refresh.exec'
                });

                for (let _m in fpcm) {

                    if (!fpcm[_m].onRefresh) {
                        continue;
                    }

                    fpcm[_m].onRefresh(_result);
                }

            },
        });

        return true;
    },

    onRefresh: function (_result) {

        if (!fpcm.vars.jsvars.sessionCheck) {
            return false;
        }

        if (_result.sessionCode) {
            return true;
        }

        fpcm.system.showSessionCheckDialog();

    }
};
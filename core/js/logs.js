/**
 * FanPress CM Logs Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.logs = {
    
    delimiter     : 'log=',

    init: function () {

        fpcm.ui_tabs.render('#tabs-logs', {
            
            reload: true,
            
            onShow: function (_el) {
                let _logParams = fpcm.system.parseUrlQuery(_el.target.attributes.href.value);
                fpcm.vars.jsvars.currentLog.name = _logParams.log ? _logParams.log : null;
                fpcm.vars.jsvars.currentLog.key = _logParams.key ? _logParams.key : null;
                fpcm.vars.jsvars.currentLog.system = _logParams.system ? _logParams.system : null;
            },
            
            onRenderJsonAfter: function (_el, _result) {

                if (!_result.logsize) {
                    return false;
                }

                let _str = '<div class="row fpcm-ui-font-small">';
                _str += '<div class="col-12 align-self-center p-2">';
                _str += fpcm.ui.getIcon('weight', { size: 'lg' }) + fpcm.ui.translate('FILE_LIST_FILESIZE') + ': ' + _result.logsize;
                _str += '</div>';
                _str += '</div>';
                fpcm.dom.appendHtml(_el.target.dataset.bsTarget, _str);                
            }
            
        });

        var cleanEl = fpcm.dom.fromId('btnCleanLogs');
        cleanEl.unbind('click');
        cleanEl.click(function () {
            
            var elData = fpcm.dom.fromTag(this).data();
            
            fpcm.ui.confirmDialog({
                clickNoDefault: true,
                clickYes: function () {
                    fpcm.logs.clearLogs(fpcm.vars.jsvars.currentLog);
                }
            });
            
            return false;
        });

    },

    clearLogs: function(_params) {

        fpcm.ajax.post('logs/clear', {
            data: {
                log: _params.name,
                key: _params.key,
                system: _params.system
            },
            execDone: function(result) {
                fpcm.logs.reloadLogs();
                fpcm.ui.addMessage(result, true);
            }
        });

        return false;
    },
    
    reloadLogs: function() {
        let _dom = document.querySelector('#tabs-logs a.nav-link.active');
        fpcm.dom.fromTag('#tabs-logs a.nav-link.active').removeClass('active');
        (new bootstrap.Tab(_dom)).show();
    }

};
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

        var cleanEl = fpcm.dom.fromId('btnCleanLogs');
        cleanEl.unbind('click');
        cleanEl.click(function () {
            
            var elData = fpcm.dom.fromTag(this).data();
            
            fpcm.ui.confirmDialog({
                clickNoDefault: true,
                clickYes: function () {
                    fpcm.logs.clearLogs(fpcm.vars.jsvars.currentLog);
                    fpcm.dom.fromTag(this).dialog('close');
                    return true;
                }
            });
            
            return false;
        });

        var tabs = fpcm.ui.tabs('#fpcm-tabs-logs', {
            initDataViewJson: true,
            addTabScroll: true,
            dataTypes: ['html', 'json'],
            initDataViewJsonBeforeLoad: function (event, ui) {
                let _logParams = fpcm.system.parseUrlQuery(ui.ajaxSettings.url);
                fpcm.vars.jsvars.currentLog.name = _logParams.log ? _logParams.log : null;
                fpcm.vars.jsvars.currentLog.key = _logParams.key ? _logParams.key : null;
                fpcm.vars.jsvars.currentLog.system = _logParams.system ? _logParams.system : null;
            },
            initDataViewJsonBefore: function (event, ui) {
                ui.tab.unbind('click');
                ui.tab.click(function () {
                    fpcm.logs.reloadLogs();
                });
            },
            initbeforeLoadDoneNoTabList: function (event, ui) {
                fpcm.ui.accordion(fpcm.dom.fromTag(ui.panel).find('.fpcm-accordion-pkgmanager'));
                ui.tab.unbind('click');
                ui.tab.click(function () {
                    fpcm.logs.reloadLogs();
                });
            },
            
        });

        var tabEl = fpcm.dom.fromId('fpcm-tabs-logs-sessions');
        tabEl.find('a').attr('href', tabEl.data('href'));
        tabEl.removeAttr('data-href');

        tabs.tabs('load', 0);

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
        var tabEl = fpcm.dom.fromId('fpcm-tabs-logs');
        tabEl.tabs('load', tabEl.tabs( "option", "active" ));
        return false;
    }

};
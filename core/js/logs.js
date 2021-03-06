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
            beforeLoad: function(event, ui) {
                
                fpcm.ui_loader.show();
                fpcm.vars.jsvars.dataviews.extSettings = null;

                let _logParams = fpcm.system.parseUrlQuery(ui.ajaxSettings.url);

                fpcm.vars.jsvars.currentLog.name = _logParams.log ? _logParams.log : null;
                fpcm.vars.jsvars.currentLog.key = _logParams.key ? _logParams.key : null;
                fpcm.vars.jsvars.currentLog.system = _logParams.system ? _logParams.system : null;

                ui.jqXHR.done(function(jqXHR) {
                    
                    if (!jqXHR instanceof Object || !jqXHR.dataViewVars) {
                        return true;
                    }
                    
                    fpcm.vars.jsvars.dataviews[jqXHR.dataViewName] = jqXHR.dataViewVars;
                    fpcm.vars.jsvars.dataviews.extSettings = {
                        fullheight: jqXHR.fullheight ? true : false,
                        logSize: jqXHR.logsize ? jqXHR.logsize : '',
                        name: jqXHR.dataViewName,
                    };
                    
                    return true;
                });

                ui.jqXHR.fail(function(jqXHR, textStatus, errorThrown) {
                    console.error(fpcm.ui.translate('AJAX_RESPONSE_ERROR'));
                    console.error('STATUS MESSAGE: ' + textStatus);
                    console.error('ERROR MESSAGE: ' + errorThrown);
                    fpcm.ajax.showAjaxErrorMessage();
                    fpcm.ui_loader.hide();
                });

                return true;
            },
            beforeActivate: function( event, ui ) {
                ui.oldTab.unbind('click');
            },
            load: function( event, ui ) {

                ui.tab.unbind('click');
                ui.tab.click(function () {
                    fpcm.logs.reloadLogs();
                });

                if (!fpcm.vars.jsvars.dataviews.extSettings) {
                    fpcm.ui.accordion('.fpcm-accordion-pkgmanager');
                    fpcm.ui_loader.hide();
                    return true;
                }

                fpcm.dom.fromClass('fpcm-ui-logslist').remove();

                ui.panel.append(fpcm.dataview.getDataViewWrapper(ui.tab.data('dataview-list'), 'fpcm-ui-logslist'));
                fpcm.dataview.updateAndRender(fpcm.vars.jsvars.dataviews.extSettings.name);

                var logSizeEl = fpcm.vars.jsvars.dataviews.extSettings.logSize ? 'fpcm-logs-size-row-' + fpcm.vars.jsvars.dataviews.extSettings.name : false;
                if (fpcm.vars.jsvars.dataviews.extSettings.logSize) {
                    fpcm.dom.fromClass('fpcm-ui-logslist').append('<div id="' + logSizeEl + '" class="row fpcm-ui-font-small fpcm-ui-margin-lg-top"><div class="col-12 align-self-center fpcm-ui-padding-none-left">' + fpcm.ui.getIcon('weight', { size: 'lg' }) + fpcm.ui.translate('FILE_LIST_FILESIZE') + ': ' + fpcm.vars.jsvars.dataviews.extSettings.logSize + '</div></div>');
                }

                if (fpcm.vars.jsvars.dataviews.extSettings.fullheight) {
                    ui.panel.height(fpcm.vars.jsvars.dataviews[fpcm.vars.jsvars.dataviews.extSettings.name].dataViewHeight + (logSizeEl ? fpcm.dom.fromId('' + logSizeEl).height() : 0) );
                }

                fpcm.ui_loader.hide();
                return true;

            },
            addTabScroll: true
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
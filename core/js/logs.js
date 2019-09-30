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
    reloadDataHref: '',
    reloadDataDest: '',
    currentTabItem: null,
    oldTabItem    : null,

    init: function () {

        var cleanEl = fpcm.dom.fromId('btnCleanLogs');
        cleanEl.unbind('click');
        cleanEl.click(function () {
            
            var logId = fpcm.dom.fromTag(this).data('logid');
            
            fpcm.ui.confirmDialog({
                clickNoDefault: true,
                clickYes: function () {
                    fpcm.logs.clearLogs(logId);
                    fpcm.dom.fromTag(this).dialog('close');
                    return true;
                }
            });
            
            return false;
        });

        var tabs = fpcm.ui.tabs('#fpcm-tabs-logs', {
            beforeLoad: function(event, ui) {
                
                fpcm.ui.showLoader(true);
                fpcm.vars.jsvars.dataviews.extSettings = null;
                
                var tabId = ui.ajaxSettings.url.split(fpcm.logs.delimiter);
                tabId = tabId[1] !== undefined ? tabId[1] : 0;

                fpcm.dom.fromId('btnCleanLogs').data('logid', tabId);
                
                ui.ajaxSettings.dataFilter = function( response ) {

                    if (tabId == 4) {
                        return response;
                    }

                    return fpcm.ajax.fromJSON(response);
                };
                
                ui.jqXHR.done(function(jqXHR) {
                    
                    if (!jqXHR.dataViewVars) {
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
                    fpcm.ui.showLoader(false);
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
                    fpcm.ui.showLoader();
                    return true;
                }

                fpcm.dom.fromClass('fpcm-ui-logslist').remove();

                ui.panel.append(fpcm.dataview.getDataViewWrapper(ui.tab.attr('data-dataview-list'), 'fpcm-ui-logslist'));
                fpcm.dataview.updateAndRender(fpcm.vars.jsvars.dataviews.extSettings.name);

                var logSizeEl = fpcm.vars.jsvars.dataviews.extSettings.logSize ? 'fpcm-logs-size-row-' + fpcm.vars.jsvars.dataviews.extSettings.name : false;
                if (fpcm.vars.jsvars.dataviews.extSettings.logSize) {
                    fpcm.dom.fromClass('fpcm-ui-logslist').append('<div id="' + logSizeEl + '" class="row fpcm-ui-font-small fpcm-ui-margin-lg-top"><div class="col-12 align-self-center fpcm-ui-padding-none-left"> <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-weight fa-lg "></span>  ' + fpcm.ui.translate('FILE_LIST_FILESIZE') + ': ' + fpcm.vars.jsvars.dataviews.extSettings.logSize + '</div></div>');
                }

                if (fpcm.vars.jsvars.dataviews.extSettings.fullheight) {
                    ui.panel.height(fpcm.vars.jsvars.dataviews[fpcm.vars.jsvars.dataviews.extSettings.name].dataViewHeight + (logSizeEl ? fpcm.dom.fromId('' + logSizeEl).height() : 0) );
                }

                fpcm.ui.showLoader();
                return true;

            },
            addTabScroll: true
        });
        
        var linkEl = fpcm.dom.fromId('fpcm-tabs-logs-sessions').find('a');
        
        linkEl.attr('href', linkEl.attr('data-href'));
        linkEl.removeAttr('data-href');

        tabs.tabs('load', 0);

    },

    clearLogs: function(id) {

        fpcm.ajax.get('logs/clear', {
            workData: id,
            data: {
                log: id
            },
            execDone: function() {
                fpcm.ui.showLoader(false);
                fpcm.logs.reloadLogs();
                fpcm.ui.addMessage(fpcm.ajax.getResult('logs/clear', true), true);
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
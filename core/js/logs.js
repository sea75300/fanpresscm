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

        jQuery('#btnCleanLogs').click(function () {
            
            var logId = jQuery(this).data('logid');
            
            fpcm.ui.confirmDialog({
                clickNoDefault: true,
                clickYes: function () {
                    fpcm.logs.clearLogs(logId);
                    jQuery(this).dialog('close');
                    return true;
                }
            });
            
            return false;
        });

        var tabs = fpcm.ui.tabs('#fpcm-tabs-logs', {
            beforeLoad: function(event, ui) {
                
                fpcm.ui.showLoader(true);
                ui.jqXHR.done(function(result) {
                    fpcm.ui.showLoader();
                    return true;
                });

                var tabId = ui.ajaxSettings.url.split(fpcm.logs.delimiter);
                jQuery('#btnCleanLogs').data('logid', tabId[1]);
                if (tabId[1] == 4) {
                    return true;
                }
                
                ui.jqXHR.fail(function(jqXHR, textStatus, errorThrown) {
                    console.error(fpcm.ui.translate('AJAX_RESPONSE_ERROR'));
                    console.error('STATUS MESSAGE: ' + textStatus);
                    console.error('ERROR MESSAGE: ' + errorThrown);
                    fpcm.ajax.showAjaxErrorMessage();
                    fpcm.ui.showLoader(false);
                });

                ui.ajaxSettings.dataFilter = function( response ) {
                    fpcm.vars.jsvars.dataviews.data.logs = response;
                };

            },
            beforeActivate: function( event, ui ) {
                ui.oldTab.unbind('click');
            },
            load: function( event, ui ) {

                ui.tab.unbind('click');
                ui.tab.click(function () {
                    fpcm.logs.reloadLogs();
                });

                if (ui.tab.attr('data-dataview-list')) {
                    
                    if (!fpcm.vars.jsvars.dataviews.data.logs) {
                        return false;
                    }
                    
                    jQuery('.fpcm-ui-logslist').remove();
                    
                    var result = fpcm.ajax.fromJSON(fpcm.vars.jsvars.dataviews.data.logs);
                    ui.panel.empty();
                    ui.panel.append(fpcm.dataview.getDataViewWrapper(ui.tab.attr('data-dataview-list'), 'fpcm-ui-logslist'));

                    fpcm.vars.jsvars.dataviews[result.dataViewName] = result.dataViewVars;
                    fpcm.dataview.updateAndRender(result.dataViewName);

                    var logSizeEl = result.logsize ? 'fpcm-logs-size-row-' + result.dataViewName : false;

                    if (result.logsize) {
                        jQuery('.fpcm-ui-logslist').append('<div id="' + logSizeEl + '" class="row fpcm-ui-font-small fpcm-ui-margin-lg-top"><div class="col-12 align-self-center fpcm-ui-padding-none-left"> <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-weight fa-lg "></span>  ' + fpcm.ui.translate('FILE_LIST_FILESIZE') + ': ' + result.logsize + '</div></div>');
                    }

                    if (result.fullheight) {
                        ui.panel.height(fpcm.vars.jsvars.dataviews[result.dataViewName].dataViewHeight + (logSizeEl ? jQuery('#' + logSizeEl).height() : 0) );
                    }
                }

                fpcm.ui.accordion('.fpcm-accordion-pkgmanager');
                return true;

            },
            addTabScroll: true
        });
        
        var linkEl = jQuery('#fpcm-tabs-logs-sessions').find('a');
        
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
        jQuery('#fpcm-tabs-logs').tabs('load', jQuery('#fpcm-tabs-logs').tabs( "option", "active" ));
        return false;
    }

};
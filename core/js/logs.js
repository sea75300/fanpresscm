/**
 * FanPress CM Logs Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2017, Stefan Seehafer
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

        jQuery('.fpcm-logs-clear').click(function () {
            var logId = jQuery(this).attr('id');
            var size  = fpcm.ui.getDialogSizes(top, 0.35);
            fpcm.ui.dialog({
                title: fpcm.ui.translate('confirmHL'),
                content: fpcm.ui.translate('confirmMessage'),
                dlWidth: size.width,
                dlButtons: [
                    {
                        text: fpcm.ui.translate('yes'),
                        icon: "ui-icon-check",                    
                        click: function() {
                            fpcm.logs.clearLogs(logId);
                            jQuery(this).dialog('close');
                        }
                    },
                    {
                        text: fpcm.ui.translate('no'),
                        icon: "ui-icon-closethick",
                        click: function() {
                            jQuery(this).dialog('close');
                        }
                    }
                ]
            });
            
            fpcm.ui.resize();

            return false;
        });

        fpcm.ui.tabs('#fpcm-tabs-logs', {
           
            beforeLoad: function(event, ui) {

                var tabId = ui.ajaxSettings.url.split(fpcm.logs.delimiter);
                jQuery('button.fpcm-logs-clear').attr('id', 'fpcm-logs-clear_' + tabId[1]);

                fpcm.ui.showLoader(true);
                ui.jqXHR.done(function(result) {
                    fpcm.ui.showLoader();
                    fpcm.logs.initTabReload();
                });

            },
            beforeActivate: function( event, ui ) {
                fpcm.logs.oldTabItem     = ui.oldTab;
                fpcm.logs.currentTabItem = ui.newTab;
            },
            load: function( event, ui ) {
                fpcm.ui.accordion('.fpcm-accordion-pkgmanager');
                fpcm.ui.resize();
            },
            addTabScroll: true
            
        });

        fpcm.logs.initTabReload();

    },

    clearLogs: function(id) {

        fpcm.ui.showLoader(true);
        var logType = id.split('_');

        fpcm.ajax.get('logs/clear', {
            workData: id,
            data: {
                log: logType[1]
            },
            execDone: function() {
                fpcm.ui.showLoader(false);
                fpcm.logs.reloadLogs();
                fpcm.ui.appendMessage(fpcm.ajax.getResult('logs/clear'));
            }
        });

        return false;
    },
    
    reloadLogs: function() {

        fpcm.ui.showLoader(true);
        var logType = fpcm.logs.reloadDataHref.split(fpcm.logs.delimiter);

        fpcm.ajax.get('logs/reload', {
            workData: logType[1],
            data: {
                log: logType[1]
            },
            execDone: function() {
                fpcm.ui.showLoader(false);

                var tabId = fpcm.ajax.getWorkData('logs/reload');
                fpcm.ui.assignHtml('#' + fpcm.logs.reloadDataDest, fpcm.ajax.getResult('logs/reload'));

                if (tabId == 4) {
                    fpcm.ui.accordion('.fpcm-accordion-pkgmanager');
                }
            }
        });
        
        return false;
    },
    
    initTabReload: function() {

        jQuery('.fpcm-logs-reload').unbind('click');

        if (fpcm.logs.currentTabItem && fpcm.logs.oldTabItem) {
            jQuery(fpcm.logs.oldTabItem).removeClass('fpcm-logs-reload');
            jQuery(fpcm.logs.currentTabItem).addClass('fpcm-logs-reload');

            fpcm.logs.oldTabItem     = null;
            fpcm.logs.currentTabItem = null;
        }

        var reloadEl             = jQuery('.fpcm-logs-reload');
        fpcm.logs.reloadDataHref = reloadEl.find('a.ui-tabs-anchor').attr('href');
        fpcm.logs.reloadDataDest = reloadEl.attr('aria-controls');

        jQuery('.fpcm-logs-reload').click(function() {
            fpcm.logs.reloadLogs();
            return false;
        });

    }

};
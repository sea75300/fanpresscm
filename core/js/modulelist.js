/**
 * FanPress CM Module Liste Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.modulelist = {

    init: function() {

        fpcm.system.checkForUpdates();

        fpcm.modulelist.tabs = fpcm.ui.tabs('#fpcm-tabs-modules', {

            beforeLoad: function(event, ui) {
                
                if (!ui.tab.attr('data-dataview-list')) {
                    fpcm.ui.showLoader();
                    return true;
                }
                
                fpcm.ui.showLoader(true);
                ui.jqXHR.done(function(result) {
                    fpcm.ui.showLoader();
                    return true;
                });

                ui.ajaxSettings.dataFilter = function( response ) {
                    fpcm.vars.jsvars.dataviews.data.modulesList = response;
                };

            },
            beforeActivate: function( event, ui ) {
                jQuery(ui.oldTab).unbind('click');
            },
            load: function( event, ui ) {

                var listId = ui.tab.attr('data-dataview-list');
                if (!listId) {
                    return false;
                }

                if (!fpcm.vars.jsvars.dataviews || !fpcm.vars.jsvars.dataviews.data.modulesList) {
                    return true;
                }

                jQuery('.fpcm-ui-modulelist').remove();

                var result = fpcm.ajax.fromJSON(fpcm.vars.jsvars.dataviews.data.modulesList);
                ui.panel.empty();
                ui.panel.append(fpcm.dataview.getDataViewWrapper(listId, 'fpcm-ui-modulelist'));

                fpcm.vars.jsvars.dataviews[result.dataViewName] = result.dataViewVars;
                fpcm.dataview.updateAndRender(result.dataViewName, {
                    onRenderAfter: fpcm.modulelist.initButtons
                });

                return true;

            },
            addTabScroll: true
        });
        
        fpcm.modulelist.tabs.tabs('load', 0);

    },
    
    initButtons: function () {

        fpcm.ui.assignCheckboxes();
        fpcm.ui.assignControlgroups();

        jQuery('button.fpcm-ui-modulelist-action-local').click(function() {
            
            fpcm.ui.showLoader(true);

            var btnEl = jQuery(this);
            var params = {
                action: btnEl.attr('data-action'),
                key: btnEl.attr('data-key'),
            };

            var fromDir = btnEl.attr('data-dir');
            if (fromDir) {
                params.fromDir = fromDir;
            }

            fpcm.ajax.exec('modules/exec', {
                data: params,
                execDone: function () {
                    var result = fpcm.ajax.getResult('modules/exec', true);
                    fpcm.modulelist.tabs.tabs('load', 0);
                }
            });
        
        });

        jQuery('button.fpcm-ui-modulelist-info').click(function() {
            var btnEl = jQuery(this);
            fpcm.ui.dialog({
                id: 'modulelist-infos',
                title: fpcm.ui.translate('MODULES_LIST_INFORMATIONS'),
                resizable: true,
                dlButtons: [
                    {
                        text: fpcm.ui.translate('GLOBAL_CLOSE'),
                        icon: "ui-icon-closethick",                
                        click: function() {
                            jQuery( this ).dialog( "close" );
                        }
                    }
                ],
                dlOnOpen: function () {
                    
                    var link = btnEl.attr('data-link');

                    jQuery('#fpcm-modulelist-info-name').text(btnEl.attr('data-name'));
                    jQuery('#fpcm-modulelist-info-author').text(btnEl.attr('data-author'));
                    jQuery('#fpcm-modulelist-info-link').html('<a href="' + link + '" target="_blank">' + link + '</a>');
                    jQuery('#fpcm-modulelist-info-require-system').text(btnEl.attr('data-system'));
                    jQuery('#fpcm-modulelist-info-require-php').text(btnEl.attr('data-php'));
                    jQuery('#fpcm-modulelist-info-description').html(btnEl.attr('data-descr'));
                },
                dlOnClose: function() {
                    jQuery('#fpcm-modulelist-info-name').empty();
                    jQuery('#fpcm-modulelist-info-author').empty();
                    jQuery('#fpcm-modulelist-info-link').empty();
                    jQuery('#fpcm-modulelist-info-require-system').empty();
                    jQuery('#fpcm-modulelist-info-require-php').empty();
                    jQuery('#fpcm-modulelist-info-description').empty();
                }
            });
            
        });
        
    }
 
};
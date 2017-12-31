/**
 * FanPress CM Module Liste Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.modulelist = {

    init: function() {
        fpcm.modulelist.actionButtons();

        jQuery('.fpcm-ui-actions-modules').click(function() {

            if (jQuery(this).hasClass('fpcm-noloader')) {
                jQuery(this).removeClass('fpcm-noloader');
            }
            
            fpcm.modulelist.initActionButtons()
        });
    },
    
    actionButtons: function() {

        jQuery('.fpcm-module-openinfo-link').click(function() {
            fpcm.modulelist.showDetails(jQuery(this).attr('id'), jQuery(this).text());
            return false;
        });
        
        jQuery('.fpcm-modulelist-singleaction-install').click(function() {
            fpcm.modulelist.doSingleAction(jQuery(this).attr('id'), 'install');
            return false;
        });
        
        jQuery('.fpcm-modulelist-singleaction-update').click(function() {
            fpcm.modulelist.doSingleAction(jQuery(this).attr('id'), 'update');
            return false;
        });
        
        jQuery('.fpcm-modulelist-singleaction-enable').click(function() {
            fpcm.modulelist.doSingleAction(jQuery(this).attr('id'), 'enable');
            return false;
        });
        
        jQuery('.fpcm-modulelist-singleaction-disable').click(function() {
            fpcm.modulelist.doSingleAction(jQuery(this).attr('id'), 'disable');
            return false;
        });
        
        jQuery('.fpcm-modulelist-singleaction-uninstall').click(function() {
            fpcm.modulelist.doSingleAction(jQuery(this).attr('id'), 'uninstall');
            return false;
        });
        
        jQuery('#fpcmuireloadpkglist').click(function() {
            fpcm.ui.showLoader(true);
            fpcm.ajax.get('modules/loadpkglist', {
                execDone: fpcm.ui.showLoader
            });
            return false;
        });

    },
    
    initActionButtons: function() {

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
                        var moduleKeys = fpcm.ui.getCheckboxCheckedValues('.fpcm-list-selectbox');
                        if (moduleKeys.length == 0 || !jQuery('#moduleActions').val()) {
                            fpcm.ui.showLoader(false);
                            fpcmJs.addAjaxMassage('error', 'SELECT_ITEMS_MSG');
                            return false;
                        }

                        var moduleAction = jQuery('#moduleActions').val();
                        if (moduleAction == 'install') {
                            fpcm.modulelist.runInstall(moduleKeys);
                        } else if (moduleAction == 'update') {
                            fpcm.modulelist.runUpdate(moduleKeys);
                        } else {
                            fpcm.modulelist.runActions(moduleAction, moduleKeys);
                        }

                        jQuery(this).dialog('close');
                    }
                },
                {
                    text: fpcm.ui.translate('no'),
                    icon: "ui-icon-closethick",
                    click: function() {
                        jQuery(this).addClass('fpcm-noloader');
                        jQuery('#moduleActions option:selected').prop('selected', false);
                        jQuery('#moduleActions').selectmenu('refresh');
                        jQuery('.fpcm-list-selectbox:checked').prop('checked', false);
                        jQuery(this).dialog('close');
                        fpcm.ui.showLoader(false);
                    }
                }
            ]
        });
    },
    
    showDetails: function (moduleKey, moduleName) {

        var size = fpcm.ui.getDialogSizes();

        var details = fpcmModuleLayerInfos[moduleKey];
        fpcm.ui.dialog({
            id         : 'modulelist-infos',
            dlWidth    : size.width,
            resizable  : true,
            title      : fpcm.ui.translate('detailsHeadline') + ' « ' + moduleName + ' »',
            dlButtons  : [
                {
                    text: fpcm.ui.translate('close'),
                    icon: "ui-icon-closethick",
                    click: function() {
                        jQuery( this ).dialog( "close" );
                    }
                }
            ],
            dlOnOpen: function (event, ui) {
                jQuery.each(details, function(key, val) {
                    fpcm.ui.appendHtml('#fpcm-dialog-modulelist-infos-' + key, '<span>' + val + '</span>');
                });
            },
            dlOnClose: function(event, ui) {
                jQuery.each(details, function(key, val) {
                    jQuery('#fpcm-dialog-modulelist-infos-' + key).empty();
                });
            }
        });
    },
    
    runActions: function (moduleAction, moduleKeys) {
        fpcm.ui.showLoader(true);
        fpcm.ajax.post('modules/actions', {
            data: {
                keys  : fpcm.ajax.toJSON(moduleKeys),
                action: moduleAction
            },
            execDone: function() {
                fpcm.ui.showLoader(false);
                fpcm.ui.assignHtml('#modules-list-content', fpcm.ajax.getResult('modules/actions'));
                noActionButtonAssign = true;
                fpcm.modulelist.actionButtons();
                fpcmJs.assignButtons();
                fpcm.ui.prepareMessages();
                jQuery('#moduleActions').prop('selectedIndex',0);
                jQuery('#moduleActions').selectmenu('refresh');
            }
        });

    },
    
    runInstall: function (moduleKeys) {
        fpcm.ajax.post('modules/actions', {
            data: {
                keys:moduleKeys,
                action:'install'
            },
            async: false,
            execDone: function() {
                fpcmJs.relocate(fpcmActionPath + 'package/modinstall');
            }
        });
    },
    
    runUpdate: function (moduleKeys) {

        fpcm.ajax.post('modules/actions', {
            data: {
                keys:moduleKeys,
                action:'update'
            },
            async: false,
            execDone: function() {
                fpcmJs.relocate(fpcmActionPath + 'package/modupdate');
            }
        });

    },
    
    doSingleAction: function (object_id, action) {
        jQuery('#cb_' + jQuery.trim(object_id)).prop('checked', true);
        jQuery('#moduleActions option[value="'+ action + '"]').prop('selected',true);
        jQuery('#moduleActions').selectmenu('refresh');
        jQuery('.fpcm-ui-actions-modules').trigger('click');  
    }

};
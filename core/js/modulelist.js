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
        
        fpcm.dataview.render('modulelist', {
            onRenderAfter: fpcm.modulelist.initButtons
        });

    },
    
    initButtons: function () {

        fpcm.ui.assignCheckboxes();
        fpcm.ui.assignControlgroups();

        jQuery('button.fpcm-ui-modulelist-info').click(function() {
            
            var btnEl = jQuery(this);
            console.log(btnEl);
            
            
            fpcm.ui.dialog({
                id: 'modulelist-infos',
                title: fpcm.ui.translate('MODULES_LIST_INFORMATIONS'),
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
                    jQuery('#fpcm-modulelist-info-name').text(btnEl.attr('data-name'));
                    jQuery('#fpcm-modulelist-info-author').text(btnEl.attr('data-author'));
                    jQuery('#fpcm-modulelist-info-link').text(btnEl.attr('data-link'));
                    jQuery('#fpcm-modulelist-info-require-system').text(btnEl.attr('data-system'));
                    jQuery('#fpcm-modulelist-info-require-php').text(btnEl.attr('data-php'));
                    jQuery('#fpcm-modulelist-info-description').text(btnEl.attr('data-descr'));
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
    
//    actionButtons: function() {
//
//        jQuery('.fpcm-module-openinfo-link').click(function() {
//            fpcm.modulelist.showDetails(jQuery(this).attr('id'), jQuery(this).text());
//            return false;
//        });
//        
//        jQuery('.fpcm-modulelist-singleaction-install').click(function() {
//            fpcm.modulelist.doSingleAction(jQuery(this).attr('id'), 'install');
//            return false;
//        });
//        
//        jQuery('.fpcm-modulelist-singleaction-update').click(function() {
//            fpcm.modulelist.doSingleAction(jQuery(this).attr('id'), 'update');
//            return false;
//        });
//        
//        jQuery('.fpcm-modulelist-singleaction-enable').click(function() {
//            fpcm.modulelist.doSingleAction(jQuery(this).attr('id'), 'enable');
//            return false;
//        });
//        
//        jQuery('.fpcm-modulelist-singleaction-disable').click(function() {
//            fpcm.modulelist.doSingleAction(jQuery(this).attr('id'), 'disable');
//            return false;
//        });
//        
//        jQuery('.fpcm-modulelist-singleaction-uninstall').click(function() {
//            fpcm.modulelist.doSingleAction(jQuery(this).attr('id'), 'uninstall');
//            return false;
//        });
//        
//        jQuery('#fpcmuireloadpkglist').click(function() {
//            fpcm.ui.showLoader(true);
//            fpcm.ajax.get('modules/loadpkglist', {
//                execDone: fpcm.ui.showLoader
//            });
//            return false;
//        });
//
//    },
//    
//    initActionButtons: function() {
//
//        var size  = fpcm.ui.getDialogSizes(top, 0.35);
//
//        fpcm.ui.dialog({
//            title: fpcm.ui.translate('GLOBAL_CONFIRM'),
//            content: fpcm.ui.translate('CONFIRM_MESSAGE'),
//            dlWidth: size.width,
//            dlButtons: [
//                {
//                    text: fpcm.ui.translate('GLOBAL_YES'),
//                    icon: "ui-icon-check",                    
//                    click: function() {
//                        var moduleKeys = fpcm.ui.getCheckboxCheckedValues('.fpcm-ui-list-checkbox');
//                        if (moduleKeys.length == 0 || !jQuery('#moduleActions').val()) {
//                            fpcm.ui.showLoader(false);
//                            fpcm.ui.addMessage({
//                                type: 'error',
//                                txt : fpcm.ui.translate('SELECT_ITEMS_MSG')
//                            });
//                            return false;
//                        }
//
//                        var moduleAction = jQuery('#moduleActions').val();
//                        if (moduleAction == 'install') {
//                            fpcm.modulelist.runInstall(moduleKeys);
//                        } else if (moduleAction == 'update') {
//                            fpcm.modulelist.runUpdate(moduleKeys);
//                        } else {
//                            fpcm.modulelist.runActions(moduleAction, moduleKeys);
//                        }
//
//                        jQuery(this).dialog('close');
//                    }
//                },
//                {
//                    text: fpcm.ui.translate('GLOBAL_NO'),
//                    icon: "ui-icon-closethick",
//                    click: function() {
//                        jQuery(this).addClass('fpcm-noloader');
//                        jQuery('#moduleActions option:selected').prop('selected', false);
//                        jQuery('#moduleActions').selectmenu('refresh');
//                        jQuery('.fpcm-ui-list-checkbox:checked').prop('checked', false);
//                        jQuery(this).dialog('close');
//                        fpcm.ui.showLoader(false);
//                    }
//                }
//            ]
//        });
//    },
//    
//    showDetails: function (moduleKey, moduleName) {
//
//        var size = fpcm.ui.getDialogSizes();
//
//        var details = fpcmModuleLayerInfos[moduleKey];
//        fpcm.ui.dialog({
//            id         : 'modulelist-infos',
//            dlWidth    : size.width,
//            resizable  : true,
//            title      : fpcm.ui.translate('MODULES_LIST_INFORMATIONS') + ' « ' + moduleName + ' »',
//            dlButtons  : [
//                {
//                    text: fpcm.ui.translate('GLOBAL_CLOSE'),
//                    icon: "ui-icon-closethick",
//                    click: function() {
//                        jQuery( this ).dialog( "close" );
//                    }
//                }
//            ],
//            dlOnOpen: function (event, ui) {
//                jQuery.each(details, function(key, val) {
//                    fpcm.ui.appendHtml('#fpcm-dialog-modulelist-infos-' + key, '<span>' + val + '</span>');
//                });
//            },
//            dlOnClose: function(event, ui) {
//                jQuery.each(details, function(key, val) {
//                    jQuery('#fpcm-dialog-modulelist-infos-' + key).empty();
//                });
//            }
//        });
//    },
//    
//    runActions: function (moduleAction, moduleKeys) {
//        fpcm.ui.showLoader(true);
//        fpcm.ajax.post('modules/actions', {
//            data: {
//                keys  : fpcm.ajax.toJSON(moduleKeys),
//                action: moduleAction
//            },
//            execDone: function() {
//                fpcm.ui.showLoader(false);
//                fpcm.ui.assignHtml('#modules-list-content', fpcm.ajax.getResult('modules/actions'));
//                noActionButtonAssign = true;
//                fpcm.modulelist.actionButtons();
//                fpcm.ui.initJqUiWidgets();
//                jQuery('#moduleActions').prop('selectedIndex',0);
//                jQuery('#moduleActions').selectmenu('refresh');
//            }
//        });
//
//    },
//    
//    runInstall: function (moduleKeys) {
//        fpcm.ajax.post('modules/actions', {
//            data: {
//                keys:moduleKeys,
//                action:'install'
//            },
//            async: false,
//            execDone: function() {
//                fpcm.ui.relocate(fpcm.vars.actionPath + 'package/modinstall');
//            }
//        });
//    },
//    
//    runUpdate: function (moduleKeys) {
//
//        fpcm.ajax.post('modules/actions', {
//            data: {
//                keys:moduleKeys,
//                action:'update'
//            },
//            async: false,
//            execDone: function() {
//                fpcm.ui.relocate(fpcm.vars.actionPath + 'package/modupdate');
//            }
//        });
//
//    },
//    
//    doSingleAction: function (object_id, action) {
//        jQuery('#cb_' + jQuery.trim(object_id)).prop('checked', true);
//        jQuery('#moduleActions option[value="'+ action + '"]').prop('selected',true);
//        jQuery('#moduleActions').selectmenu('refresh');
//        jQuery('.fpcm-ui-actions-modules').trigger('click');  
//    }

};
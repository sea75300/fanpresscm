/**
 * FanPress CM Installer Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.installer = {
    
    currentDbFileIndex: 0,

    init: function() {
        fpcm.installer.initUi();
        fpcm.installer.initDatabase();
        
        jQuery('button.fpcm-installer-next-4').hide();
    },

    checkDBData: function() {
        sfields = jQuery('.fpcm-installer-data');
        sParams = {};

        jQuery.each(sfields, function( key, obj ) {
            var objVal  = jQuery(obj).val();
            var objName = jQuery(obj).attr('id').replace('database', '');                                
            sParams[objName] = objVal;
        }); 
        
        fpcm.ajax.post('installer/checkdb', {
            data: {
                dbdata: sParams
            },
            async: false,
            execDone: function (res) {
                
                jQuery('#fpcm-messages').empty();
                if (res === '1' || res === 1) {
                    jQuery('#fpcm-ui-form').submit();
                    return true;
                }

                fpcm.ui.addMessage({
                    txt  : fpcm.ui.translate('INSTALLER_DBCONNECTION_FAILEDMSG'),
                    type : 'error',
                    id   : 'errordbtestfailed'
                });

                return false;
            }
        });
        
        return false;
    },

    initDatabase: function () {
        
        if (!fpcm.vars.jsvars.sqlFilesCount) {
            return false;
        }

        fpcm.installer.execDbFile();

    },

    execDbFile: function() {

        if (fpcm.vars.jsvars.sqlFiles[fpcm.installer.currentDbFileIndex] === undefined) {
            return true;
        }

        var obj = fpcm.vars.jsvars.sqlFiles[fpcm.installer.currentDbFileIndex];
        var rowId = 'installer-tabs-' + fpcm.installer.currentDbFileIndex;

        fpcm.ui.appendHtml('#fpcm-installer-execlist', '<div class="row no-gutters fpcm-ui-padding-md-tb"><div class="col-12" id="' + rowId + '"><span class="fa fa-spinner fa-spin fa-fw"></span> ' + fpcm.ui.translate('INSTALLER_CREATETABLES_STEP').replace('{{tablename}}', obj.descr) + '</div></div>');

        fpcm.ajax.post('installer/initdb', {
            quiet: true,
            data: {
                file: obj.path
            },
            execDone: function (result) {
                jQuery('#fpcm-installer-execlist').find('span.fa-spinner').remove();

                if(result != 0){
                    fpcm.ui.prependHtml('#' + rowId, '<span class="fa fa-check fa-fw"></span>');
                    fpcm.installer.currentDbFileIndex++;
                    fpcm.installer.execDbFile();
                    
                    if (fpcm.installer.currentDbFileIndex === fpcm.vars.jsvars.sqlFiles.length) {
                        jQuery('button.fpcm-installer-next-4').show();
                    }

                    return true;
                }

                fpcm.ui.prependHtml('#' + rowId, '<span class="fa fa-ban fa-fw fpcm-ui-important-text"></span>');
                fpcm.installer.breakDbInit = false;

                return false;
            }
        });

    },

    initUi: function() {

        fpcm.ui.tabs('#fpcm-tabs-installer', {
            disabled: fpcm.vars.jsvars.disabledTabs,
            active  : fpcm.vars.jsvars.activeTab,
            beforeActivate: function( event, ui ) {
                
                var backLink = ui.newTab.find('a').attr('data-backlink');
                if (!backLink) {
                    return false;
                }

                fpcm.ui.relocate(backLink);
            }
        });
        
        jQuery('#btnSubmitNext.fpcm-installer-next-3').click(function() {        
            fpcm.installer.checkDBData();
            return false;
        });

        fpcm.ui.selectmenu('.fpcm-ui-input-select', {
            removeCornerLeft: true
        });
    }
};
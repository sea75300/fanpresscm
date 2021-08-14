/**
 * FanPress CM Installer Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.installer = {
    
    currentDbFileIndex: 0,

    init: function() {
        fpcm.installer.initDatabase(1, 0);        
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

    initDatabase: function (_next, _current) {

        if (!fpcm.vars.jsvars.sqlFilesCount) {
            return false;
        }

        fpcm.ui.progressbar('dbtables', {
            max: fpcm.vars.jsvars.sqlFilesCount,
            value: _current
        });
        
        fpcm.dom.fromClass('fpcm-ui-progressbar-label').text(fpcm.ui.translate('INSTALLER_CREATETABLES_HEAD'));

        fpcm.ajax.post('installer/initdb', {
            quiet: true,
            data: {
                next: _next,
                current: _current
            },
            execDone: function (result) {
                
                if (result.data.msg) {
                    fpcm.ui.addMessage(result.data.msg);
                    return false;
                }

                if (result.data.html) {
                    for (var i in result.data.html) {
                        let item = result.data.html[i];                    
                        fpcm.dom.appendHtml('#fpcm-installer-execlist', '<li class="list-group-item" id="installer-tabs-' + item.tab + '">' + fpcm.ui.getIcon(item.icon, {
                            class: item.class
                        }) + fpcm.ui.translate('INSTALLER_CREATETABLES_STEP').replace('{{tablename}}', item.tab) + '</li>');
                    }
                }
                
                fpcm.ui.progressbar('dbtables', {
                    max: fpcm.vars.jsvars.sqlFilesCount,
                    value: result.current
                });

                if (result.current >= fpcm.vars.jsvars.sqlFilesCount && !result.next) {
                    fpcm.dom.fromClass('fpcm-ui-progressbar-label').hide();
                    fpcm.dom.fromTag('button.fpcm-installer-next-4').show();
                    return true;
                }

                fpcm.installer.initDatabase(result.next, result.current);
                return true;
            }
        });


    }

};
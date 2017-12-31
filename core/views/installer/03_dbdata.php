<div class="fpcm-ui-center">
    <h3><span class="fa fa-database"></span> <?php $FPCM_LANG->write('INSTALLER_DBCONNECTION'); ?></h3>

    <div class="fpcm-half-width fpcm-ui-margin-center">
        <table class="fpcm-ui-table fpcm-ui-middle fpcm-ui-left">
            <tr>
                <td><?php $FPCM_LANG->write('INSTALLER_DBCONNECTION_TYPE'); ?>:</td>
                <td><?php \fpcm\model\view\helper::select('database[DBTYPE]', $sqlDrivers, null, false, false, false, 'fpcm-installer-data'); ?></td>
            </tr>
            <tr>
                <td><?php $FPCM_LANG->write('INSTALLER_DBCONNECTION_HOST'); ?>:</td>
                <td><?php \fpcm\model\view\helper::textInput('database[DBHOST]', 'fpcm-installer-data', 'localhost'); ?></td>
            </tr>
            <tr>
                <td><?php $FPCM_LANG->write('INSTALLER_DBCONNECTION_NAME'); ?>:</td>
                <td><?php \fpcm\model\view\helper::textInput('database[DBNAME]', 'fpcm-installer-data'); ?></td>
            </tr>
            <tr>
                <td><?php $FPCM_LANG->write('INSTALLER_DBCONNECTION_USER'); ?>:</td>
                <td><?php \fpcm\model\view\helper::textInput('database[DBUSER]', 'fpcm-installer-data'); ?></td>
            </tr>
            <tr>
                <td><?php $FPCM_LANG->write('INSTALLER_DBCONNECTION_PASS'); ?>:</td>
                <td><?php \fpcm\model\view\helper::passwordInput('database[DBPASS]', 'fpcm-installer-data'); ?></td>
            </tr>
            <tr>
                <td><?php $FPCM_LANG->write('INSTALLER_DBCONNECTION_PREF'); ?>:</td>
                <td><?php \fpcm\model\view\helper::textInput('database[DBPREF]', 'fpcm-installer-data', 'fpcm3'); ?></td>
            </tr>
        </table>        
    </div>
</div>
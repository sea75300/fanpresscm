<?php
    /**
     * Common language file
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    $lang = array(
        'PASSWORD_RESET_SUBJECT'    => 'Rquest for new password',
        'PASSWORD_RESET_TEXT'       => 'A new password was requested for you account. You can new login with '.
                                       '<b>{{newpass}}</b>. In case the new password was not requested by you, please contact '.
                                       'your system administrator.',        
        
        'PUBLIC_COMMENT_EMAIL_SUBJECT'  => 'A new comment was created.',
        'PUBLIC_COMMENT_EMAIL_TEXT'     => "{{name}} (email address: {{email}}) has created a new comment for article {{articleurl}}.\n\n{{commenttext}}\n\Please log in to moderate the comment. {{systemurl}}",

        'CRONJOB_UPDATES_NEWVERSION'      => 'New FanPress CM version available',
        'CRONJOB_UPDATES_NEWVERSION_TEXT' => "FanPress CM version {{version}} was just released and is available to update. Please log into your FanPress CM system to start update.\n\n{{acplink}}",

        'CRONJOB_DBBACKUPS_SUBJECT'       => 'Database dump created',
        'CRONJOB_DBBACKUPS_TEXT'          => "A new database dump was created on {{filetime}} which includes the FanPress CM tables. The dump was saved in {{dumpfile}} and attached to this message."
        
    );

?>
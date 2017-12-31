<?php
    /**
     * Common language file
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    $lang = array(
        'PASSWORD_RESET_SUBJECT' => 'Neues Passwort angefordert',
        'PASSWORD_RESET_TEXT'    => 'Für dich wurde ein neues Passwort angefordert. Dies lautet <b>{{newpass}}</b>. Hast du dies '
                                  . 'nicht selbst veranlasst, so kontaktiere am Besten einen Administrator.',
        
        'PUBLIC_COMMENT_EMAIL_SUBJECT' => 'Es wurde ein neuer Kommentar geschrieben',
        'PUBLIC_COMMENT_EMAIL_TEXT'    => "Von {{name}} (E-Mail-Adresse: {{email}}) wurde ein Kommentar auf den Artikel {{articleurl}} geschrieben.\n\n{{commenttext}}\n\nLogge dich ein um den Kommentar zu moderieren. {{systemurl}}",

        'CRONJOB_UPDATES_NEWVERSION'      => 'Neue FanPress CM Version verfügbar',
        'CRONJOB_UPDATES_NEWVERSION_TEXT' => "Es ist eine neue Version {{version}} von FanPress CM verfügbar. Bitte melde dich im ACP an, um das Update durchzuführen.\n\n{{acplink}}",

        'CRONJOB_DBBACKUPS_SUBJECT'       => 'Datenbank-Sicherung wurde erzeugt',
        'CRONJOB_DBBACKUPS_TEXT'          => "Um {{filetime}} wurde durch den Cronjob eine Sicherung der FanPress CM Datenbank-Tabellen erzeugt. Die Datei findest du unter {{dumpfile}} und als Anhang an dieser E-Mail."
        
    );

?>
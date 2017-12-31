<?xml version="1.0" encoding="UTF-8"?>
<!--
Help language file
@author Stefan Seehafer <sea75300@yahoo.de>
@copyright (c) 2011-2017, Stefan Seehafer
@license http://www.gnu.org/licenses/gpl.txt GPLv3
*/
-->
<chapters>
    <chapter>
        <headline>
            HL_DASHBOARD
        </headline>
        <text>
        <![CDATA[
            <p>Im <b>Dashboard</b> findest du verschiedene Informationen u. a. zum Update-Status deiner FanPress CM Installation, etc. Du kannst auch
            eigene Dashboard-Container erzeugen. Erzeuge dazu eine neue Container-Datei unter "fanpress/inc/dashboard" oder über das entsprechende Modul-Event.</p>
        ]]>
        </text>
    </chapter>
    <chapter>
        <headline>
            ARTICLES_EDITOR
        </headline>
        <text>
        <![CDATA[
            <p>Mit dem <b>Artikel-Editor</b> kannst du Artikel schreiben und/oder bearbeiten. Hierbei hast du vielfältige Gestaltungsmöglichkeiten, welche
            durch Module erweitert werden können. Du kannst einem Artikel Kategorien zuweisen, ihn "anpinnen", so dass er über allen anderen Artikeln
            dargestellt wird und verschiedene weitere Einstellungen vornehmen.</p>
            <p>Der Artikel-Editor hat zwei verschiedene Ansichten:</p>
            <ul>
                <li><b>WYSIWYG-Ansicht:</b><br>
                    Diese basiert auf TinyMCE 4 und zeigt dir direkt alle Formatierungen an, welche du vornimmst. Über den Button
                    <i>HTML</i> in der Format-Leiste kannst du eine einfache HTML-Ansicht öffnen.
                </li>
                <li><b>HTML-Ansicht:</b><br>
                    Die HTML-Ansicht ist ein reiner HTML-Editor, welcher neben verschiedenen Formatierungsmöglichkeiten u. a. auch Syntax-Highlighting
                    bietet.        
                </li>
            </ul>
            <p>Die Ansicht des Editors kannst du in den Systemeinstellungen ändern.</p>

            <p>Über den Button <span class="fpcm-ui-button">Kurzlink</span> am oberen Kopf des Artikel-Editors ist es bei gespeicherten Artikeln möglich, die URL über den Dienst
            <a href=http://is.gd>is.gd</a> kürzen zu lassen. Der genutzte Dienst kann über ein Modul-Event geändert werden</p>

            <p>Sofern es dein Host zulässt, kannst du in den Systemeinstellungen FanPress CM direkt mit Twitter verbinden. Somit werden Artikel beim
            Veröffentlichen oder über das Aktions-Menü unter <i>Artikel bearbeiten</i> direkt bei Twitter bekannt gemacht werden.</p>

            <p><span class="fpcm-ui-button">Erweitert</span>-Menü:</p>
            <ul>
                <li><em>Artikelbild:</em> Mit dem Artikelbild kannst du einen Artikel eine zusätzliche Dekoration, optische
                    Beschreibung, etc. geben. Die Position und Größe des Artikelbildes kann über das Artikel-Template festgelegt werden.
                </li>
                <li><em>Quellenverzeichnis:</em> Der Inhalt dieses Feldes wird durch den Template-Tag "{{sources}}" dargestellt. Hier kannst du Links zu deinen Informationsquellen,
                Quellen von Bildern, Videos, etc. oder zu weiterführenden Informationen angeben. Links werden so weit es geht automatisch in HTML-Links umgewandelt.</li>
                <li><em>Tweet erzeugen:</em> Über diese Option kann die Erzeugung eines Tweets bei aktiver Twitter-Verbindung manuell
                    deaktiviert werden, wenn sie in den Systemoptionen aktiviert wurde.</li>
                <li><em>Twitter-Beitrag-Text:</em> Über dieses Textfeld kann das Standard-Template für einen Beitrag bei Twitter
                    überschrieben und durch einen eigenen Text ersetzt werden. Der Inhalt dieses Feldes wird nicht gespeichert.</li>
                <li><em>Artikel freischalten:</em> Mittels dieser Option kannst du einen neuen Artikel verfassen und zu einem bestimmten
                    Zeitpunkt automatisch veröffentlichen lassen. Der Zeitpunkt kann maximal zwei Monate in der Zukunft liegen.</li>                
                <li><em>Artikel als Entwurf speichern:</em> Wird diese Option aktiviert, so wird der Artikel beim Speichern nicht als
                    Entwurf abgelegt. Entwürfe werden nicht sofort veröffentlicht, sondern sind nur für angemeldete Benutzer sichtbar
                    und können vor der Veröffentlichung noch bearbeitet werden.</li>
                <li><em>Artikel pinnen:</em> "Gepinnte" Artikel werden im Frontend vor allen anderen verfügbaren Artikeln angezeigt, auch
                    auch wenn das Datum ihrer Veröffentlichung vor neueren Artikeln liegt.</li>
                <li><em>Kommentare aktiv:</em> Über diese Option kann das Kommentar-System für einen einzelnen Artikel gesteuert werden.
                    ist die Option nicht aktiv, so können keine Kommentare auf der Artikel verfasst werden.</li>
                <li><em>Artikel archivieren:</em> Bestehende Artikel können über diese Option in's Archiv verschoben werden bzw.
                    herausgenommen werden.</li>
                <li><em>Autor ändern:</em> Benutzer mit entsprechenden Rechten können hierüber den Verfasser eines Artikeln ändern.</li>
            </ul>

            <p>In FanPress CM kannst du über den <strong>&lt;readmore&gt;</strong>-Tag ein Stück Text einfügen, das beim Aufruf der Seite
            nicht angezeigt wird. (bspw. für Spoiler, etc.)</p>

            <p>Der Artikel-Editor kann am oberen Rand bis zu drei Tabs enthalten. Immer angezeigt wird der Tab <i>Artikel-Editor</i>, welcher den Editor
            an sich umfasst. Als weitere Tabs können <i>Kommentare</i> und/oder <i>Revisionen</i> folgen.</p>

            <p>Unter <i>Kommentare</i> erhältst du eine Auflistung aller Kommentare, welche zu zum ausgewählten Artikel geschrieben wurden. Die Liste
            bietet dir die Möglichkeit, einzelne Kommentare zu löschen. Über einen Klick auf den Namen des Verfassern kannst du in einem einfachen
            Editor die Kommentare bearbeiten, freischalten, auf privat setzen, etc. Den Zugriff auf die Kommentare können du über die
            Berechtigungen geregelt werden.</p>

            <p>FanPress CM besitzt ein Revisions-System, d. h. bei Änderungen wird der vorherige Zustand gesichert und kann
            jederzeit wiederhergestellt werden. Die Revisionen kannst du über den Tab <i>Revisionen</i> verwalten. Die Revisionen können
            über die Systemeinstellungen (de)aktiviert werden. Eine Liste aller Revisionen findest du über den entsprechenden Reiter
            im Editor. Dort kannst du jede Revision einzeln aufrufen bzw. den aktuelle Artikel auf eine Revision zurücksetzen.</p>
        ]]>
        </text>
    </chapter>
    <chapter>
        <headline>
            HL_ARTICLE_EDIT
        </headline>
        <text>
        <![CDATA[
            <p>Im Bereich <b>Artikel verwalten</b> kannst findest du alle gespeicherten Artikel in FanPress CM. Über das Aktions-Menü
            kannst du verschiedene Dinge durchführen, bspw. Artikel löschen oder archivieren.</p>
            <ul>
                <li><em>Ausgewählte Bearbeiten:</em> Massenbearbeitung für die ausgewählten Artikel aufrufen. Die auswählbaren Optionen
                    entsprechen denen im Artikel-Editor.</li>
                <li><em>Neuen Tweet erzeugen:</em> Für den bzw. die ausgewählten Artikel neue Posts bei Twitter erzeugen, wenn Verbindung
                    zu Twitter eingerichtet wurde.</li>
                <li><em>Löschen:</em> Den bzw. die ausgewählten Artikel löschen. Wurde in den Systemeinstellungen der Papierkorb aktiviert,
                    so werden die Artikel zuerst in den Papierkorb verschoben, an sonsten werden sie sofort gelöscht.</li>
                <li><em>Artikel wiederherstellen:</em> Den bzw. die ausgewählten Artikel aus dem Papierkorb wiederherstellen.</li>
                <li><em>Papierkorb leeren:</em> Die im Papierkorb vorhandenen Artikel endgültig löschen.</li>
            </ul>
            <p>Über den Button <span class="fpcm-ui-button">Suche & Filter</span> kannst du mithilfe eines Dialogs die angezeigten Artikel anhand verschiedener Kriterien
            weiter eingrenzen. Über die Hauptnavigation kannst du bereits eine Vorauswahl treffen, welche Artikel dir angezeigt werden sollen.</p>
            <p>Die Listen des Bereichs umfassen verschiedene Datensätze:</p>
            <ul>
                <li><em>Alle Artikel:</em> Diese Liste umfasst alle verfassten Artikel, inkl. aktiver und archivierter Artikel,
                sowie Entwürfe.</li>
                <li><em>Aktive Artikel:</em> Diese Liste umfasst ausschließlich Artikel, welche aktiv sind und entsprechend auf deiner
                Webseite angezeigt werden sowie Entwürfe.</li>
                <li><em>Archivierte Artikel:</em> Hier werden all diejenigen Artikel aufgeführt, welche archiviert wurden.</li>
                <li><em>Papierkorb:</em> Sofern aktiv ist diese Option aktiv. Hier findest du eine Übersicht aller gelöschten Artikel.
                Du kannst diese hier wieder herstellen oder vollständig löschen.</li>
            </ul>
        ]]>
        </text>
    </chapter>
    <chapter>
        <headline>
            HL_COMMENTS_MNG
        </headline>
        <text>
        <![CDATA[
            <p>Im Bereich <b>Kommentare</b> erhältst du - unabhängig von den Artikeln - eine generelle Übersicht über alle
            geschriebenen Kommentare. Hier besteht die Möglichkeit, alle Kommentare zu löschen, ent/sperren, etc.</p>
            <p>Willst du nur die Artikel zu einem bestimmten Artikel anzeigen lassen, geht das wie gewohnt über die Liste
            auf dem Kommentar-Tab im Artikel-Editor.</p>
            <ul>
                <li><em>Ausgewählte Bearbeiten:</em> Massenbearbeitung für die ausgewählten Kommentare aufrufen. Die auswählbaren
                    Optionen entsprechen denen im Kommentar-Editor.
                    <ul>
                        <li><em>Kommentar ist privat:</em> Private Kommentare werden nicht öffentlich angezeigt, sondern sind nur
                            für Benutzer innerhalb von FanPress CM sichtbar</li>
                        <li><em>Kommentar ist genehmigt:</em> Genehmigte Kommentare werden öffentlich angezeigt und können von
                            deinen Besuchern gelesen und beantwortet werden. Nicht genehmigte Kommentare verhalten sich wie
                            private Kommentare und sind nicht sichtbar. Diese Funktion kann in den Systemeinstellungen deaktiviert
                            werden.</li>
                        <li><em>Kommentar ist Spam:</em> Kommentare, welche als Spam markiert wurden, werden nicht öffentlich
                            angezeigt. Ihre Daten werden zur Verbesserung der Spam-Erkennung genutzt, sofern du sie nicht löscht.</li>
                        <li><em>Kommentar zu Artikel mit ID verschieben:</em> Die ausgewählten Kommentare zur eingetragenen Artikel-ID
                            verschieben. Das Eingabefeld unterstützt die Suche nach Artikeln mittel Autovervollständigung.</li>
                    </ul>
                </li>    
                <li><em>Löschen:</em> Ausgewählte Kommentare löschen. Achtung, für Kommentare existiert kein Papierkorb!</li>
            </ul>
             <p>Über den Button <span class="fpcm-ui-button">Suche & Filter</span> kannst du mithilfe eines Dialogs die angezeigten Kommentare anhand verschiedener Kriterien
                weiter eingrenzen.</p>
        ]]>
        </text>
    </chapter>
    <chapter>
        <headline>
            HL_FILES_MNG
        </headline>
        <text>
        <![CDATA[
        <p>Im <b>Dateimanager</b> kannst du Bilder hochladen, welche du in deinen Artikeln verwendet willst. Eine vereinfachte Ansicht lässt
        sich auch direkt aus dem Artikel-Editor heraus aufrufen. Er zeigt neben einem Vorschau-Bild noch einige zusätzliche Informationen zur
        hochgeladenen Datei an.</p>
        <p>Der Dateimanager bietet zwei verschiedene Modi. Einmal die klassischen Version, welche mittels HTML-Formularen arbeitet und vor allem
        für ältere Browser zu empfehlen ist. Alternativ steht der - standardmäßig aktive - Dateimanager auf Basis von jQuery zu Verfügung.
        Dieser bietet mehr Komfort und unterliegt weniger Beschränkungen v. a. beim Hochladen mehrerer Dateien.</p>
        <p>Welcher Modus genutzt wird, kann über die Systemeinstellungen festgelegt werden.</p>
        <p><b>Ich möchte ein Bild in einen Artikel einfügen, wie geht das?</b></p>
        <p>Um den Pfad eines Bildes direkt in den "Bild einfügen"-Formular zu kopieren, klicke auf die Buttons
        <span class="fpcm-ui-button">Thumbnail-Pfad in Quelle einfügen</span> bzw. <span class="fpcm-ui-button">Datei-Pfad in Quelle einfügen</span>
        zwischen dem Thumbnail und den Meta-Informationen des jeweiligen Bildes, je nachdem was du nutzen möchtest.</p>
        <p>Alternativ mache in der Dateiliste einen Rechtsklick auf den Bild- und/oder Thumbnail öffnen Button. Wähle nun im Kontext-Menü des
        jeweiligen Browsers "Link-Adresse kopieren", "Verknüpfung kopieren", o. ä. Füge den Pfad anschließend in das Feld "Quelle" im Editor
        ein. Im HTML-Editor kannst du auch einfach anfangen, den Dateinamen einzutippen. Hier öffnet sich dann eine
        Autovervollständigung. In TinyMCE steht im Bild einfügen Dialog auch ein Punkt auch "Image List" zur Verfügung.</p>
        ]]>
        </text>
    </chapter>
    <chapter>
        <headline>
            HL_PROFILE
        </headline>
        <text>
        <![CDATA[
            <p>Das eigene <b>Profil</b> können alle Benutzer über das Profil-Menü oben rechts aufrufen. Jeder Benutzer kann dort folgende Dinge
            anpassen:</p>
            <ul>
                <li><em>Passwort</em> zum Login</li>
                <li><em>Name</em> welcher in den Artikel als Autor-Name angezeigt wird</li>
                <li><em>E-Mail-Adresse</em> an die bspw. ein zurückgesetztes Passwort gesendet wird</li>
                <li><em>Sprache</em> des FanPress CM Admin-Bereichs</li>
                <li><em>Zeitzone</em> welche für die Umrechnung von Zeitangaben genutzt wird</li>
                <li><em>Datum- und Zeitanzeige</em>, welche für die Darstellung von Zeitangaben genutzt wird</li>
                <li><em>Anzahl an Artikeln im ACP</em>, legt die Anzahl an Artikeln fest, welche unter "Artikel bearbeiten" pro Seite angezeigt werden</li>
                <li><em>Standard-Schriftgröße im Editor:</em> Schriftgröße, die standardmäßig im Artikel-Editor genutzt wird</li>
                <li><em>jQuery Dateiupload verwenden:</em> Nutzung des modernen AJAX-Uploads oder klassischer PHP-Upload</li>
                <li><em>Biografie / Sonstiges:</em> Kurzer Info-Text zum Autor, der in den News angezeigt werden kann (optional).</li>
                <li><em>Avatar:</em> Benutzer-Avatar, Dateiname entspricht dem Muster <em>benutzername.jpg/png/gif/bmp</em></li>
            </ul>
        ]]>
        </text>
    </chapter>
    <chapter>
        <headline>
            HL_OPTIONS
        </headline>
        <text>
        <![CDATA[
        
            <h3>Systemeinstellungen</h3>
            <p>Benutzer mit den entsprechenden Rechten können hier zentrale Einstellungen von FanPress CM ändern.</p>
            <ul>
                <li><b>Allgemein:</b><br>
                    Dieser Tab enthält allgemeine Einstellungen.
                    <ul>
                        <li><em>E-Mail-Adresse:</em> Zentrale E-Mail-Adresse für Systembenachrichtigungen.</li>
                        <li><em>URL für Artikellinks:</em> Basis-URL für Artikel-Links im Frontend, wichtig v. a. bei der Nutzung
                            von phpinclude. Entspricht in vielen Fällen der <em>deine-domain.xyz/index.php</em> oder der Datei, in der
                            <em>fpcmapi.php</em> includiert ist.</li>
                        <li><em>Sprache:</em> Globale Spracheinstellung, kann durch Profileinstellung überschrieben werden.</li>
                        <li><em>Zeitzone:</em> Globale Zeitzone, kann durch Profileinstellung überschrieben werden.</li>
                        <li><em>Datum- und Zeitanzeige:</em> Maske für die Anzeige von Datums- und Zeitangaben, kann durch
                        Profileinstellung überschrieben werden.</li>
                        <li><em>Zeit bis zum Verfall des Cache-Inhaltes:</em> Zeit bis der Cache-Inhalt von
                        FanPress CM automatisch verworfen und der Cache neu aufgebaut wird.</li>
                        <li><em>Verwendung per:</em> Nutzung von FanPress CM via phpinclude oder in einem iframe.</li>
                        <li><em>Pfad zu deiner CSS-Datei:</em> Pfad zu deiner CSS-Datei mit deinen eigenen Style-Angaben. Wichtig
                        wenn du FanPress CM via iframe nutzt.</li>
                        <li><em>jQuery Bibliothek laden:</em> Soll jQuery bei Nutzung von phpinclude geladen werden oder nicht.
                        Wichtig wenn du jQuery nicht anderweitig in deiner Seite eingebunden hast.</li>                                        
                    </ul>
                </li>
                <li><b>Editor & Dateimanager:</b><br>
                    Der Tab umfasst Einstellungen zum Artikel-Editor und Dateimanager.
                    <ul>
                        <li><em>Editor auswählen:</em> Welcher Editor soll genutzt werden, die reine HTML-Ansicht oder der auf
                        Basis von TinyMCE 4.</li>
                        <li><em>Standard-Schriftgröße im Editor:</em> Schriftgröße, die standardmäßig im Artikel-Editor genutzt wird</li>
                        <li><em>Revisionen aktivieren:</em> Soll FanPress CM Revisionen beim Speichern eines Artikels anlegen.</li>
                        <li><em>Alte Revisionen löschen, wenn älter als:</em> Revisionen die älter als der angebene
                            Wert sind, werden beim nächsten Durchlauf des zugehörigen Cronjobs auf der Datenbank entfernt, wenn Wert ungleich "Nie"</li>
                        <li><em>Papierkorb aktivieren:</em> Artikel nicht direkt löschen sondern zuerst in Papierkorb verschieben, können bei Bedarf wiederherstellt werden.</li>
                        <li><em>jQuery Dateiupload verwenden:</em> Soll der moderne AJAX-Uploader genutzt werden, mit dem
                        mehrere Dateien auf einmal hochgeladen werden können. Oder den klassischen PHP-Uploader nutzten.</li>
                        <li><em>Anzahl Bilder pro Seite:</em> Anzahl an Bildern, die im Dateimanager pro Seite angezeigt werden.</li>                        
                        <li><em>Bilder-Änderungen in TinyMCE auf Server speichern:</em> Änderungen durch Bild-Bearbeitung in TinyMCE
                        als Datei im Upload-Ordner speichern.</li>
                        <li><em>Maximale Größe des Vorschaubildes:</em> Größe der von FanPress CM erzeugten Thumbnails.</li>
                        <li><em>CSS-Klassen im Editor:</em> CSS-Klassen zur Nutzung im FanPress CM Editor.</li>
                    </ul>
                </li>            
                <li><b>Artikel:</b><br>
                    Der Tab enthält verschiedene Einstellungen zur Artikel-Ausgabe.
                    <ul>
                        <li><em>Anzahl Artikel pro öffentlicher Seite:</em> Anzahl an Artikeln, die im Frontend ausgegeben werden sollen.</li>
                        <li><em>Anzahl an Artikeln im ACP:</em> Anzahl an Artikeln, die im ACP ausgegeben werden sollen,
                            kann durch Benutzer-Einstellung überschrieben werden</li>
                        <li><em>Template für Artikel-Liste::</em> Template, welches für Artikel-Liste genutzt werden soll.</li>
                        <li><em>Template für einzelnen Artikel:</em> Template, welches für die Anzeige eines einzelnen Artikels
                        genutzt werden soll.</li>
                        <li><em>News sortieren nach:</em> Reihenfolge, nach der Artikel im Frontend sortiert werden sollen.</li>
                        <li><em>Share-Buttons anzeigen:</em> Sollen Share-Buttons angezeigt werden.</li>
                        <li><em>RSS-Feed ist aktiv:</em> RSS-Feed aktivieren.</li>
                        <li><em>URL-Rewriting für Artikel-Links aktivieren:</em> statt der klassischen Artikel-URL mit der Artikel-ID wird eine erweiterte Version erzeugt, welche um den
                        Artikel-Titel erweitert wird und sich daher nachträglich ändern kann</li>
                        <li><em>Archiv-Link anzeigen:</em> Soll Link zu Archiv in der Navigation im Frontend angezeigt werden.</li>
                        <li><em>Artikel in Archiv anzeigen ab:</em> vor dem Datum angegebenen Datum veröffentlichte Artikel im Archiv nicht für Besucher anzeigen, wenn leer werden alle
                        angezeigt</li>
                    </ul>
                </li>
                <li><b>Kommentare:</b><br>
                    Der Tab enthält verschiedene Einstellungen zur Ausgabe von Artikel-Kommentaren und deren Verwaltung.
                    <ul>
                        <li><em>Kommentar-System ist aktiv:</em> Kommentar-System global de/aktivieren.</li>
                        <li><em>Kommentar-Template:</em> Template für die Anzeige von Kommentaren.</li>
                        <li><em>Anti-Spam-Frage:</em> Frage für das Standard-Spam-Plugin.</li>
                        <li><em>Antwort auf Anti-Spam-Frage:</em> Antwort für das Standard-Spam-Plugin.</li>
                        <li><em>Zeitsperre zwischen zwei Kommentaren:</em> Zeit in Sekunden, die zwischen zwei Kommentaren von
                        der selben IP-Adresse vergangen sein muss.</li>
                        <li><em>Muss E-Mail Adresse angegeben werden:</em> Muss E-Mail-Adresse beim Schreiben eines Kommentars
                        angegeben werden oder nicht.</li>
                        <li><em>Müssen Kommentare freigeschalten werden:</em> Kommentare sind sofort sichtbar oder
                        müssen manuell durch den Autor oder einen Admin freigegeben werden.</li>
                        <li><em>Benachrichtigung bei neuem Kommentare an:</em> E-Mail-Adresse festlegen, an welche die
                        Benachrichtigungen über neue Kommentare gehen. (nur an Autor, nur an globale Adresse oder an beide)</li>
                        <li><em>Automatische Spam-Markierung:</em> Sind vom aktuellen Kommentar-Autor bereits diese Anzahl an Kommentaren
                            als Spam markiert im System vorhanden, so wird der neue Kommentar automatisch als Spam markiert.</li>
                    </ul>
                </li>
                <li><b>Twitter-Verbindung:</b><br>
                    Sofern dieser Tab angezeigt wird, kannst du eine direkte Verbindung zu Twitter herstelen.
                    Siehe letzter Hilfe-Abschnitt ganz unten zur Einrichtung.
                </li>
                <li><b>Sicherheit & Wartung:</b><br>
                    Dieser Tab enthält Einstellungen zur Wartungs und System-Sicherheit:
                    <ul>
                        <li><em>Wartungsmodus aktiv:</em> Wurde der Wartungsmodus aktiviert, so haben nur angemeldete Benutzer Zugriff auf FanPress CM.
                            Besucher deiner Seite, etc. erhalten eine Hinweis-Meldung.</li>
                        <li><em>Maximale Länge einer Admin-Sitzung:</em> Länge einer Session im FanPress-CM Adminbereich.</li>
                        <li><em>Anzahl Login-Versuche vor temporärer Sperre:</em> Hiermit kannst du Anzahl der Fehlgeschlagenen Logins einstellen,
                            bis der Login vorübergehend gesperrt wird. Diese Option hilft dabei, die Übernahme von FanPress CM Accounts
                            zu erschweren.</li>
                    </ul>
                </li>
                <li><b>Erweitert:</b><br>
                    Der "Erweitert"-Tab enthält verschiedene Einstellungen, welche nur mit Bedacht geändert werden sollten.
                    <ul>
                        <li><em>E-Mail-Benachrichtigung, wenn Updates verfügbar:</em> Diese Option ermöglicht es, die Benachrichtigung über
                            verfügbare Updates durch den Update-Cronjob zu de/aktivieren.</li>
                        <li><em>Entwickler-Versionen bei Update-Check anzeigen:</em> Neben den offizielle Releases gibt es immer
                            wieder Entwickler- und Test-Versionen. Aktivieren diese Option, um solche Versionen beim Update-Check
                            ebenfalls anzuzeigen. <b>Achtung: Entwickler- und Test-Versionen können Fehler enthalten oder
                            unvollständige Änderungen enthalten, welche zu Problemen führen können!</b></li>
                        <li><em>Update-Check-Intervall, wenn externe Server-Verbindungen nicht möglich:</em> Kann deine
                            FanPress CM Installation keine direkte Verbindung zum Update herstellen, so wird dir in regelmäßigem
                            Abstand ein Dialog angezeigt, welcher die Download-Seite auf
                            <a href="https://Nobody-Knows.org">Nobody-Knows.org</a> angezeigt. Mit dieser Einstellung kann
                            festgelegt werden, in welchem zeitlichen Abstand dies passiert.</li>

                        <li><em>E-Mails via SMTP versenden:</em> Wenn diese Option aktiv ist, erfolgt der E-Mail-Versand unter
                        Nutzung der E-Mail-Kontos, welche durch die SMTP-Zugangsdaten definiert wird.</li>
                        <li><em>E-Mail-Adresse:</em> E-Mail-Server, die als Absender-Konto verwendet wird</li>
                        <li><em>SMTP-Server-Adresse:</em> E-Mail-Server-Adresse</li>
                        <li><em>SMTP-Server-Port:</em> E-Mail-Server-Port</li>
                        <li><em>SMTP-Benutzername:</em> Benutzername</li>
                        <li><em>SMTP-Passwort:</em> Passwort für das E-mail-Konto</li>
                        <li><em>SMTP-Verschlüsselung:</em> Verschlüsslung für Verbindung zum E-Mail-Server aktivieren,
                        muss vom Server unterstützt werden</li>
                    </ul>
                </li>
                <li><b>Systemprüfung:</b><br>
                    Auf diesem Tab erhältst du eine Übersicht über den aktuelle Update-Status deines FanPress CM-Systems sowie
                    der verfügbaren Funktionen, etc. deines Servers. Bei allen <i>nicht-optionalen</i> Werten sollte ein blauer
                    Haken wie <span class="fa fa-check-square fpcm-ui-booltext-yes"></span> zu sehen sein. Wenn dies nicht der
                    Fall ist, wende sich an deinen Host.
                </li>            
            </ul>

            <h3>Benutzer & Rollen</h3>
            <ul>
                <li>Mit den entsprechenden Rechten können Benutzer und Benutzer-Rollen verwaltet werden.</li>
                <li>Ein Benutzer kann lediglich Mitglied einer einzelnen Rolle sein.</li>
                <li>Benutzer können deaktiviert werden. Dabei wird der Login gesperrt und somit der Zugriff auf den
                    Administratonsbereich gesperrt. Die Erstellten Artikel, etc. bleiben erhalten. Dies kann nützlich sein, wenn
                    der Benutzer das Team deiner Seite verlassen hat, aus ihm ausgeschlossen wurde oder der Account irgendwie
                    missbraucht wurde.</li>
                <li>Die änderbaren Informationen in den Benutzern entsprechen denen im Benutzer-Profil.</li>
                <li><strong>Berechtigungen:</strong> Benutzer mit entsprechenden Rechten können hier die Zugriffsrechte
                auf verschiedene Dinge von FanPress CM ändern und den Zugriff einschränken. Der Bereich sollte nur von
                Administratoren nutzbar sein! Der Rolle "Administrator" kann der Zugriff auf die Rechte-Einstellungen nicht
                verweigert werden. Seit Version 3.6 werden Berechtigungen direkt im bereich der Rollen bearbeitet.
                </li>
            </ul>
                
            <h3>IP-Adressen</h3>
            <p>Benutzer mit Rechten zur Änderung der Systemeinstellungen können hier IP-Adressen sperren oder Sperren wieder aufheben.
               (z. B. wegen Spam) Pro Eintrag kann festgelegt werden, für welchen Bereich von FanPress CM die Sperren gelten soll:
               keine Kommentare, kein Login, überhaupt kein Zugriff.</p>
            <ul>
                <li><em>Keine Kommentare schreiben:</em> Der Besucher mit der angegebenen IP-Adresse kann keine
                    Kommentare verfassen, wenn diese nicht für den Artikel oder genrell deaktiviert sind.</li>
                <li><em>Kein ACP-Login:</em> Der Besucher mit der angegebenen IP-Adresse kann sich nicht in FanPress-CM
                    einloggen bzw. hat keinen Zugriff auf die Login-Maske.</li>
                <li><em>Kein Frontend-Zugriff:</em> Dem Besucher mit der angegebenen IP-Adresse werden veröffentlichte
                    Artikel, Kommentare, etc. nicht angezeigt. Der weitere Zugriffe auf deine Seite kann von anderen Faktoren
                    abhängen.</li>
            </ul>
                
            <h3>Textzensur</h3>
            <p>Die Textzensur ermöglicht es, bestimmte Wörter, Textgruppen oder Zeichenketten für die Verwendung in Artikeln, Kommentaren, etc. zu sperren.</p>
            <ul>
                <li><em>Text ersetzen:</em> Ist diese Checkbox markiert, so wird die entsprechende Textstelle durch den angegeben
                    Text ersetzt. Die Textzensur wird beim Erstellen von Kommentaren, Artikeln, Kategorien, Benutzern und
                    Benutzer-Rollen ausgeführt.
                </li>
                <li><em>Artikel muss überprüft werden:</em> Durch diese Option wird beim Speichern eines Artikels geprüft, ob die
                    entsprechende Phrase enthalten ist. In diesem Fall wird - unabhängig von den eingestellten Berechtigungen - der
                    Artikel markiert, dass er freigeschalten werden muss.</li>
                <li><em>Kommentar muss freigeschalten werden:</em> Analog zur Option <em>Artikel muss überprüft werden</em>, allerdings
                    wird hier der entsprechende Kommentar markiert, dass er manuell freigegeben werden muss.</li>
            </ul>
                
            <h3>Kategorien</h3>
            <ul>
                <li>Benutzer mit entsprechenden Rechten können hier neue Kategorien, sowie bestehende ändern oder löschen.</li>
                <li>Der Zugriff auf Kategorien kann auf bestimmte Benutzergruppen beschränkt werden.</li>
                <li>Für das "Kategorie-Icon" kann eine Bid-Datei auf einem externen Server oder lokal auf deinem Webspace verwendet
                    werden. In beiden Fällen sollte die vollständige URL angegeben werden.</li>
            </ul>
                
            <h3>Templates</h3>
            <p>Benutzer mit entsprechenden Rechten können die Templates zur Ausgabe von Artikeln, Kommentaren, etc. bearbeiten.
               Für eine bessere Übersicht bietet der Template-Editor Syntax-Highlighting und eine Liste der verfügbaren Platzhalter.</p>
            <ul>
                <li><em>Artikel-Liste:</em> Template für Anzeige von Artikeln in der Artikel-Liste</li>
                <li><em>Artikel-Einzel-Ansicht:</em> Template für Anzeige eines einzelnen Artikels inkl.
                    dessen Kommentaren, dem Kommentar-Formular, etc. Dieser Tab wird nicht angezeigt, wenn für
                    <em>Artikel-Liste</em> und <em>Artikel-Einzel-Ansicht</em> das gleiche Template genutzt wird.</li>
                <li><em>Kommentar:</em> Template für einen einzelnen Kommentar</li>
                <li><em>Kommentar-Formular:</em> Template für das Formular zum Verfassen eines Kommentars</li>
                <li><em>Latest News:</em> Template für die einzelnen Zeilen in den "Latest News"</li>
                <li><em>Tweet:</em> HTML-freies Template für automatisch erzeugte Einträge bei Twitter (Tweets).</li>
                <li><em>Vorlagen:</em> HTML-Vorlagen zu Nutzung im Artikel-Editor. (TinyMCE bzw. HTML-Ansicht)</li>
            </ul>
            
            <h3>Smileys</h3>
            <p>Benutzer mit den entsprechenden Rechten können die nutzbaren Smileys verwalten.</p>
            
            <h3>Cronjobs</h3>
            <ul>
                <li>Cronjobs sind Aufgaben, welche in regelmäßgen Abständen automatisch durch FanPress CM im Hintergrund
                    ausgeführt werden.</li>
                <li>Die Cronjob-Übersicht zeigt eine Liste aller verfügbaren Cronjobs, wenn sie zuletzt ausgeführt wurden,
                    sowie den Zeitpunkt der nächsten voraussichtlichen Ausführung.</li>
                <li>Die Häufigkeit der Ausführung eines Cronjobs kannst du anpassen, indem der Wert für das Intervall-Zeit
                    angepasst wird.</li>
                <li>Beachte bei der Änderung des Intervals, dass Cronjobs u. U. für erhöhte Serverlast führen kann.</li>
            </ul>
            
            <h3>System-Logs</h3>
            <p>Im Bereich der Systemlogs findest du eine Auflistung aller bisherigen Benutzer-Logins, System-Meldungen von FanPress und
               Fehlermeldungen durch PHP selbst oder der Datenbank. Über den Button <i>Log-Datei leeren</i> kannst du Meldungen, etc. löschen
               lassen.</p>
            <ul>
                <li>Das <em>System-Log</em> beinhaltet allgemeine Meldungen von FanPress CM.</li>
                <li>Das <em>PHP-Fehler-Log</em> beinhaltet einen Übersicht über Fehlermeldungen, die aufgrund von Fehlern bei der
                    Server-seitigen Ausführung des FanPress CM Codes auftreten.</li>
                <li>Im <em>Datenbank-Log</em> werden Fehler- und Hinweismeldungen geführt, welche bei der Ausführung von Datenbank-Zugriffen
                    auftreten.</li>
                <li>Das <em>Session-Log</em> ist eine Übersicht über durchgeführte Logins aller vorhandenen Benutzern.</li>
                <li>Im <em>Paket-Manager-Log</em> wird eine Übersicht über installierte Pakete geführt. Dies sind Pakete für System-Updates
                    und Pakete von Modulen.</li>
            </ul>
            
            <h3>Backupmanager</h3>
            <ul>
                <li>Im Bereich "Backupmanager" kannst du die automatisch erzeugten Datenbank-Backups herunterladen.</li>
                <li>Die erzeugten Datenbank-Backups sind gepackten SQL-Dateien, deren Struktur vom verwendeten Datenbank-System
                    abhängt.</li>
                <li>Um ein Backup bei Bedarf wiederherzustellen, kannst du Werkzeuge wie
                    <a href="https://www.phpmyadmin.net/" target="_blank">phpMyAdmin</a>, <a href="https://www.adminer.org/de" target="_blank">Adminer</a> or
                    <a href="http://phppgadmin.sourceforge.net/doku.php" target="_blank">phpPgAdmin</a> nutzen.
                </li>
            </ul>        

        ]]>
        </text>
    </chapter>
    <chapter>
        <headline>
            HL_MODULES
        </headline>
        <text>
        <![CDATA[
            <p>Im Bereich <b>Module</b> kannst du Erweiterungen verwalten, welche die Funktionalität von FanPress CM erweitern können.
            Je nachdem, welche internen Events der Ersteller eines Moduls verwendet, können diese auch direkt in der Hauptnavigation
            erscheinen. Die Aktionen, welche ein Benutzer im Module-Manager ausführen kann, hängen von seinen Berechtigungen ab. Benutzer
            ohne Administrationsrechte sollten i. d. R. keine Möglichkeit haben, Module zu installieren bzw. Änderungen an ihrer
            Konfiguration vorzunehmen.</p>
            <p>Über den Buttons am Anfang einer Zeile kannst du Module einzeln verwalten. Um Änderungen an mehren Modulen mit einmal
            vorzunehmen, aktivieren die Checkbox am Ende jeder Modul-Zeile und wähle die Aktion aus.</p>
            <p>Am komfortabelsten kannst du Module verwalten, wenn dein Host Verbindungen zu anderen Servern zulässt (siehe Info im Dashboard).
            Musst du Erweiterungen manuell installieren/aktualisieren, verwende den Tab "Modul manuell installieren". Wähle die entspreche de ZIP-Datei aus
            und klicke auf <span class="fpcm-ui-button">Upload starten</span>. Die Datei wird nun auf den Server geschoben und automatisch in das richtige Verzeichnis unter
            "fanpress/inc/modules" entpackt. Ist ein Modul noch nicht installiert werden ggf. zusätzliche Schritte zur Installation
            durchgeführt. Ist ein Modul bereits installiert, werden definierte Update-Schritte durchgeführt.</p>
            <p>Für Module lasst sich <b>Abhängigkeiten</b> definieren, d. h. ein Modul kann erst dann aktiviert/ verwendet werden wenn
            andere Module installiert sind. Wird dir vor einem Modul der Button <span class="fpcm-ui-button"><span class="ui-icon ui-icon-alert"></span></span> angezeigt,
            so wurden für dieses Modul nicht-erfüllte Abhängigkeiten festgestellt. Werde in diesem Fall einen Blick in die Modul-Informationen.</p>
            <p>Wenn du selbst ein Modul erstellen willst, schau am Besten in das <a href="https://nobody-knows.org/download/fanpress-cm/tutorial-zum-schreiben-eines-moduls/">Tutorial</a>
            und besuche die <a href="http://updates.nobody-knows.org/fanpress/docs_fpcm3/">Klassen-Doku</a>.</p>
        ]]>
        </text>
    </chapter>
    <chapter>
        <headline>
            HL_HELP_CACHE
        </headline>
        <text>
        <![CDATA[
            <ul>
                <li>FanPress CM besitzt ein Cache-System, welche die Ladenzeiten und System-Belastung deutlich reduzieren, da Daten nicht bei jedem
                    Seiten-Aufruf aus der Datenbank gezogen werden müssen. Bei Aktionen, in denen der Cache-Inhalt als veraltet gilt, wird er i. d. R
                    automatisch geleert.</li>
                <li>Sollte dies jedoch einmal nicht geschehen, so kann du über <span class="fpcm-ui-button" title="Cache leeren"><span class="fa fa-recycle fa-lg fa-fw"></span></span>
                    neben dem Profil-Menü eine manuelle Löschung des Caches anstoßen.</li>
            </ul>
        ]]>
        </text>
    </chapter>
    <chapter>
        <headline>
            HL_HELP_INTEGRATION
        </headline>
        <text>
        <![CDATA[
            <p>Wie du FanPress CM auf deiner Seite verwendest, hängt davon ab wie du den Inhalt in die Seite einbindest.</p>
            <p>Hilfe bei der Einbindung erhältst du durch das "FanPress CM Integration" Modul, welches du über die Modulverwaltung
            installieren kannst. Du kannst natürlich auch manuell vorgehen:</p>
            <p><b>php include:</b></p>
            <p>Wenn du deine Seite mittels php include verwendest, binde zuerst die API-Datei im FanPress Verzeichnis ein und erzeuge
            ein neues API-Objekt.</p>
            <pre>
                &lt;?php include_once 'fanpress/fpcmapi.php'; ?&gt;
                &lt;?php $api = new fpcmAPI(); ?&gt;
            </pre>
            <p>Anschließend kannst du die verschiedenen Funktionen aufrufen. Das sind im Detail:</p>
            <ul>
                <li><strong>$api->showArticles()</strong> zum Anzeigen der Artikel, anhand von Seiten, Archiv und einzeln inkl.
                Kommentare (entspricht der shownews.php auf FanPress CM 1.x und 2.x)</li>
                <li><strong>$api->showLatestNews()</strong> zum Anzeigen der zuletzt geschriebenen Artikel</li>
                <li><strong>$api->showPageNumber()</strong> zum Anzeigen der aktuell aufgerufenen Artikel-Seite. Als Parameter kannst du
                die Beschreibung für "Seite XYZ" angeben.</li>
                <li><strong>$api->showTitle()</strong> zum Anzeigen des Titels des aktuell aufgerufenen Artikels im &lt;title&gt;-Tag.
                Als Parameter kannst du einen Trenner zum restlichen Inhalt des &lt;title&gt;-Tags angeben.</li>
                <li><strong>$api->legacyRedirect()</strong> bietet dir die Möglichkeit, deine Besucher (v. a. bei vorheriger Nutzung des
                Importer-Modules) vom alten FanPress CM 1/2-URL-Stil zur entsprechenden Stelle von FanPress CM 3 weiterzuleiten.</li>
            </ul>
            <p>Die Ausgabe kannst du zudem über einige PHP-Konstanten beeinflussen:</p>
            <ul>
                <li><strong>FPCM_PUB_CATEGORY_LATEST</strong> Kategorie festlegen in $api->showLatestNews()</li>
                <li><strong>FPCM_PUB_CATEGORY_LISTALL</strong> Kategorie festlegen in $api->showArticles()</li>
                <li><strong>FPCM_PUB_LIMIT_LISTALL</strong> Anzahl der aktiven Artikel in $api->showArticles()</li>
                <li><strong>FPCM_PUB_LIMIT_ARCHIVE</strong> Anzahl der archivierten Artikel in $api->showArticles()</li>
                <li><strong>FPCM_PUB_LIMIT_LATEST</strong> Anzahl der Artikel in $api->showLatestNews()</li>
                <li><strong>FPCM_PUB_OUTPUT_UTF8</strong> UTF-8-Zeichensatz für Ausgabe de/aktivieren, in $api->showLatestNews(),
                $api->showArticles() und $api->showTitle(), sollte nur genutzt werden wenn Umlaute, Sonderzeichen, etc. nicht richtig
                angezeigt werden.</li>
            </ul>
            
            <p><b>iframes:</b></p>
            <p>Solltest du FanPress CM in <i>iframes</i> nutzen, so musst du die entsprechenden Controller direkt aufrufen.</p>
            <ul>
                <li><strong>deine-seite.xyz/fanpress/index.php?module=fpcm/list</strong> zum Anzeigen der aktiven Artikel
                (entspricht der shownews.php auf FanPress CM 1.x und 2.x)</li>
                <li><strong>deine-seite.xyz/fanpress/index.php?module=fpcm/archive</strong> zum Anzeigen des Artikel-Archives
                (entspricht der shownews.php auf FanPress CM 1.x und 2.x)</li>
                <li><strong>deine-seite.xyz/fanpress/index.php?module=fpcm/article&&amp;id=EINE_ZAHL</strong> zum Anzeigen eines ganz bestimmtes
                Artikels inkl. seiner Kommentare, etc.</li>
                <li><strong>deine-seite.xyz/fanpress/index.php?module=fpcm/latest</strong> zum Anzeigen der Latest News</li>
            </ul>
            
            <p><b>RSS Feed:</b></p>
            <p>Sofern du auch den RSS-Feed von FanPress CM für deine Besucher zur Verfügung stellen willst, so verlinke einfach auf
            <strong>deine-seite.xyz/fanpress/index.php?module=fpcm/feed</strong>. Der Link ist unabhängig von der restlichen Integration
            in deine Seite.</p>
        ]]>
        </text>
    </chapter>
    <chapter>
        <headline>
            SYSTEM_OPTIONS_TWITTER_CONNECTION
        </headline>
        <text>
        <![CDATA[
            <p>FanPress CM bietet dir die Möglichkeit, beim schreiben/aktualisieren eines Artikels automatisch einen Tweet bei Twitter
            erzeugen zu lassen.</p>
            <p>Um die Verbindung zu Twitter herzustellen, folge einfach der Anleitung.</p>
            <ol class="list-large">
                <li>Logge dich zuerst über die Twitter-Webseite ganz normal ein. <a href="https://twitter.com/login" class="fpcm-ui-button">zum Login</a></li>
                <li>Öffne die Einstellungen der Twitter-Verbindungen über <strong>Optionen &rarr; Systemeinstellungen &rarr;
                Twitter-Verbindung</strong>.</li>
                <li><strong>API-Schlüssel:</strong> Klicke auf den Button <span class="fpcm-ui-button">API-Schlüssel
                anzufordern</span>, du wirst zur AppVerwaltung von Twitter weitergeleitet.</li>
                <li>Wähle den Button <span class="fpcm-ui-button">Create new app</span>.</li>
                <li>Fülle das angezeigte Formular aus und bestätige mit <span class="fpcm-ui-button">Create your Twitter application</span>.</li>
                <li>Öffne den Tab <strong>Keys and Access Tokens</strong> und kopiere von dort <strong>Consumer Key (API Key)</strong>
                    und <strong>Consumer Secret (API Secret)</strong> in die Felder in den Systemeinstellungen.</li>
                <li>Um Tweets erzeugen zu können, stelle den <strong>Access Level</strong> über den Reiter <strong>Permissions</strong>
                    von <strong>Read-only</strong> auf <strong>Read and Write</strong>.</li>
                <li><strong>Access Token:</strong> Nach dem API-Key musst du nun einen Access Token erzeugen. Scrolle dafür runter zum
                    Punkt <strong>Your Access Token</strong> und klicke auf den Button <span class="fpcm-ui-button">Create my access
                    token</span>. Kopiere anschließend <strong>Access Token</strong> und <strong>Access Token Secret</strong> in
                    in die Felder in den Systemeinstellungen.</li>
                
                <li>Klicke nun in den <strong>Systemeinstellungen</strong> auf Speichern, um die Daten zu speichern.</li>
                <li>Wurden alle Schritte richtig durchgeführt, so erhältst du einen entsprechenden Hinweis.</li>
            </ol>
            <p>Um die Twitter-Verbindung zu löschen, klicke den Button <span class="fpcm-ui-button">Verbindung zu löschen</span> an.</p>
        ]]>
        </text> 
    </chapter>
    <chapter>
        <headline>
            HL_HELP_SUPPORT
        </headline>
        <text>
        <![CDATA[
            <p>Solltest du weitergehende Hilfe bei technischen Problemen brauchen oder Fragen haben, schreiben eine E-mail an
            <em>fanpress@nobody-knows.org</em> oder <em>sea75300@yahoo.de</em>. Alternativ kannst du auch auf der Download-Seite unter
            <a href="https://nobody-knows.org/download/fanpress-cm/">nobody-knows.org</a> einen Kommentar hinterlassen.</p>
            <p>Das Module <em>FanPress CM Support Module</em> kann installiert werden, um einen einfachen, temporären Zugang
            zur Verfügung zu stellen. Beachte bitte, dass bereits bei der Installation einen E-Mail mit den Zugangsdaten versendet
            wird.</p>
        ]]>
        </text> 
    </chapter>
</chapters>
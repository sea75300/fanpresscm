<?xml version="1.0" encoding="UTF-8"?>
<!--
Help language file
@author Stefan Seehafer <sea75300@yahoo.de>
@copyright (c) 2011-2018, Stefan Seehafer
@license http://www.gnu.org/licenses/gpl.txt GPLv3
*/
-->
<chapters>
    <chapter ref="HL_DASHBOARD">
        <![CDATA[
            <p>Im <b>Dashboard</b> findest du verschiedene Informationen zu deiner FanPress CM Installation, etc. Eigene Dashboard-Container
            können durch Module bzw. neue Datei unter "fanpress/inc/dashboard" erzeugt werden.</p>
            <dl>
                <dt>Zuletzt geschriebene News:</dt>
                <dd class="fpcm-ui-padding-md-bottom">Hier findest die eine Übersicht der zuletzt verfassten Artikel.</dd>
                <dt>Zuletzt geschriebene Kommentare:</dt>
                <dd class="fpcm-ui-padding-md-bottom">Hier findest die eine Übersicht der zuletzt verfassten Kommentare.</dd>
                <dt>Verfügbare Updates:</dt>
                <dd class="fpcm-ui-padding-md-bottom">Dieser Container beinhaltet Informationen zum Update-Status des Systems und von Modulen.</dd>
                <dt>Systemprüfung:</dt>
                <dd class="fpcm-ui-padding-md-bottom">Diese Box enthält grundlegende Status-Informationen zu deiner Installation.</dd>
                <dt>Statistiken:</dt>
                <dd class="fpcm-ui-padding-md-bottom">In diesem Bereich werden statistische Informationen ausgegeben, u. a. zur Anzahl verfasster
                Artikel oder Kommentare.</dd>
                <dt>Aktuelle FanPress CM News:</dt>
                <dd class="fpcm-ui-padding-md-bottom">Dieser Container beinhaltet News rund vom FanPress CM, bspw. über neue Versionen.</dd>
                <dt>Team-Kontakte:</dt>
                <dd class="fpcm-ui-padding-md-bottom">Dieser Container beinhaltet eine Liste aller aktiven Benutzer mit der Möglichkeit, dies eine
                E-Mail zu verfassen.</dd>
            </dl>

            <p>Über den Button <strong>Mein Profil öffnen</strong> kannst du dein Benutzerprofil aufrufen. Der Button
            <strong>Vollständige Systemprüfung</strong> ruft die Systemprüfung in den Systemeinstellungen auf.</p>
        ]]>
    </chapter>
    <chapter ref="ARTICLES_EDITOR">
        <![CDATA[
        <h3>Editor</h3>
        
        <p>Mit dem <b>Artikel-Editor</b> kannst du Artikel schreiben und/oder bearbeiten. Hierbei hast du vielfältige Gestaltungsmöglichkeiten, welche
            durch Module erweitert werden können. Du kannst einem Artikel Kategorien zuweisen, ihn "anpinnen", so dass er über allen anderen Artikeln
            dargestellt wird und verschiedene weitere Einstellungen vornehmen.</p>

        <dl>
            <dt>TinyMCE:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Dieser WYSIWYG-Editor zeigt alle Formatierungen und Änderungen direkt an. Außerdem
            bietet er diverse zusätzliche Informationen u. a. zur Bearbeitung von Bildern.</dd>
            <dt>CodeMirror:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Dieser Editor ist ein reiner HTML-Editor, welcher verschiedene Formatierungsmöglichkeiten
            bietet sowie zusätzliche Funktionen wie Syntax-Highlighting.</dd>
        </dl>        

        <p>Über den Button <span class="fpcm-ui-button">Kurzlink</span> am oberen Kopf des Artikel-Editors ist es bei gespeicherten Artikeln möglich, die URL über den Dienst
            <a href=http://is.gd>is.gd</a> kürzen zu lassen. Der genutzte Dienst kann über ein Modul-Event geändert werden</p>

        <h3>Eigenschaften</h3>
        
        <dl>
            <dt>Artikelbild:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Mit dem Artikelbild kannst du einen Artikel eine zusätzliche Dekoration, optische
                Beschreibung, etc. geben. Die Position und Größe des Artikelbildes kann über das Artikel-Template festgelegt werden. Über den Button rechts neben dem
            Eingabefeld kannst du ein bereits hochgeladenes Bild auswählen und weitere Bilder hochladen.</dd>
            <dt>Quellenverzeichnis:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Der Inhalt dieses Feldes wird durch den Template-Tag "{{sources}}" dargestellt. Hier kannst du Links zu deinen Informationsquellen,
                Quellen von Bildern, Videos, etc. oder zu weiterführenden Informationen angeben. Links werden so weit es geht automatisch in HTML-Links umgewandelt.</dd>
            <dt>Tweet erzeugen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diese Option kann die Erzeugung eines Tweets bei aktiver Twitter-Verbindung manuell
                deaktiviert werden, wenn sie in den Systemoptionen aktiviert wurde.</dd>
            <dt>Twitter-Beitrag-Text:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über dieses Textfeld kann das Standard-Template für einen Beitrag bei Twitter
                überschrieben und durch einen eigenen Text ersetzt werden. Der Inhalt dieses Feldes wird nicht gespeichert.</dd>
            <dt>Artikel freischalten:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Mittels dieser Option kannst du einen neuen Artikel verfassen und zu einem bestimmten
                Zeitpunkt automatisch veröffentlichen lassen. Der Zeitpunkt kann maximal zwei Monate in der Zukunft liegen.</dd>
            <dt>Artikel als Entwurf speichern:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Wird diese Option aktiviert, so wird der Artikel beim Speichern nicht als
                Entwurf abgelegt. Entwürfe werden nicht sofort veröffentlicht, sondern sind nur für angemeldete Benutzer sichtbar
                und können vor der Veröffentlichung noch bearbeitet werden.</dd>
            <dt>Artikel pinnen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">"Gepinnte" Artikel werden im Frontend vor allen anderen verfügbaren Artikeln angezeigt, auch
                auch wenn das Datum ihrer Veröffentlichung vor neueren Artikeln liegt.</dd>
            <dt>Kommentare aktiv:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diese Option kann das Kommentar-System für einen einzelnen Artikel gesteuert werden.
                ist die Option nicht aktiv, so können keine Kommentare auf der Artikel verfasst werden.</dd>
            <dt>Artikel archivieren:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Bestehende Artikel können über diese Option in's Archiv verschoben werden bzw.
                herausgenommen werden.</dd>
            <dt>Autor ändern:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer mit entsprechenden Rechten können hierüber den Verfasser eines Artikeln ändern.</dd>
            <dt>Geteilte Inhalte:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Sofern das Zählen von Klicks auf die Share-Buttons aktiviert wurde, wird in diesem Bereich
            die aktuelle Anzahl pro Modul angezeigt.</dd>
        </dl>
 
        <p>In FanPress CM kannst du über den <strong>&lt;readmore&gt;</strong>-Tag ein Stück Text einfügen, das beim Aufruf der Seite
        nicht angezeigt wird. (bspw. für Spoiler, etc.)</p>

        <h3>Register</h3>

        <p>Der Artikel-Editor kann am oberen Rand bis zu drei Tabs enthalten.</p>

        <dl>
            <dt>Artikel-Editor:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Dieser Tab wird immer angezeigt und beinhaltet den Editor an sich.</dd>
            <dt>Kommentare:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Dieses register beinhaltet Auflistung aller Kommentare, welche zu zum ausgewählten Artikel
            geschrieben wurden. Die Liste bietet dir die Möglichkeit, einzelne Kommentare zu löschen. Über einen Klick auf den Namen des
            Verfassern kannst du in einem einfachen Editor die Kommentare bearbeiten, freischalten, auf privat setzen, etc. Den Zugriff auf
            die Kommentare können du über die Berechtigungen geregelt werden.</dd>
            <dt>Revisionen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">FanPress CM besitzt ein Revisions-System, d. h. bei Änderungen wird der vorherige Zustand
            gesichert und kann jederzeit wiederhergestellt werden. Die Revisionen können über die Systemeinstellungen (de)aktiviert werden.
            Eine Liste aller Revisionen findest du über den entsprechenden Reiter im Editor. Dort kannst du jede Revision einzeln aufrufen
            bzw. den aktuelle Artikel auf eine Revision zurücksetzen.</dd>
        </dl>

        <h3>Bild in Artikel einfügen</h3>

        <p>Um den Pfad eines Bildes direkt in den <em>Bild einfügen</em>-Formular zu kopieren, klicke auf die Buttons
        <strong>Thumbnail-Pfad in Quelle einfügen</strong> bzw. <strong>Datei-Pfad in Quelle einfügen</strong>
        zwischen dem Thumbnail und den Meta-Informationen des jeweiligen Bildes, je nachdem was du nutzen möchtest.</p>

        <p>Alternativ mache in der Dateiliste einen Rechtsklick auf den Bild- und/oder Thumbnail öffnen Button. Wähle nun im Kontext-Menü des
        jeweiligen Browsers <strong>Link-Adresse kopieren / Verknüpfung kopieren / o. ä.</strong>. Füge den Pfad anschließend in das Feld <em>Quelle</em> im Editor
        ein. Im HTML-Editor kannst du auch einfach anfangen, den Dateinamen einzutippen. Hier öffnet sich dann eine
        Autovervollständigung. In TinyMCE steht im Bild einfügen Dialog auch ein Punkt <strong>Image List</strong> zur Verfügung.</p>
        ]]>
    </chapter>
    <chapter ref="HL_ARTICLE_EDIT">
        <![CDATA[
        <p>Im Bereich <b>Artikel verwalten</b> kannst findest du alle gespeicherten Artikel in FanPress CM.</p>
        
        <h3>Bereiche</h3>
        
        <dl>
            <dt>Alle Artikel:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Diese Liste umfasst alle verfassten Artikel, inkl. aktiver und archivierter Artikel,
            sowie Entwürfe.</dd>
            <dt>Aktive Artikel:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Diese Liste umfasst ausschließlich Artikel, welche aktiv sind und entsprechend auf deiner
            Webseite angezeigt werden sowie Entwürfe.</dd>
            <dt>Archivierte Artikel:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Hier werden all diejenigen Artikel aufgeführt, welche archiviert wurden.</dd>
            <dt>Papierkorb:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Sofern aktiv ist diese Option aktiv. Hier findest du eine Übersicht aller gelöschten Artikel.
            Du kannst diese hier wieder herstellen oder vollständig löschen.</dd>
        </dl>
        
        <p>Die verfügbaren Eigenschaften werden im Artikel-Editor näher beschrieben.</p>

        <h3>Aktionen</h3>
        
        <dl>
            <dt>Bearbeiten:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über die Massenbearbeitung können alle ausgewählten Artikel auf einmal bearbeitet werden.
            Die auswählbaren Optionen entsprechen denen im Artikel-Editor.</dd>
            <dt>Suche und Filter:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diesen Button kannst du mithilfe eines Dialogs die angezeigten Artikel anhand
            verschiedener Kriterien weiter eingrenzen. Über die Hauptnavigation kannst du bereits eine Vorauswahl treffen, welche Artikel
            dir angezeigt werden sollen.</dd>
            <dt>Neuen Tweet erzeugen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Für den bzw. die ausgewählten Artikel neue Posts bei Twitter erzeugen, wenn Verbindung
            zu Twitter eingerichtet wurde.</dd>
            <dt>Löschen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Den bzw. die ausgewählten Artikel löschen.</dd>
            <dt>Elemente wiederherstellen / Papierkorb leeren:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Diese Optionen stehen im Papierkorb zur Verfügung. Die ausgewählten Artikel aus dem
            Papierkorb können hierüber wiederhergestellt oder endgültig gelöscht werden.</dd>
        </dl>
        ]]>
    </chapter>
    <chapter ref="HL_COMMENTS_MNG">
        <![CDATA[
        <p>Im Bereich <b>Kommentare</b> erhältst du - unabhängig von den Artikeln - eine generelle Übersicht über alle
            geschriebenen Kommentare. Hier besteht die Möglichkeit, alle Kommentare zu löschen, ent/sperren, etc.</p>
        <p>Willst du nur die Artikel zu einem bestimmten Artikel anzeigen lassen, geht das wie gewohnt über die Liste
            auf dem Kommentar-Tab im Artikel-Editor.</p>
        
        <h3>Eigenschaften</h3>
        
        <dl>
            <dt>Kommentar ist privat:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Private Kommentare werden nicht öffentlich angezeigt, sondern sind nur
            für Benutzer innerhalb von FanPress CM sichtbar.</dd>
            <dt>Kommentar ist genehmigt:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Genehmigte Kommentare werden öffentlich angezeigt und können von
            deinen Besuchern gelesen und beantwortet werden. Nicht genehmigte Kommentare verhalten sich wie
            private Kommentare und sind nicht sichtbar. Diese Funktion kann in den Systemeinstellungen deaktiviert
            werden.</dd>
            <dt>Kommentar ist Spam:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Kommentare, welche als Spam markiert wurden, werden nicht öffentlich
            angezeigt. Ihre Daten werden zur Verbesserung der Spam-Erkennung genutzt, sofern du sie nicht löscht.</dd>
            <dt>Kommentar zu Artikel mit ID verschieben:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Die ausgewählten Kommentare zur eingetragenen Artikel-ID
                        verschieben. Das Eingabefeld unterstützt die Suche nach Artikeln mittel Autovervollständigung.</dd>
        </dl>

        <h3>Aktionen</h3>
        
        <dl>
            <dt>Bearbeiten:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über die Massenbearbeitung können alle ausgewählten Kommentare auf einmal bearbeitet werden.
            Die auswählbaren Optionen entsprechen denen im Kommentar-Editor.</dd>
            <dt>Suche und Filter:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diesen Button kannst du mithilfe eines Dialogs die angezeigten Kommenare anhand
            verschiedener Kriterien weiter eingrenzen.</dd>
            <dt>Löschen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Den bzw. die ausgewählten Kommentare löschen.</dd>
            <dt>Elemente wiederherstellen / Papierkorb leeren:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Diese Optionen stehen im Papierkorb zur Verfügung. Die ausgewählten Kommentare aus dem
            Papierkorb können hierüber wiederhergestellt oder endgültig gelöscht werden.</dd>
        </dl>
        
        <h3>Kommentar-Editor</h3>
        
        <p>Der Editor bietet genau wie der Artikel-Editor die Auswahl zwischen TinyMCE und CodeMirror, besitzt jedoch nicht alle Funktionen des Artikel-Editors. Oberhalb des Editors
        werden noch zusätzliche Informationen angezeigt, u. a. von welcher IP-Adresse der Kommentar geschrieben wurde. Diese Information kann zur Vermeidung von Spam, bei Straftaten, etc.
        wichtig sein.</p>
        
        <p>Aus Datenschutz-Gründen wird die IP-Adresse mittels den Cronjob <em>IP-Adressen aus Kommentaren anonymisieren</em> per default einmal im Monat anonymisiert. Die Anonymisierung
        erfolgt nicht für Kommentare, welche als Spam eingestuft wurden, da entsprechende Kommentare später auch zur Spam-Erkennung herangezogen werden.</p>
        
        ]]>
        
    </chapter>
    <chapter ref="HL_FILES_MNG">
        <![CDATA[
        <p>Im <b>Dateimanager</b> kannst du Bilder hochladen, welche du in deinen Artikeln verwendet willst. Eine vereinfachte Ansicht lässt
            sich auch direkt aus dem Artikel-Editor heraus aufrufen. Er zeigt neben einem Vorschau-Bild noch einige zusätzliche Informationen zur
            hochgeladenen Datei an.</p>

        <dl>
            <dt>Suche und Filter:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diesen Button kannst du mithilfe eines Dialogs die angezeigten Artikel anhand
            verschiedener Kriterien weiter eingrenzen. Über die Hauptnavigation kannst du bereits eine Vorauswahl treffen, welche Artikel
            dir angezeigt werden sollen.</dd>
            <dt>Thumbnails erzeugen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Für ausgewählte Dateien kann das Thumbnial neu erzeugt werden.</dd>
            <dt>Löschen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Die ausgewählten Dateien können gelöscht werden. Wichtig! Für Dateien existiert kein Papierkorb.</dd>
            <dt>Umbenennen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über den Button kann die Datei umbenannt werden, die Dateiendung muss dabei nicht angehangen werden.</dd>
            <dt>Thumbnail öffnen:</strong> (nur Dateimanager)</dt>
            <dd class="fpcm-ui-padding-md-bottom">Öffnen des Thumbnails.</dd>
            <dt>Bild öffnen:</strong> (nur Dateimanager)</dt>
            <dd class="fpcm-ui-padding-md-bottom">Öffnen des eigentlichen Bildes.</dd>
            <dt>Artikel-Bild festlegen:</strong> (nur Editor)</dt>
            <dd class="fpcm-ui-padding-md-bottom">Ausgewählte Datei als Artikel-Bild festlegen.</dd>
            <dt>Thumbnail-URL einfügen:</strong> (nur Editor)</dt>
            <dd class="fpcm-ui-padding-md-bottom">Thumbnail-URL der ausgewählten Datei in Dialog übernehmen.</dd>
            <dt>Bild-URL einfügen:</strong> (nur Editor)</dt>
            <dd class="fpcm-ui-padding-md-bottom">Bild-URL der ausgewählten Datei in Dialog übernehmen.</dd>
            <dt>Karten / Liste:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diese Auswahl kann die Darstellung des Dateimanagers angepasst werden,
            die Optionen können auch über die Systemeinstellungen bzw. das Profil angepasst werden.</dd>
        </dl>
        
        <p>Zum Upload von Dateien bietet der Dateimanager zwei Methoden: die klassische Version mittels HTML-Formularen und für ältere Browser
        zu empfehlen ist. Alternativ steht der - standardmäßig aktive - Dateimanager auf Basis von jQuery zu Verfügung.</p>
        ]]>
    </chapter>
    <chapter ref="HL_PROFILE">
        <![CDATA[
        <p>Das eigene <b>Profil</b> können alle Benutzer über das Profil-Menü oben rechts aufrufen. Über den Button <strong>Zurücksetzen</strong>
        können die Einstellungen auf die systemweiten Vorgaben zurücksetzen.</p>
        
        <h3>Profil</h3>
        
        <dl>
            <dt>Angezeigter Name:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Name, welcher öffentlich angezeigt wird. Wird nicht für den Login verwendet.</dd>
            <dt>Benutzername:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Dein Name für den Login. Deinen Benutzernamen kannst du nicht selbst ändern. Wende dich hierfür an einen Administrator.</dd>
            <dt>Passwort:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Zeichenkette welches für den Login verwendet wird. Neben dem Eingabefeld findest du den Button <strong>Password generieren</strong>.
            Über diesen kannst du eine zufällige Zeichenkette erzeugen lassen und als Passwort abspeichern.</dd>
            <dt>E-Mail-Adresse:</dt>
            <dd class="fpcm-ui-padding-md-bottom">E-Mail-Adresse für Benachrichtigungen, ein neu gesetztes Passwort, etc.</dd>
            <dt>Biografie / Sonstiges:</strong> (optional)</dt>
            <dd class="fpcm-ui-padding-md-bottom">Kurzer Info-Text zum Autor, der in den News angezeigt werden kann.</dd>            
            <dt>Avatar:</strong> (optional)</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer-Avatar, Dateiname entspricht dem Muster <em>benutzername.jpg/png/gif/bmp</em></dd>
            <dt>Zwei-Faktor-Authentifizierung:</strong> (optional)</dt>
            <dd class="fpcm-ui-padding-md-bottom">Die Zwei-Faktor-Authentifizierung bietet einen zusätzlichen Schutz deines Logins gegen Fishing, Bots und ähnliches. Die Nutzung ist
            optional und kann durch einen Administrator bei Bedarf aktiviert werden. Der zweite Faktor zum Login wird mittels der App "Google Authenticator" auf deinem Smartphone
            realiisert. Zur Aktivierung der Zwei-Faktor-Authentifizierung scanne den angezeigten QR Code mit deinem Smartphone mit der App. Trage im Anschluss den ersten Zahlencode
            in das Eingabefeld ein und speicher den Vorgang.</dd>
        </dl>

        <h3>Benutzereinstellungen</h3>
        <dl>
            <dt>Zeitzone:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Zeitzone für Datums- und Zeit-Angaben.</dd>
            <dt>Sprache:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Sprach-Einstellung für das FanPress-ACP.</dd>
            <dt>Datum- und Zeitanzeige:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Muster, in welcher Art Datums- und Zeitangaben dargestellt werden.</dd>
            <dt>Anzahl Elemente pro Seite im ACP:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Anzahl an dargestellten Elementen pro Seite im ACP</dd>
            <dt>Standard-Schriftgröße im Editor:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Schriftgröße, die standardmäßig im Artikel-Editor genutzt wird</dd>
            <dt>jQuery Dateiupload verwenden:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Nutzung des AJAX- oder HTML-Uploads</dd>
            <dt>Dateimanager-Ansicht:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diese Auswahl kann die Darstellung des Dateimanagers angepasst werden,
            die Optionen können auch über die Systemeinstellungen bzw. das Profil angepasst werden.</dd>
        </dl>

        ]]>
    </chapter>
    <chapter ref="HL_OPTIONS">
        <![CDATA[
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
        ]]>
    </chapter>
    <chapter ref="HL_OPTIONS_USERS">
        <![CDATA[

        <p>Mit den entsprechenden Rechten können Benutzer und Benutzer-Rollen verwaltet werden.</p>

        <h3>Benutzer</h3>
        
        <ul>
            <li>Über Benutzer wird der Zugriff auf den Administratonsbereich gesteuert, sowie dokumentiert wer welchen Artikel,
            Kommentar, etc. verfasst oder bearbeitet hat.</li>
            <li>Benutzer können deaktiviert werden. Dabei wird der Login gesperrt und somit der Zugriff auf den
            Administratonsbereich gesperrt. Die erstellten Artikel, etc. bleiben erhalten. Dies kann nützlich sein, wenn
            der Benutzer das Team deiner Seite verlassen hat, aus ihm ausgeschlossen wurde oder der Account irgendwie
            missbraucht wurde.</li>
            <li>Die änderbaren Informationen in den Benutzern entsprechen denen im Benutzer-Profil. Die von einem Benutzer
            getroffenen Einstellungen können hier auf die systemweiten Einstellungen zurückgesetzt oder angepasst werden.</li>

        </ul>

        <h3>Benutzer-Rollen</h3>
        
        <p>Ein Benutzer ist immer Mitglied einer Rolle, über deren Berechtigungen der Zugriff des Benutzers auf bestimmte Funktionen
        gesteuert wird.</p>
        
        <h3>Berechtigungen</h3>

        <p>Benutzer mit entsprechenden Rechten können hier die Zugriffsrechte auf verschiedene Dinge von FanPress CM ändern und
        den Zugriff einschränken. Der Bereich sollte nur von Administratoren nutzbar sein! Der Rolle "Administrator" kann der
        Zugriff auf die Rechte-Einstellungen nicht verweigert werden.</p>
        
        <dl>
            <dt>Artikel schreiben</dt>
            <dd class="fpcm-ui-padding-md-bottom">Funktion zum Artikel verfassen freigeben</dd>
            <dt>Eigene Artikel Bearbeiten</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann nur eigene Artikel bearbeiten</dd>
            <dt>Aktive Artikel Bearbeiten</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann aktive Artikel bearbeiten</dd>
            <dt>Artikel löschen</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann Artikel löschen</dd>
            <dt>Artikel archivieren und im Archiv bearbeiten</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann Artikel archiviert und danach noch bearbeiten</dd>
            <dt>Artikel müssen freigeschalten werden</dt>
            <dd class="fpcm-ui-padding-md-bottom">Artikel der Benutzer müssen vor der Veröffentlichung geprüft werden</dd>
            <dt>Revisionen verwalten</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer können Revisionen löschen/wiederherstellen</dd>
            <dt>Artikel-Autor ändern</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer können Autor eines Artikels ändern</dd>
            <dt>Artikel in Masse bearbeiten</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer können Artikel in Masse bearbeiten</dd>
            <dt>Kommentare auf eigene Artikel bearbeiten</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann nur Kommentare auf eigene Artikel
            bearbeiten</dd>
            <dt>Kommentare auf alle Artikel bearbeiten</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann nur Kommentare auf alle Artikel
            bearbeiten</dd>
            <dt>Kommentare löschen</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann Kommentare löschen</dd>
            <dt>Kommentare genehmigen</dt>
            <dt>Kommentare auf "Privat" setzen</dt>
            <dd class="fpcm-ui-padding-md-bottom">Der Benutzer kann den Kommentar-Status auf
            für Spam, Genehmigt und Privat ändern.</dd>
            <dt>Kommentare zu anderem Artikel verschieben</dt>
            <dd class="fpcm-ui-padding-md-bottom">Kommentare können vom aktuellen zu einem anderen
            Artikel verschieben</dd>
            <dt>Kommentare in Masse bearbeiten</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer können Kommentrae in Masse bearbeiten</dd>
            <dd class="fpcm-ui-padding-md-bottom"></dd>
            <dt>Systemeinstellungen verwalten</dt>
            <dd class="fpcm-ui-padding-md-bottom">Diese Berechtigung legt zentral fest, ob der Benutzer auf
            die Systemeinstellungen zugreifen kann.</dd>
            <dt>Benutzer verwalten</dt>
            <dt>Benutzer-Rollen verwalten</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann Benutzer und Rollen verwalten</dd>
            <dt>Kategorien verwalten</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann neue Kategorien anlegen oder
            bestehende bearbeiten/ löschen</dd>
            <dt>Berechtigungen verwalten</dt>
            <dd class="fpcm-ui-padding-md-bottom">Hierüber kann geregelt werden, ob ein Benutzer
            die Berechtigungen ändern kann. Für die Gruppe "Administratoren" kann dieses Recht
            nicht entzogen werden.</dd>
            <dt>Templates verwalten</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann die Tempaltes und Vorlagen bearbeiten</dd>
            <dt>Smileys verwalten</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann Smileys neu definieren und bestehende löschen</dd>
            <dt>Updates durchführen</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann verfügbare Updates installieren.</dd>
            <dt>System-Logs verwalten</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann die Systemlogs einsehen und bei Bedarf bereinigen</dd>
            <dt>Cronjobs verwalten</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann Cronjobs verwalten</dd>
            <dt>Backups verwalten</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann Datenbank-Backups verwalten</dd>
            <dt>Textzensur verwalten</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann Begriffe der Textzensur verwalten</dd>
            <dt>IP-Adressen verwalten</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann IP-Adress-Sperren verwalten</dd>
            <dt>Module aktivieren/deaktivieren</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann aktivieren und deaktivieren</dd>
            <dt>Module installieren</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann installieren und aktualisieren</dd>
            <dt>Module deinstallieren</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann deinstallieren</dd>
            <dt>Dateimanager ist sichtbar</dt>
            <dd class="fpcm-ui-padding-md-bottom">Der Dateimanager ist für die Benutzer sichtbar und kann
            über den Editor aufgerufen werden.</dd>
            <dt>Dateien hochladen</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann neue Dateien hochladen</dd>
            <dt>Dateien löschen</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann Dateien löschen</dd>
            <dt>Thumbnails erzeugen</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann Thumbnails für vorhandene
            Dateien neu erzeugen</dd>
            <dt>Dateien umbenennen</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann Dateien umbenennen</dd>
        </dl>
        ]]>
    </chapter>
    <chapter ref="HL_OPTIONS_IPBLOCKING">
        <![CDATA[

        <ul>
            <li>Benutzer mit Rechten zur Änderung der Systemeinstellungen können hier IP-Adressen sperren oder Sperren wieder aufheben.
            (z. B. wegen Spam)</li>
            <li>Durch die Nutzung von Proxy-Servern, privaten Netzwerken, dynamischen IP-Adressen, etc. kann es passieren, dass von der
            Sperrung einer IP-Adresse nicht nur ein einzelner, sondern viele Nutzer (ungewollt) betroffen sind.</li>
            <li>Pro Eintrag kann festgelegt werden, für welchen Bereich von FanPress CM die Sperren gelten soll.</li>
            <li>Durch entsprechende Muster können ganze IP-Adress-Bereiche gesperrt werden, Beispiele werden sind im Editor angezeigt. Dies
            kann nötig werden, wenn zum Beispiel Bots (Spam, Suchmaschinen, etc.) häufig ihre IP-Adresse wechseln.</li>
        </ul>
        
        <dl>
            <dt>Keine Kommentare schreiben</dt>
            <dd class="fpcm-ui-padding-md-bottom">Der Besucher mit der angegebenen IP-Adresse kann keine
            Kommentare verfassen, wenn diese nicht für den Artikel oder genrell deaktiviert sind.</dd>
            <dt>Kein ACP-Login</dt>
            <dd class="fpcm-ui-padding-md-bottom">Der Besucher mit der angegebenen IP-Adresse kann sich nicht in FanPress-CM
                einloggen bzw. hat keinen Zugriff auf die Login-Maske.</dd>
            <dt>Kein Frontend-Zugriff</dt>
            <dd class="fpcm-ui-padding-md-bottom">Dem Besucher mit der angegebenen IP-Adresse werden veröffentlichte Artikel, Kommentare,
            etc. nicht angezeigt. Der weitere Zugriffe auf deine Seite kann von anderen Faktoren abhängen.</dd>
        </dl>
        ]]>
    </chapter>
    <chapter ref="HL_OPTIONS_WORDBAN">
        <![CDATA[

        <ul>
            <li>Die Textzensur ermöglicht es, bestimmte Wörter, Textgruppen oder Zeichenketten für die Verwendung in Artikeln, Kommentaren, etc. zu
            sperren.</li>
            <li>Hierüber kann vermieden werden, dass Beleidigungen, Spam, usw. öffentlich auf der Seite veröffentlicht werden und/oder
            falsche/unvollständige Angaben erneut veröffentlicht werden.</li>
        </ul>
        
        <dl>
            <dt>Text ersetzen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Ist diese Checkbox markiert, so wird die entsprechende Textstelle durch den angegeben
            Text ersetzt. Die Textzensur wird beim Erstellen von Kommentaren, Artikeln, Kategorien, Benutzern und Benutzer-Rollen ausgeführt.</dd>
            <dt>Artikel muss überprüft werden:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Durch diese Option wird beim Speichern eines Artikels geprüft, ob die entsprechende Phrase
            enthalten ist. In diesem Fall wird - unabhängig von den eingestellten Berechtigungen - der Artikel markiert, dass er freigeschalten
            werden muss.</dd>
            <dt>Kommentar muss freigeschalten werden:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Analog zur Option <em>Artikel muss überprüft werden</em>, allerdings wird hier der
            entsprechende Kommentar markiert, dass er manuell freigegeben werden muss.</dd>
        </dl>
        ]]>
    </chapter>
    <chapter ref="HL_CATEGORIES_MNG">
        <![CDATA[
        <ul>
            <li>Kategorien ermöglichen die Einsortierung von Artikeln nach bestimmten Stichworten bzw. Themengebieten. Insbesondere bei der Suche
            nach Artikeln ermöglicht dies eine Beschleunigung der Suche.</li>
            <li>Benutzer mit entsprechenden Rechten können neue Kategorien anlegen, sowie bestehende ändern oder auch löschen.</li>
            <li>Der Zugriff auf Kategorien kann auf bestimmte Benutzergruppen beschränkt werden.</li>
        </ul>
        
        <dl>
            <dt>Kategorie-Name:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Der Kategorie-Name wird im Artikel-Editor angezeigt und kann zudem über den Platzhalter
            <em>{{categoryTexts}}</em> im Frontend ausgegeben werden.</dd>
            <dt>Kategorie-Icon:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Hierfür kann eine Bid-Datei auf einem externen Server oder lokal auf deinem Webspace verwendet
            werden. In beiden Fällen sollte die vollständige URL angegeben werden. Die Anzeige der vergebenen Icons erfolgt im Frontend über
            den Platzhalter <em>{{categoryIcons}}</em>.</dd>
            <dt>Verfügbar für Rollen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diese Einstellung wird festgelegt, welche Benutzer eine bestimmte Kategorie nutzen kann.</dd>
        </dl>
        ]]>
    </chapter>
    <chapter ref="HL_OPTIONS_TEMPLATES">
        <![CDATA[
        <p>Benutzer mit entsprechenden Rechten können die Templates zur Ausgabe von Artikeln, Kommentaren, etc. bearbeiten.
        Für eine bessere Übersicht bietet der Template-Editor Syntax-Highlighting und eine Liste der verfügbaren Platzhalter.</p>

        <h3>Templates</h3>
        
        <dl>
            <dt>Artikel-Liste:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Template für Anzeige von Artikeln in der Artikel-Liste.</dd>
            <dt>Artikel-Einzel-Ansicht:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Template für Anzeige eines einzelnen Artikels inkl. dessen Kommentaren, dem
            Kommentar-Formular, etc. Dieser Tab wird nicht angezeigt, wenn für <em>Artikel-Liste</em> und <em>Artikel-Einzel-Ansicht</em>
            das gleiche Template genutzt wird.</dd>
            <dt>Kommentar:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Template für die Anzeige eines einzelnen Kommentarsim Frontend.</dd>
            <dt>Kommentar-Formular:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Template für das Formular zum Verfassen eines Kommentars.</dd>
            <dt>Share-Buttons:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Template für die Darstellung der Sharebuttons in Artikeln.</dd>
            <dt>Latest News:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Template für die einzelnen Zeilen in den "Latest News".</dd>
            <dt>Tweet:</dt>
            <dd class="fpcm-ui-padding-md-bottom">HTML-freies Template für automatisch erzeugte Einträge bei Twitter (Tweets).</dd>
            <dt>Vorlagen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">HTML-Vorlagen zu Nutzung im Artikel-Editor. (TinyMCE bzw. HTML-Ansicht).</dd>
        </dl>
        
        <h3>Editor</h3>
        
        <dl>
            <dt>Verwendbare Platzhalter:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Die Verwendbaren Platzhalter können durch einen Klick auf das Plus-Icon in das ausgewählte
            Template eingefügt werden. Die Platzhalter werden später durch die entsprechenden Inhalte ersetzt.</dd>
            <dt>Erlaubte HTML-Tags:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Die erlaubte HTML-Tags umfasst die HTML-Elemente, welche in Templates genutzt werden können.
            Alle anderen Templates werden beim Speichern gefiltert.</dd>
            <dt>Editor:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Der Editor basiert ebenfalls auf CodeMirror und arbeitet ähnlich wie der Artikel-Editor.</dd>
            <dt>Vorschau anzeigen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Der Button "Vorschau anzeigen" ermöglicht es, vom im Editor vorhandenen Template-Inhalt
            eine Vorschau anzuzeigen lassen.</dd>
        </dl>
        
        <h3>Vorlagen</h3>
        
        <ul>
            <li>Vorlagen sind HTML-Dateien, deren Inhalt Inhalt im Artikel-Editor verwendet werden kann.</li>
            <li>Hiermit können wiederkehrende Artikel-Inhalte gesichert und immer in der gleichen Art wiederverwendet werden.</li>
            <li>Die Vorlagen können durch den Klick auf den Button <strong>Bearbeiten</strong> über einen CodeMirror-basierten Editor
            angepasst werden. Weitere können bei Bedarf ins System hochgeladen werden.</li>
        </ul>

        ]]>
    </chapter>
    <chapter ref="HL_OPTIONS_SMILEYS">
        <![CDATA[
        <p>Benutzer mit den entsprechenden Rechten können die nutzbaren Smileys verwalten.</p>
        <dl>
            <dt>Smiley-Code:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Der Smiley-Code wird in Artikeln und Kommentaren als Platzhalter für die entsprechende
            Grafik verwendet. Die Ersetzung erfolgt beim Parsen eines Artikels bzw. Kommentars im Frontend. Jeder Smiley-Code kann nur
            einmal angelegt werden.</dd>
            <dt>Dateiname:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Dieses Feld umfasst nur den Dateiname der entsprechenden Smiley-Grafik, welche unter
            <em>/data/smileys</em> abgelegt wurden.</dd>
        </dl>
        ]]>
    </chapter>
    <chapter ref="HL_CRONJOBS">
        <![CDATA[
        <ul>
            <li>Cronjobs sind Aufgaben, welche in regelmäßgen Abständen automatisch durch FanPress CM im Hintergrund ausgeführt werden.</li>
            <li>Die Cronjob-Übersicht zeigt eine Liste aller verfügbaren Cronjobs, wenn sie zuletzt ausgeführt wurden, sowie den Zeitpunkt der
            nächsten voraussichtlichen Ausführung.</li>
            <li>Die Häufigkeit der Ausführung eines Cronjobs kannst du anpassen, indem der Wert für das Intervall-Zeit angepasst wird.</li>
            <li>Beachte bei der Änderung des Intervals, dass Cronjobs u. U. für erhöhte Serverlast führen kann.</li>
        </ul>

        <dl>
            <dt>Artikel-Revisionen bereinigen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Wurde die Option <em>Alte Revisionen löschen, wenn älter als</em> auf einen Wert ungleich <em>Nie</em> gesetzt,
            bereinigt dieser Cronjob die Artikel-Revisionen im eingestellten Interval. Standardmäßig erfolgt dies einmal im Monat.</dd>
            <dt>Dateiindex neu aufbauen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Standardmäßig einmal pro Tag wird der Dateiindex, d. h. die Informationen über hochgeladene Bilder
            neu aufgebaut. Hierbei werden gelöschte Dateien entfernt und ggf. neu hochgeladene Bilder erfasst, sollte dies beim Upload nicht automatisch erfolgt sein.</dd>
            <dt>Dateimanager-Thumbnails erzeugen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Dieser Cronjob erzeugt die Vorschaubilder im Dateimanager neu. (Standard: einmal pro Woche)</dd>
            <dt>geplante Artikel veröffentlichen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Dieser Cronjob wird im Standard aller zehn Minuten ausgeführt und sorgt für die automatische Veröffentlichung von
            geplanten Artikeln. Wurde dieser Cronjob deaktiviert, müssen alle Artikel - auch solche die im Editor oder den Listen als geplant angezeigt werden - manuell
            veröffentlicht werden.</dd>
            <dt>IP-Adressen aus Kommentaren anonymisieren:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diesen Cronjob erfolgt die regelmäßig Anonymisierung der IP-Adressen in Kommentaren. Hierdurch wird die Personenbindung
            aufgehoben, allerdings lässt sich dann auch nicht mehr erkennen, woher der Kommentar genau kam. Die Ausführung erfolgt per default einmal im Monat.</dd>
            <dt>Prüfung auf Updates:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Die Prüfung auf System- und Modul-Updates wird durch diesen Cronjob durchgeführt. Die Ausführung erfolgt nur, wenn FanPress CM
            sich zum Update-Server verbinden kann. Die Prüfung erfolgt standardmäßig einmal täglich.</dd>
            <dt>System-Datenbank sichern:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Die Sicherung der System-Datenbank dient dieser Cronjob. Die erzeugten backups werden im Verzeichnis <em>/data/dbdump</em> abgelegt
            und können über den Backup-Manager verwaltet werden. Im Standard erfolgt die Sicherung einmal pro Woche. Bei hohem Artikel-Aufkommen sollte das Interval entsprechend
            reduziert werden.</dd>
            <dt>Systemlogs leeren:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Die Log-Dateien können unter Umständen sehr groß werden, daher werden diese (im Standard monatlich) auf ihre Dateigröße geprüft und
            bei Überschreitung einer Größe von 1 MB bereinigt. Das vorherige Log wird gespeichert. Die Bereinigung des Session-Logs erfolgt unabhängig von der Anzahl der Einträge.</dd>
            <dt>temporäre Dateien aufräumen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Bei Updates, sowie in der täglichen Arbeit fallen immer wieder temporäre Dateien an, welche unter Umständen nicht sofort bereinigt werden
            (können). Dieser Cronjob prüft auf entsprechende Dateien und löscht diese; im Standard einmal pro Woche.</dd>
        </dl>
        ]]>
    </chapter>
    <chapter ref="HL_LOGS">
        <![CDATA[
        <p>Im Bereich der Systemlogs findest du eine Auflistung aller bisherigen Benutzer-Logins, System-Meldungen von FanPress und
            Fehlermeldungen durch PHP selbst oder der Datenbank. Über den Button <strong>Leeren</strong> kannst du Meldungen, etc. löschen
            lassen.</p>
        
        <dl>
            <dt>Session-Log:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Dieses Protokoll zeigt eine Übersicht über die Logins aller Benutzer, abgesehen von aktuell aktiven Sessions, z. B. deiner eigenen.
            Angezeigt werden alle relevanten Informationen, d. h. wer hat sich wann ein- bzw. ausgeloggt. Externe Logins erfolgten über die Funktionen der FanPress CM-API. Der
            User-Agent enthält Informationen, mit welchem Browser oder Programm der Login erfolgte.</dd>
            <dt>System-Log:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Dieses Log beinhaltet allgemeine Meldungen von FanPress CM, Status-Meldungen und falls nötig Diagnose-Informationen.</dd>
            <dt>Fehler-Log:</dt>
            <dd class="fpcm-ui-padding-md-bottom">In diesem Protokoll werden allen von Fehlern, welche im Betrieb, bei Updates, Änderungen der Systemkonfiguration, etc. auftreten.
            Fatale PHP_Fehler können hier unter Umständen nicht angezeigt werden, da die System-Protokollierung zu diesem Zeitpunkt noch nicht greift. Nicht alle Einträge sind
            zwangsläufig kritisch. Bei Fragen lassen uns einfach eine Nachricht zukommen.</dd>
            <dt>Datenbank-Log:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Das Datenbank-Log enthält weitergehende Informationen zu Ereignissen auf Datenbank-Seite, z. B. fehlschlagende Abfragen, fehlschlagende
            Verbindungen zum Datenbank-Server, etc.</dd>
            <dt>Cron-Log:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Hier werden Status-Informationen, Laufzeiten, etc. zu ausgeführten Cronjobs protokolliert. Dieses Log kann in Abhängigkeit von der
            Cronjob-Konfiguration sehr schnell stark wachsen und sollte daher regelmäßig bereinigt werden.</dd>
            <dt>Paket-Manager-Log:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Das Paket-Manager-Log beinhaltet Status-Informationen zu System-Updates, sowie Installation oder Updates von Modulen.</dd>
        </dl>
        ]]>
    </chapter>
    <chapter ref="HL_BACKUPS">
        <![CDATA[
        <ul>
            <li>Dieser Bereich ermöglicht dir die Verwaltung der automatisch erzeugten Datenbank-Backups. Du kannst diese herunterladen oder löschen.</li>
            <li>Die erzeugten Datenbank-Backups sind gepackte SQL-Dateien, deren Struktur vom verwendeten Datenbank-System abhängt.</li>
            <li>Um ein Backup bei Bedarf wiederherzustellen, kannst du Werkzeuge wie
                <a href="https://www.phpmyadmin.net/" target="_blank">phpMyAdmin</a>, <a href="https://www.adminer.org/de" target="_blank">Adminer</a> or
                <a href="http://phppgadmin.sourceforge.net/doku.php" target="_blank">phpPgAdmin</a> nutzen.
            </li>
        </ul>
        ]]>
    </chapter>
    <chapter ref="HL_MODULES">
        <![CDATA[
        <p>no information yet</p>
        ]]>
    </chapter>
    <chapter ref="SYSTEM_OPTIONS_TWITTER_CONNECTION">
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
    </chapter>
    <chapter ref="ARTICLES_TRASH">
        <![CDATA[
        <p>Im Papierkorb werden Elemente beim Löschen abgelegt, so dass sie bei Bedarf wiederherstellt werden können. Elemente im
        Papierkorb sollten nicht bearbeitet werden.</p>
        <p>Die Papierkörbe werden regelmäßig automatisch geleert.</p>
        <dl>
            <dt>Element wiederherstellen</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diese Option können die ausgewählten Elemente wiederherstellt werden. Dabei wird jeweils
            die letzte Version eines Artikelns bzw. Kommentars wiederhergestellt.</dd>
            <dt>Papierkorb leeren</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über die Auswahl dieser Option wird der Papierkorb komplett geleert. Eine Lösung einzelner
            Elemente ist nicht vorgesehen.</dd>
        </dl>
        ]]>
    </chapter>
    <chapter ref="HL_HELP_SUPPORT">
        <![CDATA[
        <p>Solltest du weitergehende Hilfe bei technischen Problemen brauchen oder Fragen haben, schreiben eine E-mail an
            <em>fanpress@nobody-knows.org</em> oder <em>sea75300@yahoo.de</em>. Alternativ kannst du auch auf der Download-Seite unter
            <a href="https://nobody-knows.org/download/fanpress-cm/">nobody-knows.org</a> einen Kommentar hinterlassen.</p>
        <p>Das Module <em>FanPress CM Support Module</em> kann installiert werden, um einen einfachen, temporären Zugang
            zur Verfügung zu stellen. Beachte bitte, dass bereits bei der Installation einen E-Mail mit den Zugangsdaten versendet
            wird.</p>
        ]]>
    </chapter>
</chapters>
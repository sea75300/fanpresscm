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
            <p>Im <b>Dashboard</b> findest du verschiedene Informationen zu deiner FanPress CM Installation etc. Eigene Dashboard-Container
            können durch Module bzw. neue Datei unter "fanpress/inc/dashboard" erzeugt werden.</p>
            <dl>
                <dt>Zuletzt geschriebene News:</dt>
                <dd class="fpcm-ui-padding-md-bottom">Hier findest eine Übersicht der zuletzt verfassten Artikel.</dd>
                <dt>Zuletzt geschriebene Kommentare:</dt>
                <dd class="fpcm-ui-padding-md-bottom">Hier findest eine Übersicht der zuletzt verfassten Kommentare.</dd>
                <dt>Verfügbare Updates:</dt>
                <dd class="fpcm-ui-padding-md-bottom">Dieser Container beinhaltet Informationen zum Update-Status des Systems und von Modulen.</dd>
                <dt>Systemprüfung:</dt>
                <dd class="fpcm-ui-padding-md-bottom">Diese Box enthält grundlegende Status-Informationen zu deiner Installation.</dd>
                <dt>Statistiken:</dt>
                <dd class="fpcm-ui-padding-md-bottom">In diesem Bereich werden statistische Informationen ausgegeben, u. a. zur Anzahl verfasster
                Artikel oder Kommentare.</dd>
                <dt>Aktuelle FanPress CM News:</dt>
                <dd class="fpcm-ui-padding-md-bottom">Dieser Container beinhaltet Neuigkeiten rund vom FanPress CM, bspw. neue Versionen, künftige Entwicklungen usw..</dd>
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

        <h3>Eigenschaften</h3>
        
        <dl>
            <dt>Artikelbild:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Mit dem Artikelbild kannst du einen Artikel eine zusätzliche Dekoration, optische
                Beschreibung etc. geben. Die Position und Größe des Artikelbildes kann über das Artikel-Template festgelegt werden. Über den Button rechts neben dem
            Eingabefeld kannst du ein bereits hochgeladenes Bild auswählen und weitere Bilder hochladen.</dd>
            <dt>Quellenverzeichnis:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Der Inhalt dieses Feldes wird durch den Template-Tag "{{sources}}" dargestellt. Hier kannst du Links zu deinen Informationsquellen,
                Quellen von Bildern, Videos etc. oder zu weiterführenden Informationen angeben. Links werden so weit es geht automatisch in HTML-Links umgewandelt.</dd>
            <dt>Tweet erzeugen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diese Option kann die Erzeugung eines Tweets bei aktiver Twitter-Verbindung manuell
                deaktiviert werden, wenn sie in den Systemoptionen aktiviert wurde.</dd>
            <dt>Tweet erzeugen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über das Textfeld kann das Standard-Template für einen Beitrag bei Twitter
                überschrieben und durch einen eigenen Text ersetzt werden. Der Inhalt dieses Feldes wird nicht gespeichert.
                Das Dropdown bietet eine Schnellzugriff auf die Template-Platzhalter. Über die Checkbox am Ende kann zudem festgelegt werden,
                ob beim nächsten Speicher-Vorgang der Tweet erzeugt wird oder nicht.
            </dd>
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
            <dd class="fpcm-ui-padding-md-bottom">Bestehende Artikel können über diese Option ins Archiv verschoben werden bzw.
                herausgenommen werden.</dd>
            <dt>Autor ändern:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer mit entsprechenden Rechten können hierüber den Verfasser eines Artikeln ändern.</dd>
            <dt>Geteilte Inhalte und LIkes:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Sofern das Zählen von Klicks auf die Share-Buttons aktiviert wurde, wird in diesem Bereich
            die aktuelle Anzahl pro Artikel angezeigt, diese umfasst sowohl Teilungen bei den verfügbaren sozialen Netzwerken als auch Klicks auf den
            FanPress CM-eigenen "Gefällt mir"-Button. Eine Summe über alle geteilten Inhalte pro Artikel wird in den Artikel-Listen neben der Kommentar-Anzahl angezeigt.</dd>
        </dl>
 
        <p>In FanPress CM kannst du über den <strong>&lt;readmore&gt;</strong>-Tag ein Stück Text einfügen, das beim Aufruf der Seite
        nicht angezeigt wird (bspw. für Spoiler etc.).</p>

        <h3>Register</h3>

        <p>Der Artikel-Editor kann am oberen Rand bis zu drei Tabs enthalten.</p>

        <dl>
            <dt>Artikel-Editor:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Dieser Tab wird immer angezeigt und beinhaltet den Editor an sich.</dd>
            <dt>Erweitert:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Der zweite Tab umfasst die verschiedenen Status-Optionen wie Gepinnt, Entwurf,
            Artikel-Freigabe, Artikelbild usw.</dd>
            <dt>Kommentare:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Dieses Register beinhaltet Auflistung aller Kommentare, welche zum ausgewählten Artikel
            geschrieben wurden. Die Liste bietet dir die Möglichkeit, einzelne Kommentare zu löschen. Über einen Klick auf den Bearbeiten-Button
            kann der entsprechende Kommentare bearbeitet werden (freischalten, auf privat setzen etc.). Der Zugriff auf die Kommentare 
            wird über die Berechtigungen geregelt. Ausführliche Informationen hierzu findest du in der Hilfe den "Kommentare"-Bereichs
            über das Hauptmenü.</dd>
            <dt>Revisionen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">FanPress CM besitzt ein Revisions-System, d. h. bei Änderungen wird der vorherige Zustand
            gesichert und kann jederzeit wiederhergestellt werden. Die Revisionen können über die Systemeinstellungen (de)aktiviert werden.
            Eine Liste aller Revisionen findest du über den entsprechenden Reiter im Editor. Dort kannst du jede Revision einzeln aufrufen
            bzw. den aktuelle Artikel auf eine Revision zurücksetzen.</dd>
        </dl>
        
        <h3>Buttons und Aktionen</h3>
        
        <dl>
            <dt>Löschen-Buttons</dt>
            <dd class="fpcm-ui-padding-md-bottom">Je nach geöffnetem Register werden unterschiedlichen Löschen-Buttons angezeigt. Diese dienen dazu, entsprechende Elemente des
            angezeigten Tabs zu löschen.</dd>
            <dt>Artikel auf Webseite anzeigen</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diesen Button wird der aktuell im Editor geöffnete Artikel im Frontend, d. h. auf deiner Webseite geöffnet.</dd>
            <dt>Kurzlink</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diesen Button ist es bei gespeicherten Artikeln möglich, die URL über den Dienst <a rel="noreferrer,noopener,external" href=http://is.gd>is.gd</a> kürzen
            zu lassen und bei Twitter etc. zu nutzen. Der genutzte Dienst kann über ein Modul-Event geändert werden.</dd>
            <dt>Artikel-Bild anzeigen</dt>
            <dd class="fpcm-ui-padding-md-bottom">Wurde für den Artikel ein Artikel-Bild definiert, so kann dieses über diese Schaltfläche angezeigt werden.</dd>
            <dt>Bearbeiten (Kommentare)</dt>
            <dd class="fpcm-ui-padding-md-bottom">Die Schaltfläche <strong>Bearbeiten</strong> ind er Toolbar des Kommentar-Registers öffnen einen Massenbearbeitung-Dialog analog der
            globalen Kommentar-Liste. Hierüber kommen bestimmte Status der ausgewählten Kommentare verändert werden. Die gleiche Schaltfläche in der Kommentar-Liste
            öffnet des ausgewählten Kommentar in einem Dialog, wo dieser komplett bearbeitet werden kann.</dd>
            <dt>Revision wiederherstellen (Revisionen)</dt>
            <dd class="fpcm-ui-padding-md-bottom">Diese Schaltfläche ermöglicht es, den aktuellen Artikel auf die ausgewählte Artikel-Revision zurückzusetzen. Beim Zurücksetzen wird
            automatisch eine neue Revision des aktuellen Stands erzeugt, bevor die ältere Version wiederhergestellt wird.</dd>
            <dt>Revision öffnen (Revisionen)</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diesen Button kann eine bestimmte Revision geöffnet werden. Du erhälst dann eine Vergleichansicht zwischen der ausgewählten
            Revision (linke Seite) und dem aktuellen Zustand des Artikels (rechte Seite). Der Artikel-Text selbst wird in einer DIFF-Ansicht dargestellt, d. h. Veränderungen werden
            innerhalb des Textes dargestellt.</dd>
            <dt>Zurück zur aktuellen Ansicht (Revisionen)</dt>
            <dd class="fpcm-ui-padding-md-bottom">Wurde eine Revision geöffnet, so gelangst du durch diese Schaltfläche zurück in den aktuellen Artikel.</dd>
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
            dir angezeigt werden sollen. Unterschiedliche Felder können im Bedarfsfall miteinander verknüpft werden oder die Suche
            in speziellen Konstellationen durchgeführt werden. Hierzu dient das linke Dropdown-Feld in der jeweiligen Zeile. Auf der
            rechten Seite einer Zeile wird der jeweiligen Wert angegeben.
            </dd>
            <dt>Neuen Tweet erzeugen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Für den bzw. die ausgewählten Artikel neue Posts bei Twitter erzeugen, wenn Verbindung
            zu Twitter eingerichtet wurde.</dd>
            <dt>Löschen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Den bzw. die ausgewählten Artikel löschen.</dd>
            <dt>Artikel-Cache leeren:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diese Aktion kann bei Bedarf geziehlt der Cache eines einzelnen bzw. der ausgewählten Artikel geleert und somit
            beim Öffnen des Frontends ein erneuten Rendern der entsprechenden Artikel erzwungen werden. Dies ist hilfreich, wenn Änderungen an Artikeln nicht sofort übernommen
            werden.</dd>
        </dl>
        ]]>
    </chapter>
    <chapter ref="HL_COMMENTS_MNG">
        <![CDATA[
        <p>Im Bereich <b>Kommentare</b> erhältst du - unabhängig von den Artikeln - eine generelle Übersicht über alle
            geschriebenen Kommentare. Hier besteht die Möglichkeit, alle Kommentare zu löschen, ent/sperren etc.</p>
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
            verschieben. Das Eingabefeld unterstützt die Suche nach Artikeln mittels Autovervollständigung.</dd>
        </dl>

        <h3>Aktionen</h3>
        
        <dl>
            <dt>Bearbeiten:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über die Massenbearbeitung können alle ausgewählten Kommentare auf einmal bearbeitet werden.
            Die auswählbaren Optionen entsprechen denen im Kommentar-Editor.</dd>
            <dt>Suche und Filter:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diesen Button kannst du mithilfe eines Dialogs die angezeigten Kommentare anhand
            verschiedener Kriterien weiter eingrenzen. Unterschiedliche Felder können im Bedarfsfall miteinander verknüpft werden oder die Suche
            in speziellen Konstellationen durchgeführt werden. Hierzu dient das linke Dropdown-Feld in der jeweiligen Zeile. Auf der
            rechten Seite einer Zeile wird der jeweiligen Wert angegeben.</dd>
            <dt>Löschen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Den bzw. die ausgewählten Kommentare löschen.</dd>
            <dt>Zugehörigen Artikel bearbeiten:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Durch diesen Button gelangst du direkt in Artikel-Editor, in welchem der zum ausgewählten Kommentar zugehörige
            Artikel geöffnet wurde.</dd>
            <dt>Whois:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diesen Button kannst du eine Whois-Abfrage auf die IP-Adresse durchführen, um bspw. den etwaigen Standort
            herauszufinden.</dd>
            <dt>IP-Adresse sperren:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diesen Button kann für die gespeicherte IP-Adresse eine Sperre eingerichtet werden. Hierzu wird das entsprechende
            Recht zu verwalten von IP-Adressen benötigt. Die Sperren können unter <strong>Optionen > IP-Adressen</strong> aufgehoben werden.</dd>
        </dl>
        
        <h3>Kommentar-Editor</h3>
        
        <p>Der Editor bietet genau wie der Artikel-Editor die Auswahl zwischen TinyMCE und CodeMirror, besitzt jedoch nicht alle Funktionen des Artikel-Editors. Oberhalb des Editors
        werden noch zusätzliche Informationen angezeigt, u. a. von welcher IP-Adresse der Kommentar geschrieben wurde. Diese Information kann zur Vermeidung von Spam, bei Straftaten etc.
        wichtig sein.</p>
        
        <p>Aus Datenschutz-Gründen wird die IP-Adresse durch den Cronjob <em>IP-Adressen aus Kommentaren anonymisieren</em> per default einmal im Monat anonymisiert. Die Anonymisierung
        erfolgt nicht für Kommentare, welche als Spam eingestuft wurden, da entsprechende Kommentare später auch zur Spam-Erkennung herangezogen werden.</p>
        
        ]]>
        
    </chapter>
    <chapter ref="HL_FILES_MNG">
        <![CDATA[
        <p>Im <b>Dateimanager</b> kannst du Grafiken hochladen, welche du in deinen Artikeln verwendet willst. Eine vereinfachte Ansicht lässt
            sich auch direkt aus dem Artikel-Editor heraus aufrufen. Er zeigt neben einem Vorschau-Bild noch einige zusätzliche Informationen zur
            hochgeladenen Datei an.</p>

        <dl>
            <dt>Suche und Filter:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diesen Button kannst du mithilfe eines Dialogs die angezeigten Grafiken
            anhand verschiedener Kriterien weiter eingrenzen. Unterschiedliche Felder können im Bedarfsfall miteinander verknüpft werden oder die Suche
            in speziellen Konstellationen durchgeführt werden. Hierzu dient das linke Dropdown-Feld in der jeweiligen Zeile. Auf der
            rechten Seite einer Zeile wird der jeweiligen Wert angegeben.</dd>
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
        
        <p>Zum Upload von Dateien bietet der Dateimanager zwei Methoden: die klassische Version mittels HTML-Formularen. Diese ist für ältere Browser
        zu empfehlen. Alternativ steht der - standardmäßig aktive - Dateimanager auf Basis von jQuery zu Verfügung.</p>
        ]]>
    </chapter>
    <chapter ref="HL_PROFILE">
        <![CDATA[
        <p>Das eigene <b>Profil</b> können alle Benutzer über das Profil-Menü oben rechts aufrufen. Über den Button <strong>Zurücksetzen</strong>
        können die Einstellungen auf die Systemweiten Vorgaben zurücksetzen.</p>
        
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
            <dd class="fpcm-ui-padding-md-bottom">E-Mail-Adresse für Benachrichtigungen, ein neu gesetztes Passwort etc.</dd>
            <dt>Aktuelles Passwort zur Bestätigung eingeben:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Zur Änderung des Passwortes und bestimmter anderer Einstellungen ist eine Bestätigung per Passwort nötig.</dd>
            <dt>Zwei-Faktor-Authentifizierung:</strong> (optional)</dt>
            <dd class="fpcm-ui-padding-md-bottom">Die Zwei-Faktor-Authentifizierung bietet einen zusätzlichen Schutz deines Logins gegen Fishing, Bots und ähnliches. Die Nutzung ist
            optional und kann durch einen Administrator bei Bedarf aktiviert werden. Der zweite Faktor zum Login wird mittels der App "Google Authenticator" auf deinem Smartphone
            realisiert. Zur Aktivierung der Zwei-Faktor-Authentifizierung scanne den angezeigten QR Code mit deinem Smartphone mit der App. Trage im Anschluss den ersten Zahlencode
            in das Eingabefeld ein und speicher den Vorgang.</dd>
        </dl>

        <h3>Erweitert</h3>
        <dl>
            <dt>Biografie / Sonstiges:</strong> (optional)</dt>
            <dd class="fpcm-ui-padding-md-bottom">Kurzer Info-Text zum Autor, der in den News angezeigt werden kann.</dd>            
            <dt>Avatar:</strong> (optional)</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer-Avatar, Dateiname entspricht dem Muster <em>benutzername.jpg/png/gif/bmp</em></dd>
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
            <dt>Container-Positionen zurücksetzen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über den Button können die Positionen der Dashboard-Conatiner
            auf die Standard-Einstellungen zurücksetzen.</dd>
        </dl>

        ]]>
    </chapter>
    <chapter ref="HL_OPTIONS">
        <![CDATA[
        <p>Benutzer mit den entsprechenden Rechten können hier zentrale Einstellungen von FanPress CM ändern. Die hier getroffenen Einstellungen
        gelten grundsätzlich für alle Benutzer, sofern diese nicht in den Benutzer-Einstellungen verändert wurden. Entsprechende Änderungen
        können bei Bedarf im Profil oder der Benutzer-Verwaltung zurückgesetzt werden.</p>
        
        <p>Einige Bereiche besitzen eine <em>Frontend</em>-Box. Die entsprechenden Einstellungen beeinflussen, wie sich FanPress CM
        in den veröffentlichen Bereichen verhält, welche auf deiner Webseite angezeigt werden.</p>

        <p>Über den Button <strong>Auf Aktualisierung prüfen</strong> in der Toolbar kannst du die Prüfung auf System-Updates manuell starten.</p>
        
        <h3>Allgemein</h3>
        
        <dl>
            <dt>Allgemein - E-Mail-Adresse:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Zentrale E-Mail-Adresse für Systembenachrichtigungen.</dd>
            <dt>Allgemein - Basis-URL für Artikellinks:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Basis-URL für Artikel-Links im Frontend, wichtig v. a. bei der Nutzung
            von phpinclude. Entspricht in vielen Fällen der <em>deine-domain.xyz/index.php</em> oder der Datei, in der
            <em>fpcmapi.php</em> includiert ist.</dd>
            <dt>Allgemein - Datum- und Zeitanzeige:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Maske für die Anzeige von Datums- und Zeitangaben.</dd>
            <dt>Allgemein - Zeitzone:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Globale Zeitzone, kann durch Profileinstellung überschrieben werden.</dd>
            <dt>Allgemein - Sprache:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Globale Spracheinstellung für alle Benutzer sowie im Frontend.</dd>
            <dt>Allgemein - Anzahl Elemente pro Seite im ACP:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Anzahl an Elementen im Admin-Bereich, wenn die Liste die Möglichkeit bietet,
            durch Seiten zu blättern (z. B. Artikel- und Kommentar-Listen)</dd>
            <dt>Allgemein - Zeit bis zum Cache-Timeout:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Zeitraum, nachdem der Inhalt des Caches als abgelaufen betrachtet wird und der Inhalt
            neu aufgebaut wird. Diese Einstellung ist vor allem für den Frontend-Inhalt wichtig.</dd>
            <dt>Allgemein - Vorhaltezeit für gelöschte Elemente:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Anzahl an Tagen, bis Elemente im Papierkorb automatisch gelöscht werden.</dd>
            <dt><em>Frontend</em> - Pfad zu deiner CSS-Datei:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Pfad zu deiner CSS-Datei mit deinen eigenen Style-Angaben. Wichtig
            wenn du FanPress CM via iframe oder die Template-Vorschau nutzt.</dd>
            <dt><em>Frontend</em> - Verwendung per:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Nutzung von FanPress CM via phpinclude oder in einem iframe. Diese Einstellung beeinflusst,
            wie sich das System im Frontend verhält und welche zusätzlichen Daten beim Aufruf von Artikel-Listen etc. geladen werden.</dd>
            <dt><em>Frontend</em> - jQuery Bibliothek im Frontend laden:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Soll jQuery bei Nutzung von phpinclude geladen werden oder nicht. Wichtig wenn du phpinclude
            verwendest und jQuery nicht bereits anderweitig in deiner Seite eingebunden ist. Ohne jQuery stehen einige Frontend-Funktionen nicht
            zur Verfügung. Beim Aufruf des Frontend wird automatisch geprüft ob jQuery zur Verfügung steht. Ist dies nicht der Fall,
            so wird eine entsprechende Fehlermeldung ausgegeben.</dd>
        </dl>
        
        <h3>Editor & Dateimanager</h3>
        
        <dl>
            <dt>Editor - Editor auswählen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Standardmäßig kann hier zwischen TinyMCE und CodeMirror gewählt werden.
            Zusätzliche Editoren können über Module bereitgestellt werden. Diese Einstellung gilt für Artikel- und Kommentar-Editor im Admin-Bereich.</dd>
            <dt>Editor - Standard-Schriftgröße im Editor:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Schriftgröße, die standardmäßig im aktiven Editor genutzt wird.</dd>
            <dt>Editor - Revisionen aktivieren:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Soll FanPress CM Revisionen beim Speichern eines Artikels anlegen. Sind die Revisionen nicht aktiv,
            so werden Artikel beim Speichern sofort überschrieben und der bisherige Stand ist verloren.</dd>
            <dt>Editor - Alte Revisionen löschen, wenn älter als:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Revisionen, welche älter als der angegebene Wert sind, werden beim nächsten Durchlauf des
            zugehörigen Cronjobs aus der Datenbank entfernt. Wurde der Wert "Nie" ausgewählt, so bleiben alle Revisionen erhalten, bis sie
            irgendwann manuell gelöscht werden.</dd>
            <dt>Editor - Bilder-Änderungen in TinyMCE auf Server speichern:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Werden in TinyMCE Änderungen an Bildern vorgenommen, so werden die Änderungen bei Aktivierung dieser
            Option als neue Datei im Upload-Ordner abgelegt und können später auch im Dateimanager ausgewählt werden.</dd>
            <dt>Editor - CSS-Klassen im Editor:</dt>
            <dd class="fpcm-ui-padding-md-bottom">CSS-Klassen zur Nutzung im FanPress CM Editor. bei den CSS-Klassen handelt es sich in der Regel
            um solche, die du auch auf deiner Webseite verwendest.</dd>
            <dt>Dateimanager - jQuery Dateiupload verwenden:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Soll der moderne AJAX-Uploader genutzt werden, mit dem mehrere Dateien auf einmal hochgeladen
            werden können oder soll der klassische PHP-Uploader aktiviert werden. Der PHP-Uploader kann notwendig werden, wenn du einen
            älteren Browser verwendest, JavaScript deaktiviert bzw. teilweise geblockt ist.</dd>
            <dt>Dateimanager - Dateien beim Upload in Unterordner organisieren:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diese Option kannst du festlegen, dass Dateien beim Upload in Unterordnern abgelegt werden.
            Diese besitzen immer das Muster <em>YYYY-MM</em> (vierstellige Jahreszahl - zweistelliger Monat). </dd>
            <dt>Dateimanager - Anzahl Bilder pro Seite:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Anzahl an Bildern, die im Dateimanager pro Seite angezeigt werden.</dd>
            <dt>Dateimanager - Dateimanager-Ansicht:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diese Option kann ausgewählt werden, ob die Dateien im Dateimanager nebeneinander als Karten
            oder untereinander in einer Listenform angezeigt werden. Die dargestellten Informationen bleiben die gleichen.</dd>
            
            <dt>Vorschaubild-Größe - Breite in Pixel:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Die maximale Bereite von erzeugten Thumbnails.</dd>
            <dt>Vorschaubild-Größe - Höhe in Pixel:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Die maximale Höhe von erzeugten Thumbnails.</dd>
        </dl>

        <h3>Artikel</h3>
        
        <dl>
            <dt><em>Frontend</em> - Anzahl Artikel pro öffentlicher Seite:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Anzahl an Artikeln, die im Frontend ausgegeben werden sollen. Diese Option beeinflusst die Anzahl
            an Artikeln in der öffentlichen Liste der aktiven Artikel, des öffentlichen Archives sowie im RSS-Feed.</dd>
            <dt><em>Frontend</em> - Template für Artikel-Liste:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Template, welches für die Artikel-Liste genutzt werden soll.</dd>
            <dt><em>Frontend</em> - Template für einzelnen Artikel:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Template, welches für einen einzelnen Artikel verwendet werden soll. Die hier getroffene
            Auswahl beeinflusst die angezeigten Register im Template-Editor</dd>
            <dt><em>Frontend</em> - News sortieren nach:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Reihenfolge, nach der Artikel im Frontend sortiert werden sollen. Die erste Auswahl legt fest,
            nach welchem Kriterium die Sortierung erfolgt (im Standard den Zeitpunkt der Veröffentlichung), die zweite Auswahl die Richtung.</dd>
            <dt><em>Frontend</em> - Share-Buttons anzeigen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Hierüber können die Share-Buttons deaktiviert werden. Wurde der entsprechende Platzhalter in
            einem Template verwendet, so wird er bei der Einstellung "Nein" aus der Frontend-Anzeige entfernt.</dd>
            <dt><em>Frontend</em> - Geteilte Artikel über Share-Buttons zählen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Diese Option ermöglicht es zu zählen, wie oft ein Artikel über die Share-Buttons bereits geteilt
            wurde. Diese Option wie oft ein Artikel geteilt wurde und wann dies zuletzt erfolgte. Es erfolgt keine Erfassung, von welcher IP etc.
            dies erfolgte.</dd>
            <dt><em>Frontend</em> - URL-Rewriting für Artikel-Links aktivieren:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Statt der klassischen Artikel-URL mit der Artikel-ID wird eine erweiterte Version erzeugt,
            welche um den Artikel-Titel erweitert wird. Bei Änderung am Titel kann sich diese URL daher nachträglich ändern. Die klassische Variante
            steht weiterhin zur Verfügung.</dd>
            <dt><em>Frontend</em> - RSS-Feed ist aktiv:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diese Option kann der RSS-Feed aktiviert werden.</dd>
            <dt>Archiv - Archiv-Link anzeigen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Diese Einstellung ermöglicht es, dass öffentliche Artikel-Archiv für deine Benutzer zu deaktivieren.
            Somit sind nur die Artikel sichtbar, welche in den aktiven Artikeln im Admin-Bereich ausgelistet werden.</dd>
            <dt>Archiv - Artikel in Archiv anzeigen ab:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Vor dem Datum angegebenen Datum veröffentlichte Artikel, welche im Archiv abgelegt wurden,
            werden nicht für Besucher deiner Webseite angezeigt. ist dieses Feld leer, so werden alle archivierten Artikel angezeigt.</dd>
        </dl>
        
        <h3>Kommentare</h3>
        
        <dl>
            <dt>Kommentare - Kommentar-System ist aktiv:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Kommentar-System komplett aktivieren bzw. deaktivieren.</dd>
            <dt>Kommentare - Zustimmung zur Datenschutz-Erklärung erforderlich:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Diese Option aktiviert eine zusätzliche Prüfung, ob die Checkbox für die Zustimmung
            zur Speicherung personenbezogener Daten nach dem Verfassen eines Kommentars angehakt wurde. Diese Option sollte aktiv sein,
            wenn du das Kommentar-System verwendest und deine Webseite Besucher aus dem Raum der Europäische Union hat.</dd>
            <dt>Kommentare - Kommentar-Benachrichtigung an:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Auswahl, an welche E-Mail-Adresse die Benachrichtigung über einen neuen Kommentar geht
            (Autor des Artikels, globale E-Mail-Adresse aus den Systemeinstellungen oder an beide).</dd>
            <dt>Kommentare - Kommentar-Template:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Template für die Anzeige von Kommentaren im Frontend.</dd>
            <dt>Kommentare - Zeitsperre zwischen zwei Kommentaren:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Zeitspanne die zwischen zwei Kommentaren von der selben IP-Adresse vergangen
            sein muss.</dd>
            <dt>Kommentare - E-Mail-Adresse erforderlich:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Muss E-Mail-Adresse beim Schreiben eines Kommentars
            angegeben werden oder nicht.</dd>
            <dt>Kommentare - Kommentar-Freigabe erforderlich:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Kommentare sind sofort sichtbar oder müssen manuell durch den Autor oder einen
            Admin freigegeben werden. Ob Artikel freigegeben werden können, hängt von den Berechtigungen des Benutzers ab</dd>

            <dt>Captcha-Einstellungen - Captcha-Frage:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Frage für das Standard-Captcha.</dd>
            <dt>Captcha-Einstellungen - Antwort auf Captcha-Frage:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Antwort für das Standard-Spam-Plugin.</dd>
            <dt>Captcha-Einstellungen - Automatische Spam-Markierung:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Wurden Kommentare eines Kommentar-Autor so oft wie eingestellt als Spam markiert,
            so werden neue Kommentar automatisch als Spam deklariert.</dd>
        </dl>
        
        <h3>Erweitert</h3>
        
        <dl>
            <dt>Sicherheit & Wartung - Wartungsmodus aktiv:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Wurde der Wartungsmodus aktiviert, so haben nur angemeldete Benutzer Zugriff auf FanPress CM.
            Besucher deiner Seite etc. erhalten eine Hinweis-Meldung. Nur bereits angemeldete Benutzer können in diesem Status Änderungen
            am System vornehmen.</dd>
            <dt>Sicherheit & Wartung - Maximale Länge einer Admin-Sitzung:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Länge einer Session Admin-Bereich. Eine Session läuft automatisch ab, wenn innerhalb der angegebenen
            Zeit keine Aktion im Admin-Bereich erfolgte bzw. der Check der Session fehlgeschlagen ist.</dd>
            <dt>Sicherheit & Wartung - Anzahl Login-Versuche vor temporärer Sperre:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Hiermit kann die Anzahl der Fehlgeschlagenen Logins einstellen, bis der Login vorübergehend
            gesperrt wird. Diese Option erschwert die Übernahme von Benutzer-Accounts durch massenweises Durchprobieren von Passwörtern etc.</dd>
            <dt>Sicherheit & Wartung - Zwei-Faktor-Authentifizierung:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Die Zwei-Faktor-Authentifizierung bietet einen zusätzlichen Schutz von Benutzer-Konten gegen Fishing,
            Bots und ähnliches. Die Nutzung ist optional und wird durch jeden Benutzer selbst festgelegt. Der zweite Faktor zum Login wird mittels
            der App "Google Authenticator" auf dem Smartphone des Benutzers realisiert. Wurde die Zwei-Faktor-Authentifizierung aktiviert, so erscheint
            initial ein QR-Code, welcher eingescannt und bestätigt werden muss.</dd>
            <dt>Sicherheit & Wartung - Benutzer-Passwörter gegen Pwned Passwords prüfen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Bei Aktivierung dieser Option werden eingegebene Benutzer-Passwörter in einen SHA1-Hash umgewandet
            und dessen erste fünf Zeichen an den Dienst <a rel="noreferrer,noopener,external" href="https://haveibeenpwned.com/Passwords" target="_blank">Pwned Passwords</a>
            übermittelt. Ist das Passwort in dieser Datenbank enthalten und bereits mehr als 100-mal geknackt worden, so wird eine Meldung ausgegeben.
            </dd>
            
            <dt>Update-Einstellungen - E-Mail-Benachrichtigung, wenn Updates verfügbar:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Diese Option ermöglicht es, die Benachrichtigung über
            verfügbare Updates durch den Update-Cronjob zu de/aktivieren. Die Benachrichtigung erfolgt dabei immer an die globale
            E-Mail-Adresse in den Systemeinstellungen.</dd>
            <dt>Update-Einstellungen - Entwickler-Versionen bei Update-Check anzeigen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Neben den offizielle Releases gibt es immer wieder Entwickler- und Test-Versionen.
            Nach Aktivierung dieser Option werden solche Versionen beim Update-Check angezeigt. <b>Achtung: Entwickler- und Test-Versionen
            können Fehler oder unvollständige Änderungen enthalten! Nutze diese Versionen daher nur, wenn du dazu aufgefordert wurdest oder dir
            bei Problemen, Datenverlust, o. ä. notfalls selbst helfen kannst.</b></dd>
            <dt>Update-Einstellungen - Update-Check-Intervall, wenn externe Server-Verbindungen nicht möglich:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Kann deine FanPress CM Installation keine direkte Verbindung zum Update herstellen,
            so wird dir in regelmäßigem Abstand ein Dialog angezeigt, welcher die Download-Seite auf
            <a rel="noreferrer,noopener,external" href="https://Nobody-Knows.org">Nobody-Knows.org</a> angezeigt. Mit dieser Einstellung kann festgelegt werden, in welchem
            zeitlichen Abstand dies passiert.</dd>

            <dt>E-Mail-Versand - E-Mails via SMTP versenden:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Wenn diese Option aktiv ist, erfolgt der E-Mail-Versand unter
            welche durch die SMTP-Zugangsdaten definiert wird. Zur Nutzung des SMTP-Versands muss dein Host die Verbindung zu anderen Servern
            zulassen. Standardmäßig erfolgt der Versand von E-Mails über die PHP-eigenen Funktionen.</dd>
            <dt>E-Mail-Versand - E-Mail-Adresse:</dt>
            <dd class="fpcm-ui-padding-md-bottom">E-Mail-Server, die als Absender-Konto verwendet wird</dd>
            <dt>E-Mail-Versand - SMTP-Server-Adresse:</dt>
            <dd class="fpcm-ui-padding-md-bottom">E-Mail-Server-Adresse</dd>
            <dt>E-Mail-Versand - SMTP-Server-Port:</dt>
            <dd class="fpcm-ui-padding-md-bottom">E-Mail-Server-Port. Der Port ist abhängig davon, ob eine verschlüsselte Verbindung verwendet wird
            oder nicht.</dd>
            <dt>E-Mail-Versand - SMTP-Benutzername:</dt>
            <dt>E-Mail-Versand - SMTP-Passwort:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzername und Passwort für das zu verwendende E-Mail-Konto</dd>
            <dt>E-Mail-Versand - SMTP-Verschlüsselung:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Legt fest, ob die Verbindung zum E-Mail-Server verschlüsselt erfolgen soll oder nicht. Die
            gewählte Verschlüsselung muss vom E-Mail-Server unterstützt werden. Bei Aktivierung von "Auto" wird versucht, dies automatisch zu
            erkennen.</dd>
        </dl>

        <h3>Twitter-Verbindung</h3>
        
        <p>Dieses Register dient der Einrichtung und Überwachung der Twitter-Anbindung von FanPress-CM. Die Anleitung zur Einrichtung erreichst
        du über das Hilfe-Icon neben dem Button <strong>API-Schlüssel und/oder Token anfordern</strong>.</p>
        
        <dl>
            <dt>Verbindungsstatus:</dt>
            <dd class="fpcm-ui-padding-md-bottom">In diesem Bereich sieht du, ob bereits eine Verbindung zu Twitter hergestellt wurde oder ob diese
            noch eingerichtet werden muss. Wurde die Verbindung noch die aktiviert, so findest du hier den Button
            <strong>API-Schlüssel und/oder Token anfordern</strong>. Ansonsten steht hier, welcher Benutzername bei Twitter verwendet wird und die
            Verbindung kann hier deaktiviert werden.</dd>
            <dt>Tweet zu Artikel erzeugen beim:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über die beiden Punkte kann festgelegt werden, wann neue Tweets nur beim Veröffentlichen bzw. Ändern
            eines Artikeln oder beiden Aktionen erzeugt werden sollen.</dd>
            <dt>Zugangsdaten:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Diese Eingabe-Felder beinhalten die Informationen, welche für den erfolgreichen Zugriff auf die
            Twitter-API benötigt werden. Welche Daten hier eingetragen werden müssen, erfährst du in der Hilfe zur Einrichtung.</dd>
        </dl>

        <h3>Systemprüfung</h3>
        
        <p>Über die Systemprüfung kannst du deine aktuelle Installation auf mögliche Fehlkonfigurationen prüfen lassen. Sofern alle Icons am rechten
        Haken blau sind, wurden keine Probleme gefunden. Punkte, welche mit <em>optional</em> gekennzeichnet sind, müssen nicht zwangsläufig einen
        blauen Haken besitzen.</p>
        ]]>
    </chapter>
    <chapter ref="HL_OPTIONS_USERS">
        <![CDATA[

        <p>Mit den entsprechenden Rechten können Benutzer und Benutzer-Rollen verwaltet werden.</p>

        <h3>Benutzer</h3>
        
        <ul>
            <li>Über Benutzer wird der Zugriff auf den Admin-Bereich gesteuert, sowie dokumentiert wer welchen Artikel,
            Kommentar etc. verfasst oder bearbeitet hat.</li>
            <li>Benutzer können deaktiviert werden. Dabei wird der Login gesperrt und somit der Zugriff auf den
            Admin-Bereich gesperrt. Die erstellten Artikel etc. bleiben erhalten. Dies kann nützlich sein, wenn
            der Benutzer das Team deiner Seite verlassen hat, aus ihm ausgeschlossen wurde oder der Account irgendwie
            missbraucht wurde.</li>
            <li>Die änderbaren Informationen in den Benutzern entsprechen denen im Benutzer-Profil. Die von einem Benutzer
            getroffenen Einstellungen können hier auf die System-weiten Einstellungen zurückgesetzt oder angepasst werden.</li>
            <li>Wird ein Benutzer gelöscht, so wird der Eintrag komplett aus dem System entfernt, ein Login ist im Anschluss nicht
            mehr möglich. Für Artikel besteht die Möglichkeit, diese zu einem anderen Benutzer zu Verschieben oder ebenfalls
            löschen zu lassen. Wurde ein Benutzer gelöscht, so wird bei allen verweisen auf den Benutzer angezeigt, dass er
            nicht gefunden wurde.</li>
            <li>Über den Button <strong>E-Mail verfassen</strong> kann aus dem von euch festgelegten Standard-E-Mail-Programm eine Nachricht versendet werden.</li>
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
            <dd class="fpcm-ui-padding-md-bottom">Benutzer können Kommentare in Masse bearbeiten</dd>
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
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann die Templates und Vorlagen bearbeiten</dd>
            <dt>Smileys verwalten</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann Smileys neu definieren und bestehende löschen</dd>
            <dt>Updates durchführen</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann verfügbare Updates installieren.</dd>
            <dt>System-Protokollee verwalten</dt>
            <dd class="fpcm-ui-padding-md-bottom">Benutzer kann die vom System erzeugten Protokolledateien einsehen und bei Bedarf bereinigen</dd>
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
            <li>Durch die Nutzung von Proxy-Servern, privaten Netzwerken, dynamischen IP-Adressen etc. kann es passieren, dass von der
            Sperrung einer IP-Adresse nicht nur ein einzelner, sondern viele Nutzer (ungewollt) betroffen sind.</li>
            <li>Pro Eintrag kann festgelegt werden, für welchen Bereich von FanPress CM die Sperren gelten soll.</li>
            <li>Durch entsprechende Muster können ganze IP-Adress-Bereiche gesperrt werden, Beispiele werden sind im Editor angezeigt. Dies
            kann nötig werden, wenn zum Beispiel Bots (Spam, Suchmaschinen etc.) häufig ihre IP-Adresse wechseln.</li>
        </ul>
        
        <dl>
            <dt>Keine Kommentare schreiben</dt>
            <dd class="fpcm-ui-padding-md-bottom">Der Besucher mit der angegebenen IP-Adresse kann keine
            Kommentare verfassen, wenn diese nicht für den Artikel oder generell deaktiviert sind.</dd>
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
            <li>Die Textzensur ermöglicht es, bestimmte Wörter, Textgruppen oder Zeichenketten für die Verwendung in Artikeln, Kommentaren etc. zu
            sperren.</li>
            <li>Hierüber kann vermieden werden, dass Beleidigungen, Spam usw. öffentlich auf der Seite dargestellt bzw.
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
            <dd class="fpcm-ui-padding-md-bottom">Hierfür kann eine Bild-Datei auf einem externen Server oder lokal auf deinem Webspace verwendet
            werden. In beiden Fällen sollte die vollständige URL angegeben werden. Die Anzeige der vergebenen Icons erfolgt im Frontend über
            den Platzhalter <em>{{categoryIcons}}</em>.</dd>
            <dt>Verfügbar für Rollen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diese Einstellung wird festgelegt, welche Benutzer eine bestimmte Kategorie nutzen kann.</dd>
        </dl>
        ]]>
    </chapter>
    <chapter ref="HL_OPTIONS_TEMPLATES">
        <![CDATA[
        <p>Benutzer mit entsprechenden Rechten können die Templates zur Ausgabe von Artikeln, Kommentaren etc. bearbeiten.
        Für eine bessere Übersicht bietet der Template-Editor Syntax-Highlighting und eine Liste der verfügbaren Platzhalter.</p>

        <h3>Templates</h3>
        
        <dl>
            <dt>Artikel-Liste:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Template für Anzeige von Artikeln in der Artikel-Liste.</dd>
            <dt>Artikel-Einzel-Ansicht:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Template für Anzeige eines einzelnen Artikels inkl. dessen Kommentaren, dem
            Kommentar-Formular etc. Dieser Tab wird nicht angezeigt, wenn für <em>Artikel-Liste</em> und <em>Artikel-Einzel-Ansicht</em>
            das gleiche Template genutzt wird.</dd>
            <dt>Kommentar:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Template für die Anzeige eines einzelnen Kommentars im Frontend.</dd>
            <dt>Kommentar-Formular:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Template für das Formular zum Verfassen eines Kommentars.</dd>
            <dt>Share-Buttons:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Template für die Darstellung der Share-Buttons in Artikeln.</dd>
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
            <dd class="fpcm-ui-padding-md-bottom">Der Button <strong>Vorschau anzeigen</strong> ermöglicht es, den im Editor vorhandenen Template-Inhalt
            als Vorschau anzeigen zu lassen und somit die Wirkung, Formatierungsfehler etc. sofort zu erkennen.</dd>
        </dl>
        
        <h3>Vorlagen</h3>
        
        <ul>
            <li>Vorlagen sind HTML-Dateien, deren Inhalt Inhalt im Artikel-Editor verwendet werden kann.</li>
            <li>Hiermit können wiederkehrende Artikel-Inhalte gesichert und immer in der gleichen Art wiederverwendet werden.</li>
            <li>Die Vorlagen können durch den Klick auf den Button <strong>Bearbeiten</strong> über einen CodeMirror-basierten Editor
            angepasst werden. Weitere können bei Bedarf ins System hochgeladen werden.</li>
        </ul>

        <h3>Verfügbare Attribute</h3>
        
        <p>Die Template-Platzhalter können seit Version 4.1 <em>Attribute</em> besitzen, welche die Frontend-Ausgabe weiter beeinflussen. Attribute werden in der Form
        <em>AttributeName="AttributeWert"</em> angegeben. Platzhalter können mehrere Attribute besitzen, wobei mehrere gleichezitig verwendet werden können.
        Attribute können sich gegenseitig erfordern oder ausschließen.</p>
        
        <dl>
            <dt>Artikel-Templates - {{sources}}:</dt>
            <dd class="fpcm-ui-padding-md-bottom">
                <dl>
                    <dt>descr:</dt>
                    <dd class="fpcm-ui-padding-md-bottom">Beschreibung vor der Ausgabe der Links aus dem Quellenverzeichnis.</dd>
                </dl>
                <dl>
                    <dt>descrAlt:</dt>
                    <dd class="fpcm-ui-padding-md-bottom">Alternativer Wert für die Ausgabe, wenn im Quellenverzeichnis keine Daten eingetragen sind.</dd>
                </dl>
                <dl>
                    <dt>hideEmpty:</dt>
                    <dd class="fpcm-ui-padding-md-bottom">Quellenverzeichnis-Ausgabe unterdrücken, wenn im Quellenverzeichnis keine Daten eingetragen sind,
                    muss immer mit Wert "1" angegeben werden.</dd>
                </dl>
            </dd>
        </dl>

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
            <li>Cronjobs sind Aufgaben, welche in regelmäßigen Abständen automatisch durch FanPress CM im Hintergrund ausgeführt werden.</li>
            <li>Die Cronjob-Übersicht zeigt eine Liste aller verfügbaren Cronjobs, wenn sie zuletzt ausgeführt wurden, sowie den Zeitpunkt der
            nächsten voraussichtlichen Ausführung.</li>
            <li>Die Häufigkeit der Ausführung eines Cronjobs kannst du anpassen, indem der Wert für das Intervall-Zeit angepasst wird.</li>
            <li>Beachte bei der Änderung des Intervall, dass Cronjobs u. U. für erhöhte Serverlast führen kann.</li>
        </ul>

        <dl>
            <dt>Artikel-Revisionen bereinigen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Wurde die Option <em>Alte Revisionen löschen, wenn älter als</em> auf einen Wert ungleich <em>Nie</em> gesetzt,
            bereinigt dieser Cronjob die Artikel-Revisionen im eingestellten Intervall. Standardmäßig erfolgt dies einmal im Monat.</dd>
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
            <dd class="fpcm-ui-padding-md-bottom">Die Sicherung der System-Datenbank dient dieser Cronjob. Die erzeugten Backups werden im Verzeichnis <em>/data/dbdump</em> abgelegt
            und können über den Backup-Manager verwaltet werden. Im Standard erfolgt die Sicherung einmal pro Woche. Bei hohem Artikel-Aufkommen sollte das Intervall entsprechend
            reduziert werden.</dd>
            <dt>System-Protokollee leeren:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Die Protokoll-Dateien können unter Umständen sehr groß werden, daher werden diese (im Standard monatlich) auf ihre Dateigröße geprüft und
            bei Überschreitung einer Größe von 1 MB bereinigt. Das vorherige Log wird gespeichert. Die Bereinigung des Session-Protokolls erfolgt unabhängig von der Anzahl der Einträge.</dd>
            <dt>temporäre Dateien aufräumen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Bei Updates, sowie in der täglichen Arbeit fallen immer wieder temporäre Dateien an, welche unter Umständen nicht sofort bereinigt werden
            (können). Dieser Cronjob prüft auf entsprechende Dateien und löscht diese; im Standard einmal pro Woche.</dd>
        </dl>
        ]]>
    </chapter>
    <chapter ref="HL_LOGS">
        <![CDATA[
        <p>Im Bereich <em>Protokollee</em> findest du eine Auflistung aller bisherigen Benutzer-Logins, System-Meldungen von FanPress und
            Fehlermeldungen durch PHP selbst oder der Datenbank. Über den Button <strong>Leeren</strong> kannst du Meldungen etc. löschen
            lassen.</p>
        
        <dl>
            <dt>Sessions:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Dieses Protokoll zeigt eine Übersicht über die Logins aller Benutzer, abgesehen von aktuell aktiven Sessions, z. B. deiner eigenen.
            Angezeigt werden alle relevanten Informationen, d. h. wer hat sich wann ein- bzw. ausgeloggt. Externe Logins erfolgten über die Funktionen der FanPress CM-API. Der
            User-Agent enthält Informationen, mit welchem Browser oder Programm der Login erfolgte.</dd>
            <dt>Sonstige:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Dieses Protokoll beinhaltet allgemeine Meldungen von FanPress CM, Status-Meldungen und falls nötig Diagnose-Informationen.</dd>
            <dt>Ereignisse:</dt>
            <dd class="fpcm-ui-padding-md-bottom">In diesem Protokoll werden allen von Fehlern, welche im Betrieb, bei Updates, Änderungen der Systemkonfiguration etc. auftreten.
            Fatale PHP_Fehler können hier unter Umständen nicht angezeigt werden, da die System-Protokollierung zu diesem Zeitpunkt noch nicht greift. Nicht alle Einträge sind
            zwangsläufig kritisch. Bei Fragen lassen uns einfach eine Nachricht zukommen.</dd>
            <dt>Datenbank:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Die Datenbank-Protokolldatei enthält weitergehende Informationen zu Ereignissen auf Datenbank-Seite, z. B. fehlschlagende Abfragen, fehlschlagende
            Verbindungen zum Datenbank-Server etc.</dd>
            <dt>Cronjobs:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Hier werden Status-Informationen, Laufzeiten etc. zu ausgeführten Cronjobs protokolliert. Dieses Log kann in Abhängigkeit von der
            Cronjob-Konfiguration sehr schnell stark wachsen und sollte daher regelmäßig bereinigt werden.</dd>
            <dt>Paketmanager:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Dieses Protokolle beinhaltet Status-Informationen zu System-Updates, sowie Installation oder Updates von Modulen.</dd>
        </dl>
        ]]>
    </chapter>
    <chapter ref="HL_BACKUPS">
        <![CDATA[
        <ul>
            <li>Dieser Bereich ermöglicht dir die Verwaltung der automatisch erzeugten Datenbank-Backups. Du kannst diese löschen.</li>
            <li>Die erzeugten Datenbank-Backups sind gepackte SQL-Dateien, deren Struktur vom verwendeten Datenbank-System abhängt.</li>
            <li>Um ein Backup bei Bedarf wiederherzustellen, kannst du folgende Werkzeuge nutzen:
                <ul>
                    <li><a rel="noreferrer,noopener,external" href="https://www.phpmyadmin.net/" target="_blank">phpMyAdmin</a></li>
                    <li><a rel="noreferrer,noopener,external" href="https://www.adminer.org/de" target="_blank">Adminer</a></li>
                    <li><a rel="noreferrer,noopener,external" href="http://phppgadmin.sourceforge.net/doku.php" target="_blank">phpPgAdmin</a></li>
                </ul>
            </li>
        </ul>
        ]]>
    </chapter>
    <chapter ref="HL_MODULES">
        <![CDATA[
        <p>Durch Module kann die Funktionalität von FanPress CM umfangreich erweitert werden. Die Verwaltung von verfügbaren und/oder
        installierten Modulen erfolgt in diesem Bereich.</p>

        <h3>Register</h3>

        <dl>
            <dt>Installierte Module:</dt>
            <dd class="fpcm-ui-padding-md-bottom">In diesem Register werden alle Module angezeigt, die im System verfügbar sind. Dies können
            Module aus dem öffentlichen Repository oder eigene Modul sein, welche dort noch nicht verfügbar sind.</dd>
            <dt>Verfügbare Module:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Die verfügbaren Module listen alle Modul auf, welche im öffentlichen Repository verfügbar sind.
            Diese Module werden vor der Veröffentlichung geprüft. Über das öffentliche Repository werden auch Aktualisierungen zur Verfügung 
            gestellt. Wurde ein Modul bereits installiert, so wird dieses in der Übersicht weiterhin angezeigt.</dd>
            <dt>Modul-Paketdatei hochladen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über dieses Register können Module aus externen Quellen ins System hochgeladen werden. Die
            als ZIP-Archive gepackten Module werden bei diesem Vorgang automatisch entpackt. Die Nutzung der Upload-Funktion kann notwendig
            werden, wenn dein Host keine Verbindung zu externen Servern zulässt.</dd>
        </dl>
        
        <h3>Aktionen</h3>

        <dl>
            <dt>Modul-Detail-Informationen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Die Detail-Informationen zum Modul (siehe unten) können über diesen
            angezeigt werden.</dd>
            <dt>Modul installieren:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Über diesen Button kann ein Modul installiert werden.</dd>
            <dt>Modul deinstallieren:</dt>
            <dt>Modul löschen:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Die Deinstallation eines Moduls entfernt alle durch das Modul angelegten
            Tabellen, Konfigurationsoptionen etc. Die Dateien selbst werden dabei nicht gelöscht. Dies muss durch den
            Button <strong>Modul löschen</strong> erfolgen.</dd>
            <dt>Modul aktualisieren:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Ist im öffentlichen Repository eine neuere Version des Modules
            verfügbar, so kann die Aktualisierung des Moduls über diesen Button erfolgen. Der Button erscheint ebenfalls, wenn die Modul-Version in der
            Datenbank und im Dateisystem des Servers nicht übereinstimmen. Das kann nach manuellen Änderungen an Modul-Code passieren. Durch die
            Ausführung wird sichergestellt, dass die Datenbank dem Stand entspricht, welcher vom Modul funktional erwartet wird.</dd>
            <dt>Modul aktivieren:</dt>
            <dt>Modul deaktivieren:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Nach der Installation eines Moduls muss dies aktiviert werden, so
            dass definierte Events, Aktionen etc. zur Verfügung stehen. Die Deaktivierung ermöglicht es, die Ausführung
            z. B. bei auftretenden Fehlern zu beenden ohne das Modul komplett zu deaktivieren.</dd>
            <dt>Modul konfigurieren:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Dieser Button wird angezeigt, wenn das Modul das Template <em>configure.php</em>
            beinhaltet..</dd>
        </dl>

        <h3>Modul-Detail-Informationen</h3>

        <dl>
            <dt>Schlüssel:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Dies ist die interne Bezeichnung des Moduls. Der Schlüssel muss einmalig sein.</dd>
            <dt>Name:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Der Name ist eine nicht-technische Bezeichnung und gibt eine kurze Informationen,
            welchem Zweck das Modul dient.</dd>
            <dt>Version (lokal):</dt>
            <dt>Version (Server):</dt>
            <dd class="fpcm-ui-padding-md-bottom">Hier wird die Version angezeigt, welche lokal installiert und ggf. im öffentlichen
            Repository verfügbar ist. Beide Angaben können unterschiedliche Werte besitzen, in der Regel sollte der Wert der lokalen
            Version dem der Server-Version entsprechen.</dd>
            <dt>Autor:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Die Autor-Angabe entspricht dem Entwickler des Moduls, dies kann ein Name,
            E-Mail-Adresse o.ä. sein.</dd>
            <dt>Info-Link:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Diese Angabe enthält eine URL für weiteren Informationen zum Modul.</dd>
            <dt>Beschreibung:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Dieser Wert beinhaltet eine erweiterte Kurzbeschreibung zum Modul.</dd>
            <dt>Erfordert PHP:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Module können eine bestimmte PHP-Version voraussetzen, diese wird hier angegeben.</dd>
            <dt>Erfordert FanPress CM:</dt>
            <dd class="fpcm-ui-padding-md-bottom">Module können eine bestimmte FanPress CM -Version voraussetzen, diese wird hier angegeben.</dd>
        </dl>
        ]]>
    </chapter>
    <chapter ref="SYSTEM_OPTIONS_TWITTER_CONNECTION">
        <![CDATA[
        <p>FanPress CM bietet dir die Möglichkeit, beim Schreiben/ Aktualisieren eines Artikels automatisch einen Tweet bei Twitter
            erzeugen zu lassen.</p>
        <p>Um die Verbindung zu Twitter herzustellen, folge einfach der Anleitung.</p>
        <ol class="list-large">
            <li>Logge dich zuerst über die Twitter-Webseite ganz normal ein. <a rel="noreferrer,noopener,external" target="_blank" href="https://twitter.com/login" class="ui-button ui-corner-all ui-widget fpcm-ui-button">zum Login</a></li>
            <li>Öffne die Einstellungen der Twitter-Verbindungen über <strong>Optionen &rarr; Systemeinstellungen &rarr;
                    Twitter-Verbindung</strong>.</li>
            <li><strong>API-Schlüssel und Token:</strong> Klicke auf den Button <span class="ui-button ui-corner-all ui-widget fpcm-ui-button">API-Schlüssel bzw. Token anfordern
                    anzufordern</span>, du wirst zur AppVerwaltung von Twitter weitergeleitet.</li>
            <li>Wähle den Button <span class="ui-button ui-corner-all ui-widget fpcm-ui-button">Create new app</span>.</li>
            <li>Fülle das angezeigte Formular aus und bestätige mit <span class="ui-button ui-corner-all ui-widget fpcm-ui-button">Create your Twitter application</span>.</li>
            <li>Öffne den Tab <strong>Keys and Access Tokens</strong> und kopiere von dort <strong>Consumer Key (API Key)</strong>
                und <strong>Consumer Secret (API Secret)</strong> in die Felder in den Systemeinstellungen.</li>
            <li>Um Tweets erzeugen zu können, stelle den <strong>Access Level</strong> über den Reiter <strong>Permissions</strong>
                von <strong>Read-only</strong> auf <strong>Read and Write</strong>.</li>
            <li><strong>Access Token:</strong> Nach dem API-Key musst du nun einen Access Token erzeugen. Scrolle dafür runter zum
                Punkt <strong>Your Access Token</strong> und klicke auf den Button <span class="ui-button ui-corner-all ui-widget fpcm-ui-button">Create my access
                    token</span>. Kopiere anschließend <strong>Access Token</strong> und <strong>Access Token Secret</strong> in
                in die Felder in den Systemeinstellungen.</li>

            <li>Klicke nun in den <strong>Systemeinstellungen</strong> auf Speichern, um die Daten zu speichern.</li>
            <li>Wurden alle Schritte richtig durchgeführt, so erhältst du einen entsprechenden Hinweis.</li>
        </ol>
        <p>Um die Twitter-Verbindung zu löschen, klicke auf den Button <span class="ui-button ui-corner-all ui-widget fpcm-ui-button">Verbindung löschen</span>.</p>
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
        <p>Solltest du Fragen haben oder (technische) Hilfe benötigen, kannst du über verschiedene Wege Kontakt aufzunehmen.</p>
        <p>Unseren Bugtracker findest du auf <a rel="noreferrer,noopener,external" href="https://github.com/sea75300/fanpresscm4/issues" target="_blank">GitHub.com</a>, hier werden
        Weiterentwicklungen, Bugfixes und sonstige Fragen entgegen genommen. Alternativ schreibe eine E-Mail an
        <em>fanpress@nobody-knows.org</em> oder <em>sea75300@yahoo.de</em> oder hinterlasse unter
        <a rel="noreferrer,noopener,external" href="https://nobody-knows.org/download/fanpress-cm/" target="_blank">Nobody-Knows.org</a> einen Kommentar auf der Projekt-Seite.</p>

        <p>Das Modul <em>FanPress CM Support</em> erstellt während der Installation einen Benutzer-Zugang zur deiner FanPress CM Installation
        und übermittelt einige grundlegende System-Informationen.</p>
        ]]>
    </chapter>
</chapters>
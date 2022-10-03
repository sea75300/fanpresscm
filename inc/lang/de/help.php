<?xml version="1.0" encoding="UTF-8"?>
<!--
Help language file
@author Stefan Seehafer <sea75300@yahoo.de>
@copyright (c) 2011-2022, Stefan Seehafer
@license http://www.gnu.org/licenses/gpl.txt GPLv3
*/
-->
<chapters>
    <chapter ref="HL_DASHBOARD">
        <![CDATA[

            <p>Das Dashboard ist eine zentrale Anlaufstelle nach dem Login in FanPress CM. Der Startbildschirm
            zeigt viele verschiedene Informationen (u. a. zum Systemstatus oder geschriebenen Artikeln) an. Die 
            verfügbaren Informationen können durch Module erweitert werden.</p>
            
            <h3 class="pt-5 fs-1">Container</h3>
            
            <dl>
                <dt>Zuletzt geschriebene News:</dt>
                <dd class="pb-2">Hier findest du eine Übersicht der zuletzt verfassten Artikel.</dd>
                <dt>Zuletzt geschriebene Kommentare:</dt>
                <dd class="pb-2">Hier findest du eine Übersicht der zuletzt verfassten Kommentare.</dd>
                <dt>Verfügbare Updates:</dt>
                <dd class="pb-2">Dieser Container beinhaltet Informationen zum Update-Status des Systems und von Modulen.</dd>
                <dt>Systemprüfung:</dt>
                <dd class="pb-2">Diese Box enthält grundlegende Status-Informationen zu deiner Installation.</dd>
                <dt>Statistiken:</dt>
                <dd class="pb-2">In diesem Bereich werden statistische Informationen ausgegeben, u. a. zur Anzahl verfasster
                Artikel oder Kommentare.</dd>
                <dt>Aktuelle FanPress CM News:</dt>
                <dd class="pb-2">Dieser Container beinhaltet Neuigkeiten rund vom FanPress CM, bspw. neue Versionen, künftige Entwicklungen usw..</dd>
                <dt>Team-Kontakte:</dt>
                <dd class="pb-2">Dieser Container beinhaltet eine Liste aller aktiven Benutzer mit der Möglichkeit, dies eine
                E-Mail zu verfassen.</dd>
                <dt>Letzte Tweets:</dt>
                <dd class="pb-2">Bei eingerichteter Twitter-Verbindung werden in diesem Container (standardmäßig) bis zu 25 deiner
                letzten Tweets und Retweets angezeigt. Werden deine Beiträge retweetet oder geliked, werden diese entsprechend markiert.</dd>
            </dl>

            <h3 class="pt-5 fs-1">Container-Funktionen</h3>
            
            <p>Über die zusätzlichen Links am Ende der Container können zusätzliche Informationen abgerufen werden
            oder es ist ein Schnellzugriff auf bestimmte Funktionen möglich.</p>

            <p>Die Position der Container kann durch Klick auf den Button 
            <span class="btn btn-link btn-sm"><span class="fpcm-ui-icon fa fa-fw fa-arrows-alt  "></span></span>
            verändert werden. Die Reihenfolge legt jeder Benutzer selbst fest.
            Um bestimmte Container zu deaktivieren, klicke in der Fußzeile auf
            den Button <span class="btn btn-link btn-sm"><span class="fpcm-ui-icon fa fa-fw fa-toggle-off"></span></span>.</p>
            
            <p>Über das Dropdown "Container verwalten" in der Toolbar des Startbildschirms kann die Reihenfolge auf den Standard zurückgesetzt bzw.
            deaktivierte Container wieder reaktiviert werden.</p>
            
            
        ]]>
    </chapter>
    <chapter ref="ARTICLES_EDITOR">
        <![CDATA[
        <p>Mit dem <b>Artikel-Editor</b> kannst du Artikel schreiben und/oder bearbeiten. Hierbei hast du vielfältige Gestaltungsmöglichkeiten, welche
            durch Module erweitert werden können. Du kannst einem Artikel Kategorien zuweisen, ihn "anpinnen", sodass er über allen anderen Artikeln
            dargestellt wird und verschiedene weitere Einstellungen vornehmen.</p>

        <h3 class="pt-5 fs-1">Editor</h3>

        <dl>
            <dt>TinyMCE:</dt>
            <dd class="pb-2">Dieser WYSIWYG-Editor zeigt alle Formatierungen und Änderungen direkt an. Außerdem
            bietet er diverse zusätzliche Informationen und Funktionen u. a. zur Bearbeitung von Bildern, die Anzeige von
            eingefügten Galerien, Tabellen, Listen usw.</dd>
            <dt>CodeMirror:</dt>
            <dd class="pb-2">Dieser Editor ist ein reiner HTML-Editor, welcher verschiedene Formatierungsmöglichkeiten
            bietet sowie zusätzliche Funktionen wie Syntax-Highlighting. Dieser Editor-Type wird zudem immer für die
            Bearbeitung der Temlplates und Artikel-Vorlagen geladen.</dd>
        </dl>        

        <h3 class="pt-5 fs-1">Eigenschaften</h3>
        
        <dl>
            <dt>Artikelbild:</dt>
            <dd class="pb-2">Mit dem Artikelbild kannst du einen Artikel eine zusätzliche Dekoration, optische
                Beschreibung etc. geben. Die Position und Größe des Artikelbildes kann über das Artikel-Template festgelegt werden. Über den Button rechts neben dem
            Eingabefeld kannst du ein bereits hochgeladenes Bild auswählen und weitere Bilder hochladen.</dd>

            <dt>Text für Artikellinks:</dt>
            <dd class="pb-2">Ist die Systemeinstellung "URL-Rewriting für Artikellinks aktivieren" aktiv, kann durch dieses Feld Einfluss auf
            die erzeugten Artikel-URLs gemnommen werden. Diese setzen sich immer aus der Artikel-ID + dem hier angegeben Text zusammen. Standardmäßig
            wird für die URL immer eine bereinigte Version des Artikel-Titels verwendet.</dd>

            <dt>Quellenverzeichnis:</dt>
            <dd class="pb-2">Der Inhalt dieses Feldes wird durch den Template-Tag "{{sources}}" dargestellt. Hier kannst du Links zu deinen Informationsquellen,
                Quellen von Bildern, Videos etc. oder zu weiterführenden Informationen angeben. Links werden so weit es geht automatisch in HTML-Links umgewandelt.</dd>
            <dd class="pb-2">Einträge im Quellenverzeichnis werden gespeichert und bei Übereinstimmung zur Auswahl angezeigt. Über den Button 
                "Quellenverzeichnis-Einträge" die Einträge aus der Autovervollständigung entfernt werden.</dd>

            <dt>Tweet erzeugen:</dt>
            <dd class="pb-2">Über diese Option kann die Erzeugung eines Tweets bei aktiver Twitter-Verbindung manuell
                deaktiviert werden, wenn sie in den Systemoptionen aktiviert wurde.</dd>

            <dt>Tweet erzeugen:</dt>
            <dd class="pb-2">Über das Textfeld kann das Standard-Template für einen Beitrag bei Twitter
                überschrieben und durch einen eigenen Text ersetzt werden. Der Inhalt dieses Feldes wird nicht gespeichert.
                Das Dropdown bietet einen Schnellzugriff auf die Template-Platzhalter. Über die Checkbox am Ende kann zudem festgelegt werden,
                ob beim nächsten Speicher-Vorgang der Tweet erzeugt wird oder nicht.
            </dd>

            <dt>Artikel freischalten:</dt>
            <dd class="pb-2">Mittels dieser Option kannst du einen neuen Artikel verfassen und zu einem bestimmten
                Zeitpunkt automatisch veröffentlichen lassen. Der Zeitpunkt kann maximal zwei Monate in der Zukunft liegen.</dd>

            <dt>Artikel als Entwurf speichern:</dt>
            <dd class="pb-2">Wird diese Option aktiviert, so wird der Artikel beim Speichern nicht als
                Entwurf abgelegt. Entwürfe werden nicht sofort veröffentlicht, sondern sind nur für angemeldete Benutzer sichtbar
                und können vor der Veröffentlichung noch bearbeitet werden.</dd>

            <dt>Artikel pinnen:</dt>
            <dd class="pb-2">"Gepinnte" Artikel werden im Frontend vor allen anderen verfügbaren Artikeln angezeigt, auch
                auch wenn das Datum ihrer Veröffentlichung vor neueren Artikeln liegt.</dd>
            <dt>Kommentare aktiv:</dt>
            <dd class="pb-2">Über diese Option kann das Kommentar-System für einen einzelnen Artikel gesteuert werden.
                ist die Option nicht aktiv, so können keine Kommentare auf der Artikel verfasst werden.</dd>

            <dt>Artikel archivieren:</dt>
            <dd class="pb-2">Bestehende Artikel können über diese Option ins Archiv verschoben werden bzw.
                herausgenommen werden.</dd>

            <dt>Autor ändern:</dt>
            <dd class="pb-2">Benutzer mit entsprechenden Rechten können hierüber den Verfasser eines Artikeln ändern.</dd>

            <dt>Geteilte Inhalte und Likes:</dt>
            <dd class="pb-2">Sofern das Zählen von Klicks auf die Share-Buttons aktiviert wurde, wird in diesem Bereich
            die aktuelle Anzahl pro Artikel angezeigt, diese umfasst sowohl Teilungen bei den verfügbaren sozialen Netzwerken als auch Klicks auf den
            FanPress CM-eigenen "Gefällt mir"-Button. Eine Summe über alle geteilten Inhalte pro Artikel wird in den Artikel-Listen neben der Kommentar-Anzahl angezeigt.</dd>
        </dl>

        <h3 class="pt-5 fs-1">Register</h3>
    
        <p>Der Artikel-Editor kann am oberen Rand bis zu vier Tabs enthalten.</p>

        <dl>
            <dt>Artikel-Editor:</dt>
            <dd class="pb-2">Dieser Tab wird immer angezeigt und beinhaltet den Editor an sich.</dd>
            <dt>Erweitert:</dt>
            <dd class="pb-2">Der zweite Tab umfasst die verschiedenen Status-Optionen wie Gepinnt, Entwurf,
            Artikel-Freigabe, Artikelbild usw.</dd>
            <dt>Kommentare:</dt>
            <dd class="pb-2">Dieses Register beinhaltet Auflistung aller Kommentare, welche zum ausgewählten Artikel
            geschrieben wurden. Die Liste bietet dir die Möglichkeit, einzelne Kommentare zu löschen. Über einen Klick auf den Bearbeiten-Button
            kann der entsprechende Kommentar bearbeitet werden (freischalten, auf privat setzen etc.). Der Zugriff auf die Kommentare 
            wird über die Berechtigungen geregelt. Ausführliche Informationen hierzu findest du in der Hilfe den "Kommentare"-Bereichs
            über das Hauptmenü.</dd>
            <dt>Revisionen:</dt>
            <dd class="pb-2">FanPress CM besitzt ein Revisions-System, d. h. bei Änderungen wird der vorherige Zustand
            gesichert und kann jederzeit wiederhergestellt werden. Die Revisionen können über die Systemeinstellungen (de)aktiviert werden.
            Eine Liste aller Revisionen findest du über den entsprechenden Reiter im Editor. Dort kannst du jede Revision einzeln aufrufen
            bzw. den aktuellen Artikel auf eine Revision zurücksetzen. Beim Öffnen einer Revision werden die Änderungen zur jeweils
            aktuellen Artikel-Version angezeigt.</dd>
        </dl>
        
        <h3 class="pt-5 fs-1">Buttons und Aktionen</h3>
        
        <dl>
            <dt>Löschen-Buttons</dt>
            <dd class="pb-2">Je nach geöffnetem Register werden unterschiedlichen Löschen-Buttons angezeigt. Diese dienen dazu, entsprechende Elemente des
            angezeigten Tabs zu löschen.</dd>
            <dt>Artikel auf Webseite anzeigen</dt>
            <dd class="pb-2">Über diesen Button wird der aktuell im Editor geöffnete Artikel im Frontend, d. h. auf deiner Webseite geöffnet.</dd>
            <dt>Kurzlink</dt>
            <dd class="pb-2">Über diesen Button ist es bei gespeicherten Artikeln möglich, die URL über den Dienst <a rel="noreferrer,noopener,external" href=http://is.gd>is.gd</a> kürzen
            zu lassen und bei Twitter etc. zu nutzen. Der genutzte Dienst kann über ein Modul-Event geändert werden.</dd>
            <dt>Artikelbild anzeigen</dt>
            <dd class="pb-2">Wurde für den Artikel ein Artikelbild definiert, so kann dieses über diese Schaltfläche angezeigt werden.</dd>
            <dt>Bearbeiten (Kommentare)</dt>
            <dd class="pb-2">Die Schaltfläche <strong>Bearbeiten</strong> in der Toolbar des Kommentar-Registers öffnen einen Massenbearbeitung-Dialog analog der
            globalen Kommentar-Liste. Hierüber kommen bestimmte Status der ausgewählten Kommentare verändert werden. Die gleiche Schaltfläche in der Kommentar-Liste
            öffnet des ausgewählten Kommentars in einem Dialog, wo dieser komplett bearbeitet werden kann.</dd>
            <dt>Revision wiederherstellen (Revisionen)</dt>
            <dd class="pb-2">Diese Schaltfläche ermöglicht es, den aktuellen Artikel auf die ausgewählte Artikel-Revision zurückzusetzen. Beim Zurücksetzen wird
            automatisch eine neue Revision des aktuellen Stands erzeugt, bevor die ältere Version wiederhergestellt wird.</dd>
            <dt>Revision öffnen (Revisionen)</dt>
            <dd class="pb-2">Über diesen Button kann eine bestimmte Revision geöffnet werden. Es wird eine Vergleichansicht zwischen der ausgewählten
            Revision (linke Seite) und dem aktuellen Zustand des Artikels (rechte Seite) angezeigt. Der Artikeltext selbst wird in einer DIFF-Ansicht dargestellt, d. h. Veränderungen werden
            innerhalb des Textes dargestellt.</dd>
            <dt>Zurück zur aktuellen Ansicht (Revisionen)</dt>
            <dd class="pb-2">Wurde eine Revision geöffnet, so gelangst du durch diese Schaltfläche zurück in den aktuellen Artikel.</dd>
        </dl>
 
        <h3 class="pt-5 fs-1">Erweiterte Funktionen</h3>
        
        <h4 class="pt-3">Weiterlesen-Link/ Seitenumbruch einfügen</h4>

        <p>Vor allem bei sehr langen Beiträgen kann es sinnvoll sein, nur einen kurzen Anreißer in der Artikelliste anzuzeigen und den
        Rest erst durch Klick auf den "Weiterlesen"-Link. Hierzu bietet der Editor die "Seitenumbruch einfügen"-Funktion. Dies erzeugt im Artikel
        einen Eintrag der Form <pre>&lt;!-- pagebreak --&gt;</pre> welcher im Frontend in einen Weiterlesen-Link umgesetzt wird. <b>Achtung!</b> Hierfür muss
        das Template entsprechend angepasst werden.</p>

        <h4 class="pt-3">Einzelnes Bild in Artikel einfügen</h4>

        <p>Um den Pfad eines Bildes direkt in den <em>Bild einfügen</em>-Dialog zu übernehmen, klicke auf die Buttons <strong>Thumbnail-Pfad in Quelle einfügen</strong>
        bzw. <strong>Datei-Pfad in Quelle einfügen</strong> unterhalb/ neben dem Thumbnail des jeweiligen Bildes. Je nach gewähltem Button wird der entsprechende Pfad
        übernommen.</p>

        <p>Sollte die Übernahme einfach nicht funktionieren, so öffne via Rechtsklick das Kontextmenü der genannten Buttons. Wähle dort die
        Option <strong>Link-Adresse kopieren / Verknüpfung kopieren / o. ä.</strong> aus und für den Pfad anschließend in das Feld <em>Quelle</em> im Editor
        ein.</p>
        
        <ul>
            <li>Im HTML-Editor öffnet sich beim Eintippen in das Quelle-Feld zudem eine Autovervollständigung mit Treffern in der hochgeladenen Bilder.</li>
            <li>Weiterhin kann im Einfügen-Dialog über den Button "Bild als Link einfügen" direkt eine Verlinkung zum ausgewählten Bild erzeugt werden.</li>
            <li>In TinyMCE steht im "Bild einfügen"-Dialog zudem der Punkt <strong>Bildliste</strong> zur Verfügung.</li>
        </ul>
        
        <h4 class="pt-3">Gallery in Artikel einfügen</h4>

        <p>Über den Button "Gallery einfügen" lassen sich mehrere Bilder mit einmal in einen Artikel einfügen. Das folgende Beispiel zeigt eine Gallery mit vier Bidlern.</p>

        <pre>[gallery]thumb:2020-04/bild01.jpg:link|thumb:2020-03/bild02.png:link|thumb:2020-02/bild03.jpg:link|thumb:2020-01/bild04.jpg:link[/gallery]</pre>

        <dl>
            <dt>[gallery] & [/gallery]</dt>
            <dd class="pb-2">Die beiden PLatzhalter öffnen und schließen eine Gallery. Bitte beachte, das pro Artikel nur <strong>eine</strong>
            Gallery eingefügt werden kann/ sollte und entsprechend ausgewertet wird. Weitere, händisch eingefügt Galerien werden vom System nicht ausgewertet.</dd>
            <dt>Trennzeichen</dt>
            <dd class="pb-2">Die Bilder einer Gallery werden hintereinander angegeben, als Trennzeichen dient das "|"-Zeichen
            (die sogenannte Pipe).</dd>
            <dt>thumb:</dt>
            <dd class="pb-2">Durch Hinzufügen/ Weglassen des "thumb:"-Präfix kann gesteuert werden, ob in der Gallery das Thumbnail bzw. komplette Bild
            angezeigt wird. Beim Einfügen aus dem Dateimanager wird das Präfix standardmäßig immer vorangestellt.</dd>
            <dt>thumb:</dt>
            <dd class="pb-2">Durch Hinzufügen/ Weglassen des "link:"-Suffix kann gesteuert werden, ob das verwendete Bild in der Gallery
            verlinkt ist oder nicht. Beim Einfügen aus dem Dateimanager wird das Suffix standardmäßig immer angefügt.</dd>
        </dl>
        
        ]]>
    </chapter>
    <chapter ref="HL_ARTICLE_EDIT">
        <![CDATA[
        <p>Im Bereich <b>Artikel verwalten</b> kannst findest du alle gespeicherten Artikel in FanPress CM.</p>
        
        <h3 class="pt-5 fs-1">Bereiche</h3>
        
        <dl>
            <dt>Alle Artikel:</dt>
            <dd class="pb-2">Diese Liste umfasst alle verfassten Artikel, inkl. aktiver und archivierter Artikel,
            sowie Entwürfe.</dd>
            <dt>Aktive Artikel:</dt>
            <dd class="pb-2">Diese Liste umfasst ausschließlich Artikel, welche aktiv sind und entsprechend auf deiner
            Webseite angezeigt werden sowie Entwürfe.</dd>
            <dt>Archivierte Artikel:</dt>
            <dd class="pb-2">Hier werden all diejenigen Artikel aufgeführt, welche archiviert wurden.</dd>
        </dl>
        
        <p>Die verfügbaren Eigenschaften werden im Artikel-Editor näher beschrieben.</p>

        <h3 class="pt-5 fs-1">Aktionen</h3>
        
        <dl>
            <dt>Bearbeiten:</dt>
            <dd class="pb-2">Über die Massenbearbeitung können alle ausgewählten Artikel auf einmal bearbeitet werden.
            Die auswählbaren Optionen entsprechen denen im Artikel-Editor.</dd>
            <dt>Suche und Filter:</dt>
            <dd class="pb-2">Über diesen Button kannst du mithilfe eines Dialogs die angezeigten Artikel anhand
            verschiedener Kriterien weiter eingrenzen. Über die Hauptnavigation kannst du bereits eine Vorauswahl treffen, welche Artikel
            dir angezeigt werden sollen. Unterschiedliche Felder können im Bedarfsfall miteinander verknüpft werden oder die Suche
            in speziellen Konstellationen durchgeführt werden. Hierzu dient das linke Dropdown-Feld in der jeweiligen Zeile. Auf der
            rechten Seite einer Zeile wird der jeweilige Wert angegeben. Der Button "Filter zurücksetzen" führt eine Aktualisierung der
            aktuellen Ansicht durch. Hierdurch werden die Filter-Einstellungen komplett zurückgesetzt.
            </dd>
            <dt>Neuen Tweet erzeugen:</dt>
            <dd class="pb-2">Für den bzw. die ausgewählten Artikel neue Posts bei Twitter erzeugen, wenn Verbindung
            zu Twitter eingerichtet wurde.</dd>
            <dt>Löschen:</dt>
            <dd class="pb-2">Den bzw. die ausgewählten Artikel löschen.</dd>
            <dt>Artikel-Cache leeren:</dt>
            <dd class="pb-2">Über diese Aktion kann bei Bedarf gezielt der Cache eines einzelnen bzw. der ausgewählten Artikel geleert und somit
            beim Öffnen des Frontends ein erneutes rendern der entsprechenden Artikel erzwungen werden. Dies ist hilfreich, wenn Änderungen an Artikeln nicht sofort übernommen
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
        
        <h3 class="pt-5 fs-1">Eigenschaften</h3>
        
        <dl>
            <dt>Kommentar ist privat:</dt>
            <dd class="pb-2">Private Kommentare werden nicht öffentlich angezeigt, sondern sind nur
            für Benutzer innerhalb von FanPress CM sichtbar.</dd>
            <dt>Kommentar ist genehmigt:</dt>
            <dd class="pb-2">Genehmigte Kommentare werden öffentlich angezeigt und können von
            deinen Besuchern gelesen und beantwortet werden. Nicht genehmigte Kommentare verhalten sich wie
            private Kommentare und sind nicht sichtbar. Diese Funktion kann in den Systemeinstellungen deaktiviert
            werden.</dd>
            <dt>Kommentar ist Spam:</dt>
            <dd class="pb-2">Kommentare, welche als Spam markiert wurden, werden nicht öffentlich
            angezeigt. Ihre Daten werden zur Verbesserung der Spam-Erkennung genutzt, sofern du sie nicht löscht.</dd>
            <dt>Kommentar zu Artikel mit ID verschieben:</dt>
            <dd class="pb-2">Die ausgewählten Kommentare zur eingetragenen Artikel-ID
            verschieben. Das Eingabefeld unterstützt die Suche nach Artikeln mittels Autovervollständigung.</dd>
        </dl>

        <h3 class="pt-5 fs-1">Aktionen</h3>
        
        <dl>
            <dt>Bearbeiten:</dt>
            <dd class="pb-2">Über die Massenbearbeitung können alle ausgewählten Kommentare auf einmal bearbeitet werden.
            Die auswählbaren Optionen entsprechen denen im Kommentar-Editor.</dd>
            <dt>Suche und Filter:</dt>
            <dd class="pb-2">Über diesen Button kannst du mithilfe eines Dialogs die angezeigten Kommentare anhand
            verschiedener Kriterien weiter eingrenzen. Unterschiedliche Felder können im Bedarfsfall miteinander verknüpft werden oder die Suche
            in speziellen Konstellationen durchgeführt werden. Hierzu dient das linke Dropdown-Feld in der jeweiligen Zeile. Auf der
            rechten Seite einer Zeile wird der jeweilige Wert angegeben.</dd>
            <dt>Löschen:</dt>
            <dd class="pb-2">Den bzw. die ausgewählten Kommentare löschen.</dd>
            <dt>Zugehörigen Artikel bearbeiten:</dt>
            <dd class="pb-2">Durch diesen Button gelangst du direkt in Artikel-Editor, in welchem der zum ausgewählten Kommentar zugehörige
            Artikel geöffnet wurde.</dd>
            <dt>Whois:</dt>
            <dd class="pb-2">Über diesen Button kannst du eine Whois-Abfrage auf die IP-Adresse durchführen, um bspw. den etwaigen Standort
            herauszufinden.</dd>
            <dt>IP-Adresse sperren:</dt>
            <dd class="pb-2">Über diesen Button kann für die gespeicherte IP-Adresse eine Sperre eingerichtet werden. Hierzu wird das entsprechende
            Recht zu verwalten von IP-Adressen benötigt. Die Sperren können unter <strong>Optionen > IP-Adressen</strong> aufgehoben werden.</dd>
        </dl>
        
        <h3 class="pt-5 fs-1">Kommentar-Editor</h3>
        
        <p>Der Editor bietet genau wie der Artikel-Editor die Auswahl zwischen TinyMCE und CodeMirror, besitzt jedoch nicht alle Funktionen des Artikel-Editors. Oberhalb des Editors
        werden noch zusätzliche Informationen angezeigt, u. a. von welcher IP-Adresse der Kommentar geschrieben wurde. Diese Information kann zur Vermeidung von Spam, bei Straftaten etc.
        wichtig sein.</p>
        
        <p>Erscheint beim Aufruf des Editors die Meldung, dass der zum Kommentar gehörige Artikel nicht gefunden wurde, so wird das Feld
        <em>Kommentar zu Artikel verschieben</em> eingeblendet. Dieses hat die gleiche Funktion wie die
        Eigenschaft <em>Kommentar zu Artikel mit ID verschieben</em> in der Kommentar-Übersicht.
        Durch direkte Eingabe einer Artikel-ID bzw. die Autovervollständigung kann der Kommentar zu einem anderen
        Artikel verschoben werden.</p>
        
        <p>Aus Datenschutz-Gründen wird die IP-Adresse durch den Cronjob <em>IP-Adressen aus Kommentaren anonymisieren</em> standardmäßig einmal im Monat anonymisiert. Die Anonymisierung
        erfolgt nicht für Kommentare, welche als Spam eingestuft wurden, da entsprechende Kommentare später auch zur Spam-Erkennung herangezogen werden.</p>
        
        ]]>
        
    </chapter>
    <chapter ref="HL_FILES_MNG">
        <![CDATA[
        
        <p>Im <b>Dateimanager</b> kannst du Grafiken hochladen, welche du in deinen Artikeln verwenden willst. Eine vereinfachte Ansicht lässt
            sich auch direkt aus dem Artikel-Editor heraus aufrufen. Er zeigt neben einem Vorschau-Bild noch einige zusätzliche Informationen zur
            hochgeladenen Datei an.</p>

        <h3 class="pt-5 fs-1">Aktionen</h3>

        <dl>
            <dt>Suche und Filter:</dt>
            <dd class="pb-2">Über diesen Button kannst du mithilfe eines Dialogs die angezeigten Grafiken
            anhand verschiedener Kriterien weiter eingrenzen. Unterschiedliche Felder können im Bedarfsfall miteinander verknüpft werden oder die Suche
            in speziellen Konstellationen durchgeführt werden. Hierzu dient das linke Dropdown-Feld in der jeweiligen Zeile. Auf der
            rechten Seite einer Zeile wird der jeweilige Wert angegeben.</dd>
            <dt>Thumbnails erzeugen:</dt>
            <dd class="pb-2">Für ausgewählte Dateien kann das Thumbnail neu erzeugt werden.</dd>
            <dt>Löschen:</dt>
            <dd class="pb-2">Die ausgewählten Dateien können gelöscht werden. Wichtig! Für Dateien existiert kein Papierkorb.</dd>
            <dt>Umbenennen:</dt>
            <dd class="pb-2">Über den Button kann die Datei umbenannt werden, die Dateiendung muss dabei nicht angehangen werden.</dd>
            <dt>Bild bearbeiten:</dt>
            <dd class="pb-2">FanPress CM bringt einen einfachen Bildeditor mit. Dieser ermöglicht es, hochgeladenen Grafiken in ihrer Größe
            zu verändern, zu drehen, zoomen oder Bereiche auszuschneiden.</dd>
            <dt>Alternativtext eingeben:</dt>
            <dd class="pb-2">Der Alternativtext ermöglicht eine gesonderte Beschreibung für die gewählte Grafik zu hinterlegen.</dd>
            <dt>Eigenschaften:</dt>
            <dd class="pb-2">Über diesen Button werden relevante Informationen zum gewählten Eintrag (Upload-Datum/ Benutzer, Dateigröße, Auflösung usw.) angezeigt.</dd>
            <dt>Artikelbild festlegen:</strong> (nur Editor)</dt>
            <dd class="pb-2">Ausgewählte Datei als Artikelbild festlegen.</dd>
            <dt>Thumbnail-URL einfügen:</strong> (nur Editor)</dt>
            <dd class="pb-2">Thumbnail-URL der ausgewählten Datei in Dialog übernehmen.</dd>
            <dt>Bild-URL einfügen:</strong> (nur Editor)</dt>
            <dd class="pb-2">Bild-URL der ausgewählten Datei in Dialog übernehmen.</dd>
            <dt>Karten / Liste:</dt>
            <dd class="pb-2">Über diese Auswahl kann die Darstellung des Dateimanagers angepasst werden,
            die Optionen können auch über die Systemeinstellungen bzw. das Profil angepasst werden.</dd>
        </dl>
        
        <h3 class="pt-5 fs-1">Eigenschaften</h3>

        <dl>
            <dt>Zuletzt geändert:</dt>
            <dd class="pb-2">Zeitpunkt der letzten Änderung bzw. des Uploads</dd>
            <dt>Hochgeladen von:</dt>
            <dd class="pb-2">Benutzer der Uploads</dd>
            <dt>Dateigröße:</dt>
            <dd class="pb-2">Dateigröße der Grafik</dd>
            <dt>Auflösung:</dt>
            <dd class="pb-2">Größe der Grafike in Pixel (Breite mal Höhe)</dd>
            <dt>MIME-Typ:</dt>
            <dd class="pb-2">Internet Media Type der Datei</dd>
            <dt>Dateihash:</dt>
            <dd class="pb-2">SHA-256-Hash der Datei</dd>
            <dt>Credits:</dt>
            <dd class="pb-2">IPTC-Daten der Grafik</dd>
        </dl>

        <h3 class="pt-5 fs-1">Ansichten</h3>

        <dl>
            <dt>Karten:</dt>
            <dd class="pb-2">Diese Ansicht stellt die hochgeladenen Grafiken nebeneinander dar.</dd>
            <dt>Liste:</dt>
            <dd class="pb-2">Diese Ansicht stellt die hochgeladenen Grafiken in einer Liste untereinander dar.</dd>
        </dl>
        ]]>
    </chapter>
    <chapter ref="HL_PROFILE">
        <![CDATA[
        <p>Das eigene <b>Profil</b> können alle Benutzer über das Profil-Menü oben rechts aufrufen. Über den Button <strong>Zurücksetzen</strong>
        können die Einstellungen auf die Systemweiten Vorgaben zurücksetzen.</p>
        
        <h3 class="pt-5 fs-1">Profil</h3>
        
        <dl>
            <dt>Angezeigter Name:</dt>
            <dd class="pb-2">Name, welcher öffentlich angezeigt wird. Wird nicht für den Login verwendet.</dd>
            <dt>Benutzername:</dt>
            <dd class="pb-2">Dein Name für den Login. Deinen Benutzernamen kannst du nicht selbst ändern. Wende dich hierfür an einen Administrator.</dd>
            <dt>Passwort:</dt>
            <dd class="pb-2">Zeichenkette welches für den Login verwendet wird. Neben dem Eingabefeld findest du den Button <strong>Password generieren</strong>.
            Über diesen kannst du eine zufällige Zeichenkette erzeugen lassen und als Passwort abspeichern.</dd>
            <dt>E-Mail-Adresse:</dt>
            <dd class="pb-2">E-Mail-Adresse für Benachrichtigungen, ein neu gesetztes Passwort etc.</dd>
            <dt>Aktuelles Passwort zur Bestätigung eingeben:</dt>
            <dd class="pb-2">Zur Änderung des Passwortes und bestimmter anderer Einstellungen ist eine Bestätigung per Passwort nötig.</dd>
        </dl>

        <h3 class="pt-5 fs-1">Zwei-Faktor-Authentifizierung (optional)</h3>
        
        <p>Die Zwei-Faktor-Authentifizierung bietet einen zusätzlichen Schutz deines Logins gegen Fishing, Bots und ähnliches. Die Nutzung ist
        optional und kann durch einen Administrator bei Bedarf aktiviert werden. Der zweite Faktor zum Login wird mittels der App "Google Authenticator" auf deinem Smartphone
        realisiert. </p>
        
        <p>Zur Aktivierung der Zwei-Faktor-Authentifizierung scanne den angezeigten QR-Code mit deinem Smartphone mit der App. Trage im Anschluss den ersten Zahlencode
        in das Eingabefeld ein und speicher den Vorgang..</p>

        <h3 class="pt-5 fs-1">Erweitert</h3>
        <dl>
            <dt>Biografie / Sonstiges:</strong> (optional)</dt>
            <dd class="pb-2">Kurzer Info-Text zum Autor, der in den News angezeigt werden kann.</dd>            
            <dt>Avatar:</strong> (optional)</dt>
            <dd class="pb-2">Benutzer-Avatar, Dateiname entspricht dem Muster <em>benutzername.jpg/png/gif/bmp</em></dd>
        </dl>
        
        <h3 class="pt-5 fs-1">Benutzereinstellungen</h3>
        <dl>
            <dt>Zeitzone:</dt>
            <dd class="pb-2">Zeitzone für Datums- und Zeit-Angaben.</dd>
            <dt>Sprache:</dt>
            <dd class="pb-2">Sprach-Einstellung für das FanPress-ACP.</dd>
            <dt>Datum- und Zeitanzeige:</dt>
            <dd class="pb-2">Muster, in welcher Art Datums- und Zeitangaben dargestellt werden.</dd>
            <dt>Anzahl Elemente pro Seite im ACP:</dt>
            <dd class="pb-2">Anzahl an dargestellten Elementen pro Seite im ACP</dd>
            <dt>Standard-Schriftgröße im Editor:</dt>
            <dd class="pb-2">Schriftgröße, die standardmäßig im Artikel-Editor genutzt wird</dd>
            in Kürze entfernt.</dd>
            <dt>Dateimanager-Ansicht:</dt>
            <dd class="pb-2">Über diese Auswahl kann die Darstellung des Dateimanagers angepasst werden,
            die Optionen können auch über die Systemeinstellungen bzw. das Profil angepasst werden.</dd>
            <dt>Container-Positionen zurücksetzen:</dt>
            <dd class="pb-2">Über den Button können die Positionen der Dashboard-Container
            auf die Standard-Einstellungen zurücksetzen.</dd>
        </dl>

        ]]>
    </chapter>
    <chapter ref="HL_OPTIONS">
        <![CDATA[
        <p>Benutzer mit den entsprechenden Rechten können hier zentrale Einstellungen von FanPress CM ändern. Die hier getroffenen Werte
        gelten grundsätzlich für alle Benutzer, sofern diese nicht vom einzelnen Anwender verändert wurden. Entsprechende Änderungen
        können bei Bedarf im Profil oder der Benutzer-Verwaltung zurückgesetzt werden.</p>
        
        <p>Einige Bereiche besitzen eine <em>Frontend</em>-Box. Die entsprechenden Einstellungen beeinflussen, wie sich FanPress CM
        in den veröffentlichen Bereichen verhält, welche auf deiner Webseite angezeigt werden.</p>

        <p>Über den Button <strong>Auf Aktualisierung prüfen</strong> in der Toolbar kannst du die Prüfung auf System-Updates manuell starten.</p>
        
        <h3 class="pt-5 fs-1">Allgemein</h3>
        
        <dl>
            <dt>Allgemein - E-Mail-Adresse:</dt>
            <dd class="pb-2">Zentrale E-Mail-Adresse für Systembenachrichtigungen.</dd>
            <dt>Allgemein - Basis-URL für Artikellinks:</dt>
            <dd class="pb-2">Basis-URL für Artikellinks im Frontend, wichtig v. a. bei der Nutzung
            von phpinclude. Entspricht in vielen Fällen der <em>deine-domain.xyz/index.php</em> oder der Datei, in der
            <em>fpcmapi.php</em> inkludiert ist.</dd>
            <dt>Allgemein - Datum- und Zeitanzeige:</dt>
            <dd class="pb-2">Maske für die Anzeige von Datums- und Zeitangaben.</dd>
            <dt>Allgemein - Zeitzone:</dt>
            <dd class="pb-2">Globale Zeitzone, kann durch Profileinstellung überschrieben werden.</dd>
            <dt>Allgemein - Sprache:</dt>
            <dd class="pb-2">Globale Spracheinstellung für alle Benutzer sowie im Frontend.</dd>
            <dt>Allgemein - Anzahl Elemente pro Seite im ACP:</dt>
            <dd class="pb-2">Anzahl an Elementen im Admin-Bereich, wenn die Liste die Möglichkeit bietet,
            durch Seiten zu blättern (z. B. Artikel- und Kommentar-Listen)</dd>
            <dt>Allgemein - Zeit bis zum Cache-Timeout:</dt>
            <dd class="pb-2">Zeitraum, nachdem der Inhalt des Cache als abgelaufen betrachtet wird und der Inhalt
            neu aufgebaut wird. Diese Einstellung ist vor allem für den Frontend-Inhalt wichtig.</dd>
            <dt>Allgemein - Vorhaltezeit für gelöschte Elemente:</dt>
            <dd class="pb-2">Anzahl an Tagen, bis Elemente im Papierkorb automatisch gelöscht werden.</dd>
            <dt><em>Frontend</em> - Pfad zu deiner CSS-Datei:</dt>
            <dd class="pb-2">Pfad zu deiner CSS-Datei mit deinen eigenen Style-Angaben. Wichtig
            wenn du FanPress CM via iframe oder die Template-Vorschau nutzt.</dd>
            <dt><em>Frontend</em> - Verwendung per:</dt>
            <dd class="pb-2">Nutzung von FanPress CM via phpinclude oder in einem iframe. Diese Einstellung beeinflusst,
            wie sich das System im Frontend verhält und welche zusätzlichen Daten beim Aufruf von Artikel-Listen etc. geladen werden.</dd>
            <dt><em>Frontend</em> - jQuery Bibliothek im Frontend laden:</dt>
            <dd class="pb-2">Soll jQuery bei Nutzung von phpinclude geladen werden oder nicht. Wichtig wenn du phpinclude
            verwendest und jQuery nicht bereits anderweitig in deiner Seite eingebunden ist. Ohne jQuery stehen einige Frontend-Funktionen nicht
            zur Verfügung. Beim Aufruf des Frontends wird automatisch geprüft ob jQuery zur Verfügung steht. Ist dies nicht der Fall,
            so wird eine entsprechende Fehlermeldung ausgegeben.</dd>
        </dl>
        
        <h3 class="pt-5 fs-1">Editor & Dateimanager</h3>
        
        <dl>
            <dt>Editor - Editor auswählen:</dt>
            <dd class="pb-2">Standardmäßig kann hier zwischen TinyMCE und CodeMirror gewählt werden.
            Zusätzliche Editoren können über Module bereitgestellt werden. Diese Einstellung gilt für Artikel- und Kommentar-Editor im Admin-Bereich.</dd>
            <dt>Editor - Standard-Schriftgröße im Editor:</dt>
            <dd class="pb-2">Schriftgröße, die standardmäßig im aktiven Editor genutzt wird.</dd>
            <dt>Editor - Revisionen aktivieren:</dt>
            <dd class="pb-2">Soll FanPress CM Revisionen beim Speichern eines Artikels anlegen. Sind die Revisionen nicht aktiv,
            so werden Artikel beim Speichern sofort überschrieben und der bisherige Stand ist verloren.</dd>
            <dt>Editor - Alte Revisionen löschen, wenn älter als:</dt>
            <dd class="pb-2">Revisionen, welche älter als der angegebene Wert sind, werden beim nächsten Durchlauf des
            zugehörigen Cronjobs aus der Datenbank entfernt. Wurde der Wert "Nie" ausgewählt, so bleiben alle Revisionen erhalten, bis sie
            irgendwann manuell gelöscht werden.</dd>
            <dt>Editor - CSS-Klassen im Editor:</dt>
            <dd class="pb-2">CSS-Klassen zur Nutzung im FanPress CM Editor. Bei den CSS-Klassen handelt es sich in der Regel
            um solche, die du auch auf deiner Webseite verwendest.</dd>
            <dt>Dateimanager - Dateien beim Upload in Unterordner organisieren:</dt>
            <dd class="pb-2">Über diese Option kannst du festlegen, dass Dateien beim Upload in Unterordnern abgelegt werden.
            Diese besitzen immer das Muster <em>YYYY-MM</em> (vierstellige Jahreszahl - zweistelliger Monat). </dd>
            <dt>Dateimanager - Anzahl Bilder pro Seite:</dt>
            <dd class="pb-2">Anzahl an Bildern, die im Dateimanager pro Seite angezeigt werden.</dd>
            <dt>Dateimanager - Dateimanager-Ansicht:</dt>
            <dd class="pb-2">Über diese Option kann ausgewählt werden, ob die Dateien im Dateimanager nebeneinander als Karten
            oder untereinander in einer Listenform angezeigt werden. Die dargestellten Informationen bleiben die gleichen.</dd>
            <dt>Dateimanager - Dateiname-Muster bearbeiteter Bilder:</dt>
            <dd class="pb-2">Werden Bilder im Dateimanager oder Artikel-Editor bearbeitet, so erfolgt die Speicherung der neuen Datei
            unter einem Namen, welcher dem eingestellten Muster entspricht. Folgende Platzhalter können verwendet werden:
            <ul>
                <li>{{filename}}: Dateiname</li>
                <li>{{date}}: aktuelles Datum</li>
                <li>{{datelong}}: aktuelles Datum mit Zeit</li>
                <li>{{hash}}: Dateihash</li>
                <li>{{userid}}:  Benutzer-ID</li>
                <li>{{random}}: Zufallszahl</li>
            </ul></dd>
            <dt>Dateimanager - Thumbnail-Größe:</dt>
            <dd class="pb-2">Größe von erzeugten Thumbnails.</dd>
        </dl>

        <h3 class="pt-5 fs-1">Artikel</h3>
        
        <dl>
            <dt><em>Frontend</em> - Anzahl Artikel pro öffentlicher Seite:</dt>
            <dd class="pb-2">Anzahl an Artikeln, die im Frontend ausgegeben werden sollen. Diese Option beeinflusst die Anzahl
            an Artikeln in der öffentlichen Liste der aktiven Artikel, des öffentlichen Archivs sowie im RSS-Feed.</dd>
            <dt><em>Frontend</em> - Template für Artikel-Liste:</dt>
            <dd class="pb-2">Template, welches für die Artikel-Liste genutzt werden soll.</dd>
            <dt><em>Frontend</em> - Template für einzelnen Artikel:</dt>
            <dd class="pb-2">Template, welches für einen einzelnen Artikel verwendet werden soll. Die hier getroffene
            Auswahl beeinflusst die angezeigten Register im Template-Editor</dd>
            <dt><em>Frontend</em> - News sortieren nach:</dt>
            <dd class="pb-2">Reihenfolge, nach der Artikel im Frontend sortiert werden sollen. Die erste Auswahl legt fest,
            nach welchem Kriterium die Sortierung erfolgt (im Standard den Zeitpunkt der Veröffentlichung), die zweite Auswahl die Richtung.</dd>
            <dt><em>Frontend</em> - Share-Buttons anzeigen:</dt>
            <dd class="pb-2">Hierüber können die Share-Buttons deaktiviert werden. Wurde der entsprechende Platzhalter in
            einem Template verwendet, so wird er bei der Einstellung "Nein" aus der Frontend-Anzeige entfernt.</dd>
            <dt><em>Frontend</em> - Geteilte Artikel über Share-Buttons zählen:</dt>
            <dd class="pb-2">Diese Option ermöglicht es zu zählen, wie oft ein Artikel über die Share-Buttons bereits geteilt
            wurde. Diese Option wie oft ein Artikel geteilt wurde und wann dies zuletzt erfolgte. Es erfolgt keine Erfassung, von welcher IP etc.
            dies erfolgte.</dd>
            <dt><em>Frontend</em> - URL-Rewriting für Artikellinks aktivieren:</dt>
            <dd class="pb-2">Statt der klassischen Artikel-URL mit der Artikel-ID wird eine erweiterte Version erzeugt,
            welche um den Artikel-Titel erweitert wird. Bei Änderung am Titel kann sich diese URL daher nachträglich ändern. Die klassische Variante
            steht weiterhin zur Verfügung.</dd>
            <dt><em>Frontend</em> - RSS-Feed ist aktiv:</dt>
            <dd class="pb-2">Über diese Option kann der RSS-Feed aktiviert werden.</dd>
            <dt>Archiv - Archiv-Link anzeigen:</dt>
            <dd class="pb-2">Diese Einstellung ermöglicht es, dass öffentliche Artikel-Archiv für deine Benutzer zu deaktivieren.
            Somit sind nur die Artikel sichtbar, welche in den aktiven Artikeln im Admin-Bereich ausgelistet werden.</dd>
            <dt>Archiv - Artikel in Archiv anzeigen ab:</dt>
            <dd class="pb-2">Vor dem Datum angegebenen Datum veröffentlichte Artikel, welche im Archiv abgelegt wurden,
            werden nicht für Besucher deiner Webseite angezeigt. ist dieses Feld leer, so werden alle archivierten Artikel angezeigt.</dd>
        </dl>
        
        <h3 class="pt-5 fs-1">Kommentare</h3>
        
        <dl>
            <dt>Kommentare - Kommentar-System ist aktiv:</dt>
            <dd class="pb-2">Kommentar-System komplett aktivieren bzw. deaktivieren.</dd>
            <dt>Kommentare - Zustimmung zur Datenschutz-Erklärung erforderlich:</dt>
            <dd class="pb-2">Diese Option aktiviert eine zusätzliche Prüfung, ob die Checkbox für die Zustimmung
            zur Speicherung personenbezogener Daten nach dem Verfassen eines Kommentars angehakt wurde. Diese Option sollte aktiv sein,
            wenn du das Kommentar-System verwendest und deine Webseite Besucher aus dem Raum der Europäische Union hat.</dd>
            <dt>Kommentare - Kommentar-Benachrichtigung an:</dt>
            <dd class="pb-2">Auswahl, an welche E-Mail-Adresse die Benachrichtigung über einen neuen Kommentar geht
            (Autor des Artikels, globale E-Mail-Adresse aus den Systemeinstellungen oder an beide).</dd>
            <dt>Kommentare - Kommentar-Template:</dt>
            <dd class="pb-2">Template für die Anzeige von Kommentaren im Frontend.</dd>
            <dt>Kommentare - Zeitsperre zwischen zwei Kommentaren:</dt>
            <dd class="pb-2">Zeitspanne die zwischen zwei Kommentaren von derselben IP-Adresse vergangen
            sein muss.</dd>
            <dt>Kommentare - E-Mail-Adresse erforderlich:</dt>
            <dd class="pb-2">Muss E-Mail-Adresse beim Schreiben eines Kommentars
            angegeben werden oder nicht.</dd>
            <dt>Kommentare - Kommentar-Freigabe erforderlich:</dt>
            <dd class="pb-2">Kommentare sind sofort sichtbar oder müssen manuell durch den Autor oder einen
            Admin freigegeben werden. Ob Artikel freigegeben werden können, hängt von den Berechtigungen des Benutzers ab</dd>

            <dt>Captcha-Einstellungen - Captcha-Frage:</dt>
            <dd class="pb-2">Frage für das Standard-Captcha.</dd>
            <dt>Captcha-Einstellungen - Antwort auf Captcha-Frage:</dt>
            <dd class="pb-2">Antwort für das Standard-Spam-Plugin.</dd>
            <dt>Captcha-Einstellungen - Automatische Spam-Markierung:</dt>
            <dd class="pb-2">Wurden Kommentare eines Autors so oft wie eingestellt als Spam markiert,
            so werden neue Kommentare automatisch als Spam deklariert.</dd>
        </dl>
        
        <h3 class="pt-5 fs-1">Erweitert</h3>
        
        <dl>
            <dt>Sicherheit & Wartung - Wartungsmodus aktiv:</dt>
            <dd class="pb-2">Wurde der Wartungsmodus aktiviert, so haben nur angemeldete Benutzer Zugriff auf FanPress CM.
            Besucher deiner Seite etc. erhalten eine Hinweis-Meldung. Nur bereits angemeldete Benutzer können in diesem Status Änderungen
            am System vornehmen.</dd>
            <dt>Sicherheit & Wartung - Maximale Länge einer Admin-Sitzung:</dt>
            <dd class="pb-2">Länge einer Session Admin-Bereich. Eine Session läuft automatisch ab, wenn innerhalb der angegebenen
            Zeit keine Aktion im Admin-Bereich erfolgte bzw. der Check der Session fehlgeschlagen ist.</dd>
            <dt>Sicherheit & Wartung - Anzahl Login-Versuche vor temporärer Sperre:</dt>
            <dd class="pb-2">Hiermit kann die Anzahl der fehlgeschlagenen Logins einstellen, bis der Login vorübergehend
            gesperrt wird. Diese Option erschwert die Übernahme von Benutzer-Accounts durch massenweises Durchprobieren von Passwörtern etc.</dd>
            <dt>Sicherheit & Wartung - Zwei-Faktor-Authentifizierung:</dt>
            <dd class="pb-2">Die Zwei-Faktor-Authentifizierung bietet einen zusätzlichen Schutz von Benutzer-Konten gegen Fishing,
            Bots und ähnliches. Die Nutzung ist optional und wird durch jeden Benutzer selbst festgelegt. Der zweite Faktor zum Login wird mittels
            der App "Google Authenticator" auf dem Smartphone des Benutzers realisiert. Wurde die Zwei-Faktor-Authentifizierung aktiviert, so erscheint
            initial ein QR-Code, welcher eingescannt und bestätigt werden muss.</dd>
            <dt>Sicherheit & Wartung - Benutzer-Passwörter gegen Pwned Passwords prüfen:</dt>
            <dd class="pb-2">Bei Aktivierung dieser Option werden eingegebene Benutzer-Passwörter in einen SHA1-Hash umgewandelt
            und dessen erste fünf Zeichen an den Dienst <a rel="noreferrer,noopener,external" href="https://haveibeenpwned.com/Passwords" target="_blank">Pwned Passwords</a>
            übermittelt. Ist das Passwort in dieser Datenbank enthalten und bereits mehr als 100-mal geknackt worden, so wird eine Meldung ausgegeben.
            </dd>
            
            <dt>Update-Einstellungen - E-Mail-Benachrichtigung, wenn Updates verfügbar:</dt>
            <dd class="pb-2">Diese Option ermöglicht es, die Benachrichtigung über
            verfügbare Updates durch den Update-Cronjob zu de/aktivieren. Die Benachrichtigung erfolgt dabei immer an die globale
            E-Mail-Adresse in den Systemeinstellungen.</dd>
            <dt>Update-Einstellungen - Entwickler-Versionen bei Update-Check anzeigen:</dt>
            <dd class="pb-2">Neben den offiziellen Releases gibt es immer wieder Entwickler- und Test-Versionen.
            Nach Aktivierung dieser Option werden solche Versionen beim Update-Check angezeigt. <b>Achtung: Entwickler- und Test-Versionen
            können Fehler oder unvollständige Änderungen enthalten! Nutze diese Versionen daher nur, wenn du dazu aufgefordert wurdest oder dir
            bei Problemen, Datenverlust, o. ä. notfalls selbst helfen kannst.</b></dd>
            <dt>Update-Einstellungen - Update-Check-Intervall, wenn externe Server-Verbindungen nicht möglich:</dt>
            <dd class="pb-2">Kann deine FanPress CM Installation keine direkte Verbindung zum Update herstellen,
            so wird dir in regelmäßigem Abstand ein Dialog angezeigt, welcher die Download-Seite auf
            <a rel="noreferrer,noopener,external" href="https://Nobody-Knows.org">Nobody-Knows.org</a> angezeigt. Mit dieser Einstellung kann festgelegt werden, in welchem
            zeitlichen Abstand dies passiert.</dd>

            <dt>E-Mail-Versand - E-Mails via SMTP versenden:</dt>
            <dd class="pb-2">Wenn diese Option aktiv ist, erfolgt der E-Mail-Versand unter
            welche durch die SMTP-Zugangsdaten definiert wird. Zur Nutzung des SMTP-Versands muss dein Host die Verbindung zu anderen Servern
            zulassen. Standardmäßig erfolgt der Versand von E-Mails über die PHP-eigenen Funktionen.</dd>
            <dt>E-Mail-Versand - E-Mail-Adresse:</dt>
            <dd class="pb-2">E-Mail-Server, die als Absender-Konto verwendet wird</dd>
            <dt>E-Mail-Versand - SMTP-Server-Adresse:</dt>
            <dd class="pb-2">E-Mail-Server-Adresse</dd>
            <dt>E-Mail-Versand - SMTP-Server-Port:</dt>
            <dd class="pb-2">E-Mail-Server-Port. Der Port ist abhängig davon, ob eine verschlüsselte Verbindung verwendet wird
            oder nicht.</dd>
            <dt>E-Mail-Versand - SMTP-Benutzername:</dt>
            <dt>E-Mail-Versand - SMTP-Passwort:</dt>
            <dd class="pb-2">Benutzername und Passwort für das zu verwendende E-Mail-Konto</dd>
            <dt>E-Mail-Versand - SMTP-Verschlüsselung:</dt>
            <dd class="pb-2">Legt fest, ob die Verbindung zum E-Mail-Server verschlüsselt erfolgen soll oder nicht. Die
            gewählte Verschlüsselung muss vom E-Mail-Server unterstützt werden. Bei Aktivierung von "Auto" wird versucht, dies automatisch zu
            erkennen.</dd>
        </dl>

        <h3 class="pt-5 fs-1">Twitter-Verbindung</h3>
        
        <p>Dieses Register dient der Einrichtung und Überwachung der Twitter-Anbindung von FanPress-CM. Die Anleitung zur Einrichtung erreichst
        du über das Hilfe-Icon neben dem Button <strong>API-Schlüssel und/oder Token anfordern</strong>.</p>
        
        <dl>
            <dt>Verbindungsstatus:</dt>
            <dd class="pb-2">In diesem Bereich sieht du, ob bereits eine Verbindung zu Twitter hergestellt wurde oder ob diese
            noch eingerichtet werden muss. Wurde die Verbindung noch die aktiviert, so findest du hier den Button
            <strong>API-Schlüssel und/oder Token anfordern</strong>. Ansonsten steht hier, welcher Benutzername bei Twitter verwendet wird und die
            Verbindung kann hier deaktiviert werden.</dd>
            <dt>Tweet zu Artikel erzeugen beim:</dt>
            <dd class="pb-2">Über die beiden Punkte kann festgelegt werden, wann neue Tweets nur beim Veröffentlichen bzw. Ändern
            eines Artikels oder beiden Aktionen erzeugt werden sollen.</dd>
            <dt>Zugangsdaten:</dt>
            <dd class="pb-2">Diese Eingabe-Felder beinhalten die Informationen, welche für den erfolgreichen Zugriff auf die
            Twitter-API benötigt werden. Welche Daten hier eingetragen werden müssen, erfährst du in der Hilfe zur Einrichtung.</dd>
        </dl>

        <h3 class="pt-5 fs-1">Systemprüfung</h3>
        
        <p>Über die Systemprüfung kannst du deine aktuelle Installation auf mögliche Fehlkonfigurationen prüfen lassen.</p>
        
        <p>Die Prüfung erfolgt erstmalig bei der Installation und sorgt dafür, dass alle notwendigen Module von PHP verfügbar sind,
        die Unterordner im <em>/data</em>-Verzeichnis beschreibbar sind etc.</p>
        
        <p>Die Systemprüfung unterscheidet zwischen zwingend erforderlichen Punkten. 
            Dies sind z. B. eine bestimmte PHP- oder MariaDB/Postgres-Version, bestimmte Funktionen von PHP oder 
            Schreibrechten auf die Ordner unter im <em>/data</em>-Verzeichnis. Wurden diese Punkte nicht erfolgreich 
            geprüft, kann es bei der Nutzung von FanPress CM zu Fehlern kommen oder diese gar nicht möglich sein.</p>

        <p>Punkte, welche mit <em>optional</em> gekennzeichnet sind, müssen nicht zwangsläufig erfüllt sein, erhöhen jedoch den
            Komfort, die Sicherheit oder die Performance.</p>

        <p>Bei der Prüfung auf einen verfügbaren MariaDB- oder Postgres-Datenbanktreiber ist es ausreichend, dass eine der beiden
            Datenbanken verfügbar ist.</p>

        <p>Der Startbildschirm-Container "systemprüfung" umfasst eine Kurzzusammenfassung der Systemprüfung.</p>
        ]]>
    </chapter>
    <chapter ref="HL_OPTIONS_USERS">
        <![CDATA[

        <p>Mit den entsprechenden Rechten können Benutzer und Benutzer-Rollen verwaltet werden.</p>

        <h3 class="pt-5 fs-1">Benutzer</h3>
        
        <ul>
            <li>Über Benutzer wird der Zugriff auf den Admin-Bereich gesteuert, sowie dokumentiert, wer welchen Artikel,
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
        
        <h3 class="pt-5 fs-1">Benutzer-Rollen</h3>
        
        <p>Ein Benutzer ist immer Mitglied einer Rolle, über deren Berechtigungen der Zugriff des Benutzers auf bestimmte Funktionen
        gesteuert wird.</p>
        
        <h4>Codex</h4>
        
        <p>Mit Version 4.5 wurde die Möglichkeit eingeführt, einen <b>Codex</b> für Gruppen zu definieren. Dieser dient dazu, Benutzern
        der gewählten Rolle bspw. Tipps zu Quellen, Hinweise beim Verfassen von Artikeln oder sonstige Anmerkungen zu geben.</p>

        <h3 class="pt-5 fs-1">Berechtigungen</h3>

        <p>Benutzer mit entsprechenden Rechten können hier die Zugriffsrechte auf verschiedene Dinge von FanPress CM ändern und
        den Zugriff einschränken. Der Bereich sollte nur von Administratoren nutzbar sein! Der Rolle "Administrator" kann der
        Zugriff auf die Rechte-Einstellungen nicht verweigert werden.</p>
        
        <dl>
            <dt>Artikel schreiben</dt>
            <dd class="pb-2">Funktion zum Artikel verfassen freigeben</dd>
            <dt>Eigene Artikel Bearbeiten</dt>
            <dd class="pb-2">Benutzer kann nur eigene Artikel bearbeiten</dd>
            <dt>Aktive Artikel Bearbeiten</dt>
            <dd class="pb-2">Benutzer kann aktive Artikel bearbeiten</dd>
            <dt>Artikel löschen</dt>
            <dd class="pb-2">Benutzer kann Artikel löschen</dd>
            <dt>Artikel archivieren und im Archiv bearbeiten</dt>
            <dd class="pb-2">Benutzer kann Artikel archiviert und danach noch bearbeiten</dd>
            <dt>Artikel müssen freigeschaltet werden</dt>
            <dd class="pb-2">Artikel der Benutzer müssen vor der Veröffentlichung geprüft werden</dd>
            <dt>Revisionen verwalten</dt>
            <dd class="pb-2">Benutzer können Revisionen löschen/wiederherstellen</dd>
            <dt>Artikel-Autor ändern</dt>
            <dd class="pb-2">Benutzer können Autor eines Artikels ändern</dd>
            <dt>Artikel in Masse bearbeiten</dt>
            <dd class="pb-2">Benutzer können Artikel in Masse bearbeiten</dd>
            <dt>Kommentare auf eigene Artikel bearbeiten</dt>
            <dd class="pb-2">Benutzer kann nur Kommentare auf eigene Artikel
            bearbeiten</dd>
            <dt>Kommentare auf alle Artikel bearbeiten</dt>
            <dd class="pb-2">Benutzer kann nur Kommentare auf alle Artikel
            bearbeiten</dd>
            <dt>Kommentare löschen</dt>
            <dd class="pb-2">Benutzer kann Kommentare löschen</dd>
            <dt>Kommentare genehmigen</dt>
            <dt>Kommentare auf "Privat" setzen</dt>
            <dd class="pb-2">Der Benutzer kann den Kommentar-Status auf
            für Spam, Genehmigt und Privat ändern.</dd>
            <dt>Kommentare zu anderem Artikel verschieben</dt>
            <dd class="pb-2">Kommentare können vom aktuellen zu einem anderen
            Artikel verschieben</dd>
            <dt>Kommentare in Masse bearbeiten</dt>
            <dd class="pb-2">Benutzer können Kommentare in Masse bearbeiten</dd>
            <dt>IP-Adresse des Kommentars sperren</dt>
            <dd class="pb-2">Benutzer können die IP-Adresse des Kommentar-Authors sperren</dd>
            <dt>Systemeinstellungen verwalten</dt>
            <dd class="pb-2">Diese Berechtigung legt zentral fest, ob der Benutzer auf
            die Systemeinstellungen zugreifen kann.</dd>
            <dt>Benutzer verwalten</dt>
            <dt>Benutzer-Rollen verwalten</dt>
            <dd class="pb-2">Benutzer kann Benutzer und Rollen verwalten</dd>
            <dt>Kategorien verwalten</dt>
            <dd class="pb-2">Benutzer kann neue Kategorien anlegen oder
            bestehende bearbeiten/ löschen</dd>
            <dt>Berechtigungen verwalten</dt>
            <dd class="pb-2">Hierüber kann geregelt werden, ob ein Benutzer
            die Berechtigungen ändern kann. Für die Gruppe "Administratoren" kann dieses Recht
            nicht entzogen werden.</dd>
            <dt>Templates verwalten</dt>
            <dd class="pb-2">Benutzer kann die Templates und Vorlagen bearbeiten</dd>
            <dt>Smileys verwalten</dt>
            <dd class="pb-2">Benutzer kann Smileys neu definieren und bestehende löschen</dd>
            <dt>Updates durchführen</dt>
            <dd class="pb-2">Benutzer kann verfügbare Updates installieren.</dd>
            <dt>System-Protokolle verwalten</dt>
            <dd class="pb-2">Benutzer kann die vom System erzeugten Protokolldateien einsehen und bei Bedarf bereinigen</dd>
            <dt>Cronjobs verwalten</dt>
            <dd class="pb-2">Benutzer kann Cronjobs verwalten</dd>
            <dt>Backups verwalten</dt>
            <dd class="pb-2">Benutzer kann Datenbank-Backups verwalten</dd>
            <dt>Textzensur verwalten</dt>
            <dd class="pb-2">Benutzer kann Begriffe der Textzensur verwalten</dd>
            <dt>IP-Adressen verwalten</dt>
            <dd class="pb-2">Benutzer kann IP-Adress-Sperren verwalten</dd>
            <dt>Profil ändern</dt>
            <dd class="pb-2">Benutzer kann sein Profil ändern</dd>
            <dt>Module aktivieren/deaktivieren</dt>
            <dd class="pb-2">Benutzer kann aktivieren und deaktivieren</dd>
            <dt>Module installieren</dt>
            <dd class="pb-2">Benutzer kann installieren und aktualisieren</dd>
            <dt>Module deinstallieren</dt>
            <dd class="pb-2">Benutzer kann deinstallieren</dd>
            <dt>Dateimanager ist sichtbar</dt>
            <dd class="pb-2">Der Dateimanager ist für die Benutzer sichtbar und kann
            über den Editor aufgerufen werden.</dd>
            <dt>Dateien hochladen</dt>
            <dd class="pb-2">Benutzer kann neue Dateien hochladen</dd>
            <dt>Dateien löschen</dt>
            <dd class="pb-2">Benutzer kann Dateien löschen</dd>
            <dt>Thumbnails erzeugen</dt>
            <dd class="pb-2">Benutzer kann Thumbnails für vorhandene
            Dateien neu erzeugen</dd>
            <dt>Dateien umbenennen</dt>
            <dd class="pb-2">Benutzer kann Dateien umbenennen</dd>
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

        <h3 class="pt-5 fs-1">Sperren einrichten</h3>

        <ul>
            <li>Um eine <strong>einzelne</strong> IP-Adresse (z. B. 192.168.1.2) zu sperren, trage diese komplett in das Feld <em>IP-Adresse</em> ein.</li>
            <li>Um <strong>mehrere IP-Adressen bzw. einen Bereich</strong> zu sperren (z. B. 192.168.2.1, 192.168.2.11, 192.168.2.111), ersetzte die 
                entsprechenden Stellen Zahl(en) durch einen <em>*</em> (z. B. 192.168.2.* oder 192.168.*.*) und trage dies in das Feld <em>IP-Adresse</em> ein.</li>
            <li>Für IPv6-Adressen (aa11::22bb:cc33:d4d4:e5e5) gehen analog vor, ersetze hier die Stellen zwischen den Doppelpunkten.</li>
        </ul>

        <h3 class="pt-5 fs-1">Aktionen</h3>
        
        <dl>
            <dt>Keine Kommentare schreiben</dt>
            <dd class="pb-2">Der Besucher mit der angegebenen IP-Adresse kann keine
            Kommentare verfassen, wenn diese nicht für den Artikel oder generell deaktiviert sind.</dd>
            <dt>Kein ACP-Login</dt>
            <dd class="pb-2">Der Besucher mit der angegebenen IP-Adresse kann sich nicht in FanPress-CM
                einloggen bzw. hat keinen Zugriff auf die Login-Maske.</dd>
            <dt>Kein Frontend-Zugriff</dt>
            <dd class="pb-2">Dem Besucher mit der angegebenen IP-Adresse werden veröffentlichte Artikel, Kommentare,
            etc. nicht angezeigt. Der weitere Zugriff auf deine Seite kann von anderen Faktoren abhängen.</dd>
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
            <dd class="pb-2">Ist diese Checkbox markiert, so wird die entsprechende Textstelle durch den angegeben
            Text ersetzt. Die Textzensur wird beim Erstellen von Kommentaren, Artikeln, Kategorien, Benutzern und Benutzer-Rollen ausgeführt.</dd>
            <dt>Artikel muss überprüft werden:</dt>
            <dd class="pb-2">Durch diese Option wird beim Speichern eines Artikels geprüft, ob die entsprechende Phrase
            enthalten ist. In diesem Fall wird - unabhängig von den eingestellten Berechtigungen - der Artikel markiert, dass er freigeschaltet
            werden muss.</dd>
            <dt>Kommentar muss freigeschaltet werden:</dt>
            <dd class="pb-2">Analog zur Option <em>Artikel muss überprüft werden</em>, allerdings wird hier der
            entsprechende Kommentar markiert, dass er manuell freigegeben werden muss.</dd>
        </dl>
        ]]>
    </chapter>
    <chapter ref="HL_CATEGORIES_MNG">
        <![CDATA[
        <ul>
            <li>Kategorien ermöglichen die Einsortierung von Artikeln nach bestimmten Stichworten bzw. Themengebieten. Insbesondere bei der Suche
            nach Artikeln ermöglicht dies eine Beschleunigung der Suche.</li>
            <li>Benutzer mit entsprechenden Rechten können neue Kategorien anlegen, sowie Bestehende ändern oder auch löschen.</li>
            <li>Der Zugriff auf Kategorien kann auf bestimmte Benutzergruppen beschränkt werden.</li>
            <li>Der Button <strong>Bearbeiten</strong> öffnet die Massenbearbeitung. Über diese können alle ausgewählten Kategorien
            auf einmal verändert werden.</li>
        </ul>
        
        <dl>
            <dt>Kategorie-Name:</dt>
            <dd class="pb-2">Der Kategorie-Name wird im Artikel-Editor angezeigt und kann zudem über den Platzhalter
            <em>{{categoryTexts}}</em> im Frontend ausgegeben werden.</dd>
            <dt>Kategorie-Icon:</dt>
            <dd class="pb-2">Hierfür kann eine Bild-Datei auf einem externen Server oder lokal auf deinem Webspace verwendet
            werden. In beiden Fällen sollte die vollständige URL angegeben werden. Die Anzeige der vergebenen Icons erfolgt im Frontend über
            den Platzhalter <em>{{categoryIcons}}</em>.</dd>
            <dt>Verfügbar für Rollen:</dt>
            <dd class="pb-2">Über diese Einstellung wird festgelegt, welche Benutzer eine bestimmte Kategorie nutzen kann.</dd>
        </dl>
        ]]>
    </chapter>
    <chapter ref="HL_OPTIONS_TEMPLATES">
        <![CDATA[
        <p>Benutzer mit entsprechenden Rechten können die Templates zur Ausgabe von Artikeln, Kommentaren etc. bearbeiten.
        Für eine bessere Übersicht bietet der Template-Editor Syntax-Highlighting und eine Liste der verfügbaren Platzhalter.</p>

        <h3 class="pt-5 fs-1">Templates</h3>
        
        <dl>
            <dt>Artikel-Liste:</dt>
            <dd class="pb-2">Template für Anzeige von Artikeln in der Artikel-Liste.</dd>
            <dt>Artikel-Einzel-Ansicht:</dt>
            <dd class="pb-2">Template für Anzeige eines einzelnen Artikels inkl. dessen Kommentaren, dem
            Kommentar-Formular etc. Dieser Tab wird nicht angezeigt, wenn für <em>Artikel-Liste</em> und <em>Artikel-Einzel-Ansicht</em>
            das gleiche Template genutzt wird.</dd>
            <dt>Kommentar:</dt>
            <dd class="pb-2">Template für die Anzeige eines einzelnen Kommentars im Frontend.</dd>
            <dt>Kommentar-Formular:</dt>
            <dd class="pb-2">Template für das Formular zum Verfassen eines Kommentars.</dd>
            <dt>Share-Buttons:</dt>
            <dd class="pb-2">Template für die Darstellung der Share-Buttons in Artikeln.</dd>
            <dt>Latest News:</dt>
            <dd class="pb-2">Template für die einzelnen Zeilen in den "Latest News".</dd>
            <dt>Tweet:</dt>
            <dd class="pb-2">HTML-freies Template für automatisch erzeugte Einträge bei Twitter (Tweets).</dd>
            <dt>Vorlagen:</dt>
            <dd class="pb-2">HTML-Vorlagen zu Nutzung im Artikel-Editor. (TinyMCE bzw. HTML-Ansicht).</dd>
        </dl>
        
        <h3 class="pt-5 fs-1">Editor</h3>
        
        <dl>
            <dt>Verwendbare Platzhalter:</dt>
            <dd class="pb-2">Die Verwendbaren Platzhalter können durch einen Klick auf das Plus-Icon in das ausgewählte
            Template eingefügt werden. Die Platzhalter werden später durch die entsprechenden Inhalte ersetzt.</dd>
            <dt>Erlaubte HTML-Tags:</dt>
            <dd class="pb-2">Die erlaubten HTML-Tags umfasst die HTML-Elemente, welche in Templates genutzt werden können.
            Alle anderen Templates werden beim Speichern gefiltert.</dd>
            <dt>Editor:</dt>
            <dd class="pb-2">Der Editor basiert ebenfalls auf CodeMirror und arbeitet ähnlich wie der Artikel-Editor.</dd>
            <dt>Vorschau anzeigen:</dt>
            <dd class="pb-2">Der Button <strong>Vorschau anzeigen</strong> ermöglicht es, den im Editor vorhandenen Template-Inhalt
            als Vorschau anzeigen zu lassen und somit die Wirkung, Formatierungsfehler etc. sofort zu erkennen.</dd>
        </dl>
        
        <h3 class="pt-5 fs-1">Vorlagen</h3>
        
        <ul>
            <li>Vorlagen sind HTML-Dateien, deren Inhalt im Artikel-Editor verwendet werden kann.</li>
            <li>Hiermit können wiederkehrende Artikel-Inhalte gesichert und immer in der gleichen Art wiederverwendet werden.</li>
            <li>Die Vorlagen können durch den Klick auf den Button <strong>Bearbeiten</strong> über einen CodeMirror-basierten Editor
            angepasst werden. Weitere können bei Bedarf ins System hochgeladen werden.</li>
        </ul>

        <h3 class="pt-5 fs-1">Verfügbare Attribute</h3>
        
        <p>Die Template-Platzhalter können seit Version 4.1 <em>Attribute</em> besitzen, welche die Frontend-Ausgabe weiter beeinflussen. Attribute werden in der Form
        <em>AttributeName="AttributeWert"</em> angegeben. Platzhalter können mehrere Attribute besitzen, wobei mehrere gleichzeitig verwendet werden können.
        Attribute können sich gegenseitig erfordern oder ausschließen.</p>
        
        <dl>
            <dt>Artikel-Templates - {{sources}}:</dt>
            <dd class="pb-2">
                <dl>
                    <dt>descr:</dt>
                    <dd class="pb-2">Beschreibung vor der Ausgabe der Links aus dem Quellenverzeichnis.</dd>
                </dl>
                <dl>
                    <dt>descrAlt:</dt>
                    <dd class="pb-2">Alternativer Wert für die Ausgabe, wenn im Quellenverzeichnis keine Daten eingetragen sind.</dd>
                </dl>
                <dl>
                    <dt>hideEmpty:</dt>
                    <dd class="pb-2">Quellenverzeichnis-Ausgabe unterdrücken, wenn im Quellenverzeichnis keine Daten eingetragen sind,
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
            <dd class="pb-2">Der Smiley-Code wird in Artikeln und Kommentaren als Platzhalter für die entsprechende
            Grafik verwendet. Die Ersetzung erfolgt beim Parsen eines Artikels bzw. Kommentars im Frontend. Jeder Smiley-Code kann nur
            einmal angelegt werden.</dd>
            <dt>Dateiname:</dt>
            <dd class="pb-2">Dieses Feld umfasst nur den Dateinamen der entsprechenden Smiley-Grafik, welche unter
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
            <li>Die Häufigkeit der Ausführung eines Cronjobs kannst du anpassen, indem der Wert für das Intervall angepasst wird.</li>
            <li>Beachte bei der Änderung des Intervalls, dass Cronjobs u. U. für erhöhte Serverlast führen kann.</li>
        </ul>

        <dl>
            <dt>Artikel-Revisionen bereinigen:</dt>
            <dd class="pb-2">Wurde die Option <em>Alte Revisionen löschen, wenn älter als</em> auf einen Wert ungleich <em>Nie</em> gesetzt,
            bereinigt dieser Cronjob die Artikel-Revisionen im eingestellten Intervall. Standardmäßig erfolgt dies einmal im Monat.</dd>

            <dt>Dateiindex neu aufbauen:</dt>
            <dd class="pb-2">Standardmäßig einmal pro Tag wird der Dateiindex, d. h. die Informationen über hochgeladene Bilder
            neu aufgebaut. Hierbei werden gelöschte Dateien entfernt und ggf. neu hochgeladene Bilder erfasst, sollte dies beim Upload nicht automatisch erfolgt sein.</dd>

            <dt>Dateimanager-Thumbnails erzeugen:</dt>
            <dd class="pb-2">Dieser Cronjob erzeugt die Vorschaubilder im Dateimanager neu. (Standard: einmal pro Woche)</dd>

            <dt>geplante Artikel veröffentlichen:</dt>
            <dd class="pb-2">Dieser Cronjob wird im Standard alle zehn Minuten ausgeführt und sorgt für die automatische Veröffentlichung von
            geplanten Artikeln. Wurde dieser Cronjob deaktiviert, müssen alle Artikel - auch solche die im Editor oder den Listen als geplant angezeigt werden - manuell
            veröffentlicht werden.</dd>

            <dt>IP-Adressen aus Kommentaren anonymisieren:</dt>
            <dd class="pb-2">Über diesen Cronjob erfolgt die regelmäßig Anonymisierung der IP-Adressen in Kommentaren. Hierdurch wird die Personenbindung
            aufgehoben, allerdings lässt sich dann auch nicht mehr erkennen, woher der Kommentar genau kam. Die Ausführung erfolgt per Default einmal im Monat.</dd>

            <dt>Papierkorb bereinigen:</dt>
            <dd class="pb-2">Werden Artikel oder Kommentare gelöscht, so werden diese zuerst in den Papierkorb verschoben. Dieser Automatismus
            führt eine regelmäßige Bereinigung der Papierkörbe durch.</dd>
            
            <dt>Prüfung auf Updates:</dt>
            <dd class="pb-2">Die Prüfung auf System- und Modul-Updates wird durch diesen Cronjob durchgeführt. Die Ausführung erfolgt nur, wenn FanPress CM
            sich zum Update-Server verbinden kann. Die Prüfung erfolgt standardmäßig einmal täglich.</dd>

            <dt>System-Datenbank sichern:</dt>
            <dd class="pb-2">Die Sicherung der System-Datenbank dient dieser Cronjob. Die erzeugten Backups werden im Verzeichnis <em>/data/dbdump</em> abgelegt
            und können über den Backup-Manager verwaltet werden. Im Standard erfolgt die Sicherung einmal pro Woche. Bei hohem Artikel-Aufkommen sollte das Intervall entsprechend
            reduziert werden.</dd>

            <dt>System-Protokolle leeren:</dt>
            <dd class="pb-2">Die Protokoll-Dateien können unter Umständen sehr groß werden, daher werden diese (im Standard monatlich) auf ihre Dateigröße geprüft und
            bei Überschreitung einer Größe von 1 MB bereinigt. Das vorherige Log wird gespeichert. Die Bereinigung des Session-Protokolls erfolgt unabhängig von der Anzahl der Einträge.</dd>

            <dt>temporäre Dateien aufräumen:</dt>
            <dd class="pb-2">Bei Updates, sowie in der täglichen Arbeit fallen immer wieder temporäre Dateien an, welche unter Umständen nicht sofort bereinigt werden
            (können). Dieser Cronjob prüft auf entsprechende Dateien und löscht diese; im Standard einmal pro Woche.</dd>
        </dl>
        ]]>
    </chapter>
    <chapter ref="HL_LOGS">
        <![CDATA[
        <p>Im Bereich <em>Protokolle</em> findest du eine Auflistung aller bisherigen Benutzer-Logins, System-Meldungen von FanPress und
            Fehlermeldungen durch PHP selbst oder der Datenbank. Über den Button <strong>Leeren</strong> kannst du Meldungen etc. löschen
            lassen.</p>
        
        <dl>
            <dt>Sessions:</dt>
            <dd class="pb-2">Dieses Protokoll zeigt eine Übersicht über die Logins aller Benutzer, abgesehen von aktuell aktiven Sessions, z. B. deiner eigenen.
            Angezeigt werden alle relevanten Informationen, d. h. wer hat sich, wann ein- bzw. ausgeloggt. Externe Logins erfolgten über die Funktionen der FanPress CM-API. Der
            User-Agent enthält Informationen, mit welchem Browser oder Programm der Login erfolgte.</dd>
            <dt>Sonstige:</dt>
            <dd class="pb-2">Dieses Protokoll beinhaltet allgemeine Meldungen von FanPress CM, Status-Meldungen und falls nötig Diagnose-Informationen.</dd>
            <dt>Ereignisse:</dt>
            <dd class="pb-2">In diesem Protokoll werden allen von Fehlern, welche im Betrieb, bei Updates, Änderungen der Systemkonfiguration etc. auftreten.
            Fatale PHP_Fehler können hier unter Umständen nicht angezeigt werden, da die System-Protokollierung zu diesem Zeitpunkt noch nicht greift. Nicht alle Einträge sind
            zwangsläufig kritisch. Bei Fragen lassen uns einfach eine Nachricht zukommen.</dd>
            <dt>Datenbank:</dt>
            <dd class="pb-2">Die Datenbank-Protokolldatei enthält weitergehende Informationen zu Ereignissen auf Datenbank-Seite, z. B. fehlschlagende Abfragen, fehlschlagende
            Verbindungen zum Datenbank-Server etc.</dd>
            <dt>Cronjobs:</dt>
            <dd class="pb-2">Hier werden Status-Informationen, Laufzeiten etc. zu ausgeführten Cronjobs protokolliert. Dieses Log kann in Abhängigkeit von der
            Cronjob-Konfiguration sehr schnell stark wachsen und sollte daher regelmäßig bereinigt werden.</dd>
            <dt>Paketmanager:</dt>
            <dd class="pb-2">Dieses Protokoll beinhaltet Status-Informationen zu System-Updates, sowie Installation oder Updates von Modulen.
            Im Paketmanager-Protokoll werden Dateien ohne Änderungen ausgegraut angezeigt, andere Einträge sind schwarz.</dd>
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

        <h3 class="pt-5 fs-1">Register</h3>

        <dl>
            <dt>Installierte Module:</dt>
            <dd class="pb-2">In diesem Register werden alle Module angezeigt, die im System verfügbar sind. Dies können
            Module aus dem öffentlichen Repository oder eigene Modul sein, welche dort noch nicht verfügbar sind.</dd>
            <dt>Verfügbare Module:</dt>
            <dd class="pb-2">Die verfügbaren Module listen alle Modul auf, welche im öffentlichen Repository verfügbar sind.
            Diese Module werden vor der Veröffentlichung geprüft. Über das öffentliche Repository werden auch Aktualisierungen zur Verfügung 
            gestellt. Wurde ein Modul bereits installiert, so wird dieses in der Übersicht weiterhin angezeigt.</dd>
            <dt>Modul-Paketdatei hochladen:</dt>
            <dd class="pb-2">Über dieses Register können Module aus externen Quellen ins System hochgeladen werden. Die
            als ZIP-Archive gepackten Module werden bei diesem Vorgang automatisch entpackt. Die Nutzung der Upload-Funktion kann notwendig
            werden, wenn dein Host keine Verbindung zu externen Servern zulässt. Dieses Register ist standardmäßig deaktiviert und muss
            durch Anpassung der Konstante "FPCM_DISABLE_MODULE_ZIPUPLOAD" aktiviert werden.</dd>
        </dl>
        
        <h3 class="pt-5 fs-1">Aktionen</h3>

        <dl>
            <dt>Modul-Detail-Informationen:</dt>
            <dd class="pb-2">Die Detail-Informationen zum Modul (siehe unten) können über diesen
            angezeigt werden.</dd>
            <dt>Modul installieren:</dt>
            <dd class="pb-2">Über diesen Button kann ein Modul installiert werden.</dd>
            <dt>Modul deinstallieren:</dt>
            <dt>Modul löschen:</dt>
            <dd class="pb-2">Die Deinstallation eines Moduls entfernt alle durch das Modul angelegten
            Tabellen, Konfigurationsoptionen etc. Die Dateien selbst werden dabei nicht gelöscht. Dies muss durch den
            Button <strong>Modul löschen</strong> erfolgen.</dd>
            <dt>Modul aktualisieren:</dt>
            <dd class="pb-2">Ist im öffentlichen Repository eine neuere Version des Modules
            verfügbar, so kann die Aktualisierung des Moduls über diesen Button erfolgen. Der Button erscheint ebenfalls, wenn die Modul-Version in der
            Datenbank und im Dateisystem des Servers nicht übereinstimmen. Das kann nach manuellen Änderungen an Modul-Code passieren. Durch die
            Ausführung wird sichergestellt, dass die Datenbank dem Stand entspricht, welcher vom Modul funktional erwartet wird.</dd>
            <dt>Modul aktivieren:</dt>
            <dt>Modul deaktivieren:</dt>
            <dd class="pb-2">Nach der Installation eines Moduls muss dies aktiviert werden, so
            dass definierte Events, Aktionen etc. zur Verfügung stehen. Die Deaktivierung ermöglicht es, die Ausführung
            z. B. bei auftretenden Fehlern zu beenden, ohne das Modul komplett zu entfernen.</dd>
            <dt>Modul konfigurieren:</dt>
            <dd class="pb-2">Dieser Button wird angezeigt, wenn das Modul das Template <em>configure.php</em>
            beinhaltet.</dd>
        </dl>
        
        <p>Im Bereich der Aktionen werden im Bedarfsfall weitere Informationen angezeigt, z. B. bei fehlenden Abhängigkeiten oder Schreibrechten
        auf dem Server. Fahre in diesem Fall mit der Maus über das entsprechende Icon. Nach kurzer Zeit erscheint ein Tooltip inkl. weiteren Informationen.</p>
        
        <p>Sind für mehrere Module Aktualisierungen verfügbar, so erscheint in der Toolbar der Button "Updates für alle Module einspielen".
        Über diesen können - ohne zusätzlichen Wechsel zurück in den Modulmanager - alle Aktualisierungen nacheinander eingespielt werden.</p>

        <h3 class="pt-5 fs-1">Modul-Detail-Informationen</h3>

        <dl>
            <dt>Schlüssel:</dt>
            <dd class="pb-2">Dies ist die interne Bezeichnung des Moduls. Der Schlüssel muss einmalig sein.</dd>
            <dt>Name:</dt>
            <dd class="pb-2">Der Name ist eine nicht-technische Bezeichnung und gibt eine kurze Information, welchem Zweck das Modul dient.</dd>
            <dt>Version (lokal):</dt>
            <dt>Version (Server):</dt>
            <dd class="pb-2">Hier wird die Version angezeigt, welche lokal installiert und ggf. im öffentlichen
            Repository verfügbar ist. Beide Angaben können unterschiedliche Werte besitzen, in der Regel sollte der Wert der lokalen
            Version dem der Server-Version entsprechen.</dd>
            <dt>Autor:</dt>
            <dd class="pb-2">Die Autor-Angabe entspricht dem Entwickler des Moduls, dies kann ein Name,
            E-Mail-Adresse o.ä. sein.</dd>
            <dt>Info-Link:</dt>
            <dd class="pb-2">Diese Angabe enthält eine URL für weiteren Informationen zum Modul.</dd>
            <dt>Beschreibung:</dt>
            <dd class="pb-2">Dieser Wert beinhaltet eine erweiterte Kurzbeschreibung zum Modul.</dd>
            <dt>Erfordert PHP:</dt>
            <dd class="pb-2">Module können eine bestimmte PHP-Version voraussetzen, diese wird hier angegeben.</dd>
            <dt>Erfordert FanPress CM:</dt>
            <dd class="pb-2">Module können eine bestimmte FanPress CM -Version voraussetzen, diese wird hier angegeben.</dd>
        </dl>
        
        ]]>
    </chapter>
    <chapter ref="SYSTEM_HL_OPTIONS_TWITTER">
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
        <p>Im Papierkorb werden Elemente beim Löschen abgelegt, sodass sie bei Bedarf wiederherstellt werden können. Elemente im
        Papierkorb sollten nicht bearbeitet werden.</p>
        <p>Die Papierkörbe werden regelmäßig automatisch geleert.</p>
        <dl>
            <dt>Element wiederherstellen</dt>
            <dd class="pb-2">Über diese Option können die ausgewählten Elemente wiederherstellt werden. Dabei wird jeweils
            die letzte Version eines Artikels bzw. Kommentars wiederhergestellt.</dd>
            <dt>Papierkorb leeren</dt>
            <dd class="pb-2">Über die Auswahl dieser Option wird der Papierkorb komplett geleert. Eine Lösung einzelner
            Elemente ist nicht vorgesehen.</dd>
        </dl>
        ]]>
    </chapter>
    <chapter ref="HL_HELP_SUPPORT">
        <![CDATA[
        <p>Solltest du Fragen haben oder (technische) Hilfe benötigen, kannst du über verschiedene Wege Kontakt aufzunehmen.</p>
        <p>Unseren Bugtracker findest du auf <a rel="noreferrer,noopener,external" href="https://github.com/sea75300/fanpresscm4/issues" target="_blank">GitHub.com</a>, hier werden
        Weiterentwicklungen, Bugfixes und sonstige Fragen entgegengenommen. Alternativ schreibe eine E-Mail an
        <em>fanpress@nobody-knows.org</em> oder <em>sea75300@yahoo.de</em> oder hinterlasse unter
        <a rel="noreferrer,noopener,external" href="https://nobody-knows.org/download/fanpress-cm/" target="_blank">Nobody-Knows.org</a> einen Kommentar auf der Projekt-Seite.</p>

        <p>Das Modul <em>FanPress CM Support</em> erstellt während der Installation einen Benutzer-Zugang zur deiner FanPress CM Installation
        und übermittelt einige grundlegende System-Informationen.</p>
        ]]>
    </chapter>
    <chapter ref="IMPORT_MAIN">
        <![CDATA[
        <p>Der CSV-Import dient der Übernahme von Daten aus anderen Content-Management-Systemen nach FanPress CM mittels 
        <a rel="noreferrer,noopener,external" href="https://de.wikipedia.org/wiki/CSV_(Dateiformat)" target="_blank">CSV-Datei</a>.</p>
        
        <h3 class="pt-5 fs-1">Einstellungen</h3>
        
        <p>Bevor der Import gestartet werden kann, müsse verschiedene Einstellungen festgelegt werden.</p>
        
        <dl>
            <dt>Dateien hochladen</dt>
            <dd>Über das Upload-Formular kannst du eine CSV-Datei beliebiger Größe hochladen. Ohne Upload einer Datei kann kein Import
            gestartet werden. Wurde eine Datei erfolgreich hochgeladen, wird das Formular ausgeblendet und stattdessen der Dateiname
            angezeigt.</dd>

            <dt>Importieren nach</dt>
            <dd>Über diese Option wählst du aus, wohin die Daten importiert werden sollen. Die Liste kann aktuell (Stand: 12/2020) nicht
            individuell erweitert werden.</dd>

            <dt>Trennzeichen</dt>
            <dd>Die Datensätze von CSV-Dateien können mit unterschiedlichen Trennzeichen arbeiten, FanPress CM unterstützt ausschließlich Semikolon (;) bzw. Komma (,) verwendet.
            Wird kein Trennzeichen angegeben, so wird automatisch das Semikolon verwendet.</dd>

            <dt>Begrenzungszeichen</dt>
            <dd>Texte mit Leerzeichen oder den o. g. Trennzeichen müssen für einen korrekten Import so in der CSV-Datei hinterlegt werden, dass
            es beim Auswerten der Informationen nicht zu Seiteneffekten kommt. Hierzu dient das "Begrenzungszeichen", meistens sind dies Anführungszeichen (&quot;).</dd>

            <dt>Erste Zeile überspringen</dt>
            <dd>CSV-Dateien beinhalten in der ersten Zeile meist die Beschreibung der Spaltenköpfe. Ist dies in der zu importierenden Datei nicht der Fall,
            so kann dies über diese Einstellung hinterlegt werden.</dd>

            <dt>Verfügbare Felder / Reihenfolge in CSV-Datei</dt>
            <dd>Anhand der Einstellung "Importieren nach" erscheint hier eine Liste mit Feldern, welche durch den CSV-Import befüllt werden können.
            Um ein Feld zu befüllen, ziehe es von der Liste "Verfügbare Felder" in den Bereich "Reihenfolge in CSV-Datei". Anhand der Reihenfolge
            legst du zudem fest, mit welchem Inhalt ein bestimmtes Feld befüllt wird.<br>
            <em>Beispiel:</em>
                <ol>
                    <li>Artikel > Überschrift: wird die Daten aus Spalte 1 der CSV-Datei befüllt</li>
                    <li>Artikel > Artikeltext: wird die Daten aus Spalte 2 der CSV-Datei befüllt</li>
                    <li>Artikel > Veröffentlichung: wird die Daten aus Spalte 3 der CSV-Datei befüllt</li>
                </ol>            
            </dd>
        </dl>

        <h3 class="pt-5 fs-1">Aufbereitung von Import-Daten</h3>
        <p>In Abhängigkeit vom ursprünglichen CMS ist im Regelfall eine vorherige Aufbereitung der Daten notwendig.</p>
        
        <dl>
            <dt>Datumsangabe</dt>
            <dd>Datumsangaben müssen im Format YYYY-MM-DD HH:II (bspw. 2020-01-01 09:00) importiert werden - andere
            Datumsformate werden nicht unterstützt!</dd>

            <dt>Checkboxen</dt>
            <dd>Die Werte von Checkboxen bzw. Js/ Nein-Werte müssen in der CSV-Datei immer mit "0" (Nein) bzw. "1" (Ja) abgebildet werden.</dd>

            <dt>Listenwerte</dt>
            <dd>Die Werte aus Selectboxen (bspw. Artikel-Autor) wird im Regelfall über einen Index-Wert abgebildet. Dies kann bspw.
            die interne ID (Benutzer, Kategorien, Artikel-Verknüpfung in Kommentaren) sein. </dd>

            <dt>Artikel-Kategorien</dt>
            <dd>Artikel können mehrere Kategorien besitzen. Der Import erfolgt anhand der internen ID der Katgorien.
            Diese ermittelst du bspw. in der Adresszeile deines Browsers nach dem Parameter "id"
            (bspw. index.php?module=categories/edit&id=<b>1</b>). Trenne mehrere Kategorien durch ein Semikolon zwischen
            den IDs (z. B. "1;2;5").</dd>

            <dt>Kommentare zu Artikel zuweisen</dt>
            <dd>Kommentare können via CSV-Import bei einem Artikel abgelegt werden. Hierzu muss beim Import für jeden Kommentar
            die interne Artikel-ID hinterlegt sein.</dd>

            <dt>Kategorien zur Rollen zuweisen</dt>
            <dd>Analog zu Artikeln können Kategorien für mehrere Rollen verfügbar sein. Der Import erfolgt anhand der internen ID der Rollen.
            Diese ermittelst du bspw. in der Adresszeile deines Browsers nach dem Parameter "id"
            (bspw. index.php?module=users/editroll&id=<b>4</b>). Trenne mehrere Rollen durch ein Semikolon zwischen
            den IDs (z. B. "1;2;3"). Die standardmäßig mitgelieferten Rollen haben folgende IDs:
                <ul>
                    <li>Administrator: 1</li>
                    <li>Redakteur: 2</li>
                    <li>Autor: 3</li>
                </ul>
            
            </dd>
        </dl>

        <h3 class="pt-5 fs-1">Hinweise</h3>
        
        <dl>
            <dt>Vermeidung von defekten Umlauten und Sonderzeichen</dt>
            <dd>Die zu importierenden Daten <strong>müssen</strong> im UTF-8-Zeichensatz vorliegen, andernfalls kann
            es zu defekten Umlauten und Sonderzeichen kommen! Der CSV-Import unterstützt keine Konvertierung von anderen Zeichensätzen (bspw.
            ISO-8895-1).</dd>

            <dt>Doppelter Import / Aktualisierung von Daten</dt>
            <dd>Der CSV-Import führt für eingeschränkt Prüfungen durch, ob ein zu importierender Datensatz bereits existiert.
            Ist ein Element also bereits vorhanden, kann es nach dem Import zu Dubletten kommen. Der Import unterstützt
            zudem keine Aktualisierung bereits vorhandener Daten, sondern ist in erster Linie für die initiale Befüllung gedacht.</dd>

            <dt>Vorschau</dt>
            <dd>Die Vorschau-Funktion zeigt dir, ob die Informationen aus der CSV-Datei den korrekten Feldern zugewiesen wurden.
            Aus der Datei werden maximal zehn Elemente herangezogen.</dd>

            <dt>Zurücksetzen</dt>
            <dd>Wurde die falsche CSV-Datei hochgeladen oder diese nach dem Upload nochmals verändert, starte den Import
            über den Button "Zurücksetzen" neu.</dd>

        </dl>

        ]]>
    </chapter>
</chapters>
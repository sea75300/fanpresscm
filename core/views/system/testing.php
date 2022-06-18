<!DOCTYPE HTML>
<HTML lang="de">
    <head>
        <title>FanPress CM News System</title>
        <meta charset="utf-8"> 
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="apple-touch-icon" sizes="180x180" href="http://debian10-vbox/fanpress4_bs/core/theme/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="http://debian10-vbox/fanpress4_bs/core/theme/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="http://debian10-vbox/fanpress4_bs/core/theme/favicon-16x16.png">        
        <link rel="shortcut icon" href="http://debian10-vbox/fanpress4_bs/core/theme/favicon.ico" />
        <link rel="manifest" href="http://debian10-vbox/fanpress4_bs/core/theme/manifest.json">
        <link rel="stylesheet prefetch" type="text/css" href="http://debian10-vbox/fanpress4_bs/lib/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet prefetch" type="text/css" href="http://debian10-vbox/fanpress4_bs/lib/fancybox/jquery.fancybox.min.css">
        <link rel="stylesheet prefetch" type="text/css" href="http://debian10-vbox/fanpress4_bs/lib/font-awesome/css/all.min.css">
        <link rel="stylesheet prefetch" type="text/css" href="http://debian10-vbox/fanpress4_bs/core/theme/style.php">

        <script src="http://debian10-vbox/fanpress4_bs/lib/jquery/jquery-3.6.0.min.js" rel="prefetch"></script>
        <script src="http://debian10-vbox/fanpress4_bs/lib/bootstrap/js/bootstrap.bundle.min.js" rel="prefetch"></script>
        <script src="http://debian10-vbox/fanpress4_bs/lib/bs-autocomplete/autocomplete.js" rel="prefetch"></script>
        <script src="http://debian10-vbox/fanpress4_bs/lib/fancybox/jquery.fancybox.min.js" rel="prefetch"></script>
        <script src="http://debian10-vbox/fanpress4_bs/core/js/script.php?uq=5493eeb7c825beb8d6d7c66aca4fb778a581f37bf091608beb487e9d6026ca2e" rel="prefetch"></script>
        <script>fpcm.system.mergeToVars({"vars": {"ui": {"messages": [], "lang": {"GLOBAL_CONFIRM": "Bitte best\u00e4tigen", "GLOBAL_CLOSE": "Schlie\u00dfen", "GLOBAL_OK": "OK", "GLOBAL_YES": "Ja", "GLOBAL_NO": "Nein", "GLOBAL_SAVE": "Speichern", "GLOBAL_OPENNEWWIN": "In neuem Fenster \u00f6ffnen", "GLOBAL_EXTENDED": "Erweitert", "GLOBAL_EDIT_SELECTED": "Ausgew\u00e4hlte Bearbeiten", "GLOBAL_NOTFOUND": "Nicht gefunden", "SAVE_FAILED_ARTICLES": "Die \u00c4nderungen an den Artikeln konnte nicht gespeichert werden!", "AJAX_REQUEST_ERROR": "Beim Ausf\u00fchren der Aktion ist ein Fehler aufgetreten! Weitere Informationen findest du im Javascript-Log deines Browsers.", "AJAX_RESPONSE_ERROR": "Vom Server wurde eine ung\u00fcltige Antwort geliefert! Weitere Informationen findest du im Javascript-Log deines Browsers und ggf. im PHP-Log.", "CONFIRM_MESSAGE": "Willst du diese Aktion wirklich durchf\u00fchren?", "CACHE_CLEARED_OK": "Der Cache wurde geleert!", "SELECT_ITEMS_MSG": "Bitte w\u00e4hle Elemente oder eine Aktion aus!", "HL_HELP": "Hilfe", "CSRF_INVALID": "Das CSRF-Token ist ung\u00fcltig. Die Aktion wurde nicht durchgef\u00fchrt!", "HEADLINE": "FanPress CM News System", "calendar": {"days": ["Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag"], "daysShort": ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"], "months": ["Januar", "Februar", "M\u00e4rz", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"]}, "DASHBOARD_LOADING": "Dashboard-Container werden geladen", "SESSION_TIMEOUT": "Es wurde festgestellt, dass deine aktuelle Session abgelaufen ist. Willst du zur Login-Seite gehen? (w\u00e4hle \"Nein\" um auf der aktuellen Seite zu bleiben)"}, "notifyicon": "http:\/\/debian10-vbox\/fanpress4_bs\/core\/theme\/favicon-32x32.png", "components": {"icon": {"unstacked": "<span class=\"fpcm-ui-icon fpcm-ui-icon-single {{class}} {{prefix}} fa-fw fa-{{icon}} fa-{{size}} fa-{{spinner}}\"><\/span> ", "stacked": "<span class=\"fpcm-ui-icon-single {{class}} fa-stack fa-{{size}}\" >\n<span class=\"fa fa-{{stack}} fa-stack-2x\"><\/span>\n<span class=\"fpcm-ui-icon {{prefix}} fa-fw fa-{{icon}} fa-{{spinner}} fa-stack-1x\"><\/span>\n\n<\/span>", "stackedTop": "<span class=\"fpcm-ui-icon-single {{class}} fa-stack fa-{{size}}\" >\n\n<span class=\"fpcm-ui-icon {{prefix}} fa-fw fa-{{icon}} fa-{{spinner}} fa-stack-1x\"><\/span>\n<span class=\"fa fa-{{stack}} fa-stack-2x\"><\/span>\n<\/span>", "defaultPrefix": "fa"}, "input": "<div class=\"input-group mb-3\"><label title=\"{{text}}\" class=\"col-form-label pe-3  col-12 col-sm-6 col-md-4\" for=\"{{id}}\"><span class=\"fpcm-ui-label ps-1\">{{text}}<\/span><\/label><input type=\"{{type}}\" maxlength=\"255\" name=\"{{name}}\" id=\"{{id}}\"  class=\"fpcm-ui-input form-control {{class}}\" value=\"&lbrace;&lbrace;value&rcub;&rcub;\"    placeholder=\"{{placeholder}}\"   ><\/div>\n"}, "dialogTpl": "<div class=\"modal fade {$modalClass}\" id=\"{$id}\" tabindex=\"-1\" aria-labelledby=\"{$opener}\" aria-hidden=\"true\">\n    <div class=\"modal-dialog modal-dialog-centered modal-dialog-scrollable {$size} {$class}\">\n        <div class=\"modal-content shadow\">\n            <div class=\"modal-header bg-primary text-white\">\n                <h5 class=\"modal-title\" id=\"exampleModalLabel\">{$title}<\/h5>\n                <button type=\"button\" class=\"btn-close btn-close-white\" data-bs-dismiss=\"modal\" aria-label=\"Close\"><\/button>\n            <\/div>\n            <nav id=\"{$id}-navbar\" class=\"navbar navbar-light bg-white d-none\">\n                <ul class=\"nav nav-pills px-2\" role=\"tablist\"><\/u>\n            <\/nav>\n            <div class=\"modal-body position-relative p-2 {$modalBodyClass}\" data-bs-spy=\"scroll\" data-bs-target=\"#{$id}-navbar\" data-bs-offset=\"0\">\n                {$content}\n            <\/div>\n            <div class=\"modal-footer\">\n                {$buttons}\n            <\/div>\n        <\/div>\n    <\/div>\n<\/div>"}, "jsvars": {"sessionCheck": true, "currentModule": "system\/dashboard"}, "actionPath": "http:\/\/debian10-vbox\/fanpress4_bs\/index.php?module=", "ajaxActionPath": "http:\/\/debian10-vbox\/fanpress4_bs\/index.php?module=ajax\/"}});</script>    </head>    

    <body class="fpcm-body " id="fpcm-body">

        <div class="fpcm ui-wrapper">


            <header>
                <nav class="navbar navbar-expand navbar-dark bg-primary bg-gradient ui-navigation" id="fpcm-top-menu">
                    <div class="container-fluid g-0">

                        <div class="navbar-brand px-3 me-0">
                            <!-- FanPress CM News System 5.0-dev -->
                            <div class="border-bottom border-5 border-info d-inline-block">
                                <img src="http://debian10-vbox/fanpress4_bs/core/theme/logo.svg" role="presentation" alt="FanPress CM News System 5.0-dev" class="fpcm ui-invert-1">
                            </div>
                            <h1 class="d-none">FanPress CM News System</h1>
                        </div>

                        <div class="align-items-end">
                            <ul class="navbar-nav me-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="http://debian10-vbox/tester.php" title="Artikel auf Webseite anzeigen">
                                        <span class="fpcm-ui-icon fpcm-ui-icon-single fpcm-navicon fa fa-fw fa-play fa-lg "></span> 
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a id="fpcm-clear-cache" href="#" class="nav-link" title="Cache leeren">
                                        <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-hdd fa-lg "></span> 
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" title="Hilfe-Eintrag aufrufen" role="button" data-bs-toggle="offcanvas" data-bs-target="#fpcm-offcanvas-help">
                                        <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-question-circle fa-lg "></span> 
                                    </a>
                                </li>
                                <li class="nav-item dropdown me-2">
                                    <a class="nav-link dropdown-toggle" href="#" id="fpcm-notify-menu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="fpcm-ui-icon fpcm-ui-icon-single fpcm-navicon fa fa-fw fa-envelope fa-lg "></span> 

                                        <span class="d-none d-md-inline">Benachrichtigungen</span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="fpcm-notify-menu">
                                        <li id="fpcm-notification-item61255378a1db0" class="dropdown-item text-truncate"><span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-times fa-lg " title="12345"></span> 
                                            12345</li>
                                        <li id="fpcm-notification-item61255378a218d" class="dropdown-item fpcm-ui-important-text text-truncate"><span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-terminal fa-lg " title="Debug-Modus aktiv"></span> 
                                            Debug-Modus aktiv</li>

                                    </ul>
                                </li>
                                <li class="nav-item dropdown me-2">
                                    <a class="nav-link dropdown-toggle" href="#" id="fpcm-profile-menu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="fpcm-ui-icon fpcm-ui-icon-single fpcm-navicon fa fa-fw fa-user-circle fa-lg "></span> 

                                        <span class="d-none d-md-inline">Hallo Stefan.</span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="fpcm-profile-menu">
                                        <li class="dropdown-item fpcm-ui-font-small">
                                            <b>Du angemeldet seit:</b><br>
                                            24.08.2021 22:07 (Europe/Berlin)
                                        </li>
                                        <li class="dropdown-item fpcm-ui-font-small">
                                            <b>Deine IP-Adresse:</b><br>
                                            192.168.178.24                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li class="dropdown-item">
                                            <a class="text-truncate" href="http://debian10-vbox/fanpress4_bs/index.php?module=system/profile">
                                                <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-wrench  "></span> 
                                                Benutzerprofil                            </a>
                                        </li>
                                        <li class="dropdown-item">
                                            <a href="http://debian10-vbox/fanpress4_bs/index.php?module=system/info" rel="license">
                                                <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-info-circle  "></span> 
                                                Kontakt & Support                            </a>
                                        </li>
                                        <li class="dropdown-item">
                                            <a href="http://debian10-vbox/fanpress4_bs/index.php?module=system/logout">
                                                <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-sign-out-alt  "></span> 
                                                Abmelden                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <nav class="navbar navbar-expand-xl py-0 fpcm ui-background-white-50p ui-navigation" id="fpcm-navigation">

                    <div class="container-fluid">

                        <button class="navbar-toggler my-2 my-xl-0 mx-auto" type="button" data-bs-toggle="collapse" data-bs-target="#fpcm-navigation-menu" aria-controls="fpcm-navigation-menu" aria-expanded="false" aria-label="Menü">
                            <span class="fpcm-ui-icon fpcm-ui-icon-single py-2 fa fa-fw fa-bars  "></span> 
                        </button>

                        <div class="collapse navbar-collapse" id="fpcm-navigation-menu">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">



                                <li class="nav-item "  id="fpcm-nav-item-61255378a1ded">
                                    <a class="nav-link text-center p-3 fpcm ui-nav-link active"
                                       href="http://debian10-vbox/fanpress4_bs/index.php?module=system/dashboard"
                                       aria-current="page">

                                        <span class="d-block"><span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-home fa-lg  "></span> 
                                        </span>
                                        <span class="fpcm nav-text text-nowrap">Dashboard</span>
                                    </a>

                                </li>





                                <li class="nav-item "  id="fpcm-nav-item-61255378a1e02">
                                    <a class="nav-link text-center p-3 fpcm ui-nav-link "
                                       href="http://debian10-vbox/fanpress4_bs/index.php?module=articles/add"
                                       >

                                        <span class="d-block"><span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-pen-square fa-lg  "></span> 
                                        </span>
                                        <span class="fpcm nav-text text-nowrap">Artikel schreiben</span>
                                    </a>

                                </li>





                                <li class="nav-item dropdown"  id="fpcm-nav-item-editnews">
                                    <a class="nav-link text-center p-3 fpcm ui-nav-link dropdown-toggle"
                                       href="http://debian10-vbox/fanpress4_bs/index.php?module=#"
                                       role="button" data-bs-toggle="dropdown" aria-expanded="false"                       >

                                        <span class="d-block"><span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-book fa-lg  "></span> 
                                        </span>
                                        <span class="fpcm nav-text text-nowrap">Artikel verwalten</span>
                                    </a>


                                    <ul class="dropdown-menu shadow fpcm ui-blurring" aria-labelledby="itemfpcm-nav-item-editnews">

                                        <li id="fpcm-nav-item-61255378a1e14">
                                            <a class="dropdown-item nav-link px-2 "
                                               href="http://debian10-vbox/fanpress4_bs/index.php?module=articles/listall"
                                               >
                                                <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-book  "></span> 
                                                Alle Artikel                            </a>
                                        </li>

                                        <li id="fpcm-nav-item-61255378a1e1b">
                                            <a class="dropdown-item nav-link px-2 "
                                               href="http://debian10-vbox/fanpress4_bs/index.php?module=articles/listactive"
                                               >
                                                <span class="fpcm-ui-icon fpcm-ui-icon-single far fa-fw fa-newspaper  "></span> 
                                                Aktive Artikel                            </a>
                                        </li>

                                        <li id="fpcm-nav-item-61255378a1e21">
                                            <a class="dropdown-item nav-link px-2 "
                                               href="http://debian10-vbox/fanpress4_bs/index.php?module=articles/listarchive"
                                               >
                                                <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-archive  "></span> 
                                                Archivierte Artikel                            </a>
                                        </li>


                                    </ul>
                                </li>





                                <li class="nav-item "  id="fpcm-nav-item-editcomments">
                                    <a class="nav-link text-center p-3 fpcm ui-nav-link "
                                       href="http://debian10-vbox/fanpress4_bs/index.php?module=comments/list"
                                       >

                                        <span class="d-block"><span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-comments fa-lg  "></span> 
                                        </span>
                                        <span class="fpcm nav-text text-nowrap">Kommentare</span>
                                    </a>

                                </li>





                                <li class="nav-item "  id="fpcm-nav-item-61255378a1e33">
                                    <a class="nav-link text-center p-3 fpcm ui-nav-link "
                                       href="http://debian10-vbox/fanpress4_bs/index.php?module=files/list&mode=1"
                                       >

                                        <span class="d-block"><span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-folder-open fa-lg  "></span> 
                                        </span>
                                        <span class="fpcm nav-text text-nowrap">Dateimanager</span>
                                    </a>

                                </li>





                                <li class="nav-item dropdown"  id="fpcm-nav-item-options-submenu">
                                    <a class="nav-link text-center p-3 fpcm ui-nav-link dropdown-toggle"
                                       href="http://debian10-vbox/fanpress4_bs/index.php?module=#"
                                       role="button" data-bs-toggle="dropdown" aria-expanded="false"                       >

                                        <span class="d-block"><span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-cog fa-lg  "></span> 
                                        </span>
                                        <span class="fpcm nav-text text-nowrap">Optionen</span>
                                    </a>


                                    <ul class="dropdown-menu shadow fpcm ui-blurring" aria-labelledby="itemfpcm-nav-item-options-submenu">

                                        <li id="fpcm-nav-item-61255378a1e40">
                                            <a class="dropdown-item nav-link px-2 "
                                               href="http://debian10-vbox/fanpress4_bs/index.php?module=system/options"
                                               >
                                                <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-cog  "></span> 
                                                Systemeinstellungen                            </a>
                                        </li>

                                        <li id="fpcm-nav-item-users">
                                            <a class="dropdown-item nav-link px-2 "
                                               href="http://debian10-vbox/fanpress4_bs/index.php?module=users/list"
                                               >
                                                <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-users  "></span> 
                                                Benutzer & Rollen                            </a>
                                        </li>

                                        <li id="fpcm-nav-item-ips">
                                            <a class="dropdown-item nav-link px-2 "
                                               href="http://debian10-vbox/fanpress4_bs/index.php?module=ips/list"
                                               >
                                                <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-globe  "></span> 
                                                IP-Adressen                            </a>
                                        </li>

                                        <li id="fpcm-nav-item-wordban">
                                            <a class="dropdown-item nav-link px-2 "
                                               href="http://debian10-vbox/fanpress4_bs/index.php?module=wordban/list"
                                               >
                                                <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-ban  "></span> 
                                                Textzensur/ Autokorrektur                            </a>
                                        </li>

                                        <li id="fpcm-nav-item-categories">
                                            <a class="dropdown-item nav-link px-2 "
                                               href="http://debian10-vbox/fanpress4_bs/index.php?module=categories/list"
                                               >
                                                <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-tags  "></span> 
                                                Kategorien                            </a>
                                        </li>

                                        <li id="fpcm-nav-item-61255378a1e79">
                                            <a class="dropdown-item nav-link px-2 "
                                               href="http://debian10-vbox/fanpress4_bs/index.php?module=templates/templates"
                                               >
                                                <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-code  "></span> 
                                                Templates                            </a>
                                        </li>

                                        <li id="fpcm-nav-item-smileys">
                                            <a class="dropdown-item nav-link px-2 "
                                               href="http://debian10-vbox/fanpress4_bs/index.php?module=smileys/list"
                                               >
                                                <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-smile-beam  "></span> 
                                                Smileys                            </a>
                                        </li>

                                        <li id="fpcm-nav-item-61255378a1e85">
                                            <a class="dropdown-item nav-link px-2 "
                                               href="http://debian10-vbox/fanpress4_bs/index.php?module=system/crons"
                                               >
                                                <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-history  "></span> 
                                                Cronjobs                            </a>
                                        </li>

                                        <li id="fpcm-nav-item-61255378a1e8a">
                                            <a class="dropdown-item nav-link px-2 "
                                               href="http://debian10-vbox/fanpress4_bs/index.php?module=system/backups"
                                               >
                                                <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-life-ring  "></span> 
                                                Backupmanager                            </a>
                                        </li>

                                        <li id="fpcm-nav-item-61255378a1e8f">
                                            <a class="dropdown-item nav-link px-2 "
                                               href="http://debian10-vbox/fanpress4_bs/index.php?module=system/logs"
                                               >
                                                <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-exclamation-triangle  "></span> 
                                                Protokolle                            </a>
                                        </li>


                                    </ul>
                                </li>





                                <li class="nav-item "  id="fpcm-nav-item-61255378a1e98">
                                    <a class="nav-link text-center p-3 fpcm ui-nav-link "
                                       href="http://debian10-vbox/fanpress4_bs/index.php?module=modules/list"
                                       >

                                        <span class="d-block"><span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-plug fa-lg  "></span> 
                                        </span>
                                        <span class="fpcm nav-text text-nowrap">Module</span>
                                    </a>

                                </li>





                                <li class="nav-item dropdown"  id="fpcm-nav-item-trashmain">
                                    <a class="nav-link text-center p-3 fpcm ui-nav-link dropdown-toggle"
                                       href="http://debian10-vbox/fanpress4_bs/index.php?module=#"
                                       role="button" data-bs-toggle="dropdown" aria-expanded="false"                       >

                                        <span class="d-block"><span class="fpcm-ui-icon fpcm-ui-icon-single far fa-fw fa-trash-alt  "></span> 
                                        </span>
                                        <span class="fpcm nav-text text-nowrap">Papierkorb</span>
                                    </a>


                                    <ul class="dropdown-menu shadow fpcm ui-blurring" aria-labelledby="itemfpcm-nav-item-trashmain">

                                        <li id="fpcm-nav-item-61255378a1ebb">
                                            <a class="dropdown-item nav-link px-2 "
                                               href="http://debian10-vbox/fanpress4_bs/index.php?module=articles/trash"
                                               >
                                                <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-book  "></span> 
                                                Artikel                            </a>
                                        </li>

                                        <li id="fpcm-nav-item-61255378a1ec4">
                                            <a class="dropdown-item nav-link px-2 "
                                               href="http://debian10-vbox/fanpress4_bs/index.php?module=comments/trash"
                                               >
                                                <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-comments  "></span> 
                                                Kommentare                            </a>
                                        </li>


                                    </ul>
                                </li>



                                <li class="nav-item dropdown"  id="fpcm-nav-item-utilities">
                                    <a class="nav-link text-center p-3 fpcm ui-nav-link dropdown-toggle"
                                       href="http://debian10-vbox/fanpress4_bs/index.php?module=#"
                                       role="button" data-bs-toggle="dropdown" aria-expanded="false"                       >

                                        <span class="d-block"><span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-tools  "></span> 
                                        </span>
                                        <span class="fpcm nav-text text-nowrap">Werkzeuge</span>
                                    </a>


                                    <ul class="dropdown-menu shadow fpcm ui-blurring" aria-labelledby="itemfpcm-nav-item-utilities">

                                        <li id="fpcm-nav-item-61255378a1ed2">
                                            <a class="dropdown-item nav-link px-2 "
                                               href="http://debian10-vbox/fanpress4_bs/index.php?module=system/import"
                                               >
                                                <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-file-import  "></span> 
                                                CSV-Import                            </a>
                                        </li>

                                        <li id="fpcm-nav-item-61255378a1ed9">
                                            <a class="dropdown-item nav-link px-2 "
                                               href="http://debian10-vbox/fanpress4_bs/index.php?module=system/langedit"
                                               >
                                                <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-language  "></span> 
                                                Language Editor                            </a>
                                        </li>


                                    </ul>
                                </li>





                                <li class="nav-item dropdown"  id="fpcm-nav-item-61255378a1fca">
                                    <a class="nav-link text-center p-3 fpcm ui-nav-link dropdown-toggle"
                                       href="http://debian10-vbox/fanpress4_bs/index.php?module=#"
                                       role="button" data-bs-toggle="dropdown" aria-expanded="false"                       >

                                        <span class="d-block"><span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-angle-double-down  "></span> 
                                        </span>
                                        <span class="fpcm nav-text text-nowrap">Erweitert</span>
                                    </a>


                                    <ul class="dropdown-menu shadow fpcm ui-blurring" aria-labelledby="itemfpcm-nav-item-61255378a1fca">

                                        <li id="fpcm-nav-item-61255378a1f48">
                                            <a class="dropdown-item nav-link px-2 "
                                               href="http://debian10-vbox/fanpress4_bs/index.php?module=extstats/statistics"
                                               >
                                                <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-fa fa-chart-pie fa-fw  "></span> 
                                                Erweiterte Statistiken                            </a>
                                        </li>

                                        <li id="fpcm-nav-item-61255378a1f62">
                                            <a class="dropdown-item nav-link px-2 "
                                               href="http://debian10-vbox/fanpress4_bs/index.php?module=calendar/overview"
                                               >
                                                <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-calendar-day  "></span> 
                                                Kalender                            </a>
                                        </li>

                                        <li id="fpcm-nav-item-61255378a1fbc">
                                            <a class="dropdown-item nav-link px-2 "
                                               href="http://debian10-vbox/fanpress4_bs/index.php?module=polls/list"
                                               >
                                                <span class="fpcm-ui-icon fpcm-ui-icon-single fa fa-fw fa-poll  "></span> 
                                                Umfragen verwalten                            </a>
                                        </li>


                                    </ul>
                                </li>



                            </ul>

                        </div>
                    </div>

                </nav>
            </header>

            <div class="navbar navbar-dark fpcm ui-background-white-50p" id="fpcm-ui-toolbar">

                <div class="container-fluid justify-content-start">
                    <div class="navbar me-auto d-flex gap-1">
                        <a href="http://debian10-vbox/fanpress4_bs/index.php?module=system/profile"  id="openProfile" class="btn btn-light shadow-sm fpcm-ui-button fpcm-ui-button-link"   ><span class="fpcm-ui-icon fa fa-fw fa-wrench  "></span> <span class="fpcm-ui-label ps-1">Mein Profil öffnen</span> </a>
                        <a href="http://debian10-vbox/fanpress4_bs/index.php?module=system/options&syscheck=1"  id="runSyscheck" class="btn btn-light shadow-sm fpcm-ui-button fpcm-ui-button-link"   ><span class="fpcm-ui-icon fa fa-fw fa-sync  "></span> <span class="fpcm-ui-label ps-1">Vollständige Systemprüfung</span> </a>
                    </div>
                    <div class="navbar ms-auto gap-1">
                    </div>
                </div>

            </div>

            <div class="container-fluid mx-0 px-0 px-md-2 my-2"><div class="row g-0" id="fpcm-dashboard-containers">
                    <div class="card-group row-cols-1 row-cols-md-3">
                        <div class="col">
                            <div class="card m-1 shadow-sm fpcm dashboard-container ui-background-white-50p ui-blurrin">
                                <div class="card-body pt-1 ps-1 pe-1 pb-2">
                                    <h3 class="card-title text-secondary fpcm dashboard-container headline m-2 fs-5 placeholder-glow">
                                        <span class="placeholder col-6">&nbsp;</span>
                                    </h3>
                                    <div class="card-text placeholder-glow fpcm dashboard-container content w-100">
                                        <span class="placeholder col-7"></span>
                                        <span class="placeholder col-4"></span>
                                        <span class="placeholder col-4"></span>
                                        <span class="placeholder col-6"></span>
                                        <span class="placeholder col-8"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card m-1 shadow-sm fpcm dashboard-container ui-background-white-50p ui-blurrin">
                                <div class="card-body pt-1 ps-1 pe-1 pb-2">
                                    <h3 class="card-title text-secondary fpcm dashboard-container headline m-2 fs-5 placeholder-glow">
                                        <span class="placeholder col-6">&nbsp;</span>
                                    </h3>
                                    <div class="card-text placeholder fpcm dashboard-container content w-100"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card m-1 shadow-sm fpcm dashboard-container ui-background-white-50p ui-blurrin">
                                <div class="card-body pt-1 ps-1 pe-1 pb-2">
                                    <h3 class="card-title text-secondary fpcm dashboard-container headline m-2 fs-5 placeholder-glow">
                                        <span class="placeholder col-6">&nbsp;</span>
                                    </h3>
                                    <div class="card-text placeholder-glow fpcm dashboard-container content w-100">
                                        <span class="placeholder col-12"></span>
                                        <span class="placeholder col-4"></span>
                                        <span class="placeholder col-11"></span>
                                        <span class="placeholder col-12"></span>
                                        <span class="placeholder col-8"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="fpcm-debug-data row row-cols-1 row-cols-md-5 text-light bg-dark p-2 fs-6"><div class="col text-center">Memory usage: 2,000 MiB</div><div class="col text-center">Memory usage peak: 2,000 MiB</div><div class="col text-center">Base directory: /var/www/html/fanpress4_bs/</div><div class="col text-center">Execution time: 0.0076 sec</div><div class="col text-center">Database queries: 2</div></div>


            <div class="row row-cols-1 row-cols-md-2 py-2 bg-dark text-light fs-6">
                <div class="col bg-dark">
                    &copy; 2011-2021 <a class="text-light" href="https://nobody-knows.org/download/fanpress-cm/" target="_blank" rel="noreferrer,noopener,external">nobody-knows.org</a>                
                </div>
                <div class="col">
                    <div class="d-flex justify-content-md-end">
                        <b>Version:</b>&nbsp;5.0-dev                        
                    </div>
                </div>
            </div>

            <div class="offcanvas offcanvas-start" id="fpcm-offcanvas-help" aria-labelledby="offcanvasLabel" data-ref="SExfREFTSEJPQVJE" data-chapter="0">
                <div class="offcanvas-header">
                    <h3 class="offcanvas-title">Hilfe</h3>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Schließen"></button>
                </div>
                <div class="offcanvas-header offcanvas-nav d-none"></div>
                <div class="offcanvas-body" data-bs-spy="scroll"></div>
            </div>            

        </div>

        <script src="http://debian10-vbox/fanpress4_bs/core/js/init.js" rel="prefetch"></script>
    </body>
</html>

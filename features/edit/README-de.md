Edit 0.8.14
===========
Webseite im Webbrowser bearbeiten.

<p align="center"><img src="edit-screenshot.png?raw=true" alt="Bildschirmfoto"></p>

## Wie man diese Erweiterung installiert

1. [Datenstrom Yellow herunterladen und installieren](https://github.com/datenstrom/yellow/).
2. [Erweiterung herunterladen](https://github.com/datenstrom/yellow-extensions/raw/master/zip/edit.zip). Falls du Safari verwendest, rechtsklicke und wähle "Verknüpfte Datei laden unter".
3. Kopiere `edit.zip` in dein `system/extensions`-Verzeichnis.

Zum Deinstallieren lösche einfach die [Erweiterungsdateien](extension.ini).

## Wie man eine Webseite bearbeitet

Die Anmeldeseite ist auf deiner Website vorhanden als `http://website/edit/`. Melde dich mit deinem Benutzerkonto an. Du kannst deine Webseite anschauen, Änderungen machen und das Ergebnis sofort sehen. Es ist eine grossartige Art Webseiten zu aktualisieren. Um einen Link zum Bearbeiten anzuzeigen, fügen eine `[edit]`-Abkürzung auf der Seite ein.

## Wie man ein Benutzerkonto erstellt

Die erste Möglichkeit besteht darin, ein Benutzerkonto im Webbrowser zu erstellen. Gehe zur Anmeldeseite. Du kannst ein Benutzerkonto erstellen und dein Kennwort ändern. Der Webmaster muss neue Benutzerkonten genehmigen. Die E-Mail des Webmasters wird in der Datei `system/settings/system.ini` festgelegt.

Die zweite Möglichkeit besteht darin, ein Benutzerkonto in der [Befehlszeile](https://github.com/datenstrom/yellow-extensions/tree/master/features/command) zu erstellen. Öffne ein Terminalfenster. Gehe ins Installations-Verzeichnis, dort wo sich die `yellow.php` befindet. Gib ein `php yellow.php user add` gefolgt von E-Mail und Kennwort. Alle Benutzerkonten werden in der Datei `system/settings/user.ini` gespeichert.

## Wie man ein Benutzerkonto beschränkt

Falls du nicht willst dass Benutzer Seiten verändern, beschränke Benutzerkonten. Öffne die Datei `system/settings/user.ini` und ändere Startseite und Zugriffsrechte. Benutzer dürfen Seiten innerhalb ihrer Startseite bearbeiten, aber nirgendwo sonst.

Falls du nicht willst dass Benutzerkonten erstellt werden, beschränke die Anmeldeseite. Öffne die Datei `system/settings/system.ini` und ändere `EditLoginRestriction: 1`. Benutzer dürfen sich dann einloggen, aber keine neue Benutzerkonten erstellen.

Falls du nicht jedem Benutzer auf deiner Webseite vertrauen kannst, aktiviere den Sicherheitsmodus. Öffne die Datei `system/settings/system.ini` und ändere `CoreSafeMode: 1`. Benutzer dürfen dann [Markdown](https://github.com/datenstrom/yellow-extensions/tree/master/features/markdown) benutzen, aber kein HTML und JavaScript verwenden.

## Einstellungen

Die folgenden Einstellungen können in der Datei `system/settings/system.ini` vorgenommen werden:

`Author` = Name des Webmasters  
`Email` = E-Mail des Webmasters  
`EditLocation` = Ort der Anmeldeseite  
`EditUploadNewLocation` = Ort für neue Mediendateien  
`EditUploadExtensions` = Dateierweiterungen zum Hochladen, `none` um zu deaktivieren  
`EditKeyboardShortcuts` = Tastaturkürzel und Befehle, `none` um zu deaktivieren  
`EditToolbarButtons` = Symbolleistenschaltflächen, `auto` für automatische Erkennung, `none` um zu deaktivieren  
`EditNewFile` = Inhaltsdatei für neue Seite  
`EditEndOfLine` = Zeilenenden, z.B. `auto`, `lf`, `crlf`  
`EditUserPasswordMinLength` = Mindestänge von Kennwörtern  
`EditUserHome` = Standard-Startseite für neues Benutzerkonto  
`EditUserAccess` = Standard-Zugriffsrechte für neues Benutzerkonto  
`EditLoginSessionTimeout` = Gültigkeit der Anmeldung in Sekunden  
`EditLoginRestriction` = Anmeldebeschränkung aktivieren, 1 oder 0  
`EditBruteForceProtection` = Anzahl fehlgeschlagener Anmeldeversuche  

Die folgenden Einstellungen können in der Datei `system/settings/user.ini` vorgenommen werden:

`Email` = E-Mail des Benutzers  
`Name` =  Name des Benutzers  
`Language` = Sprache des Benutzers  
`Home` = Startseite des Benutzers  
`Access` = Zugriffsrechte des Benutzers, z.B. `edit`  
`Status` = Status des Benutzers, z.B. `active`  
`Pending` = ausstehende Änderungen  
`Hash` = verschlüsseltes Kennwort  
`Stamp` = eindeutiges Token zur Authentifizierung  
`Failed` = Anzahl fehlgeschlagener Anmeldeversuche  
`Modified` = Änderungszeit, Unix-Zeit  

Die folgenden Benutzer-Zugriffsrechte werden unterstützt:

`create` =  Benutzer kann Seite erstellen  
`edit` = Benutzer kann Seite bearbeiten  
`delete` = Benutzer kann Seite löschen  
`upload` = Benutzer kann Mediendateien hochladen  
`system` = Benutzer kann [Systemeinstellungen ändern](https://github.com/datenstrom/yellow-extensions/tree/master/features/core#settings)  
`update` = Benutzer kann [Webseite aktualisieren](https://github.com/datenstrom/yellow-extensions/tree/master/features/update)  

Die folgenden Benutzer-Statuswerte werden unterstützt:

`active` = Benutzer ist aktiv  
`inactive` = Benutzer wurde vorübergehend deaktiviert  
`unconfirmed` = Benutzer hat Benutzerkonto nicht bestätigt  
`unapproved` = Benutzer wurde vom Webmaster nicht genehmigt  
`unverified` = Benutzer hat neue E-Mail-Adresse nicht bestätigt  
`unchanged` = Benutzer hat ausstehende Änderungen nicht bestätigt  
`removed` = Benutzer hat ausstehende Löschung nicht bestätigt  

## Beispiele

Inhaltsdatei mit Link zum Bearbeiten:

    ---
    Title: Über
    ---
    Für Menschen die Webseiten machen. [edit - Anmelden].
    
    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut 
    labore et dolore magna pizza. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris 
    nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit 
    esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt 
    in culpa qui officia deserunt mollit anim id est laborum.
    
    Diese Webseite ist erstellt mit [Datenstrom Yellow](https://datenstrom.se/de/yellow/). 

Verschiedene Symbolleistenschaltflächen festlegen:

```
EditToolbarButtons: auto 
EditToolbarButtons: format, bold, italic, strikethrough, code, separator, list, link, file, undo, redo
EditToolbarButtons: bold, italic, h1, h2, h3, code, quote, ul, ol, tl, link, file, preview, markdown
EditToolbarButtons: format, bold, italic, separator, quote, code, link, file, separator, emojiawesome
```

Ein Benutzerkonto mit maximalen Zugriffsrechten ausstatten:

```
Email: anna@svensson.com
Name: Anna Svensson
Language: de
Home: /
Access: create, edit, delete, upload, system, update
Status: active
Pending: none
Hash: $2y$10$j26zDnt/xaWxC/eqGKb9p.d6e3pbVENDfRzauTawNCUHHl3CCOIzG
Stamp: 21196d7e857d541849e4
Failed: 0
Modified: 946684800
```

Benutzerkonten in der Befehlszeile aktualisieren:
 
`php yellow.php user add email@example.com password`  
`php yellow.php user change email@example.com password`  
`php yellow.php user remove email@example.com`  

## Entwickler

Datenstrom. [Support finden](https://extensions.datenstrom.se/de/help/).

<p>
<a href="README-de.md"><img src="https://raw.githubusercontent.com/datenstrom/yellow-extensions/master/features/help/language-de.png" width="15" height="15" alt="Deutsch">&nbsp; Deutsch</a>&nbsp;
<a href="README.md"><img src="https://raw.githubusercontent.com/datenstrom/yellow-extensions/master/features/help/language-en.png" width="15" height="15" alt="English">&nbsp; English</a>&nbsp;
</p>
# IPSHomeMaticExtended
Erweitert IPS um die native Unterst�tzung von:

* Systemvariablen der CCU
* Programmen auf der CCU
* Summenz�hler der Leistungsmesser
* Display Status-Anzeige
* HomeMaticScript

## Dokumentation

**Inhaltsverzeichnis**

1. [Funktionsumfang](#1-funktionsumfang) 
2. [Voraussetzungen](#2-voraussetzungen)
3. [Installation](#3-installation)
4. [HomeMatic Systemvariablen] (#4-homematic-systemvariablen)
5. [HomeMatic Powermeter](#5-homematic-powermeter)
6. [HomeMatic Programme](#6-homematic-programme)
7. [HomeMatic WM55-Dis](#7-homematic-wm55-dis)
8. [HomeMatic-Script](#8-homematic-script) 
9. [Anhang](#9-anhang)

## 1. Funktionsumfang

   Abfragen von System- und Alarmvariablen inkl. Profilen und Werten von der CCU.  
   Schreiben von Werten der Systemvariablen zur CCU.    
   Standard Actionhandler f�r die Bedienung der System- und Alarmvariablen aus dem IPS-Webfront.  
   (Die Alarmvariablen werden erst ab IPS4.0 unters�tzt.)  

   Abfragen des Summenz�hlers der Schaltaktoren mit Leistungsmessung aus der CCU.  
   (Weitere Energiemesser folgen)  

   Abfragen der auf der CCU vorhandenen HM-Programme.  
   Ausf�hren der HM-Programme auf der CCU.  
   Standard Actionhandler f�r die Bedienung der HM-Programme aus dem IPS-Webfront.  

   Dynamische Textanzeige auf dem Display-Wandtaster mit Statusdisplay.  
   Unterst�tzt mehrseite Anzeigen und das durchbl�ttern per Tastendruck.  
   Ausf�hren von benutzerspezifischen Aktionen, auch in Abh�ngigkeit der angezeigten Seite.  
   
   Native Schnittstelle zur CCU, um HomeMatic-Scripte durch die CCU ausf�hren zu lassen.  
   Direkte R�ckmeldung der Ausf�hrung durch einen Antwortstring im JSON-Format.  

   XML-API-Patch wird nicht ben�tigt.  
   Unterst�tzung von mehreren CCUs.  
   Einfache Einrichtung und Handhabung.  
   PHP-Befehle entsprechen dem vorhanden Standard von IPS.  
 
## 2. Voraussetzungen

   Funktionsf�hige CCU1 und/oder CCU2, welche schon mit einem HomeMatic Socket in IPS eingerichtet ist.  
   In der CCU mu� die Firewall entsprechend eingerichtet sein, das IPS auf die 'Remote HomeMatic-Script API' der CCU zugreifen kann.

    Einstellungen -> Systemsteuerung -> Firewall

   Bei 'Remote HomeMatic-Script API' mu� entweder 'Vollzugriff' oder 'Eingeschr�nkt' eingestellt sein.
   Bei 'Eingeschr�nkt' ist dann unter 'IP-Adressen f�r eingeschr�nkten Zugriff' euer LAN / IPS-PC einzugeben.  
   (z.B. 192.168.178.0/24 => /24 ist die Subnet-Maske f�r das Netzwerk. Bei 255.255.255.0 ist das 24 bei 255.255.0.0. ist es 16.
   Oder es kann direkt eine einzelne Adresse eingetragen werden. z.B. 192.168.0.2

## 3. Installation

   - IPS 3.x  
        Kopieren von der HMSysVar.dll in das Unterverzeichniss 'modules' unterhalb des IP-Symcon Installationsverzeichnisses.  
        Der Ordner 'modules' muss u.U. manuell angelegt werden.
        Beispiel: 'C:\\IP-Symcon\\modules'  
        IPS-Dienst Neustarten.  

   - IPS 4.x  
        �ber das 'Modul Control' folgende URL hinzuf�gen:  
        `git://github.com/Nall-chan/IPSHomematicExtended.git`  

## 4. HomeMatic Systemvariablen

   Unter Instanz hinzuf�gen sind die Systemvariablen unter dem Hersteller 'HomeMatic' zu finden.  
   Nach dem Anlegen der Instanz sollte als �bergeordnetes Ger�t schon der HomeMatic Socket ausgew�hlt sein.  
   Existieren in IPS mehrere Homematic Socket, so ist der auszuw�hlen, der der CCU entspricht von dem die Systemvariablen gelesen werden sollen.  

   Dieses Modul unterst�tzt zwei M�glichkeiten die Systemvariablen von der CCU abzufragen:  

   - Abfrage erfolgt �ber einen einstellbaren Intervall (Pull).

   - Die CCU l�st einen Tastendruck einer virtuellen Fernbedienung aus,  
     welche in diesem Modul als Trigger f�r eine Abfrage verwendet wird (Push).

    **Vor/Nachteile der beiden Varianten:**

    * Intervall (Pull):  
        - \+ Ben�tigt kein Programm in der CCU.  
        - \- �nderungen werden in IPS nur mit Verz�gerung erkannt.  
        - \- Unn�tige Abfragen der CCU, wenn sich kein Wert in der CCU ge�ndert hat.  
        - \- Hierdurch unn�tiger Netzwerkverkehr und CPU-Rechenzeit der CCU und des IPS-Systems.  
        - \- R�ckmeldung im WebFront nach ausl�sen einer Aktion kann bis zur Intervallzeit  
             verz�gert dargestellt werden. (Status emulieren einschalten um Dies zu unterbinden.)  

    * Trigger von der CCU (Push):  
        - \- Ben�tigt ein Zentralenprogramm in der CCU, welches bei Aktualisierung von  
             Systemvariablen einen Tastendruck einer virtuellen Fernbedienung ausl�st.  
        - \+ �nderungen werden sofort erkannt.  
        - \+ Unn�tige Abfragen werden minimiert.  
        - \+ R�ckmeldung im WebFront nach ausl�sen einer Aktion, entspricht sofort dem Wert der CCU.  

    F�r die Intervall-Variante ist die Einstellung des Abfrage-Intervalls in Sekunden
    vorzunehmen, und bei Bedarf der Haken bei 'Status emulieren' zu setzen.

    F�r die Trigger-Variante ist der in dem Zentralenprogramm der CCU verwendete
    Datenpunkt der virtuellen Fernbedienung unter 'Trigger f�r Refresh' auszuw�hlen
    (z.B. PRESS_SHORT).  

    **Hinweis:** �ber den Homematic Konfigurator in IPS kann das ben�tigte Homematic Device
    komfortabel angelegt werden.  

    �ber das Testcenter des Einstellungsdialog k�nnen die Systemvariablen sofort eingelesen
    werden, ohne auf den Intervall oder einen Trigger zu warten.  

    Unter dem Reiter 'Statusvariablen' sollten jetzt alle (\* siehe Powermeter) in der CCU
    vorhandenen Systemvariablen angezeigt werden.  

    Hier kann mit dem entfernen des Haken 'Benutze Standardaktion' die Bedienung einer
    Variable, aus dem WebFront heraus, unterbunden werden.  

    **Achtung:**  
    Die Profile der Systemvariablen werden nur beim Anlegen in IPS aus der CCU ausgelesen
    und �bernommen.  
    Sp�ter in der CCU vorgenommene �nderungen an dem Profil einer Systemvariable werden nicht abgeglichen !  
    �nderungen sind dann entweder von Hand in IPS durchzuf�hren, oder das entsprechende Profil
    ist manuell zu l�schen, es wird dann automatisch neu angelegt.

    Manuelle �nderungen an den Profilen sind teilweise n�tig, da die CCU nur begrenzt
    Informationen zur Verf�gung stellt.
    Dies betrifft z.B. die Schrittweite und die Anzahl der Kommastellen bei Float-Variablen.

    Au�erdem k�nnen die Profile individuell ver�ndert / erg�nzt werden, dieses Modul �ndert
    vorhandene Profile nicht.

    Der Profilname lautet immer:
    'HM.SysVar\<ID der Systemvariablen Instanz\>.\<IDENT der Systemvariable\>; (z.B. HM.SysVar12345.950).  

    Alle Statusvariablen dieses Moduls werden so benannt wie in der CCU.  

    **Hinweis:**  
    Namens�nderungen in IPS werden durch die CCU immer �berschrieben!  
    In der CCU gel�schte Systemvariablen, werden in IPS nicht antomatisch gel�scht.  

    Alle aus der CUU ausgelesenen Werte werden in IPS aufgrund des Zeitstempels der
    CCU-Variable und der IPS-Variable abgeglichen.  
    Somit werden unn�tige Variablen-Updates in IPS vermieden, wenn die Variable in der
    CCU gar nicht aktualisiert wurde.  

    Hierbei ist es irrelevant ob sich der Wert ge�ndert hat, ausschlaggebend ist die
    Aktualisierung.  

    Eventuelle Differenzen der Uhrzeiten und/oder Zeitzonen beider Systeme werden dabei
    automatisch ber�cksichtigt und erfordern somit keinen Eingriff durch den Benutzer.  

    **Hinweis:**  
    Eine Aktualisierung einer Alarmvariable, kann ein in der Instanz hinterlegtes Script starten.  
    Hierzu werden folgene Werte in der Variable $_IPS �bergeben und stehen im Alarm-Script zur Verf�gung.  

| Indexname   | Type    | Bedeutung                          |
| :---------: | :-----: | :--------------------------------: |
| Channel     | string  | Kannalbezeichnung des Melders      |
| ChannelName | string  | Bezeichnung des Kanals aus der CCU |
| DP          | string  | Bezeichnung des Datenpunktes       |
| FirstTime   | integer | Erste Ausl�sung (Unixtimestamp)    |
| LastTime    | integer | Letzte Ausl�sung (Unixtimestamp)   |
| OLDVALUE    | boolean | Vorheriger Wert                    |
| SENDER      | string  | FixWert 'AlarmDP'                  |
| VALUE       | boolean | Aktueller Wert                     |
| VARIABLE    | integer | ObjektID der Alarmvariable         |

![Alarmvariable.png](Alarmvariable.png)
    
### PHP-Funktionen

    Um einen Wert einer Systemvariable aus IPS heraus in die CCU zu schreiben, werden die
    schon vorhandenen HM_WriteValue* Befehle von IPS genutzt.  

    Hier entspricht der Parameter mit dem Namen 'Parameter' dem IDENT der Systemvariable.  
    (Die IDENT�werden unter dem Reiter 'Statusvariablen' des Einstellungsdialogs der Instanz angezeigt.)  

   **ACHTUNG bei IPS 4.0: Aktuell m�ssen die Funktionen HM_WriteValueBoolean2, HM_WriteValueFloat2, HM_WriteValueInteger2 und HM_WriteValueString2 verwendet werden!**

    **Beispiele:**  

        HM_WriteValueBoolean(integer $InstantID /*[HomeMatic Systemvariablen]*/, string '950' /* IDENT von Anwesenheit */, boolean true);  
        HM_WriteValueFloat(integer $InstantID /*[HomeMatic Systemvariablen]*/, string '2588' /* IDENT von Solltemp Tag */, float 21.0);  
        HM_WriteValueInteger(integer $InstantID /*[HomeMatic Systemvariablen]*/, string '12829', integer 56);  
        HM_WriteValueString(integer $InstantID /*[HomeMatic Systemvariablen]*/, string '14901', string 'TestString');  

## 5. HomeMatic Powermeter

   Die CCU legt f�r jeden 'Schaltaktor mit Leistungsmessung' automatisch eine Systemvariable
   und ein Programm an, welches den Totalwert dieses Aktors hoch z�hlt.  

   Dieser Wert wird auch bei Stromausfall bzw. ausstecken des entsprechenden Aktors, gehalten.  

   Diese Systemvariable unterscheidet sich von den 'normalen' Systemvariablen dahingehend,
   dass Sie nicht in der der �bersicht aller Systemvariablen in der CCU auftaucht.  
   (Im Gegensatz zu den Regenmengen Z�hlern des OC3.)  

   Entsprechend war es n�tig f�r diesen Typ von Systemvariable ein eingenes IPS-Device zu
   implementieren.  

   Unter Instanz hinzuf�gen ist die Systemvariable 'Powermeter' unter dem Hersteller
   'HomeMatic' zu finden.  

   Nach dem Anlegen der Instanz sollte als �bergeordnetes Ger�t schon der HomeMatic Socket
   ausgew�hlt sein.  
   Existieren in IPS mehrere Homematic Socket, so ist der auszuw�hlen, der der CCU
   entspricht an dem der Aktor angelernt ist.  

   Dieses Modul fragt den Wert aus der CCU immer dann ab, wenn der Wert
   der Variable 'ENERGY_COUNTER' des entsprechenden Aktors sich in IPS aktualisiert.  
   Oder der IPS-Dienst startet bzw. wenn eine Instanz neu konfiguriert wurde.  

   Im Einstellungsdialog der Instanz ist entsprechend die zugeh�rige 'ENERGY_COUNTER'
   Variable des Aktors auszuw�hlen, von dem der 'ENERGY_COUNTER_TOTAL' Wert
   gelesen werden soll.  

   Als Profil f�r diese Variable ist ein Standard-IPS-Profil zugeordnet, und die Werte werden
   automatisch nach kWh umgerechnet.  

   
## 6. HomeMatic Programme

   Die auf der CCU eingerichteten Programme k�nnen mit dieser Instanz ausgelesen und auch gestartet werden.  

   Unter Instanz hinzuf�gen sind die 'HomeMatic Programme' unter dem Hersteller 'HomeMatic' zu finden.  
   Nach dem Anlegen der Instanz sollte als �bergeordnetes Ger�t schon der HomeMatic Socket ausgew�hlt sein.  
   Existieren in IPS mehrere Homematic Socket, so ist der auszuw�hlen, aus welcher CCU die Programme gelesen werden sollen.  

   Dieses Modul hat keinerlei Einstellungen, welche konfiguriert werden m�ssen.  

   Im Testcenter ist es jedoch �ber den Button 'CCU auslesen' m�glich, die auf der CCU vorhandenen Programme auszulesen.
   Dies erfolgt auch autoamtisch bei Systemstart von IPS und wenn die Instanz angelegt wird.  

   Die Programme werden als Integer-Variable unterhalb der Instanz erzeugt. Es wird automatisch der Name und die Beschreibung aus der CCU �bernommen.  

   Des weiteren wird ein Standard-Profil 'Execute-HM' angelegt und den Variablen zugeordnet.  

   Es ist somit sofort m�glich die Programme aus dem WebFront heraus zu starten.  

   Werden in der CCU Programme gel�scht, so m�ssen die dazugeh�rigen Variablen in IPS bei Bedarf manuell gel�scht werden.  

### PHP-Funktionen

    string HM_ReadPrograms(integer $InstantID /*[HomeMatic Programme]*/)
   Alle Programme auf der CCU werden ausgelesen und bei Bedarf umbenannt oder neu angelegt.

    string HM_StartProgram(integer $InstantID /*[HomeMatic Programme]*/, string $IDENT);
   Startet ein auf der CCU hinterlegtes Programm. Als `$IDENT` muss der Ident der Variable des Programmes �bergeben werden.  
   (Die IDENT�werden unter dem Reiter 'Statusvariablen' des Einstellungsdialogs der Instanz angezeigt.)  

   **Beispiele:**

        HM_ReadPrograms(12345 /*[HomeMatic Programme]*/);  
        HM_StartProgram(12345 /*[HomeMatic Programme]*/, string '4711' /* IDENT von Programm Licht Alles aus */);  


## 7. HomeMatic WM55-Dis

  Hier handelt es sich um eine Instanz, welche die Verwendung des farbigen Statusdisplays im 55er-Rahmen vereinfachen soll.  
  �ber eine konfigurierbare Anzahl von 'Seiten' ist es m�glich verschiedene Inhalte darzustellen und durch diese zu bl�ttern (z.B. mit den beiden Tasten der Statusanzeige).  
  F�r die darzustellenen Inhalte muss das unterhalb der Instanz erzeugt Display-Script den eigenen Bed�rfnissen angepa�t werden.  
  Grunds�tzlich ist die Statusanzeige nur empfangsbereit, und stellt eine Inhalt auf dem Display dar, wenn unmittelbar zuvor eine der beiden Tasten gedr�ckt wurde.  
  Hierzu ist wenigstens eine der vier Felder "Hoch-Taste", "Runter-Taste", "Aktion Hoch-Taste" oder "Aktion Runter-Taste" mit einem der PRESS Datenpunkte der Statusanzeige zu belegen.  
  Wird von IPS ein Telegramm mit einem der vier Datenpunkte empfange, so wird das "Display-Script" mit den entsprechenden Parametern ausgef�hrt und das Ergebnis anschlie�end zur Statusanzeige �bertragen.  
  Die Anzahl der m�glichen Seiten l��t sich in der Konfiguration der Instanz einstellen (1 ist auch m�glich).  
  Ebenso ist das Timeout einstellbar, nach wieviel Sekunden wieder auf Seite 1 gesprungen wird.  
  
  Details zu dem Display-Script und die dort Verf�gbaren $_IPS-Variablen, sind dem Script zu entnehemen.

## 8. HomeMatic-Script

Dies Instanz erm�glicht es eigene Homematic-Scripte zur CCU zu senden.  
Des weiteren wird die R�ckgabe der Ausf�hrung an den Aufrufer zur�ck gegeben.  
So kann z.B. per PHP-Script in IPS ein dynamisches Homematic-Script als String erstellt werden,
und die erfolgte Ausf�hrung ausgewertet werden.  

### PHP-Funktionen

    string HM_RunScript(integer $InstantID /*[HomeMatic RemoteScript Interface]*/,string $Script)

   **Beispiel:**

   Abfrage der Uhrzeit und Zeitzone von der CCU:

    $HMScript = 'Now=system.Date("%F %T%z");' . PHP_EOL  
              . 'TimeZone=system.Date("%z");' . PHP_EOL;   
    $HMScriptResult = HM_RunScript(12345 /*[HomeMatic RemoteScript Interface]*/, $HMScript);  
    var_dump(json_decode($HMScriptResult));  


## 9. Anhang

**GUID's:**  

| Device                           | GUID                                   |
| :------------------------------: | :------------------------------------: |
| HomeMatic Systemvariablen        | {400F9193-FE79-4086-8D76-958BF9C1B357} |
| HomeMatic Powermeter             | {AF50C42B-7183-4992-B04A-FAFB07BB1B90} |
| HomeMatic Programme              | {A5010577-C443-4A85-ABF2-3F2D6CDD2465} |
| HomeMatic RemoteScript Interface | {246EDB89-70BC-403B-A1FA-3B3B1B540401} |
| HomeMatic Dis-WM55               | {271BCAB1-0658-46D9-A164-985AEB641B48} |

**Changelog:**

Version 2.0:

Version 1.5:

Version 1.3:

Version 1.1:



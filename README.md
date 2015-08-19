Unvollst�ndig
=============

Work in progress...

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

   Abfragen von Systemvariablen inkl. Profilen und Werten von der CCU.  
   Schreiben von Werten der Systemvariablen zur CCU.    
   Standard Actionhandler f�r die Bedienung der Systemvariablen aus dem IPS-Webfront.  

   Abfragen des Summenz�hlers der Schaltaktoren mit Leistungsmessung aus der CCU.  
   (Weitere Energiemesser folgen)  

   Abfragen der auf der CCU vorhandenen HM-Programme.  
   Ausf�hren der HM-Programme auf der CCU.  
   Standard Actionhandler f�r die Bedienung der HM-Programme aus dem IPS-Webfront.  

   Dynamische Textanzeige auf dem Display-Wandtaster mit Statusdisplay.  
   Unterst�tzt mehrseite Anzeigen und das durchbl�ttern per Tastendruck.  
   Ausf�hren von benutzerspezifischen Aktionen, auch in abh�ngigkeit der angezeigten Seite.  
   
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
    Alle aus der CUU ausgelesenen Werte werden in IPS aufgrund des Zeitstempels der
    CCU-Variable und der IPS-Variable abgeglichen.  

    Somit werden unn�tige Variablen-Updates in IPS vermieden, wenn die Variable in der
    CCU gar nicht aktualisiert wurde.  

    Hierbei ist es irrelevant ob sich der Wert ge�ndert hat, ausschlaggebend ist die
    Aktualisierung.  

    Eventuelle Differenzen der Uhrzeiten und/oder Zeitzonen beider Systeme werden dabei
    automatisch ber�cksichtigt und erfordern somit keinen Eingriff durch den Benutzer.  

    Um einen Wert einer Systemvariable aus IPS heraus in die CCU zu schreiben, werden die
    schon vorhandenen HM_WriteValue* Befehle von IPS genutzt.

    Hier entspricht der Parameter mit dem Namen 'Parameter' dem IDENT der Systemvariable.  
    (Die IDENT�werden unter dem Reiter 'Statusvariablen' des Einstellungsdialogs des Moduls
    angezeigt.)

    **Beispiele:**  
    `HM_WriteValueBoolean(54353 /*[HomeMatic Systemvariablen]*/, 950 /* IDENT von Anwesenheit */, true);`  
    `HM_WriteValueFloat(54353 /*[HomeMatic Systemvariablen]*/, 2588 /* IDENT von Solltemp Tag */, 21.0);`  
    `HM_WriteValueInteger(54353 /*[HomeMatic Systemvariablen]*/, 12829, 56);`  
    `HM_WriteValueString(54353 /*[HomeMatic Systemvariablen]*/, 14901, 'TestString');`  

## 5. HomeMatic Powermeter

Die CCU legt f�r jeden 'Schaltaktor mit Leistungsmessung' automatisch eine Systemvariable
und ein Programm an, welches den Totalwert dieses Aktors hoch z�hlt. Dieser Wert wird
auch bei Stromausfall bzw. ausstecken des entsprechenden Aktors, gehalten.
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
'ENERGY_COUNTER' des entsprechenden Aktors sich in IPS aktualisiert.
Somit arbeitet das Modul immer nach dem Trigger-Prinzip.
Im Einstellungsdialog der Instanz ist entsprechend die zugeh�rige 'ENERGY_COUNTER'
Variable des Aktors auszuw�hlen, von dem der 'ENERGY_COUNTER_TOTAL' Wert
gelesen werden soll.
Als Profil f�r diese Variable ist ein Standard-IPS-Profil zugeordnet, und die Werte werden
automatisch nach kWh umgerechnet.

## 6. HomeMatic Programme

Die auf der CCU eingerichteten Programme k�nnen mit dieser Instanz ausgelesen und auch gestartet werden.

Unter Instanz hinzuf�gen sind die 'HomeMatic Programme' unter dem Hersteller
'HomeMatic' zu finden.
Nach dem Anlegen der Instanz sollte als �bergeordnetes Ger�t schon der HomeMatic Socket
ausgew�hlt sein.
Existieren in IPS mehrere Homematic Socket, so ist der auszuw�hlen, aus welcher CCU die Programme gelesen werden sollen.

Dieses Modul hat keinerlei Einstellungen, welche konfiguriert werden m�ssen.

Im Testcenter ist es jedoch �ber den Button 'CCU auslesen' m�glich, die auf der CCU vorhandenen Programme auszulesen.

Die Programme werden als Integer-Variable unterhalb der Instanz erzeugt. Es wird automatisch der Name und die Beschreibung aus der CCU �bernommen.

Des weiteren wird ein Standard-Profil 'Execute-HM' angelegt und den Variablen zugeordnet.

Es ist somit sofort m�glich die Programme aus dem WebFront heraus zu starten.

Es gibt auch nur zwei PHP-Funktionen f�r dieses Modul:

`HM_ReadPrograms(54353 /*[HomeMatic Systemvariablen]*/);`  
`HM_StartProgram(54353 /*[HomeMatic Systemvariablen]*/, 4711 /* IDENT von Programm Licht Alles aus */);`  

## 7. HomeMatic WM55-Dis

## 8. HommMatic-Script

## 9. Anhang

**GUID's:**  

| Device          | GUID                                   |
| :-------------: | :------------------------------------: |
| SystemVariablen | {400F9193-FE79-4086-8D76-958BF9C1B357} |
| PowerMeter      | {AF50C42B-7183-4992-B04A-FAFB07BB1B90} |
| CCU-Programme   | {A5010577-C443-4A85-ABF2-3F2D6CDD2465} |

**Changelog:**

Version 2.0:

Version 1.5:

Version 1.3:

Version 1.1:



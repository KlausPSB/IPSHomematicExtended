<?
### GRUNDFUNKTION
/*
Beispiel f�r das Zusammenstellern der Daten f�r die Dis-WM55 Instanz.
Das Script wird als 'Display-Script' in der dazugeh�rigen Dis-WM55 Instanze eingetragen.
Die vorbereiteten Daten f�r das Display werden als JSON kodierter String an die
Dis-WM55 Instanz als R�ckgabewert 'Script-Result' �bergeben.
Beispiel der erzeugten Daten:
{"1":{"Text":"SEITE 1","Icon":130,"Color":129},"2":{"Text":"Zeile2","Icon":0,"Color":129},"3":{"Text":"Zeile3","Icon":130,"Color":130},"4":{"Text":"Zeile4","Icon":0,"Color":130},"5":{"Text":"Zeile5","Icon":131,"Color":132},"6":{"Text":"Zeile6","Icon":0,"Color":132}}

Der JSON-String wird aus einem Array erzeugt, welches folgendem Aufbau haben
__MUSS__, damit die Dis-WM55 Instanz die Daten verarbeiten und an das Display
senden kann.
Zeile[1]['Text']  = Text Zeile 1
Zeile[1]['Icon']  = Icon Zeile 1
Zeile[1]['Color']  = Farbe Zeile 1
Zeile[2]['Text']  = Text Zeile 2
Zeile[2]['Icon']  = Icon Zeile 2
Zeile[2]['Color']  = Farbe Zeile 2
.
.
.
Zeile[6]['Text']  = Text Zeile 6
Zeile[6]['Icon']  = Icon Zeile 6
Zeile[6]['Color']  = Farbe Zeile 6

Um nicht immer die Zahlen f�r die Icons und Farben eintragen zu m�ssen wurden
Konstanten definiert.
Des weiteresn m�ssen Textzeilen mit der Funktion text_encode("Zeile m�t �mlaut")
�bergeben werden, wenn Umlaute in der Zeile verwendet werden.
*/

### Konstanten
//--------------------------------
// Definition der Werte f�r die Icons

// 0x80 AUS                Icon_on
// 0x81 EIN                Icon_off
// 0x82 OFFEN              Icon_open
// 0x83 geschlossen        Icon_closed
// 0x84 fehler             Icon_error
// 0x85 alles ok           Icon_ok
// 0x86 information        Icon_information
// 0x87 neue nachricht     Icon_message
// 0x88 servicemeldung     Icon_service
// 0x89 Signal gr�n        Icon_green
// 0x8A Signal gelb        Icon_yellow
// 0x8B Signal rot         Icon_red
//      ohne Icon          Icon_no

define ("Icon_on"		,0x80);
define ("Icon_off"		,0x81);
define ("Icon_open"		,0x82);
define ("Icon_closed"		,0x83);
define ("Icon_error"		,0x84);
define ("Icon_ok"		,0x85);
define ("Icon_information"	,0x86);
define ("Icon_message"		,0x87);
define ("Icon_service"		,0x88);
define ("Icon_signal_green"	,0x89);
define ("Icon_signal_yellow"	,0x8A);
define ("Icon_signal_red"	,0x8B);
define ("Icon_no"		,0);


// Definition der Werte f�r die Farben

// 0x80 weiss              Color_white
// 0x81 rot                Color_red
// 0x82 orange             Color_orange
// 0x83 gelb               Color_yellow
// 0x84 gr�n               color_green
// 0x85 blau               color_blue

define ("Color_white"	,0x82);
define ("Color_red"	,0x81);
define ("Color_orange"	,0x82);
define ("Color_yellow"	,0x83);
define ("Color_green"	,0x84);
define ("Color_blue"	,0x85);

### VERWENDUNG VON $_IPS

/*
Die Dis-WM55 Instanz stellt �ber die IPS-Systemvariable $_IPS folgende Daten zur Verf�gung:

(string) $_IPS['ACTION']
	'UP'				=>  Trigger f�r Taste-Hoch wurde ausgel��t
	'DOWN'			=>  Trigger f�r Taste-Runter wurde ausgel��t
	'ActionUP'		=>  Trigger f�r Aktion-Hoch wurde ausgel��t
	'ActionDOWN'	=>  Trigger f�r Aktion-Runter wurde ausgel��t

(int) $_IPS['PAGE']
	Die 'Seite' welche dargestellt oder deren Aktion ausgef�hrt werden soll.

(string) $_IPS['SENDER'] => 'HMDisWM55'
	Fester Wert
	
(int) $_IPS['EVENT']
	Die Instanz-ID der HMDis-WM55 Instanz.


Auf der Basis der Variable $_IPS['PAGE'] ist es nun m�glich verschiedene Daten
je nach 'Seite' zu berechnen und �bergeben.
Ebenso ist es m�glich (z.B. durch langen und kurzen Tastendruck) zwischen UP/DOWN
und ActionUP/ActionDOWN zu unterscheiden und so Aktionen wie das Schalten von Licht ausf�hren zu lassen.

Nat�rlich kann man auch nur kurze Tastendr�cke verwenden und z.B. Kanal:2 als ActionUP und Kanal:1 als DOWN zu definieren.

*/
if ($_IPS['SENDER'] <> 'HMDisWM55')
{
    echo 'Dieses Skript wird automatisch �ber die Homematic Dis-WM55 Instanz ausgef�hrt';
    return;
}

if (($_IPS['ACTION'] == 'UP') or ( $_IPS['ACTION'] == 'DOWN'))
{
    switch ($_IPS['PAGE'])                                  // Anzeige pro Seite
    {
        case 1:  // Seite 1

            $display_line[1] = array('Text' => "SEITE 1",   // Text  Seite 1 Zeile 1
                'Icon' => Icon_open,                        // Icon  Seite 1 Zeile 1
                'Color' => Color_red);                      // Farbe Seite 1 Zeile 1

            $display_line[2] = array('Text' => "Zeile2",
                'Icon' => Icon_no,
                'Color' => Color_red);

            $display_line[3] = array('Text' => "Zeile3",
                'Icon' => Icon_open,
                'Color' => Color_orange);

            $display_line[4] = array('Text' => "Zeile4",
                'Icon' => Icon_no,
                'Color' => Color_orange);

            $display_line[5] = array('Text' => "Zeile5",
                'Icon' => Icon_closed,
                'Color' => Color_green);

            $display_line[6] = array('Text' => "Zeile6",
                'Icon' => Icon_no,
                'Color' => Color_green);
            break;
        case 2:  // Seite 2
            $display_line[1] = array('Text' => ":",
                'Icon' => Icon_no);

            $display_line[2] = array('Text' => "SEITE 2",
                'Icon' => Icon_open,
                'Color' => Color_orange);

            $display_line[3] = array('Text' => "",
                'Icon' => Icon_no);

            $display_line[4] = array('Text' => "Uhrzeit",
                'Icon' => Icon_no,
                'Color' => Color_white);


            $display_line[5] = array('Text' => date("H:i:s",time()),  // Uhrzeit
                'Icon' => Icon_no,
                'Color' => Color_white);

            $display_line[6] = array('Text' => "",
                'Icon' => Icon_no);

            break;
        case 3:  // Seite 3
            $display_line[1] = array('Text' => "",
                'Icon' => Icon_no);

            $display_line[4] = array('Text' => "SEITE 3",
                'Icon' => Icon_open,
                'Color' => Color_orange);

            $display_line[2] = array('Text' => "",  // GetValueFormatted(12345 /*[Objekt #12345 existiert nicht]*/);
                'Icon' => Icon_no);

            $display_line[3] = array('Text' => "",
                'Icon' => Icon_no);

            $display_line[5] = array('Text' => "",
                'Icon' => Icon_no);

            $display_line[6] = array('Text' => "",
                'Icon' => Icon_no);

            break;
    }
}

if ($_IPS['ACTION'] == 'ActionUP')                              // Aktion & Anzeige bei ActionUP
{
    // Hier kann auch wie oben bei 'PAGE' noch je nach Seite unterschieden werden !
    $display_line[1] = array('Text' => hex_encode("F�hre"),
        'Icon' => Icon_no,
        'Color' => Color_orange);

    $display_line[2] = array('Text' => "Aktion",
        'Icon' => Icon_no,
        'Color' => Color_orange);

    $display_line[3] = array('Text' => "OBEN ",
        'Icon' => Icon_no,
        'Color' => Color_orange);

    $display_line[4] = array('Text' => "Seite " . $_IPS['PAGE'],
        'Icon' => Icon_no,
        'Color' => Color_orange);

    $display_line[5] = array('Text' => "aus",
        'Icon' => Icon_no,
        'Color' => Color_orange);

    $display_line[6] = array('Text' => "",
        'Icon' => Icon_no);
}

if ($_IPS['ACTION'] == 'ActionDOWN')                             // Aktion & Anzeige bei ActionDOWN
{
    // Hier kann auch wie oben bei 'PAGE' noch je nach Seite unterschieden werden !
    $display_line[1] = array('Text' => hex_encode("F�hre"),
        'Icon' => Icon_no,
        'Color' => Color_orange);

    $display_line[2] = array('Text' => "Aktion",
        'Icon' => Icon_no,
        'Color' => Color_orange);

    $display_line[3] = array('Text' => "UNTEN",
        'Icon' => Icon_no,
        'Color' => Color_orange);

    $display_line[4] = array('Text' => "Seite " . $_IPS['PAGE'],
        'Icon' => Icon_no,
        'Color' => Color_orange);

    $display_line[5] = array('Text' => "aus",
        'Icon' => Icon_no,
        'Color' => Color_orange);

    $display_line[6] = array('Text' => "",
        'Icon' => Icon_no);
}

$data = json_encode($display_line);
echo $data; //Daten zur�ckgeben an Dis-WM55-Instanz

function hex_encode ($string)
{
	$umlaut =  array("�"   ,"�"   ,"�"   ,"�"   ,"�"   ,"�"   ,"�"   ,":"   );
   $hex_neu = array(chr(0x5b),chr(0x23),chr(0x24),chr(0x7b),chr(0x7c),chr(0x7d),chr(0x5f),chr(0x3a));
   $return = str_replace($umlaut, $hex_neu, $string);
   return $return;
}

?>

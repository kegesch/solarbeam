# Solar Beam ![alt text](https://github.com/Y0nnyy/solarbeam/raw/master/web/favicons/favicon-32x32.png "Logo")

Ein Projekt um Zählerstände eines SmartMeters auszulesen, in eine Datenbank zu schreiben und zu visualisieren.

`data\` Ordner mit privaten alten Zählerstenden die nachträglich in die Datenbank eingepflegt wurden.
`python\` enthält die _Python_-Scripts, die für das Auslesen des Zählerstandes genutzt werden. 

## Hardware 
Es wird ein __Raspberry Pi Model A__ genutzt. An dieses sind zwei IR-Leseköpfe per USB angeschlossen. Hier bestellen: [Weidmann Elektronik](http://shop.weidmann-elektronik.de/index.php?page=product&info=24)
Eingerichtete Smartmeter sind die EhZ von der Firma EMH. 

## Einrichtung
__Raspberry Pi Image__
Raspberry Pi Image (Raspbian) auf einer SD-Karte (min. 8GB) installieren. 
[Anleitung](https://www.raspberrypi.org/documentation/installation/installing-images/)
Standareinstellungen vornehmen, wie in etwa Zeit einstellen und Speicher erweitern:
```sh
sudo raspi-config
sudo apt-get upgrade all
```
__Python Skripte einrichten__
`python\` auf das Raspberry kopieren. Beispielsweise über FTP. Ich habe die Daten in den Ordner `home\pi\Documents\EHZ\` kopiert. 

Nun muss man die Python-Skripte noch anpassen. Um zu testen welche Nachrichten der Stromzähler alles so sendet kann man das Skript `python\Sensortest.py` aufrufen. Davor sollte man sicherstellen, dass auch der richige analoge Anschluss dort eingetragen ist. Und natürlich auch auf die Parität und Stoppbits muss man achten. Standardmäßig ist der IR-Lesekopf auf `\dev\ttyUSB0` gebunden. 
Um nachzuschauen kann man davor unter folgenden Geräten suchen: 
```sh 
cd dev\
```
Nach dem `python\Sensortest.py` ausgeführt wird sollten dann die verschiedenen Nachrichten kommen. Dabei wird allerdings nur der relevante Ausschnitt, der die Zählerstände beinhaltet, ausgegeben.

```sh
# 77078181c78203ff0101010104454d4801
# 77070100000009ff010101010b06454d480104c56ec5bd01
# 77070100020800ff6400000001621e52ff5600020f37e201
# 77070100020801ff0101621e52ff5600020f37e201 //Zählerstand
# 77070100100700ff0101621b52ff550000000001 //momentaner Bezug / Leistung
# 77078181c78205ff01726201650136ece901018302739899a4350308b2be3a7022b69067cf0a021eb85e02a2f95810a06a6a1f5e48ed56bc3a53e771f68d66540c260e6d1c010101+
```

Nun schaut man nach welche OBIS-Kennzahlen dort erkenn bar sind. Im Normalfall sollte das für den Bezug `01-08-01` und für Lieferung `02-08-01` sein.
Hier im Beispiel steht dies in der 3. Zeile: 77070100`020801`ff0101621e52ff5600020f37e201
Den Wert den man dann haben möchte ist zwischen dem `ff56` und der `01` also hier: `00020f37e2`. Diesen Hex-Wert wandelt man dann noch in einen Integer-Wert um teilt ihn durch 10^4. Dann hat man das gewünschte Ergebnis.

Hat man also seinen entsprechenden Wert gefunden, merkt man sich die Zahlenfolge die die OBIS-Kennzahl darstellt. Hier z.B.: `77070100020801ff`. Diese gibt man im Skript dann als Suchparameter ein. Meine Skripte basieren auf dem Skript von [Alexander Kabza](http://www.kabza.de/MyHome/RPi.html).
Ich habe es so weiter bearbeitet, dass sobald eine Nachricht fertig gelesen wurde, der Wert in eine Datenbank geschrieben wird. Zu dem Python-Skript mache ich dann noch ein ausführbares Shell-Skript. 

Shell-Skript: 
````sh
#!/bin/bash
sudo python /home/pi/Documents/EHZ/strombezug.py
````
Ausführbar machen: 
````sh
chmod +x strombezug.sh
````

Das Skript lasse ich dann per Cronjob jeden Tag um 23:59 einmal ausführen. 
Cronjobs lasen sich mit 
````sh
crontab -e
````
erstellen / modifizieren. Und mit 
````sh
crontab -l
````
kann man sich alle Cronjobs anzeigen lassen.
````sh
59 23 * * * /home/pi/Documents/EHZ/stromlieferung.sh
59 23 * * * /home/pi/Documents/EHZ/strombezug.sh
````
So erhalte ich was die Photovoltaik anlage am Tag geleistet hat bzw. was das Haus an Strom pro Tag verbraucht und kann das ganze dann später visualisiert darstellen.

## MySQL und Apache ##
````sh
sudo apt-get mysql-server mysql-client apache2 php5-cgi php5-mysql
````
-mysql user erstellen und datenbank erstellen
-Create sql ausführen
-mysql-zugangsdaten in die Python-Skripte speichern / in der index.php und
api.php

Die Datenbank enthält eine Tabelle, die die Zähler speichert. Ein Zähler hat
eine ID, einen Namen und ein Offset. Der Offset dient dazu den Zählerstand zu
speichern, wenn ein ein neuer Zähler eingesetzt wird. So legt man einen neuen
Zähler mit dem Offset = Zählerstand des alten Zählers an. Somit kann man später
die Berechnung genaustens Durchführen.

Weiter gibt es noch eine Tabelle für den Bezug und Lieferung in der jeweils die
Zähler-ID, ein Timestamp und der Zählerstand gespeichert wird. 
Wenn man will kann man auch noch ein Skript laufen lassen, was sekündlich den
aktuellen Wert des Zähler, also die momentane Leistung / Bezug in eine Tabelle
schreibt. Das kann man aber natürlich auch anders lösen.

TODO Create-SQL

## Front-End
Zur Visualisierung habe ich eine kleine API geschrieben, die mir beispielsweise
die Werte pro Monat im Jahr oder die Werte pro Jahre im JSON-Format
zurückliefert. Um das ganze dann darzustellen benutze ich die OSS Chart.js. Und
lasse dann per Ajax die Werte von der API holen. 

### API

`?q=yearly` Ein Wert pro Jahr (Lieferung)

`?q=yearlybezug` Ein Wert pro Jahr (Bezug)

`?q=year&y=xxxx` Ein Wert pro Monat für das Jahr `xxxx`

`?q=year` Ein Wert pro Monat für alle vorhandenen Jahre

`?q=c` Gibt die aktuellen Werte des Zählerstandes (Lieferung + Bezug) zurück

`?q=w` Gibt die Werte der letzten 7 Tage zurück

## Quellen
http://wiki.volkszaehler.org/software/sml
http://www.kabza.de/MyHome/RPi.html
https://github.com/chartjs/Chart.js/releases
https://jquery.com

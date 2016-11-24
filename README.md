# Solar Beam ![alt text](https://github.com/Y0nnyy/solarbeam/raw/master/favicons/favicon-32x32.png "Logo")

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

Nun schaut man nach welche OBIS-Kennzahlen dort erkenn bar sind. Im Normalfall sollte das für den Bezug `1-08-01` und für Lieferung `2-08-01` sein.



## Quellen
http://wiki.volkszaehler.org/software/sml
http://www.kabza.de/MyHome/RPi.html
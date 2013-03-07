# AssetsExtraBundle
Dieses Bundle stellt eine erweiterte Konfiguration und Twig-Extension für Assets zur Verfügung.

## Installation
Befolge folgende Schritte, um das Bundle in deiner Symfony-Umgebung zu installieren.

### 1. Schritt
Füge die folgende Zeile zu deiner **composer.json** hinzu:

	"require" :  {
    	// ...
    	"checkdomain/assets-extra-bundle": "dev-master",
	}
	
### 2. Schritt
Führe ein **composer update** aus, um die Pakete neu zu laden.

### 3. Schritt
Registriere das Bundle mit folgender Codezeile:

	<?php
	// app/AppKernel.php
	
	public function registerBundles()
	{
    	$bundles = array(
        	// ...
        	new Checkdomain\CheckdomainAssetsExtraBundle(),
    	);
    	// ...
	}

## Konfiguration
Folgende Konfigurationen stehen dir zur Verfügung

	assets_extra:
		write_to: assets
		encrpyt_bundle: false
		assets_path: bundles
		
- **write_to** <br /> ist bekannt aus dem [AsseticBundle](https://github.com/symfony/AsseticBundle). Du kannst angeben, in welchen Ordner die Assets beim Ausführen von **app/console assetic:install** standardmäßig geschrieben werden sollen.

- **encrpyt_bundle** <br /> verschleiert, wenn aktiviert, den Bundle-Namen im Asset-Pfad. Aus **bundles/acmedemo/test.jpg** wird zum Beispiel **bundles/e0b6011f/test.jpg**

- **assets_path** <br /> gibt das Verzeichnis für Assets an. Als Standard ist bei Symfony der Wert "*bundles*" gesetzt, aber vielleicht findet ja der ein oder andere zum Beispiel "*assets*" schöner.


## Anwendung
Der Console-Command **assets:install** funktioniert wie gewohnt, berücksichtigt jedoch auch die AssetsExtra-Konfiguration.

Die Twig-Funktion **asset(path)** berücksichtigt ebenfalls die Konfiguration und lässt zudem nun **@BundleName** im Pfad zu.

## Beispiel
Der Wert für **assets_path** steht auf "*asstes*" und **encrypt_bundle** steht auf "*true*".

**test.html.twig**

	{{ asset('bundles/acmedemo/test.jpg') }}
	{{ asset('@AcmeDemoBundle/test.jpg') }}

**Ergebnis**

	assets/e0b6011f/test.jpg
	assets/e0b6011f/test.jpg
	
Es werden also auch direkte Pfade zu den Standard-Verzeichnissen automatisch umgeschrieben.

## ToDo
1. Assetic-Filter, wie *LessPHP* oder *cssrewrite*, auf konfigurierbare Pfade einstellen.

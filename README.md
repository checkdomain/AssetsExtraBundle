# AssetsExtraBundle
Erweitert die Konfiguration der internen Symfony 2 [Assets-Verwaltung](https://github.com/symfony/FrameworkBundle/tree/master/Templating/Asset), um Bundlenamen im Dateipfad zu verschlüsseln oder auch den Assets-Ordner von "bundles" auf einen anderen umzustellen. Beinhaltet Erweiterungen der Twig [*asset()*-Funktion](http://symfony.com/doc/2.0/book/templating.html#linking-to-assets), des [Assetic](https://github.com/kriswallsmith/assetic) LessPHP-Compilers und CssRewrite-Filters, welche das Verweisen mit [Logical File Names](http://symfony.com/doc/current/quick_tour/the_architecture.html#logical-file-names) erlauben sowie einen Assetic [CssRewrite-Filter-Bug](http://stackoverflow.com/questions/9500573/path-of-assets-in-css-files-in-symfony2) Fix.

## Installation
Befolge folgende Schritte, um das Bundle in deiner Symfony-Umgebung zu installieren.

### 1. Schritt
Füge die folgende Zeile zu deiner ```composer.json``` hinzu:

```json
"require" :  {
	// ...
	"checkdomain/assets-extra-bundle": "dev-master",
}
```
	
### 2. Schritt
Führe ein ```composer update``` aus, um die Pakete neu zu laden.

### 3. Schritt
Registriere das Bundle mit folgender Codezeile:

```php
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
```
	
### 4. Schritt
Wenn du LessPHP nutzen möchtest, aktiviere die Erweiterung wie gewohnt in deiner Konfiguration. Eine Anleitung dazu findest du zum Beispiel auf [howto24.net](http://code.howto24.net/2012/07/09/symfony-2-1-how-to-manage-less-file-by-lessphp/).

Wir empfehlen das aktivieren des CssRewrite-Filters von Assetic auf alle Dateiendungen, wie:

- *.css
- *.less
- *.sass
- und *.scss

Bearbeite deine Konfiguration dazu einfach wie folgt:

```yaml
assetic:
	filters:
		cssrewrite:
    		apply_to: '\.(css|less|sass|scss)$'
```

## Konfiguration
Folgende Konfigurationen stehen dir zur Verfügung

```yaml
checkdomain_assets_extra:
	write_to: web
	encrpyt_bundle: false
	assets_path: bundles
```
		
- **write_to** <br /> ist bekannt aus dem [AsseticBundle](https://github.com/symfony/AsseticBundle). Du kannst angeben, in welchen Ordner die Assets beim Ausführen von ```app/console assetic:install``` standardmäßig geschrieben werden sollen.

- **encrpyt_bundle** <br /> verschleiert, wenn aktiviert, den Bundle-Namen im Asset-Pfad. Aus ```bundles/acmedemo/test.jpg``` wird zum Beispiel ```bundles/e0b6011f/test.jpg```

- **assets_path** <br /> gibt das Verzeichnis für Assets an. Als Standard ist bei Symfony der Wert ```bundles``` gesetzt, aber vielleicht findet ja der ein oder andere zum Beispiel ```assets``` schöner.


## Anwendung
Im folgenden werden die verschiedenen Anwendungsbereiche kurz erklärt. In den Beispielen nutzen wir folgende Konfiguration.

```yaml
checkdomain_assets_extra:
	encrpyt_bundle: true
	assets_path: assets
```

### Assets installieren
Mit dem Konsolen-Kommando ```assets:install``` lassen sich alle Assets entsprechend der Konfiguration installieren. Weitere Informationen liefert der Befehl ```assets:install --help```.

### Twig asset()-Funktion
Die Twig-Funktion funktioniert wie gewohnt. Zusätzlich ist der Gebrauch von *Logical File Names* möglich.

**test.html.twig**

```twig
{{ asset('bundles/acmedemo/test.jpg') }}
{{ asset('@AcmeDemoBundle/test.jpg') }}
```

**Ergebnis**

```html
/assets/e0b6011f/test.jpg
/assets/e0b6011f/test.jpg
```

### Css-Rewrite-Filter
Ohne dieses Bundle funktioniert dieser Filter nur, wenn keine *Logical File Names* in der Twig-Extension für Assetic genutzt werden. Dieses Problem ist gelöst und zudem sind auch *Logical File Names* in den CSS-Dateien selbst möglich.

**/src/Acme/DemoBundle/Resources/public/css/test.css**

```less
// Zeigt auf: /src/Checkdomain/TwitterBootstrapBundle/Resources/public/css/bootstrap.css
@import url(@CheckdomainTwitterBootstrapBundle/css/bootstrap.css);

.logo {
	// Zeigt auf: /src/Acme/DemoBundle/Resources/public/img/logo.jpg
	background-image: url(../img/logo.jpg);
}
```
		
**Ergebnis**

```css
@import url(/assets/19e3eda3/css/bootstrap.css);
	
.logo {
	background-image: url(/assets/19e3eda3/img/logo.jpg);
}
```
	
### LessPHP-Compiler
Interessant sind hier Imports aus verschiedenen Bundles, welche ohne dieses Bundle nur durch mühselige Angabe des kompletten Verzeichnispfades möglich wären. Wir nutzen einfach *Logical File Names*. In diesem Beispiel nutzen wir das [TwitterBootstrapBundle](https://gitbub.com/checkdomain/twitter-bootstrap-bundle).

**/src/Acme/DemoBundle/Resources/public/css/test.css**

```less
// Zeigt auf: /src/Checkdomain/TwitterBootstrapBundle/Resources/private/less/bootstrap.less
@import url(@CheckdomainTwitterBootstrapBundle/Resources/private/less/bootstrap.less);
```

**Achtung:** Da die Less-Dateien nicht zwangsläufig im ```public```-Ordner liegen müssen, ist hier die Angabe des kompletten Pfades nötig, während in den anderen Beispielen der Pfad ```Resources/public/``` komplett weggelassen werden muss und automatisch von diesem Ordner ausgegangen wird, da nur dieser bei einem ```assets:install``` kopiert wird.

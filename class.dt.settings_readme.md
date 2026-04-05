# DT_SETs - Verzeichnis- und Link-Management

## **Zweck**

Die Klasse `DT_SETs` verwaltet **Verzeichnisstrukturen** und generiert **dynamisch HTML-Links** für CSS- und JS-Dateien. Sie ist ideal für Webprojekte, die eine **konsistente Verzeichnisstruktur** und **automatisierte Ressourcen-Einbindung** benötigen.

---

## **Installation**

```php
require_once 'path/to/class.mysets.php';
$SET = new DT_SETs("."); // Basisverzeichnis setzen
```

---

## **Kernfunktionen**

### **1. Verzeichnisverwaltung**

```php
// Basisverzeichnis setzen
$SET->set_DIR_BASE("/pfad/zu/projekt/");

// Standardverzeichnisse abrufen
echo $SET->get_DIR_CSS(); // Gibt: "/pfad/zu/projekt/includes/css/"
```

### **2. HTML-Links generieren**

```php
// Alle CSS-Dateien im Verzeichnis einbinden
echo $SET->createLink("css", 2); // 2 Tabs Einrückung

// JS-Dateien mit Priorisierung einbinden
$priorities = ["main", "vendor"];
echo $SET->createLink("js", 2, $priorities);
```

### **3. Benutzerdefinierte Verzeichnisse**

```php
// Zusätzliche Verzeichnisse setzen
$customDirs = [
    "DIR" => [
        "INC" => "/custom/includes/",
        "CSS" => "/custom/css/"
    ]
];
$SET->set_arDIRs($customDirs);
```

---

## **Architektur**

- **Keine externen Abhängigkeiten** (reines Vanilla-PHP).
- **Flexible Konfiguration**: Verzeichnisse können **standardmäßig oder benutzerdefiniert** gesetzt werden.
- **Automatisierte Link-Generierung**: CSS/JS-Dateien werden **dynamisch eingelesen und priorisiert**.
- **Fehlerbehandlung**: Rückgabewerte wie `false` ermöglichen **individuelles Handling** durch den Nutzer.

---

**Hinweis**: Die Klasse ist **erweiterbar** – z. B. für zusätzliche Dateitypen oder komplexere Priorisierungslogik.

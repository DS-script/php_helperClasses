# Helper Classes - PHP (Vanilla)

## Zweck

Modulare Helper-Klassen und Traits für wiederkehrende Aufgaben in PHP-Projekten. Framework-unabhängig, für Szenarien, in denen Standardfunktionen unzureichend oder umständlich sind.

## Einsatzbereiche

- Datenbankoperationen (Prepared Statements, Transaktionslogik)
- Array-Verarbeitung (Validierung, Transformation)
- Utility-Funktionen (String-Array-Konvertierung, kontrollierte Validierung)

---

## Design-Prinzipien

### 1. Modularität

Jede Klasse/Trait ist **selbstständig nutzbar** und löst ein klar definiertes Problem.

### 2. Minimalismus

- **Keine externen Abhängigkeiten** (reines Vanilla-PHP)
- **Keine erzwungene Fehlerbehandlung** – Rückgabewerte ermöglichen individuelles Handling

### 3. Dokumentation

- **PHPDoc-Standard** für alle Methoden
- **Praktische Beispiele** in den Klassendokumentationen:
  - `class.trait.array_readme.md` >> `class.trait.array.php`
  - `class.db_readme.md` >> `class.db.php`

---

## Zielgruppe

Für Entwickler, die **praktische, wiederverwendbare Lösungen** für Standardaufgaben benötigen – ohne Framework-Ballast.

---

**Kontakt**  
Dave Taylor  
[dt-script@descriptsign.de](mailto:dt-script@descriptsign.de)

---

> *Kein Framework, sondern eine **Sammlung spezialisierter Utilities** – wie ein Werkzeugkasten für gezielten Einsatz.*

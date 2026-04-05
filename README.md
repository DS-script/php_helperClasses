# DB Helper Classes PHP (Vanilla)

**Zweck**: Eigenständige, wiederverwendbare Hilfsklassen für Datenbankoperationen in PHP-Projekten. Framework-unabhängig und optimiert für häufige Aufgaben.

---

## **Installation**

1. **Dateien einbinden**:
  ```php
   require_once 'path/to/DB.php';
   require_once 'path/to/class.trait.array.php'; // Für Array-Hilfsfunktionen
  ```
2. **Datenbankkonfiguration** (z. B. in `config.php`):
  ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'deine_datenbank');
   define('DB_USER', 'benutzer');
   define('DB_PASS', 'passwort');
   define('DB_PORT', '3306');
  ```

---

## **Schnellstart**

### **1. Verbindung herstellen**

```php
$db = new DB(); // Nutzt Konstanten aus config.php
// Oder mit Parametern:
$db = new DB('localhost', 'db_name', 'user', 'pass', '3306');
```

### **2. Häufige Abfragen**

#### **SELECT (mehrere Ergebnisse)**

```php
$query = "SELECT * FROM users WHERE id = :id";
$params = [ [":id", 1, "int"] ]; // [Parameter, Wert, Typ]
$results = $db->query_prepare_fetchAll($query, $params);
```

#### **INSERT (mehrere Datensätze)**

```php
$query = "INSERT INTO users (name) VALUES (:name)";
$users = [
    [":name", "Alice", "strg"],
    [":name", "Bob", "strg"]
];
$affectedRows = $db->query_prepare_multiWrite($query, $users);
```

#### **Letzte Insert-ID**

```php
$db->query_prepare("INSERT INTO users (name) VALUES ('Test')", null);
$lastId = $db->get_lastID();
```

#### **Array-Transformation (für dynamische Keys)**

```php
$params = [
    ["name", "Alice", "strg"],
    ["name", "Bob", "strg"] // → Wird zu "name_0", "name_1"
];
$uniqueKeys = $db->transform_arParam_keyIterator($params);
```

---

## **Architektur**

- **Vanilla-PHP**: Keine Abhängigkeiten.
- **Fehlerbehandlung**: PDO-Exceptions werden direkt geworfen (Nutzer entscheidet über Handling).
- **Modular**: Trait `DB_PREPARES` kann in andere Klassen eingebunden werden.

---

**Autor**: Dave Taylor
**eMail**: dt-script@descriptsign.de

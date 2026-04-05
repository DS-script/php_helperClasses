# ARRAY Helper - Classes PHP (Vanilla)

**Zweck**: Universell einsetzbare Funktionen zur Array-Prüfung **ohne aufdringliche Fehlerbehandlung** – der Nutzer entscheidet selbst, wie mit ungültigen Daten umgegangen wird.

---

## **Installation**

```php
require_once 'path/to/DT_ARRAY.php';
```

---

## **Nutzung**

### **1. Array-Prüfungen**

```php
// Prüft, ob ein Array mindestens $min Elemente hat
$isValid = MY_ARRAY::is_array_valide([1, 2, 3], 1); // true

// Prüft, ob ein Array ein 2D-Array ist (locker/strikt)
$is2D = MY_ARRAY::is_array_2D([[1], [2]]); // true (mind. 1 Sub-Array)
$isStrict2D = MY_ARRAY::is_array_2D([[1], [2]], true); // true (ALLE Elemente sind Arrays)
```

### **2. String-zu-Array-Prüfung**

```php
// Prüft, ob ein Wert in einem kommaseparierten String enthalten ist
$exists = MY_ARRAY::in_array_STRG("apple", "apple,banana,orange"); // true
```

---

## **Philosophie**

- **Keine Exceptions** für einfache Prüfungen (z. B. ungültige Arrays) – der Nutzer entscheidet über das Handling.
- **Exceptions nur bei kritischen Fehlern** (z. B. PDO-Verbindungsprobleme).

---

**Autor**: Dave Taylor
[dt-script@descriptsign.de](mailto:dt-script@descriptsign.de)

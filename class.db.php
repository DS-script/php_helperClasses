<?php
/*
--- Comment -----------------------------------------------------
    CLASS - DB              Verbindung zur DB
    CLASS - PREPARES        Prepare-Methoden

    Beispiel :
                            const DB_HOST   = "SERVER";
                            const DB_NAME   = "DATABASE";
                            const DB_USER   = "NAME";
                            const DB_PASS   = "PASS";
                            const DB_PORT   = "3006";
----------------------------------------------------------------
*/
require_once(__DIR__ . '/class.trait.array.php');

trait DB_PREPARES {
    use DT_ARRAY; # TRAIT einbinden

    /**
     * ----------------------------------------------------------------------
     * Bereitet eine SQL-Abfrage vor und führt sie aus.
     *
     * @param   string              $query Die SQL-Abfrage
     * @param   array|null          $ar Ein Array von Parametern für die EINZELNE Abfrage
     * @return  Int|false           Die Anzahl der Geschriebenen Zeilen oder false bei Fehlern
     * ----------------------------------------------------------------------
     */
    public function query_prepare(string $query, ?array &$ar = null) {
        $out = false;
        $DB = $this->get_DB();

        $stmt = $DB->prepare($query);

        if (self::is_array_valide($ar)) {
            foreach ($ar as $key => $value) {
                $type = PDO::PARAM_STR;
                if (isset($value[2])) {
                    $type = $this->get_paramType($value[2]);
                }
                $stmt->bindValue($value[0], $value[1], $type);
            }
        }
        if ($stmt->execute()) {
            $out = $stmt;
        }
        return $out;
    } // END : query_prepare

    /**
     * ----------------------------------------------------------------------
     * Bereitet eine SQL-Abfrage vor und führt sie aus.
     *
     * @param   string              $query Die SQL-Abfrage
     * @param   array|null          $ar Ein Array von Parametern für MEHRERE Abfragen
     * @return  Int|false           Die Anzahl der Geschriebenen Zeilen oder false bei Fehlern
     * ----------------------------------------------------------------------
     */
    public function query_prepare_multiWrite(string $query, ?array &$ar = null) {
        $out = false;
        $DB = $this->get_DB();
        $stmt = $DB->prepare($query);

        if (self::is_array_valide($ar)) {
            $out = 0;
            foreach ($ar as $key => $value) {
                $type = PDO::PARAM_STR;
                if (isset($value[2])) {
                    $type = $this->get_paramType($value[2]);
                }
                $stmt->bindValue($value[0], $value[1], $type);
                $stmt->execute();
                $out += $stmt->rowCount();
            }
        }
        return $out;
    } // END : query_prepare_multiWrite  

    /**
     * ----------------------------------------------------------------------
     * Führt eine SQL-Abfrage aus und gibt alle Ergebnisse als Array zurück.
     *
     * @param   string      $query Die SQL-Abfrage
     * @param   array|null  $ar Ein Array von Parametern für die Abfrage
     * @return  array|false Ein Array mit den Ergebnissen oder false bei Fehlern
     * ----------------------------------------------------------------------
     */
    public function query_prepare_fetchAll(string $query, ?array &$ar = null) {
        $out = false;
        $stmt = $this->query_prepare($query, $ar);

        if ($stmt) {
            $out = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $out;

        // $arParam = [
        //     [":SQL_PARAM", $PARAM, "TYPE"]
        // ];
    } // END : query_prepare_fetchAll

    /**
     * ----------------------------------------------------------------------
     * Führt eine SQL-Abfrage aus und gibt das erste Ergebnis als Array zurück.
     *
     * @param   string      $query Die SQL-Abfrage
     * @param   array|null  $ar Ein Array von Parametern für die Abfrage
     * @return  array|false Ein Array mit dem ersten Ergebnis oder false bei Fehlern
     * ----------------------------------------------------------------------
     */
    public function query_prepare_fetch(string $query, ?array &$ar = null) {
        $out = false;
        $stmt = $this->query_prepare($query, $ar);

        if ($stmt) {
            $out = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $out;
    }

    /**
     * ----------------------------------------------------------------------
     * Gibt den PDO-Parametertyp basierend auf dem übergebenen String zurück.
     *
     * @param   string      $param Der Parameter als String ('strg' oder 'int')
     * @return  int|false   Der PDO-Parametertyp oder false bei ungültigem Parameter
     * ----------------------------------------------------------------------
     */
    private function get_paramType(string $param) {
        $out = false;
        switch (mb_strtolower($param)) {
            case 'strg':
                $out = PDO::PARAM_STR;
                break;
            case 'int':
                $out = PDO::PARAM_INT;
                break;
            default:
                break;
        }
        return $out;
    } // END : get_paramType

    /**
     * ----------------------------------------------------------------------
     * Iteriert über ein Array und modifiziert den ersten Wert jedes Elements,
     * indem ein fortlaufender Index pro Schlüssel angehängt wird.
     * Zusätzlich wird ein formatierter String für SQL-IN-Klauseln generiert.
     *
     * @param   array   &$ar    Referenz auf das zu verarbeitende Array.
     *                          Erwartete Struktur:
     *                          $arParam[] = ["Key", value, type];
     *
     * @param   string  $strg   Trennzeichen zwischen Schlüssel und Index
     *                          (Standard: "_")
     *
     * @return  array           Assoziatives Array mit:
     *                          - "arStrg":   Array der modifizierten Schlüssel (z. B. ["user_0", "user_1"])
     *                          - "arKeys":   Array der Zähler pro Schlüssel (z. B. ["user" => 2])
     *                          - "strgValue": Formatierter String für SQL-IN-Klauseln
     *                                    (z. B. "(user_0, user_1), (role_0)")
     * 
     * // Beispiel:
     *              $arParam = [["user", "Alice"], ["user", "Bob"], ["role", "admin"]];
     *              $result["strgValue"] => "(user_0, user_1), (role_0)"
     * ----------------------------------------------------------------------
     */
    public function transform_arParam_keyIterator(array &$ar, string $strg="_"){
        $arKeys_iter = []; // iterator CHK bs: [a] = n++ start bei 0 - n
        $arOut["arStrg"] = [];
        $arOut["arKeys"] = [];
        $arOut["strgValue"] = "";

        $arFirstKey = array_key_first($ar);
        $arFirstSubKey = array_key_first($ar[$arFirstKey]);
        $key_control = $ar[$arFirstKey][$arFirstSubKey];

        if (self::is_array_2D($ar, true)) {
            foreach ($ar as $key => &$value) {
                $key_first = array_key_first($value);
                $key_set = $value[$key_first];
                $arKeys_iter[$key_set] = ($arKeys_iter[$key_set] ?? -1) + 1;
                $new_value = $key_set . $strg . $arKeys_iter[$key_set];
                // Baue einen formatierten String für SQL-IN-Klauseln (z. B. "(user_0, user_1, user_2)")
                if ($key_set === $key_control && $arKeys_iter[$key_control] === 0) {
                    $arOut["strgValue"] .= "(" . $new_value;
                } elseif ($key_set === $key_control && $arKeys_iter[$key_control] > 0) {
                    $arOut["strgValue"] .= "), (" . $new_value;
                } else {
                    $arOut["strgValue"] .= ", " . $new_value;
                }

                $value[$key_first] = $new_value;
                $arOut["arStrg"][] = $new_value;
            }
            $arOut["arKeys"] = $arKeys_iter;
            $arOut["strgValue"] .= ")";
        }
        return $arOut;
    } // END : transform_arParam_keyIterator
}
?>
<?php

class DB {
    use DB_PREPARES;
    private static $DB;

    /**
     * ----------------------------------------------------------------------
     * Konstruktor für die DB-Klasse.
     * Stellt eine Verbindung zur Datenbank her, falls noch keine besteht.
     *
     * @param string|null $DB_HOST      Datenbank-Host
     * @param string|null $DB_NAME      Datenbank-Name
     * @param string|null $DB_USER      Datenbank-Benutzer
     * @param string|null $DB_PASSWORD  Datenbank-Passwort
     * @param string|null $DB_PORT      Datenbank-Port
     * ----------------------------------------------------------------------
     */
    public function __construct(string $DB_HOST = null, string $DB_NAME = null, string $DB_USER = null, string $DB_PASSWORD = null, string $DB_PORT = null, string $DB_CHAR = null) {
        if (is_null(self::$DB)) {
            $DB_HOST = $DB_HOST ?? DB_HOST;
            $DB_NAME = $DB_NAME ?? DB_NAME;
            $DB_USER = $DB_USER ?? DB_USER;
            $DB_PASS = $DB_PASSWORD ?? DB_PASS;
            $DB_PORT = $DB_PORT ?? DB_PORT;
            $DB_CHAR = $DB_CHAR ?? 'utf8mb4';
            try {
                self::$DB = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;port=$DB_PORT", $DB_USER, $DB_PASS);
                self::$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $error) {
                die("Verbindungsfehler: " . $error->getMessage());
            }
        }
    }

    /**
     * ----------------------------------------------------------------------
     * Gibt die Datenbankverbindung zurück.
     * INFO :   kann bei transaktionen nütig sein da dann direkt auf das 
     *          PDO-Objekt zugegriffen werden muss
     * 
     * @return PDO|null Die Datenbankverbindung
     * ----------------------------------------------------------------------
     */
    public static function get_DB() {
        return self::$DB;
    }

    /**
     * ----------------------------------------------------------------------
     * Gibt die zuletzt eingefügte ID eines Datensatzes zurück.
     * INFO :   nutzt die PDO-Methode lastInsertId(), um die ID des letzten
     *          INSERT-Statements zu ermitteln
     * 
     * @return string|false Die zuletzt eingefügte ID oder false im Fehlerfall
     * ----------------------------------------------------------------------
     */
    public function get_lastID(){
        $out    = false;
        $DB     = self::get_DB();
        $out    = $DB->lastInsertId();
    
        return $out;
    } // END : get_lastID
} # END : DB

class DB_arPARAM{
    private $param = [];
    private $param_key;   
    private $param_value;   
    private $param_type;
    /* 
    #########################################################
    GETTERs & SETTERs                
    #########################################################
    */
    // ATTRIBUT : param
    public function get_param(): array
    {
        return $this->param;
    } // END : get_param

    public function set_param(array $param): void
    {
        $this->param = $param;
    } // END : set_param
    //.......................................................

    // ATTRIBUT : param_key
    public function get_param_key(): string
    {
        return $this->param_key;
    } // END : get_param_key

    public function set_param_key(string $key): void
    {
        $this->param_key = $key;
    } // END : set_param_key
    //.......................................................


    // ATTRIBUT : param_value
    public function get_param_value(): mixed
    {
        return $this->param_value;
    } // END : get_param_value

    public function set_param_value(mixed $value): void
    {
        $this->param_value = $value;
    } // END : set_param_value
    //.......................................................


    // ATTRIBUT : param_type
    public function get_param_type(): int
    {
        return $this->param_type;
    } // END : get_param_type

    public function set_param_type(int $type): void
    {
        $this->param_type = $type;
    } // END : set_param_type
    //.......................................................  
}

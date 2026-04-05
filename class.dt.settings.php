<?php
/*
##########################################################################
    Title	:   SETTINGs
    Comment	:   alle einstellungen der 
                Seite werden hier gesetzt und verwaltet
##########################################################################
*/
require_once (__DIR__ . "/class.trait.array.php");
require_once (__DIR__ . "/class.trait.msg.php");
?>
<?php
/*
--- comment ----------------------------------------------
    CLASS : DT_SETs
    erzeugt die Basisstruktur der seite 
----------------------------------------------------------
*/
class DT_SETs{
    use DT_ARRAY;
    #######################################################
    # ATTRIBUTES
    #######################################################
    private string $DIR_BASE;
    private string $DIR_INC;
    private string $DIR_PHP;
    private string $DIR_CSS;
    private string $DIR_JS;
    #######################################################
    # CONSTRUCTOR
    #######################################################

    /**
     * ----------------------------------------------------------------------
     * Konstruktor der Klasse DT_SETs.
     * Erstellt die Basisverzeichnisse der Seite und optional ein Array
     * von Verzeichnissen.
     *
     * @param   string  $dir_base   Basisverzeichnis der Seite
     * @param   array   $arDirs     Optional: Array mit benutzerdefinierten Verzeichnissen
     *
     * @return  void
     * ----------------------------------------------------------------------
     */
    public function __construct(string $dir_base, array &$arDirs = []) {
        $out = false;
        $this->set_DIR_BASE($this->chk_dirStruc($dir_base));

        $this->set_DIR_INC  ($this->get_DIR_BASE() . "includes/");
        $this->set_DIR_CSS  ($this->get_DIR_INC() . "css/");
        $this->set_DIR_PHP  ($this->get_DIR_INC() . "php/");
        $this->set_DIR_JS   ($this->get_DIR_INC() . "js/");
        if ($this->is_array_valide($arDirs)) {
            if ($this->set_arDIRs($arDirs) === true) {
                $out = true;
            }
        }
    } // END : __construct

    #######################################################
    # GETTERs & SETTERs
    #######################################################

    /**
     * ----------------------------------------------------------------------
     * Gibt das Basisverzeichnis zurück.
     *
     * @return  string  Das Basisverzeichnis
     * ----------------------------------------------------------------------
     */
    public function get_DIR_BASE(): string{
        return $this->DIR_BASE;
    } // END : get_DIR_BASE

    /**
     * ----------------------------------------------------------------------
     * Setzt das Basisverzeichnis.
     *
     * @param   string  $value  Neues Basisverzeichnis
     *
     * @return  void
     * ----------------------------------------------------------------------
     */
    public function set_DIR_BASE(string $value): void{
        $this->DIR_BASE = $value;
    } // END : set_DIR_BASE

    /**
     * ----------------------------------------------------------------------
     * Gibt das Include-Verzeichnis zurück.
     *
     * @return  string  Das Include-Verzeichnis
     * ----------------------------------------------------------------------
     */
    public function get_DIR_INC(): string{
        return $this->DIR_INC;
    } // END : get_DIR_INC

    /**
     * ----------------------------------------------------------------------
     * Setzt das Include-Verzeichnis.
     *
     * @param   string  $value  Neues Include-Verzeichnis
     *
     * @return  void
     * ----------------------------------------------------------------------
     */
    public function set_DIR_INC(string $value): void{
        $this->DIR_INC = $value;
    } // END : set_DIR_INC

    /**
     * ----------------------------------------------------------------------
     * Gibt das PHP-Verzeichnis zurück.
     *
     * @return  string  Das PHP-Verzeichnis
     * ----------------------------------------------------------------------
     */
    public function get_DIR_PHP(): string{
        return $this->DIR_PHP;
    } // END : get_DIR_PHP

    /**
     * ----------------------------------------------------------------------
     * Setzt das PHP-Verzeichnis.
     *
     * @param   string  $value  Neues PHP-Verzeichnis
     *
     * @return  void
     * ----------------------------------------------------------------------
     */
    public function set_DIR_PHP(string $value): void{
        $this->DIR_PHP = $value;
    } // END : set_DIR_PHP

    /**
     * ----------------------------------------------------------------------
     * Gibt das CSS-Verzeichnis zurück.
     *
     * @return  string  Das CSS-Verzeichnis
     * ----------------------------------------------------------------------
     */
    public function get_DIR_CSS(): string{
        return $this->DIR_CSS;
    } // END : get_DIR_CSS

    /**
     * ----------------------------------------------------------------------
     * Setzt das CSS-Verzeichnis.
     *
     * @param   string  $value  Neues CSS-Verzeichnis
     *
     * @return  void
     * ----------------------------------------------------------------------
     */
    public function set_DIR_CSS(string $value): void{
        $this->DIR_CSS = $value;
    } // END : set_DIR_CSS

    /**
     * ----------------------------------------------------------------------
     * Gibt das JS-Verzeichnis zurück.
     *
     * @return  string  Das JS-Verzeichnis
     * ----------------------------------------------------------------------
     */
    public function get_DIR_JS(): string{
        return $this->DIR_JS;
    } // END : get_DIR_JS

    /**
     * ----------------------------------------------------------------------
     * Setzt das JS-Verzeichnis.
     *
     * @param   string  $value  Neues JS-Verzeichnis
     *
     * @return  void
     * ----------------------------------------------------------------------
     */
    public function set_DIR_JS(string $value): void{
        $this->DIR_JS = $value;
    } // END : set_DIR_JS

    #######################################################
    # METHODs
    #######################################################

    /**
     * ----------------------------------------------------------------------
     * Prüft und bereinigt die Verzeichnisstruktur eines Pfades.
     *
     * @param   string  $dir  Verzeichnis-Pfad
     *
     * @return  string  Bereinigter Verzeichnis-Pfad
     * ----------------------------------------------------------------------
     */
    public function chk_dirStruc(string $dir){
        $out = false;
        $sep = DIRECTORY_SEPARATOR;
        $dir = str_replace(["//", "\\", "/", "\\\\"], $sep, $dir);
        $dir = $dir[-1] === $sep ? $dir : $dir . $sep;
        if (is_dir($dir)) {
            $out = $dir; 
        }
        return $out;
    } //END :chk_dirStruc

    /**
     * ----------------------------------------------------------------------
     * Setzt mehrere Verzeichnisse aus einem Array.
     *
     * @param   array  $ar  Array mit Verzeichnispfaden
     *
     * @return  bool
     * ----------------------------------------------------------------------
     */
    public function set_arDIRs(array &$ar){
        $out = false;
        if ($this->is_array_valide($ar)) {
            $out = true;
            if (isset($ar["DIR"]["INC"])) {
                $this->set_DIR_INC($this->chk_dirStruc($ar["DIR"]["INC"]));
            }

            if (isset($ar["DIR"]["PHP"])) {
                $this->set_DIR_PHP($this->chk_dirStruc($ar["DIR"]["PHP"]));
            }
            
            if (isset($ar["DIR"]["CSS"])) {
                $this->set_DIR_CSS($this->chk_dirStruc($ar["DIR"]["CSS"]));
            }
            
            if (isset($ar["DIR"]["JS"])) {
                $this->set_DIR_JS($this->chk_dirStruc($ar["DIR"]["JS"]));
            }
        }
        return $out;
    } //END :set_arDIRs

    /**
     * ----------------------------------------------------------------------
     * Erstellt Links für CSS- oder JS-Dateien eines Verzeichnisses.
     *
     * @param   string  $value  "css" oder "js"
     * @param   int     $tab    Anzahl der Tabs für Einrückung
     * @param   array   $ar     Prioritäten-Array
     *
     * @return  string  HTML-Links für die Dateien
     * ----------------------------------------------------------------------
     */
    public function createLink(string $value, int $tab=0, array &$ar=[]){
        $dir = "";
        $out = "";
        if (in_array($value, ["js","css"])) {
            if ($value === "js") {
                $dir = $this->get_DIR_JS();
            }else{
                $dir = $this->get_DIR_CSS();
            }

            $arFiles = glob($dir . "*." . $value);
            $out = $value === "css" ?   $this->createLink_css($arFiles, $tab, $ar) 
                                    :   $this->createLink_js($arFiles, $tab, $ar);
            
        }
        return $out;
    } //END :createLink

    /**
     * ----------------------------------------------------------------------
     * Erstellt HTML-Link-Tags für CSS-Dateien.
     *
     * @param   array  $ar       Array mit Dateipfaden
     * @param   int    $tab      Tab-Einrückung
     * @param   array  $arPrio   Prioritäten-Array
     *
     * @return  string  HTML-Ausgabe für CSS-Dateien
     * ----------------------------------------------------------------------
     */
    private function createLink_css(array &$ar, int $tab, array $arPrio){
        $tab_n  = $this->get_tabStrg($tab+1);
        $tab_e  = $this->get_tabStrg($tab);
        
        $out = "<!-- AREA_LINK : CSS -->\n";
        
        $arSet = $this->create_arPrio($ar, $arPrio, "css");

        foreach ($arSet as $key => $value) {
            $out .= $tab_n;
            $out .= '<link rel="stylesheet" href="' . $value . '">' . "\n";
        }
        $out .= "$tab_e<!-- AREA_END : CSS -->\n";
        return $out;
    } //END :createLink_css

    /**
     * ----------------------------------------------------------------------
     * Erstellt HTML-Script-Tags für JS-Dateien.
     *
     * @param   array  $ar       Array mit Dateipfaden
     * @param   int    $tab      Tab-Einrückung
     * @param   array  $arPrio   Prioritäten-Array
     *
     * @return  string  HTML-Ausgabe für JS-Dateien
     * ----------------------------------------------------------------------
     */
    private function createLink_js(array &$ar, int $tab, array $arPrio){
        $tab_n  = $this->get_tabStrg($tab+1);
        $tab_e  = $this->get_tabStrg($tab);

        $arSet = $this->create_arPrio($ar, $arPrio, "js");        

        $out = "<!-- AREA_LINK : JS -->\n";

        foreach ($arSet as $key => $value) {
            $out .= $tab_n;
            $out .= '<script src="' . $value . '"></script>' . "\n";
        }
        $out .= "$tab_e<!-- AREA_END : JS -->\n";
        return $out;
    } //END :createLink_js

    /**
     * ----------------------------------------------------------------------
     * Hilfsmethode zur Erstellung von Tab-Einrückungen als String.
     *
     * @param   int  $Value  Anzahl der Tabs
     *
     * @return  string  Tabulatoren als String
     * ----------------------------------------------------------------------
     */
    private function get_tabStrg(int $Value){
        $out  = "";
        if ($Value >= 0) {
            $out = str_repeat("\t", $Value+1);
        }
        return $out;
    } // END : get_tabStrg

    /**
     * ----------------------------------------------------------------------
     * Sortiert ein Array nach Prioritäten und hängt verbleibende Dateien an.
     *
     * @param   array   $ar      Original-Array der Dateien
     * @param   array   $arPrio  Array mit Prioritäten
     * @param   string  $ext     Dateiendung ("js" oder "css")
     *
     * @return  array   Sortiertes Array der Dateien
     * ----------------------------------------------------------------------
     */
    private function create_arPrio($ar, $arPrio, $ext="js"){
        $out = false;
        if ($this->is_array_valide($arPrio)) {
            $arSort = [];
            foreach ($arPrio as $key => $value) {
                $fileSearch = ($ext === "js" ? $this->get_DIR_JS() : $this->get_DIR_CSS()) . $value . ".$ext";
                $arSort[] = $fileSearch;
                $eleDel = array_search($fileSearch, $ar); 
                if ($eleDel !== false) {
                    unset($ar[$eleDel]);
                }
            }
            $out = array_merge($arSort, $ar);
        }else {
            $out = $ar;
        }
        return $out;
    } //END :create_arPrio

}
?>
<?php
    $SET = new DT_SETs(".");
    define('DIR_PHP', $SET->get_DIR_PHP());
    define('PAGE_SELF', htmlspecialchars($_SERVER['PHP_SELF']));
    define("SET", $SET);
?>

<?php // UTF-8 marker äöüÄÖÜß€
/**
 * Class Page for the exercises of the EWA lecture
 * Demonstrates use of PHP including class and OO.
 * Implements Zend coding standards.
 * Generate documentation with Doxygen or phpdoc
 *
 * PHP Version 5
 *
 * @category File
 * @package  Pizzaservice
 * @author   Bernhard Kreling, <b.kreling@fbi.h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 * @author  Noah Kottenhahn, <noah.kottenhahn@stud.h-da.de>
 * @author  Max Klosterhalfen, <max.klosterhalfen@stud.h-da.de>
 */

/**
 * This abstract class is a common base class for all
 * HTML-pages to be created.
 * It manages access to the database and provides operations
 * for outputting header and footer of a page.
 * Specific pages have to inherit from that class.
 * Each inherited class can use these operations for accessing the db
 * and for creating the generic parts of a HTML-page.
 *
 * @author   Bernhard Kreling, <b.kreling@fbi.h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 */

class JSONObject
{
    public $status;
    public $optionals;

    public function __construct()
    {
    }
}

abstract class Page
{
    /** @var mysqli Reference to the MySQLi-Database that is accessed by all operations of the class. */
    protected $_database = null;

    /** @var string Konstante für Redirect auf Phase 0 */
    static $LOC_PHASE0 = "Location: phase0.php";

    /** @var string Konstante für Redirect auf Phase 1 */
    static $LOC_PHASE1 = "Location: phase1.php";

    /** @var string Konstante für Redirect auf Phase 2 */
    static $LOC_PHASE2 = "Location: phase2.php";

    /** @var string Konstante für Redirect auf Phase 3 */
    static $LOC_PHASE3 = "Location: phase3.php";

    /** @var string Konstante für Redirect auf Phase 4 */
    static $LOC_PHASE4 = "Location: phase4.php";

    /** @var string Konstate für Key des Session-Werts der aktuellen Phase */
    static $SESSION_KEY_PHASE = "phase";

    // --- OPERATIONS ---

    /**
     * Connects to DB and stores
     * the connection in member $_database.
     * Needs name of DB, user, password.
     *
     * @return void
     */
    protected function __construct()
    {
        $this->_database = new mysqli("localhost", "website", "ewa2019sose", "bestelldienst");
        if ($this->_database->connect_error) {
            die("Connection failed: " . $this->_database->connect_error);
        }
        $this->_database->set_charset("utf8"); // stellt sicher, dass Sonderzeichen korrekt übertragen werden
    }

    /**
     * Closes the DB connection and cleans up
     *
     * @return void
     */
    protected function __destruct()
    {
        $this->_database->close();
    }

    /**
     * Verhindert SQL-Injection
     *
     * @param String $string Zu verarbeitender String
     * @return string String ohne Escape-Characters
     */
    protected function real_escape_string($string) {
        return $this->_database->real_escape_string($string);
    }

    /**
     * Generates the header section of the page.
     * i.e. starting from the content type up to the body-tag.
     * Takes care that all strings passed from outside
     * are converted to safe HTML by htmlspecialchars.
     *
     * @param String $title Titel für Website
     * @return void
     */
    protected function generatePageHeader($title)
    {
        header("Content-type: text/html; charset=UTF-8");
echo <<<HTML
        <!DOCTYPE html>
        <html lang="de">
          <head>
            <meta charset="UTF-8">
            <title>$title</title>
            <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
            <link rel="stylesheet" href="main.css">
            <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
            <link href="https://fonts.googleapis.com/css?family=Raleway|Roboto:500,900" rel="stylesheet">
          </head>

          <body>
HTML;
    }

    protected function generatePageTitle() {
echo<<<HTML
      <header>
        <span class="headerTitle">Genochoice</span>
        <span class="headerSubtitle">Legacy by Design</span>
      </header>
HTML;
    }

    /**
     * Generiert Navigationsleiste.
     * Setzt "active"-class je nachdem, welche Seite aktiv ist (diese Seite)
     *
     * @param $active_index int Index des aktiven Navigationselements
     * @return void
     */
    protected function generateNavigationBarUser($active_index)
    {
        $active = " class=\"active\"";
        echo "<ul class=\"navlist\">";
        echo    "<li".(($active_index == 0)?$active:"")."><a href=\"phase0.php\">Anmeldung</a></li>";
        echo    "<li".(($active_index == 1)?$active:"")."><a href=\"phase1.php\">Phase 1</a></li>";
        echo    "<li".(($active_index == 2)?$active:"")."><a href=\"phase2.php\">Phase 2</a></li>";
        echo    "<li".(($active_index == 3)?$active:"")."><a href=\"phase3.php\">Phase 3</a></li>";
        echo    "<li".(($active_index == 4)?$active:"")."><a href=\"phase4.php\">Phase 4</a></li>";
        echo    '<li id="btnWrapNavbar"><i class="material-icons">menu</i></li>';
        echo "</ul>";
    }

    protected function generateNavigationBarAgent($active_index)
    {
        $active = " class=\"active\"";
        echo '<ul class="navlist">';
        echo    "<li".(($active_index == 0)?$active:"")."><a href=\"phase1_agent.php\">Phase 1</a></li>";
        echo    "<li".(($active_index == 1)?$active:"")."><a href=\"phase4_agent.php\">Phase 4</a></li>";
        echo    '<li id="btnWrapNavbar"><i class="material-icons">menu</i></li>';
        echo '</ul>';
    }

    /**
     * @param $is_agent boolean Ob für einen Bearbeiter (oder User) generiert werden soll
     */
    protected function generateCurrentUser($is_agent)
    {
        if ($is_agent) {
            echo <<<HTML
            <div class="currentUser">
              Bearbeiter: <div class="userName">stnokott</div>
            </div>
HTML;
        } else if (isset($_SESSION["userid"])) {
            $query = "SELECT firstname, lastname, email FROM user WHERE userid = '".$_SESSION["userid"]."'";
            $result = $this->_database->query($query);

            if ($row = $result->fetch_assoc()) {
                $name = htmlspecialchars($row["firstname"])." ".htmlspecialchars($row["lastname"]);
                echo "<div class=\"currentUser\">";
                echo    "Nutzer: <div class=\"userName\">$name</div>";
                echo    "<form action=\"resetUser.php\" class=\"inline\">";
                echo        "<button class=\"noshadow floatright nobackground\"><i class=\"material-icons\">clear</i></button>";
                echo    "</form>";
                echo "</div>";
            }
        }
    }

    /**
     * Outputs the end of the HTML-file i.e. /body etc.
     * Fügt JavaScript am Ende der Datei ein
     *
     * @param $jspath String Pfad der JavaScript-Datei
     * @return void
     */
    protected function generatePageFooter($jspath)
    {
        //$this->generateClearSessionButton();
        if (isset($jspath)) {
            echo "<script src=\"$jspath\"></script>";
        }
        echo "</body>";
        echo "</html>";
    }

    /**
     * Outputs the end of the HTML-file i.e. /body etc.
     * Version without JavaScript
     *
     * @return void
     */
    protected function generatePageFooterNoJS()
    {
        $this->generateClearSessionButton();
        echo<<<HTML
        </body>
        </html>
HTML;
    }

    protected function generateClearSessionButton() {
        echo<<<HTML
        <section>
            <form action="resetUser.php">
                <button type="submit">Session löschen</button>
            </form>
        </section>
HTML;

    }

    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If every page is supposed to do something with submitted
     * data do it here. E.g. checking the settings of PHP that
     * influence passing the parameters (e.g. magic_quotes).
     *
     * @throws Exception Fehler, falls magic quotes an sind
     * @return void
     */
    protected function processReceivedData()
    {
        if (get_magic_quotes_gpc()) {
            throw new HttpInvalidParamException
                ("Bitte schalten Sie magic_quotes_gpc in php.ini aus!");
        }
    }

    /**
     * Helfer-Methode, um Daten in Datenbank einzufügen
     * @param $table string Tabellenname in der Datenbank
     * @param $columns array Liste an zu setzenden Spalten
     * @param $values array Liste an zu setzenden Werten
     * @return string SQL Query
     */
    protected function getMySQLInsertString($table, $columns, $values) {
        $columns_string = join(", ", $columns);
        $values_string = join("', '", $values);
        return "INSERT INTO ".$table." (".$columns_string.") VALUES ('".$values_string."')";
    }

    /**
     * Prüft die Session-Variable "phase", um sicherzugehen, dass der
     * Nutzer die angeforderte Seite überhaupt abrufen darf
     * Falls die angeforderte Seite nicht schon der korrekten Seite entspricht, wird
     * auf die in der Session gespeicherte Seite weitergeleitet
     *
     * @param $requestedPhase Integer angeforderte Phasennummer
     */
    protected function checkSessionPhase($requestedPhase) {
        if (!isset($_SESSION[self::$SESSION_KEY_PHASE])) {
            $_SESSION[self::$SESSION_KEY_PHASE] = 0;
            header(self::$LOC_PHASE0);
            exit(1);
        }
        $curPhase = $_SESSION[self::$SESSION_KEY_PHASE];
        if ($requestedPhase != $curPhase) {
            $this->redirectToPhase($curPhase);
        }
    }

    /**
     * Leitet Nutzer zu korrekter Seite weiter
     * @param $phaseNum int Phase, die in Session hinterlegt ist
     */
    protected function redirectToPhase($phaseNum) {
        switch($phaseNum) {
            case 0:
                header(self::$LOC_PHASE0);
                break;
            case 1:
                header(self::$LOC_PHASE1);
                break;
            case 2:
                header(self::$LOC_PHASE2);
                break;
            case 3:
                header(self::$LOC_PHASE3);
                break;
            case 4:
                header(self::$LOC_PHASE4);
                break;
            default:
                header(self::$LOC_PHASE0);
        }
    }
} // end of class

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").

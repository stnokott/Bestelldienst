<?php	// UTF-8 marker äöüÄÖÜß€
/**
 * @category File
 * @package  Bestelldienst
 * @author   Noah Kottenhahn, <noah.kottenhahn@stud.h-da.de>
 * @author   Max Klosterhalfen, <max.klosterhalfen@stud.h-da.de>
 * @license  http://www.h-da.de  none
 */

require_once './Page.php';

/**
 * This is a template for top level classes, which represent
 * a complete web page and which are called directly by the user.
 * Usually there will only be a single instance of such a class.
 * The name of the template is supposed
 * to be replaced by the name of the specific HTML page e.g. baker.
 * The order of methods might correspond to the order of thinking
 * during implementation.
 *
 * Angepasst für Bestelldienst
 *
 * @author   Noah Kottenhahn, <noah.kottenhahn@stud.h-da.de>
 * @author   Bernhard Kreling, <b.kreling@fbi.h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 */
class Phase1 extends Page
{
    /**
     * @var bool Definiert, ob beim Aufruf ein neuer User erstellt wurde
     */
    private $new_user = false;

    /**
     * @var string Konstante für Datenbankspaltenname
     */
    static $KEY_USERID = "userid";

    /**
     * Fetch all data that is necessary for later output.
     * Data is stored in an easily accessible way e.g. as associative array.
     *
     * Speichert den Bestellstatus des aktuell angemeldeten Nutzers
     * @return void
     */
    protected function getViewData()
    {
        if(!isset($_SESSION[self::$KEY_USERID])){
            header('Location: phase0.php');
        }
    }

    /**
     * Generiert kurze <section>, um zu zeigen, dass beim Aufruf dieser Seite mit Hilfe der
     * POST-Parameter ein neuer User erstellt wurde
     * @return void
     */
    protected function generateNewUser()
    {
        echo<<<HTML
        <section>
          <div class="sectionHeader">User erstellt!</div>
        </section>
HTML;
    }

    /**
     * Generiert erste <section>, die den Inhalt dieser Seite beschreibt
     * @return void
     */
    protected function generatePageDescription()
    {
        echo<<<HTML
        <section>
          <div class="sectionHeader">GenoCheck&trade; - Sendungsverfolgung</div>
          <p>
            Hier können Sie den Fortschritt Ihres persönlichen GenoCheck&trade;-Tests verfolgen.<br>
            Bei abgeschlossener Analyse werden Sie zu Ihren Ergebnissen weitergeleitet.
          </p>
        </section>
HTML;
    }

    /**
     * Generiert Ansicht zur Verfolgung des GenoCheck-Fortschritts für den Nutzer
     * Verwendet das order_status-Attribut zur (De-)Aktivierung der Elemente
     * @return void
     */
    protected function generateGenoCheckProgress()
    {
        echo<<<HTML
            <section class="genoCheckStatus">
            <div class="progresssteps-container">
                <ul class="progresssteps quarter">
                    <li class="confirmed">Bestellung bestätigt</li>
                    <li class="sent">GenoCheck&trade; versandt</li>
                    <li class="analysis">Labor-Analyse läuft</li>
                    <li class="done">Analyse fertiggestellt</li>
                </ul>
            </div>

            <form action="phase2.php" method="get">
                <button type="submit" name="getGenoCheckResults" id="getGenoCheckResults" disabled>Zu Ihren Ergebnissen</button>
            </form>
        </section>
HTML;
    }

    /**
     * Prüft, ob empfangene POST-Parameter für die Erstellung eines neuen Nutzers valide sind
     * @return Boolean Ob die empfangenen POST-Parameter valide sind
     */
    protected function checkPostParameters() {
        // prüfe, ob alle Werte vorhanden
        $check = array("inputFirstName", "inputLastName", "inputStreet",
            "inputCity", "inputZipcode", "inputEmail");

        $valid = true;
        foreach ($check as $checkString) {
            if (!isset($_POST[$checkString])) {
                $valid = false;
                break;
            }
        }

        return $valid;
    }

    /**
     * Prüft, ob der Nutzer mit der angegebenen Email-Adresse in der Datenbank vorhanden ist
     * @param String $email Email des zu prüfenden Nutzers
     * @return Boolean Ob der User existiert
     */
    protected function checkUserExists($email) {
        // wenn Ergebnis leer -> User existiert nicht
        return !($this->getUserId($email) == null);
    }

    /**
     * Prüft, ob der Nutzer mit der angegebenen Email-Adresse einen GenoCheck-Eintrag in
     * der genocheckorder-Datenbank hat
     * @param  String $userid ID des zu prüfenden Nutzers
     * @return Boolean Ob der User eine GenoCheck-Bestellung besitzt
     */
    protected function checkUserHasGenoCheckOrder($userid) {
        $query = "SELECT checkid FROM genocheckorder WHERE userid='".$userid."'";
        $result = $this->_database->query($query);

        return !$result->fetch_assoc() == null;
    }

    /**
     * Gibt den Wert des userId-Attributs der user-Datenbank für den User mit der angegebenen Email-Adresse aus
     * @param  string $email Email des Nutzers
     * @return int userId des Nutzers mit der angegebenen Email
     */
    protected function getUserId($email) {
        $query = "SELECT userid FROM user WHERE email='".$email."'";
        $result = $this->_database->query($query);

        if ($row = $result->fetch_assoc()) {
            return $row[self::$KEY_USERID];
        } else {
            return null;
        }
    }

    /**
     * Gibt den Wert des status-Attributs der genocheckorder-Datenbank für den User mit der
     * angegebenen Email-Adresse aus
     * @param  String $userid ID des Nutzers
     * @return Int Bestellungsstatus (0=bestätigt, 1=gesendet, 2=im Labor, 3=fertig)
     */
    protected function getUserOrderStatus($userid) {
        $query = "SELECT status FROM genocheckorder WHERE userid='".$userid."'";
        $result = $this->_database->query($query);

        if ($row = $result->fetch_assoc()) {
            return $row["status"];
        } else {
            return null;
        }
    }

    /**
     * Erstellt Eintrag in der user-Datenbank mit den angegebenen Spalteninhalten
     * @param  String $email     Email des Nutzers
     * @param  String $firstname Vorname des Nutzers
     * @param  String $lastname  Nachname des Nutzers
     * @param  String $address1  Straße & Hausnummer des Nutzers
     * @param  String $address2  Stadt des Nutzers
     * @param  String $address3  PLZ des Nutzers
     * @return void
     */
    protected function createUser($email, $firstname, $lastname, $address1, $address2, $address3) {

        $query = $this->getMySQLInsertString(
            "user",
            array("email", "firstname", "lastname", "address1", "address2", "address3"),
            array($this->_database->real_escape_string($email),
                $this->_database->real_escape_string($firstname),
                $this->_database->real_escape_string($lastname),
                $this->_database->real_escape_string($address1),
                $this->_database->real_escape_string($address2),
                $this->_database->real_escape_string($address3))
        );
        $this->_database->query($query);
        if ($this->_database->errno != 0) {
            exit("Fehler beim Erstellen des Nutzers: ".$this->_database->error);
        }
        $this->new_user = true;
    }

    /**
     * Erstellt GenoCheckOrder für einen User
     * @param $userid User-ID in DB, für den GenoCheckOrder erstellt werden soll
     * @return void
     */
    protected function createGenoCheckOrder($userid) {
        $query = $this->getMySQLInsertString(
            "genocheckorder",
            array(self::$KEY_USERID),
            array($userid)
        );
        $this->_database->query($query);
        if ($this->_database->errno != 0) {
            exit("Fehler beim Erstellen der GenoCheck-Bestellung: ".$this->_database->error);
        }
    }

    /**
     * First the necessary data is fetched and then the HTML is
     * assembled for output. i.e. the header is generated, the content
     * of the page ("view") is inserted and -if avaialable- the content of
     * all views contained is generated.
     * Finally the footer is added.
     * @return void
     */
    protected function generateView()
    {
        $this->getViewData();
        $this->generatePageHeader('GenoChoice&trade; - GenoCheck&trade; Fortschritt');
        $this->generateNavigationBarUser(1);
        $this->generatePageTitle();

        if ($this->new_user) {
            $this->generateNewUser();
        }
        $this->generatePageDescription();
        $this->generateCurrentUser(false);
        $this->generateGenoCheckProgress();

        $this->generatePageFooter("phase1.js");
    }

    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If this page is supposed to do something with submitted
     * data do it here.
     * If the page contains blocks, delegate processing of the
     * respective subsets of data to them.
     *
     * Führt Parameter-Prüfungen durch, erstellt Nutzer und einen passenden GenoCheck-Auftrag
     * @throws Exception Fehler, falls magic quotes an sind
     * @return void
     */
    protected function processReceivedData()
    {
        parent::processReceivedData();
        if ($_SERVER["REQUEST_METHOD"]=="POST") {
            // prüfe POST Parameter
            if (!$this->checkPostParameters()) {
                // redirect zu phase2.php
                header('Location: phase0.php');
                exit();
            }

            // weise Variablen zu
            $email = $_POST["inputEmail"];
            $firstname = $_POST["inputFirstName"];
            $lastname = $_POST["inputLastName"];
            $address1 = $_POST["inputStreet"];
            $address2 = $_POST["inputCity"];
            $address3 = $_POST["inputZipcode"];

            // prüfe, ob Nutzer bereits existiert
            if (!$this->checkUserExists($email)) {
                // User mit dieser Email noch nicht vorhanden
                $this->createUser($email, $firstname, $lastname, $address1, $address2, $address3);
            }

            $userid = $this->getUserId($email);

            $_SESSION[self::$KEY_USERID] = $userid;

            if ($this->new_user && !$this->checkUserHasGenoCheckOrder($userid)) {
                // User ist neu und hat noch kein GenoCheck bestellt
                $this->createGenoCheckOrder($userid);
            }

            $_SESSION["phase"] = 1;
            // Lädt die Seite nach setzen der Parameter neu, um POST-Popup bei Neuladen der Seite zu verhindern
            header('Location: phase1.php');
        }
    }

    /**
     * This main-function has the only purpose to create an instance
     * of the class and to get all the things going.
     * I.e. the operations of the class are called to produce
     * the output of the HTML-file.
     * The name "main" is no keyword for php. It is just used to
     * indicate that function as the central starting point.
     * To make it simpler this is a static function. That is you can simply
     * call it without first creating an instance of the class.
     * @return void
     */
    public static function main()
    {
        session_start();
        try {
            $page = new Phase1();
            $page->processReceivedData();
            $page->checkSessionPhase(1);
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

// This call is starting the creation of the page.
// That is input is processed and output is created.
Phase1::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").
//? >

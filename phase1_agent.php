<?php // UTF-8 marker äöüÄÖÜß€
/**
 * @category File
 * @package  Bestelldienst
 * @author   Noah Kottenhahn, <noah.kottenhahn@stud.h-da.de>
 * @author   Max Klosterhalfen, <max.klosterhalfen@stud.h-da.de>
 * @license  http://www.h-da.de  none
 */

require_once './Page.php';
require_once './User.php';

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
class Phase1Agent extends Page
{
    /**
     * @var array Liste der verfügbaren Nutzer in der DB
     */
    private $users = [];

    /**
     * Fetch all data that is necessary for later output.
     * Data is stored in an easily accessible way e.g. as associative array.
     *
     * Ruft die verfügbaren Nutzer aus der DB ab und speichert sie in &$this->users
     *
     * @return void
     */
    protected function getViewData()
    {
        // Dropdown -> User-Auswahl
        $query = "SELECT userid, firstname, lastname, email FROM user";
        $result = $this->_database->query($query);

        while ($row = $result->fetch_assoc()) {
            $user = new User($row["userid"], $row["firstname"], $row["lastname"], $row["email"]);
            array_push($this->users, $user);
        }
    }

    /**
     * Prüft, ob empfangene POST-Parameter für die Erstellung eines neuen Nutzers valide sind
     * @return Boolean Ob die empfangenen POST-Parameter valide sind
     */
    protected function checkPostParameters()
    {
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
    protected function checkUserExists($email)
    {
        // wenn Ergebnis leer -> User existiert nicht
        return !($this->getUserId($email) == null);
    }

    /**
     * Gibt den Wert des userId-Attributs der user-Datenbank für den User mit der angegebenen Email-Adresse aus
     * @param string $email Email des Nutzers
     * @return int userId des Nutzers mit der angegebenen Email
     */
    protected function getUserId($email)
    {
        $query = "SELECT userid FROM user WHERE email='" . $email . "'";
        $result = $this->_database->query($query);

        if ($row = $result->fetch_assoc()) {
            return $row["userid"];
        } else {
            return null;
        }
    }

    /**
     * Generiere Dropwdown-Liste der User und RadioButtons zur Auswahl des Status
     * @return void
     */
    protected function generateAgentMenu()
    {
        echo <<<HTML
        <section>
          <span class="sectionHeader"><i class="material-icons md-24">notifications_active</i> Offene Bestellungen</span>

          <form action="phase1_agent.php" name="statusOrderChange[]" id="statusOrderChange" method="post">
            <div class="dropdownWrapper">
              <select class="dropdown" name="genoChoiceOrdersSelect" id="genoChoiceOrdersSelect">
HTML;
        // verfügbare Bestellungen in <select> einfügen
        foreach ($this->users as $user) {
            echo '<option value="' . htmlspecialchars($user->getUserId()) . '">' . htmlspecialchars($user->toString()) . '</option>';
        }
        // <option value="443">443 - Max Musterhalfen</option>
        echo<<<HTML
                  </select>
                </div>
            <div id="phase1_radioWrapper">
                <div class="inputRadioGroup">
                    <input type="radio" name="statusOrder" id="statusOrderConfirmed" value="0">
                    <label for="statusOrderConfirmed">Bestellung bestätigt</label>
                </div>
                <div class="inputRadioGroup">
                    <input type="radio" name="statusOrder" id="statusSent" value="1">
                    <label for="statusSent">GenoCheck&trade; versandt</label>
                </div>
                <div class="inputRadioGroup">
                    <input type="radio" name="statusOrder" id="statusAnalysis" value="2">
                    <label for="statusAnalysis">Labor-Analyse läuft</label>
                </div>
                <div class="inputRadioGroup">
                    <input type="radio" name="statusOrder" id="statusDone" value="3">
                    <label for="statusDone">Analyse fertiggestellt</label>
                </div>
            </div>
          </form>
        </section>
HTML;
    }

    /**
     * First the necessary data is fetched and then the HTML is
     * assembled for output. i.e. the header is generated, the content
     * of the page ("view") is inserted and -if avaialable- the content of
     * all views contained is generated.
     * Finally the footer is added.
     *
     * @return void
     */
    protected function generateView()
    {
        $this->getViewData();
        $this->generatePageHeader('GenoChoice&trade; - GenoCheck&trade; Agent');
        $this->generateNavigationBarAgent(0);
        $this->generatePageTitle();
        $this->generateCurrentUser(true);
        $this->generateAgentMenu();

        $this->generatePageFooter("phase1_agent.js");
    }

    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If this page is supposed to do something with submitted
     * data do it here.
     * If the page contains blocks, delegate processing of the
     * respective subsets of data to them.
     *
     * Führt Parameter-Prüfungen durch, erstellt Nutzer und einen passenden GenoCheck-Auftrag
     *
     * @return void
     */
    protected function processReceivedData()
    {
        $ordersSelectKey = "genoChoiceOrdersSelect";

        try {
            parent::processReceivedData();
        } catch (Exception $e) {
            echo "Fehler bei der Verarbeitung der Daten: " . $e;
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Wende Änderungen an
            $userid = $_POST[$ordersSelectKey];
            $new_status = $_POST["statusOrder"];
            $this->setStatusOrder($userid, $new_status);
            header('Location: phase1_agent.php');
        }
    }

    /**
     * @param $userid int ID des Users, dessen Status modifiziert werden soll
     * @param $status int Einzufügender Status (0 = bestätigt, 1 = verarbeitet usw.)
     * @return void
     */
    protected function setStatusOrder($userid, $status) {
        $query = "UPDATE genocheckorder SET status='" .$status. "' WHERE userid='" .$userid. "'";
        $this->_database->query($query);

        if ($this->_database->error != "") {
            echo $this->_database->error;
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
     *
     * @return void
     */
    public static function main()
    {
        session_start();
        try {
            $page = new Phase1Agent();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

// This call is starting the creation of the page.
// That is input is processed and output is created.
Phase1Agent::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").
//? >

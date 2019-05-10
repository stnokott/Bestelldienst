<?php // UTF-8 marker äöüÄÖÜß€
/**
 * @category File
 * @package  Bestelldienst
 * @author   Noah Kottenhahn, <noah.kottenhahn@stud.h-da.de>
 * @author   Max Klosterhalfen, <max.klosterhalfen@stud.h-da.de>
 * @license  http://www.h-da.de  none
 */

require_once './Page.php';

class User
{
    private $userid;
    private $firstname;
    private $lastname;
    private $email;

    public function __construct($userid, $firstname, $lastname, $email)
    {
        $this->userid = $userid;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
    }

    public function __destruct()
    {

    }

    public function getUserid()
    {
        return $this->userid;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function toString()
    {
        return $this->firstname . " " . $this->lastname . " - " . $this->email;
    }
}

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
    private $users = []; // Liste der verfügbaren Nutzer
    private $selected_userid; // userid des ausgewählten Nutzers, dessen Bestellstatus abgerufen werden soll
    private $selected_status; // status der Bestellung des ausgewählten Nutzers

    /**
     * Instantiates members (to be defined above).
     * Calls the constructor of the parent i.e. page class.
     * So the database connection is established.
     *
     * @return void
     */
    protected function __construct()
    {
        parent::__construct();
        // to do: instantiate members representing substructures/blocks
    }

    /**
     * Cleans up what ever is needed.
     * Calls the destructor of the parent i.e. page class.
     * So the database connection is closed.
     *
     * @return void
     */
    protected function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Fetch all data that is necessary for later output.
     * Data is stored in an easily accessible way e.g. as associative array.
     *
     * Speichert den Bestellstatus des aktuell angemeldeten Nutzers
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
     * Gibt den Wert des status-Attributs der genocheckorder-Datenbank für den User mit der
     * angegebenen Email-Adresse aus
     * @param String $userid id des Nutzers
     * @return Int Bestellungsstatus (0=bestätigt, 1=gesendet, 2=im Labor, 3=fertig)
     */
    protected function getUserOrderStatus($userid)
    {
        $query = "SELECT status FROM genocheckorder WHERE userid='" . $userid . "'";
        $result = $this->_database->query($query);

        if ($row = $result->fetch_assoc()) {
            return $row["status"];
        } else {
            return null;
        }
    }


    protected function generateCurrentAgent()
    {
        echo <<<HTML
        <div class="currentAgent">
          Bearbeiter: <div class="agentName">stnokott</div>
        </div>
HTML;
    }

    protected function generateAgentMenu()
    {
        echo <<<HTML
        <section>
          <span class="sectionHeader"><i class="material-icons md-24">notifications_active</i> Offene Bestellungen</span>

          <form action="phase1_agent.php" name="statusOrderChange[]" method="post">
            <div class="dropdownWrapper">
              <select class="dropdown" name="genoCheckOrdersSelect" id="genoCheckOrdersSelect">
HTML;
        // verfügbare Bestellungen in <select> einfügen
        foreach ($this->users as $user) {
            echo '<option value="' . htmlspecialchars($user->getUserId()) . '">' . htmlspecialchars($user->toString()) . '</option>';
        }
        // <option value="443">443 - Max Musterhalfen</option>
        echo<<<HTML
              </select>
            </div>
HTML;
        if ($this->selected_status == "0") {
            echo "<div class=\"inputRadioGroup active\">";
            echo "<input type=\"radio\" name=\"statusOrder\" id=\"statusOrderConfirmed\" value=\"0\" checked>";
        } else {
            echo "<div class=\"inputRadioGroup\">";
            echo "<input type=\"radio\" name=\"statusOrder\" id=\"statusOrderConfirmed\" value=\"0\">";
        }
        echo<<<HTML
              <label for="statusOrderConfirmed">Bestellung bestätigt</label>
            </div>

HTML;
        if ($this->selected_status == "1") {
            echo "<div class=\"inputRadioGroup active\">";
            echo "<input type=\"radio\" name=\"statusOrder\" id=\"statusSent\" value=\"1\" checked>";
        } else {
            echo "<div class=\"inputRadioGroup\">";
            echo "<input type=\"radio\" name=\"statusOrder\" id=\"statusSent\" value=\"1\">";
        }
        echo<<<HTML
              <label for="statusSent">GenoCheck&trade; versandt</label>
            </div>

HTML;
        if ($this->selected_status == "2") {
            echo "<div class=\"inputRadioGroup active\">";
            echo "<input type=\"radio\" name=\"statusOrder\" id=\"statusAnalysis\" value=\"2\" checked>";
        } else {
            echo "<div class=\"inputRadioGroup\">";
            echo "<input type=\"radio\" name=\"statusOrder\" id=\"statusAnalysis\" value=\"2\">";
        }
        echo<<<HTML
              <label for="statusAnalysis">Labor-Analyse läuft</label>
            </div>

HTML;
        if($this->selected_status == "3") {
            echo "<div class=\"inputRadioGroup active\">";
            echo "<input type=\"radio\" name=\"statusOrder\" id=\"statusDone\" value=\"3\" checked>";
        } else {
            echo "<div class=\"inputRadioGroup\">";
            echo "<input type=\"radio\" name=\"statusOrder\" id=\"statusDone\" value=\"3\">";
        }
        echo<<<HTML
              <label for="statusDone">Analyse fertiggestellt</label>
            </div>

            <button class="genoCheckSubmit" type="submit">Änderung bestätigen</button>
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
        $this->generatePageTitle();
        $this->generateCurrentAgent();
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
        try {
            parent::processReceivedData();
        } catch (Exception $e) {
            echo "Fehler bei der Verarbeitung der Daten: " . $e;
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

        } elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["genoCheckOrdersSelect"])) {
            $this->selected_userid = $_GET["genoCheckOrdersSelect"];
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

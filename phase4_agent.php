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
class Phase4Agent extends Page
{
    private $users = []; // Liste der verfügbaren Nutzer
    private $optionals = []; // Liste der gebuchten Optionals als optionaltypes

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
        // User für User-Auswahl Dropdown holen
        $query = "SELECT userid, firstname, lastname, email FROM user
                    WHERE userid IN (SELECT userid FROM genochoiceorder)";
        $result = $this->_database->query($query);

        while ($row = $result->fetch_assoc()) {
            $user = new User($row["userid"], $row["firstname"], $row["lastname"], $row["email"]);
            array_push($this->users, $user);
            $query_optionals = "SELECT optionaltype FROM orderoptionals
                                JOIN genochoiceorder ON orderoptionals.choiceid = genochoiceorder.choiceid
                                WHERE userid = '".$row["userid"]."'";
            $result_optionals = $this->_database->query($query_optionals);
            while ($row_optionals = $result_optionals->fetch_assoc()) {
                array_push($this->optionals, $row_optionals["optionaltype"]);
            }
        }
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

    protected function generateAgentMenu()
    {
        echo <<<HTML
        <section>
          <span class="sectionHeader"><i class="material-icons md-24">notifications_active</i> Offene Bestellungen</span>

          <form action="phase4_agent.php" name="statusOrderChange[]" id="statusOrderChange" method="post">
            <div class="dropdownWrapper">
              <select class="dropdown" name="genoChoiceOrdersSelect" id="genoChoiceOrdersSelect">
HTML;
        // verfügbare Bestellungen in <select> einfügen
        foreach ($this->users as $user) {
            echo '<option value="' . htmlspecialchars($user->getUserId()) . '">' . htmlspecialchars($user->toString()) . '</option>';
        }

        echo <<<HTML
                  </select>
                </div>
                <div id="phase4_radioWrapper1">
                    <div class="inputRadioGroup">
                      <input type="radio" name="statusOrder" id="statusOrderConfirmed" value="0">
                      <label for="statusOrderConfirmed">Bestellung bestätigt</label>
                    </div>
                    <div class="inputRadioGroup">
                      <input type="radio" name="statusOrder" id="statusExtraction" value="1">
                      <label for="statusExtraction">DNA-Extraktion aus GenoCheck&trade;</label>
                    </div>
                    <div class="inputRadioGroup">
                      <input type="radio" name="statusOrder" id="statusIncubation" value="2">
                      <label for="statusIncubation">Inkubationsbehälter füllen</label>
                    </div>
                </div>
                <div id="phase4_checkWrapper">
HTML;
        if (in_array(0, $this->optionals)) {
            echo <<< HTML
                    <div class="inputCheckGroup">
                      <input type="checkbox" name="statusOptionals[]" id="statusInsertion" value="0">
                      <label for="statusInsertion">Merkmalinsertion (CRISPR-cas9)</label>
                    </div>
HTML;
        }
        if (in_array(1, $this->optionals)) {
            echo <<< HTML
                    <div class="inputCheckGroup">
                      <input type="checkbox" name="statusOptionals[]" id="statusDisease" value="1">
                      <label for="statusDisease">Krankheitspotential reduzieren (Immunomodifikation)</label>
                    </div>
HTML;
        }
        if (in_array(2, $this->optionals)) {
            echo <<< HTML
                    <div class="inputCheckGroup">
                      <input type="checkbox" name="statusOptionals[]" id="statusSocial" value="2">
                      <label for="statusSocial">Soziale Bereiche verbessern (kombinierte Methoden)</label>
                    </div>
HTML;
        }
        echo <<< HTML
                </div>
                <div id="phase4_radioWrapper2">
                    <div class="inputRadioGroup">
                      <input type="radio" name="statusOrder" id="statusMeiosis" value="3">
                      <label for="statusMeiosis">(Künstliche) Meiose initiieren</label>
                    </div>
                    <div class="inputRadioGroup">
                      <input type="radio" name="statusOrder" id="statusEmbryo" value="4">
                      <label for="statusEmbryo">Embryonalstatus erreicht</label>
                    </div>
                    <div class="inputRadioGroup">
                      <input type="radio" name="statusOrder" id="statusCheck" value="5">
                      <label for="statusCheck">Phänotypen prüfen</label>
                    </div>
                    <div class="inputRadioGroup">
                      <input type="radio" name="statusOrder" id="statusDone" value="6">
                      <label for="statusDone"><strong>Produktion fertiggestellt</strong></label>
                    </div>
                </div>
                
                <button type="submit">Änderung bestätigen</button>
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
        $this->generatePageHeader('GenoChoice&trade; - Bearbeiter');
        $this->generatePageTitle();
        $this->generateCurrentUser(true);
        $this->generateAgentMenu();

        $this->generatePageFooter("phase4_agent.js");
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
            // Wende Status-Änderungen an
            $userid = $_POST[$ordersSelectKey];
            $new_status = $_POST["statusOrder"];
            $this->setStatusOrder($userid, $new_status);

            $activated_optionaltypes = array();

            foreach ($_POST["statusOptionals"] as $item) {
                array_push($activated_optionaltypes, $item);
            }
            $this->setStatusOptionals($userid, $activated_optionaltypes);

            // Wende Optionals-Änderungen an
            header('Location: phase4_agent.php');
        }
    }

    protected function setStatusOrder($userid, $status) {
        $query = "UPDATE genochoiceorder SET status='" .$status. "' WHERE userid='" .$userid. "'";
        $this->_database->query($query);

        if ($this->_database->error != "") {
            echo $this->_database->error;
        }
    }

    /**
     * @param $userid int UserId des Users, dessen Optionals verändert werden sollen
     * @param $activated_optionaltypes array Liste der aktivierten optionaltypes
     */
    protected function setStatusOptionals($userid, $activated_optionaltypes) {
        // TODO: SQL vereinfachen?
        foreach ($this->optionals as $optional_type) {
            $is_active = in_array($optional_type, $activated_optionaltypes);
            $query = "UPDATE orderoptionals SET done='".$is_active."'
                      WHERE orderoptionals.choiceid IN
                      (SELECT genochoiceorder.choiceid FROM genochoiceorder
                      WHERE genochoiceorder.userid ='".$userid."')
                      AND orderoptionals.optionaltype = '".$optional_type."'";
            $this->_database->query($query);

            if ($this->_database->error != "") {
                echo $this->_database->error;
            }
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
            $page = new Phase4Agent();
            $page->getViewData();
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
Phase4Agent::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").
//? >

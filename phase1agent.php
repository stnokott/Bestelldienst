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


 class User{
   private $firstname;
   private $lastname;
   private $userid;
   private $email;

   public funtion __construct()
   {
     $this->firstname = firstname;
     $this->lastname = lastname;
     $this->userid = userid;
     $this->email = email;
   }

   public funtion __destruct()
   {

   }

   public function getFirstName()
   {
     return $this->firstname;
   }

   public function getLastName()
   {
     return $this->lastname;
   }

   public function getemail()
   {
     return $this->email;
   }

   public function getuserid()
   {
     return $this->userid;
   }
 }


class Phase1agent extends Page
{
    private $users = [];
    private $order_status; // 0 = confirmed, 1 = sent, 2 = analysis, 3 = done

    /**
     * Instantiates members (to be defined above).
     * Calls the constructor of the parent i.e. page class.
     * So the database connection is established.
     *
     * @return none
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
     * @return none
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
     * @return none
     */
    protected function getViewData()
    {
      $query = "SELECT firstname, lastname, email FROM user";
      $result = $this->_database->query($query);
      while ($row = $result->fetch_assoc()){
        $user = new User($row["firstname"], $row["lastname"], $row["email"]);
        array_push($this->users, $user);
      }
    }

    protected function agentMenu(){
      echo <<< HTML
      <section>
        <span class="sectionHeader"><i class="material-icons md-24">notifications_active</i> Offene Bestellungen</span>
        <form action="https://enlx6f766q8jc.x.pipedream.net" name="statusOrderChange[]" method="post">
          <!-- "order443" könnte Key in Datenbank sein -->
          <div class="dropdownWrapper">
            <select class="dropdown" name="genoCheckOrdersSelect">
HTML;
        foreach ($users as $user) {
          echo '<option value="'.$user->getUserId().'">.$user->toString().'
        }
              <option value="order443">443 - Max Musterhalfen</option>
              <option value="order444">444 - Erika Musterfrau</option>
            </select>
          </div>

          <div class="inputRadioGroup">
            <input type="radio" name="statusOrder" id="statusOrderConfirmed">
            <label for="statusOrderConfirmed">Bestellung bestätigt</label>
          </div>

          <div class="inputRadioGroup">
            <input type="radio" name="statusOrder" id="statusSent">
            <label for="statusSent">GenoCheck&trade; versandt</label>
          </div>

          <div class="inputRadioGroup active">
            <input type="radio" name="statusOrder" id="statusAnalysis" checked>
            <label for="statusAnalysis">Labor-Analyse läuft</label>
          </div>
  
          <div class="inputRadioGroup">
            <input type="radio" name="statusOrder" id="statusDone">
            <label for="statusDone">Analyse fertiggestellt</label>
          </div>

          <button class="genoCheckSubmit" type="submit">Änderung bestätigen</button>
        </form>
      </section>
HTML;
    }

    protected function generateCurrentAgent(){
echo <<< HTML
      <div class="currentAgent">
        Bearbeiter: <div class="agentName">stnokott</div>
      </div>
HTML;
    }

    /**
     * Generiert Ansicht zur Verfolgung des GenoCheck-Fortschritts für den Nutzer
     * Verwendet das order_status-Attribut zur (De-)Aktivierung der Elemente
     * @return none
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
     * Gibt den Wert des userId-Attributs der user-Datenbank für den User mit der angegebenen Email-Adresse aus
     * @param  string $email Email des Nutzers
     * @return int userId des Nutzers mit der angegebenen Email
     */
    protected function getUserId($email) {
        $query = "SELECT userid FROM user WHERE email='".$email."'";
        $result = $this->_database->query($query);

        while ($row = $result->fetch_assoc()) {
            return $row["userid"];
        }
        return null;
    }

    /**
     * Gibt den Wert des status-Attributs der genocheckorder-Datenbank für den User mit der
     * angegebenen Email-Adresse aus
     * @param  String $email Email des Nutzers
     * @return Int Bestellungsstatus (0=bestätigt, 1=gesendet, 2=im Labor, 3=fertig)
     */
    protected function getUserOrderStatus($email) {
        $query = "SELECT status FROM genocheckorder WHERE userId='".$this->getUserId($email)."'";
        $result = $this->_database->query($query);

        while ($row = $result->fetch_assoc()) {
            return $row["status"];
        }
        return null;
    }

    /**
     * Erstellt Eintrag in der user-Datenbank mit den angegebenen Spalteninhalten
     * @param  String $email     Email des Nutzers
     * @param  String $firstname Vorname des Nutzers
     * @param  String $lastname  Nachname des Nutzers
     * @param  String $address1  Straße & Hausnummer des Nutzers
     * @param  String $address2  Stadt des Nutzers
     * @param  String $address3  PLZ des Nutzers
     * @return none
     */

    protected function generateView()
    {
        $this->getViewData();
        $this->generatePageHeader("GenoChoice&trade; GenoCheck&trade; Agent");
        $this->generatePageTitle();
        $this->generatePageFooter();
        $this->generateCurrentAgent();
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
     * @return none
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
            if ($this->checkUserExists($email) == false) {
                // User mit dieser Email noch nicht vorhanden
                $this->createUser($email, $firstname, $lastname, $address1, $address2, $address3);
            }

            if ($this->new_user == true && $this->checkUserHasGenoCheckOrder($email) == false) {
                // User ist neu und hat noch kein GenoCheck bestellt
                $this->createGenoCheckOrder($email);
            }
            header('Location: phase1.php');
            return;
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
     * @return none
     */
    public static function main()
    {
        try {
            $page = new Phase1agent();
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
Phase1agent::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").
//? >

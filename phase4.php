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

class Phase4 extends Page
{
    /**
     * Instantiates members (to be defined above).
     * Calls the constructor of the parent i.e. page class.
     * So the database connection is established.
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
     */
    protected function __destruct()
    {
        parent::__destruct();
    }

/*
vgl post query usererstellung
Post Argumente Validieren
Genochoiceordereintrag erstellen mit userid + post Info
  Post-Info:
    kittype=0/1/2/3/4
    optionals=[0/1/2, 0/1/2, ...]
Für jede optional eintrag in die orderoptionalsdatenbank
*/

protected function createGenoChoiceOrder($userid, $kittype) {
    $query = $this->getMySQLInsertString(
        "genochoiceorder",
        array("userid","kittype"),
        array($this->_database->real_escape_string($userid),
              $this->_database->real_escape_string($kittype))
    );
    $this->_database->query($query);
    if ($this->_database->errno != 0) {
        exit("Fehler beim Erstellen der GenoChoice-Bestellung: ".$this->_database->error);
    }
}

protected function createOrderOptional($optionaltype, $choiceid){
  $query = $this->getMySQLInsertString(
      "orderoptionals",
      array("optionaltype","choiceid"),
      array($this->_database->real_escape_string($optionaltype),
            $this->_database->real_escape_string($choiceid))
  );
  $this->_database->query($query);
  if ($this->_database->errno != 0) {
      exit("Fehler beim Erstellen von Bestelloptionen: ".$this->_database->error);
  }
}

protected function checkPostParameters() {
    // prüfe, ob alle Werte vorhanden
    $check = array("kittype", "orderoptionals");
    $valid = true;

    foreach ($check as $checkString) {
        if (!isset($_POST[$checkString])) {
            $valid = false;
            break;
        }
    }

    return $valid;
}

protected function checkUserHasGenoChoiceOrder($userid) {
    $query = "SELECT choiceid FROM genochoiceorder WHERE userid='".$userid."'";
    $result = $this->_database->query($query);

    return !$result->fetch_assoc() == null;
}

protected function getGenoChoiceId($userid) {
    $query = "SELECT choiceid FROM genochoiceorder WHERE userid='".$userid."'";
    $result = $this->_database->query($query);

    if($row = $result->fetch_assoc()){
      return $row["choiceid"];
    }

    return NULL;
}

protected function processReceivedData()
{
    parent::processReceivedData();
    if ($_SERVER["REQUEST_METHOD"]=="POST") {
        // prüfe POST Parameter
        if (!$this->checkPostParameters()) {
            // redirect zu phase3.php
            header('Location: phase3.php');
            exit();
        }

        // weise Variablen zu
        $userid = $_SESSION["userid"];
        $kittype = $_POST["kittype"];

        //Erstellen der Order falls noch keine Bestellung vorhanden
        if (NULL == $this->checkUserHasGenoChoiceOrder($userid)) {
            // User ist neu und hat noch kein GenoCheck bestellt
            $this->createGenoChoiceOrder($userid, $kittype);
        }

        $choiceid = $this->getGenoChoiceId($userid);

        //Bestelloptionen hinzufügen
        for($i=0; $i<sizeof($_POST["selectedoptionals"]); $i++){
            $optionaltype = $_POST["selectedoptionals"][$i];
            $this->createOrderOptional($choiceid, $optionaltype);
       }

        // Lädt die Seite nach setzen der Parameter neu, um POST-Popup bei Neuladen der Seite zu verhindern
        $_SESSION["phase"] = 4;
        header('Location: phase4.php');
    }
}

public static function main()
{
    try {
        $page = new Phase4();
        $page->processReceivedData();

    } catch (Exception $e) {
        header("Content-type: text/plain; charset=UTF-8");
        echo $e->getMessage();
    }
}
}


// This call is starting the creation of the page.
// That is input is processed and output is created.
Phase4::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").
//? >

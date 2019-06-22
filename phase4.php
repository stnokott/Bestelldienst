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

    static $KEY_KITTYPE = "kittype";
    static $SELECTEDOPTIONALS_KEY = "selectedoptionals";
    private $optionals = []; // Liste der gebuchten Optionals als optionaltypes
    private $kitname = "Unbekannt";
    private $kitclass = "";

    /*
    vgl post query usererstellung
    Post Argumente Validieren
    Genochoiceordereintrag erstellen mit userid + post Info
      Post-Info:
        kittype=0/1/2/3/4
        optionals=[0/1/2, 0/1/2, ...]
    Für jede optional eintrag in die orderoptionalsdatenbank
    */

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
        if(!isset($_SESSION["userid"])){
            header('Location: phase3.php');
        }

        $query = "SELECT name, cssclass FROM kit
                    JOIN genochoiceorder ON userid = '".$_SESSION["userid"]."' 
                    WHERE kittype = kitid";
        $result = $this->_database->query($query);
        if ($row = $result->fetch_assoc()) {
            $this->kitname = $row["name"];
            $this->kitclass = $row["cssclass"];
        }

        // Gebuchte Optionals aus Datenbank holen
        // Verhindert, dass Optionals angezeigt werden, die nicht gebucht wurden
        $query = "SELECT optionaltype FROM orderoptionals
                            JOIN genochoiceorder ON orderoptionals.choiceid = genochoiceorder.choiceid
                            WHERE userid = '".$_SESSION["userid"]."'";
        $result = $this->_database->query($query);
        while ($row_optionals = $result->fetch_assoc()) {
            array_push($this->optionals, $row_optionals["optionaltype"]);
        }
    }

    /**
     * Generiert erste <section>, die den Inhalt dieser Seite beschreibt
     */
    protected function generatePageDescription()
    {
        echo<<<HTML
        <section>
          <div class="sectionHeader">Ihre GenoCheck&trade;-Ergebnisse</div>
          <p>
            Diese Übersicht zeigt den Fortschritt Ihrer persönlichen GenoChoice&trade;-Bestellung.<br>
            Wir freuen uns, Sie bald mit ihrem neuen Familienmitglied zusammenbringen zu können.
          </p>
        </section>
HTML;
    }

    private function generateChosenKit() {
        echo '<div class="genoChoiceChosenKit">';
        echo 'Gewähltes Kit: <span id="chosenKitName" class="'.$this->kitclass.'">'.$this->kitname.'</span>';
        echo '</div><br>';
    }

    /**
     * Generiert Ansicht zur Verfolgung des GenoChoice-Fortschritts für den Nutzer
     * Verwendet das order_status-Attribut zur (De-)Aktivierung der Elemente
     */
    protected function generateGenoChoiceProgress()
    {
        echo '<section class="genoChoiceStatus">';
        $this->generateChosenKit();
        echo<<<HTML
            <div class="sectionHeader"><div class="sectionHeaderNumber">1</div>Vorbereitung</div>
            <div class="progresssteps-container">
                <ul class="progresssteps third statuskit">
                    <li class="confirmed">Bestellung bestätigt</li>
                    <li class="extraction">DNA-Extraktion aus GenoCheck&trade;</li>
                    <li class="incubate">Inkubationsbehälter füllen</li>
                </ul>
            </div>
HTML;
        if (sizeof($this->optionals) > 0) {
            switch (sizeof($this->optionals)) {
                case 1:
                    $progressstepsclass = "full";
                    break;
                case 2:
                    $progressstepsclass = "half";
                    break;
                case 3:
                    $progressstepsclass = "third";
                    break;
                default:
                    die("Unmögliche Optionals-Größe festgestellt");
            }

            echo<<<HTML
                <div class="sectionHeader"><div class="sectionHeaderNumber">2</div>Optionale Schritte</div>
                <div class="progresssteps-container">
                    <ul class="progresssteps {$progressstepsclass} statusoptionals">
HTML;

            if (in_array(0, $this->optionals)) {
                echo '<li class="insertion">Merkmalinsertion (CRISPR-cas9)</li>';
            }

            if (in_array(1, $this->optionals)) {
                echo '<li class="sickness">Krankheitspotential reduzieren</li>';
            }

            if (in_array(2, $this->optionals)) {
                echo '<li class="social">Soziale Bereiche verbessern</li>';
            }

            echo<<<HTML
                </ul>
            </div>
HTML;
        }
        $nextheadernum = sizeof($this->optionals) == 0 ? 2 : 3; // Header-Nummer muss 2 sein, falls keine Optionals angezeigt werden
        echo<<<HTML
            <div class="sectionHeader"><div class="sectionHeaderNumber">{$nextheadernum}</div>Produktion</div>
            <div class="progresssteps-container">
                <ul class="progresssteps quarter statuskit">
                    <li class="meiosis">(Künstliche) Meiose initiieren</li>
                    <li class="embryo">Embryonalstatus erreicht</li>
                    <li class="analysis">Phänotypen prüfen</li>
                    <li class="choiceready">Produktion fertiggestellt</li>
                </ul>
            </div>

            <form action="" method="get">
                <button type="submit" name="getGenoChoiceResults" id="getGenoChoiceResults" disabled>Produkt abholen</button>
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
     */
    protected function generateView()
    {
        $this->getViewData();
        $this->generatePageHeader('GenoChoice&trade; - Ihr GenoChoice&trade; Fortschritt');
        $this->generateNavigationBarUser(4);
        $this->generatePageTitle();
        $this->generatePageDescription();

        $this->generateGenoChoiceProgress();

        $this->generatePageFooter("phase4.js");
    }

/**####################DATA ACQUISITION#############################*/

    /**
     * GenoChoice-Order für User erstellen
     * @param $userid int User, für den die Bestellung erstellt werden soll
     * @param $kittype int Vom User gewählte Kit-ID
     */
protected function createGenoChoiceOrder($userid, $kittype) {
    $query = $this->getMySQLInsertString(
        "genochoiceorder",
        array("userid",self::$KEY_KITTYPE),
        array($this->_database->real_escape_string($userid),
              $this->_database->real_escape_string($kittype))
    );

    $this->_database->query($query);
    if ($this->_database->errno != 0) {
        exit("Fehler beim Erstellen der GenoChoice-Bestellung: ".$this->_database->error);
    }
}

protected function createOrderOptional($choiceid, $optionaltype){
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
    $check = array(self::$KEY_KITTYPE, self::$SELECTEDOPTIONALS_KEY);
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
        $kittype = $_POST[self::$KEY_KITTYPE];

        //Erstellen der Order falls noch keine Bestellung vorhanden
        if (!$this->checkUserHasGenoChoiceOrder($userid)) {
            // User ist neu und hat noch kein GenoCheck bestellt
            $this->createGenoChoiceOrder($userid, $kittype);
        }

        $choiceid = $this->getGenoChoiceId($userid);

        //Bestelloptionen hinzufügen
        for($i=0; $i<sizeof($_POST[self::$SELECTEDOPTIONALS_KEY]); $i++){
            $optionaltype = $_POST[self::$SELECTEDOPTIONALS_KEY][$i];
            $this->createOrderOptional($choiceid, $optionaltype);
       }

        // Lädt die Seite nach setzen der Parameter neu, um POST-Popup bei Neuladen der Seite zu verhindern
        $_SESSION["phase"] = 4;
        header('Location: phase4.php');
    }
}

public static function main()
{
    session_start();
    try {
        $page = new Phase4();
        $page->processReceivedData();
        $page->checkSessionPhase(4);
        $page->generateView();
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

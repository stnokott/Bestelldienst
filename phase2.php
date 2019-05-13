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

class Phase2 extends Page
{
    /**
     * Instantiates members (to be defined above).
     * Calls the constructor of the parent i.e. page class.
     * So the database connection is established.
     *
     * @return null
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
     * @return null
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
     * @return null
     */
    protected function getViewData()
    {

    }

    /**
     * Generiert Navigationsleiste.
     * Setzt "active"-class je nachdem, welche Seite aktiv ist (diese Seite)
     * @return null
     */
    protected function generateNavigationBar()
    {
        echo <<<HTML
        <ul class="navlist">
            <li><a href="#">Phase 1</a></li>
            <li><a href="#">Phase 1-2</a></li>
            <li class="active"><a href="#">Phase 3</a></li>
        </ul>
HTML;
    }

    /**
     * Generiert erste <section>, die den Inhalt dieser Seite beschreibt
     * @return null
     */
    protected function generatePageDescription()
    {
        echo<<<HTML
        <section>
          <span class="sectionHeader">Ihre GenoCheck&trade;-Ergebnisse</span>
          <p>
            Diese Übersicht zeigt die Ergebnisse der labortechnischen Untersuchung Ihres persönlichen GenoCheck&trade;-Tests.<br>
            Hier werden Ihnen die möglichen Genotypen eines auf Ihren Genen basierendem Babys angezeigt.
          </p>
        </section>
HTML;
    }

    protected function generateGenoTypes() {
        echo<<<HTML
        <section class="genoCheckResultsGenotypes">
             <span class="sectionHeader">Genotypen</span>
        
             <h2>Augen</h2>
             <ul class="list">
                 <li class="green">Grün</li>
                 <li class="blue">Blau</li>
                 <li class="grey disabled">Grau</li>
                 <li class="brown disabled">Braun</li>
             </ul>
        
             <h2>Haarfarbe</h2>
             <ul class="list">
                <li class="blonde">Blond</li>
                <li class="brown">Braun</li>
                <li class="black">Schwarz</li>
                <li class="red disabled">Rot</li>
             </ul>
        </section>
HTML;

    }

    protected function generateRisks() {
        echo<<<HTML
        <section class="genoCheckResultsRisks">
          <span class="sectionHeader">Risiken</span>
          <p>
            Unser patentiertes GenoCheck&trade;-Verfahren ermöglicht eine
            Risikobeurteilung zu diversen Erkrankungen in der Lebensspanne Ihres Babys.<br>
            Für nicht erblich bedingte Krankheiten wird das potentielle Verhalten Ihres Kindes errechnet
            und daraus ein Risikowert berechnet.
          </p>
    
          <h3>Lungenkrebs</h3>
          <div class="disease">
            <meter value="45" min="0" max="100" low="15" optimum="5" high="60"></meter>
            <label for="meterLungcancer">45% - bedenklich</label>
          </div>
          <h3>Alkoholismus</h3>
          <div class="disease">
            <meter value="10" min="0" max="100" low="15" optimum="5" high="60"></meter>
            <label for="meterLungcancer">10% - unbedenklich</label>
          </div>
          <h3>Exzessiver Drogenkonsum</h3>
          <div class="disease">
            <meter value="3" min="0" max="100" low="15" optimum="5" high="60"></meter>
            <label for="meterLungcancer">3% - unbedenklich</label>
          </div>
          <h3>Ametropie (Fehlsichtigkeit)</h3>
          <div class="disease">
            <meter value="80" min="0" max="100" low="15" optimum="5" high="60"></meter>
            <label for="meterLungcancer">80% - kritisch</label>
          </div>
        </section>
HTML;

    }

    protected function generateContinueButton() {
        echo<<<HTML
        <section>
          <form action="phase3.html">
            <button type="submit">Weiter zur Paketbuchung</button>
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
     * @return null
     */
    protected function generateView()
    {
        $this->getViewData();
        $this->generatePageHeader('GenoChoice&trade; - Ihre GenoCheck&trade;-Ergebnisse');
        $this->generateNavigationBar();
        $this->generatePageTitle();
        $this->generatePageDescription();
        $this->generateGenoTypes();
        $this->generateRisks();
        $this->generateContinueButton();

        $this->generatePageFooter(null);
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
     * @return null
     * @throws Exception Fehler, falls magic quotes an sind
     */
    protected function processReceivedData()
    {
        parent::processReceivedData();

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
     * @return null
     */
    public static function main()
    {
        try {
            $page = new Phase2();
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
Phase2::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").
//? >
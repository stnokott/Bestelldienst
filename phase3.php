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

class Phase3 extends Page
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

    /**
     * Fetch all data that is necessary for later output.
     * Data is stored in an easily accessible way e.g. as associative array.
     *
     * Speichert den Bestellstatus des aktuell angemeldeten Nutzers
     */
    protected function getViewData()
    {

    }

    /**
     * Generiert Navigationsleiste.
     * Setzt "active"-class je nachdem, welche Seite aktiv ist (diese Seite)
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
     */
    protected function generatePageDescription()
    {
        echo<<<HTML
        <section>
          <span class="sectionHeader">Wählen Sie Ihr GenoChoice&trade;-Kit</span>
          <p>
            In unserem breiten Angebot finden Sie bestimmt die richtige Wahl für Ihr
            zukünftiges Ich.
            <!-- TODO: mehr Text hinzufügen -->
          </p>
        </section>
HTML;
    }

    protected function generateAvailableKits() {
        echo<<<HTML
    <section>
      <span class="sectionHeader">Verfügbare Kits</span>

      <form action="phase4.html" name="selectedKit" method="post">
        <div class="kit kitBasic">
          <div class="kitHeader">
            <span class="kitTitle">Basic Kit</span><br>
            <span class="kitSubtitle">Für den kleinen Geldbeutel</span>
            <span class="kitPrice">€ 5999.99</span>
          </div>
          <ul>
            <li>Attribute werden naturgegeben zufällig aus dem Genpool bestimmt</li>
            <li>Kein Einfluss auf negative Attribute Ihres Kindes</li>
          </ul>
          <button type="submit" id="btnBasic">In den Warenkorb</button>
        </div>

        <div class="kit kitComfort">
          <div class="kitRibbon recommended">Empfohlen <i class="material-icons">done</i></div>
          <div class="kitHeader">
            <span class="kitTitle">Comfort Kit</span><br>
            <span class="kitSubtitle">Für eine erhöhte Lebenserwartung</span>
            <span class="kitPrice">€ 7999.99</span>
          </div>
          <ul>
            <li>Auswahl bestimmter visueller Attribute, auch außerhalb des elterlich verfügbaren Genpool, möglich</li>
            <li>Erheblich reduzierte Krankheitswahrscheinlichkeiten</li>
          </ul>
          <div class="kitAdvice recommended">
            <span class="kitAdviceHeader recommended">EMPFOHLEN</span>
            Ihre <strong>GenoCheck&trade;-Analyse</strong> ergab eine <strong>kritische Ametropie-Wahrscheinlichkeit</strong> von
            <strong>80%</strong>.<br>
            Mit diesem Kit kann diese auf <strong>5%</strong> gesenkt werden.
          </div>
          <button type="submit" id="btnComfort">In den Warenkorb</button>
        </div>

        <div class="kit kitSocial">
          <div class="kitHeader">
            <span class="kitTitle">Social Kit</span><br>
            <span class="kitSubtitle">Beliebtheit garantiert</span>
            <span class="kitPrice">€ 8499.99</span>
          </div>
          <ul>
            <li>Auswahl bestimmter visueller Attribute möglich (auch außerhalb des elterlichen Genpools)</li>
            <li>Erhöhte Werte in sozial anerkannten Bereichen wie <strong>Intelligenz, Körpergröße, Sportlichkeit</strong></li>
          </ul>
          <button type="submit" id="btnSocial">In den Warenkorb</button>
        </div>

        <div class="kit kitPremium">
          <div class="kitRibbon popular">Beliebt <i class="material-icons">favorite</i></div>
          <div class="kitHeader">
            <span class="kitTitle">Premium Kit</span><br>
            <span class="kitSubtitle">Das Beste von GenoChoice&trade;</span>
            <span class="kitPrice">€ 14499.99</span>
          </div>
          <ul>
            <li>Auswahl bestimmter visueller Attribute möglich (auch außerhalb des elterlichen Genpools)</li>
            <li>inkl. Comfort Kit</li>
            <li>inkl. Social Kit</li>
          </ul>
          <div class="kitAdvice popular">
            <span class="kitAdviceHeader popular">Beliebt</span>
            Am meisten gewählt
          </div>
          <button type="submit" id="btnPremium">In den Warenkorb</button>
        </div>

        <div class="kit kitCustom">
          <div class="kitRibbon flexible">Flexibilität <i class="material-icons">call_split</i></div>
          <div class="kitHeader">
            <span class="kitTitle">Custom Kit</span><br>
            <span class="kitSubtitle">Erstellen Sie Ihr Traum-Baby</span>
            <span class="kitPrice">€ 24999.99</span>
          </div>
          <ul>
            <li><strong>Optische Attribute</strong> frei wählbar</li>
            <li><strong>Charakterwerte</strong> frei wählbar</li>
            <li>Krankheiten eliminiert</li>
          </ul>
          <div class="kitAdvice flexible">
            <span class="kitAdviceHeader flexible">Flexibilität</span>
            Anpassung in allen Bereichen möglich
          </div>
          <button type="submit" id="btnCustom">In den Warenkorb</button>
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
     */
    protected function generateView()
    {
        $this->getViewData();
        $this->generatePageHeader('GenoChoice&trade; - Wählen Sie Ihr GenoChoice&trade;-Kit');
        $this->generateNavigationBar();
        $this->generatePageTitle();
        $this->generatePageDescription();
        $this->generateAvailableKits();

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
     */
    public static function main()
    {
        try {
            $page = new Phase3();
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
Phase3::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").
//? >

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
     * @return none
     */
    protected function getViewData()
    {

    }

    /**
     * Generiert Navigationsleiste.
     * Setzt "active"-class je nachdem, welche Seite aktiv ist (diese Seite)
     *
     * @return none
     */
    protected function generateNavigationBar() {
echo <<<HTML
        <div>
            <ul class="navlist">
                <li class="active"><a href="#">Phase 1</a></li>
                <li><a href="#">Phase 1-2</a></li>
                <li><a href="#">Phase 3</a></li>
            </ul>
        </div>
HTML;
    }

    /**
     * Generiert erste <section>, die den Inhalt dieser Seite beschreibt
     *
     * @return none
     */
    protected function generatePageDescription() {
echo<<<HTML
        <section>
            <span class="sectionHeader">GenoCheck&trade; bestellen</span>
            <p>
                Fordern Sie heute ihren <strong>kostenlosen</strong> GenoChoice&trade;-Gentest an.<br> Ein Team
                aus professionellen Genforschern prüft mit unserem patentierten GenoCheck&trade;-Verfahren die
                Stärken und Schwächen ihres zukünftigen Kindes.
            </p>
            <figure>
                <img src="img/family.jpg" alt="Diese fröhliche Familie könnten Sie sein!">
                <figcaption>Malte & Sombra Trontheim sind zufrieden mit ihrer GenoChoice&trade;-Entscheidung</figcaption>
            </figure>
        </section>
HTML;
    }

    /**
     * Generiert <form> zur Eingabe der Daten, die für die GenoCheck-Bestellung nötig sind
     *
     * @return none
     */
    protected function generateGenoCheckForm() {
echo<<<HTML
    <section>
        <span class="sectionHeader">Persönliche Daten</span>
        <form id="genoCheckForm" name="genoCheckForm[]" action="phase1tophase2.php" method="post">
            <label>Vorname</label>
            <input type="text" id="inputFirstName" name="inputFirstName" value="Max" required autofocus>

            <label>Name</label>
            <input type="text" id="inputLastName" name="inputLastName" value="Musterhalfen" required>

            <label>Straße & Hausnummer</label>
            <input type="text" id="inputStreet" name="inputStreet" value="Musterstraße 1" required>

            <label>Stadt</label>
            <input type="text" id="inputCity" name="inputCity" value="Musterstadt" required>

            <label>PLZ</label>
            <input type="text" id="inputZipcode" name="inputZipcode" pattern="\d{5}" value="12345" required>

            <label>E-Mail</label>
            <input type="email" id="inputEmail" name="inputEmail" value="m.musterhalfen@gmail.com" required>

            <button id="genoCheckSubmit" type="submit">
                GenoCheck&trade; bestellen
            </button>
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
     * @return none
     */
    protected function generateView()
    {
        $this->getViewData();
        $this->generatePageHeader('GenoChoice&trade; - GenoCheck&trade; bestellen');
        $this->generateNavigationBar();
        $this->generatePageTitle();

        $this->generatePageDescription();
        $this->generateGenoCheckForm();

        $this->generatePageFooter();
    }

    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If this page is supposed to do something with submitted
     * data do it here.
     * If the page contains blocks, delegate processing of the
	 * respective subsets of data to them.
     *
     * @return none
     */
    protected function processReceivedData()
    {
        parent::processReceivedData();
        // to do: call processReceivedData() for all members
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
            $page = new Phase1();
            $page->processReceivedData();
            $page->generateView();
        }
        catch (Exception $e) {
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

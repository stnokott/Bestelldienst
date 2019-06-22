<?php // UTF-8 marker äöüÄÖÜß€
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
class Phase0 extends Page
{
    /**
     * Fetch all data that is necessary for later output.
     * Data is stored in an easily accessible way e.g. as associative array.
     *
     * @return void
     */
    protected function getViewData()
    {

    }

    /**
     * Generiert erste <section>, die den Inhalt dieser Seite beschreibt
     * @return void
     */
    protected function generatePageDescription()
    {
        echo <<<HTML
        <section>
            <figure>
                <img src="img/family.jpg" alt="Diese fröhliche Familie könnten Sie sein!">
            </figure>
            <div class="sectionHeader"><span class="themeFont">GenoCheck&trade;</span> bestellen</div>
            <p>
                Fordern Sie heute ihren <strong>kostenlosen</strong> GenoChoice&trade;-Gentest an.<br> Ein Team
                aus professionellen Genforschern prüft mit unserem patentierten GenoCheck&trade;-Verfahren die
                Stärken und Schwächen ihres zukünftigen Kindes.
            </p>
        </section>
HTML;
    }

    /**
     * Generiert <form> zur Eingabe der Daten, die für die GenoCheck-Bestellung nötig sind
     * @return void
     */
    protected function generateGenoCheckForm()
    {
        echo <<<HTML
      <section>
        <span class="sectionHeader">Persönliche Daten</span>
        <form name="genoCheckForm[]" action="phase1.php" method="post">
          <div class="inputTextGroup">
            <input type="text" id="inputFirstName" name="inputFirstName" placeholder="" required>
            <label for="inputFirstName">Vorname</label>
          </div>

          <div class="inputTextGroup">
            <input type="text" id="inputLastName" name="inputLastName" placeholder="" required>
            <label for="inputLastName">Name</label>
          </div>

          <div class="inputTextGroup">
            <input type="text" id="inputStreet" name="inputStreet" placeholder="" required>
            <label for="inputStreet">Straße & Hausnummer</label>
          </div>

          <div class="inputTextGroup">
            <input type="text" id="inputCity" name="inputCity" placeholder="" required>
            <label for="inputCity">Stadt</label>
          </div>

          <div class="inputTextGroup">
            <input type="text" id="inputZipcode" name="inputZipcode" placeholder="" pattern="\d{5}" required>
            <label for="inputZipcode">PLZ</label>
          </div>

          <div class="inputTextGroup">
            <input type="email" id="inputEmail" name="inputEmail" placeholder="" required>
            <label for="inputEmail">E-Mail</label>
          </div>

          <button class="floatright" type="submit">
            Bestellen
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
     * @return void
     */
    protected function generateView()
    {
        $this->getViewData();
        $this->generatePageHeader('GenoChoice&trade; - GenoCheck&trade; bestellen');
        $this->generateNavigationBarUser(0);
        $this->generatePageTitle();

        $this->generatePageDescription();
        $this->generateGenoCheckForm();

        $this->generatePageFooter("phase0.js");
    }

    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If this page is supposed to do something with submitted
     * data do it here.
     * If the page contains blocks, delegate processing of the
     * respective subsets of data to them.
     *
     * @return void
     */
    protected function processReceivedData()
    {
        try {
            parent::processReceivedData();
        } catch (Exception $e) {
            echo "Fehler beim Verarbeiten der Daten: " . $e;
        }
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
     * @return void
     */
    public static function main()
    {
        session_start();
        try {
            $page = new Phase0();
            $page->processReceivedData();
            $page->checkSessionPhase(0);
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

// This call is starting the creation of the page.
// That is input is processed and output is created.
Phase0::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").

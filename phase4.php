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

    protected function generateSectionStart() {
        echo<<<HTML
            <section>
                <span class="sectionHeader" id="chooseKitHeader">Ihr Kit wählen</span>
HTML;

    }

    protected function generateAvailableKits() {
        echo<<<HTML
            <div id="kitContainer">
                <div class="bookable">
                    <div class="bookableHeader kitBasic"><img src="img/wrench.svg" alt=""/>Basic Kit</div>
                    <ul>
                        <li>Attribute aus naturgegebenem Genpool</li>
                        <li>Kein Einfluss auf negative Attribute</li>
                    </ul>
                    <button class="noshadow" value="basic">5999.99€</button>
                </div>
                <div class="bookable">
                    <div class="bookableHeader kitComfort"><img src="img/couch.svg" alt=""/>Comfort Kit</div>
                    <ul>
                        <li>Visuelle Attribute individuell wählbar</li>
                        <li>Reduzierte Krankheitswahrscheinlichkeiten</li>
                    </ul>
                    <button class="noshadow" value="comfort">7999.99€</button>
                </div>
                <div class="bookable">
                    <div class="bookableHeader kitSocial"><img src="img/laugh.svg" alt=""/>Social Kit</div>
                    <ul>
                        <li>Visuelle Attribute individuell wählbar</li>
                        <li>Erhöhte Werte in sozial anerkannten Bereichen</li>
                    </ul>
                    <button class="noshadow" value="social">8499.99€</button>
                </div>
                <div class="bookable">
                    <div class="bookableHeader kitPremium"><img src="img/trophy.svg" alt=""/>Premium Kit</div>
                    <ul>
                        <li>Visuelle Attribute individuell wählbar</li>
                        <li>Inkl. Comfort Kit</li>
                        <li>Inkl. Social Kit</li>
                    </ul>
                    <button class="noshadow" value="premium">14499.99€</button>
                </div>
                <div class="bookable stretched">
                    <div class="bookableHeader kitCustom"><img src="img/growth.svg" alt=""/>Custom Kit</div>
                    <ul>
                        <li>Visuelle Attribute individuell wählbar</li>
                        <li>Charakter-Attribute individuell wählbar</li>
                        <li>Krankheiten eliminiert</li>
                    </ul>
                    <button class="noshadow" value="custom">24499.99€</button>
                </div>
            </div>
HTML;
    }

    protected function generateAvailableOptionals() {
        echo <<<HTML
            <div class="sectionHeader" id="chooseOptionalsHeader">Optionale Zusatzpakete buchen</div>
            <div id="optionalsContainer">
                <div class="bookable">
                    <div class="bookableHeader optional"><img src="img/pregnant.svg" alt=""/>Klinikgeburt</div>
                    <ul>
                        <li>Ersetzt klassiche Inkubation</li>
                        <li>Leihmutter trägt Ihren Embryo aus</li>
                    </ul>
                    <button class="noshadow" value="clinic">499.99€</button>
                </div>
                <div class="bookable">
                    <div class="bookableHeader optional"><img src="img/drone-delivery.svg" alt=""/>Lieferung per Drohne</div>
                    <ul>
                        <li>Kein Termin in Klinik nötig</li>
                        <li>Gepolsterter Behälter</li>
                    </ul>
                    <button class="noshadow" value="drone">249.99€</button>
                </div>
                <div class="bookable">
                    <div class="bookableHeader optional"><img src="img/heron.svg" alt=""/>Lieferung per Storch</div>
                    <ul>
                        <li>Traditionsbewusst</li>
                        <li>Komfortable Lieferung in Leinenbeutel</li>
                    </ul>
                    <button class="noshadow" value="heron">0.99€</button>
                </div>
            </div>
HTML;
    }

    protected function generateShoppingCart() {
        echo <<<HTML
            <div class="sectionHeader">Bestellung prüfen</div>
            <div class="shoppingCartHeader">Warenkorb</div>
            <div class="shoppingCart">
                <div class="cartItem kitBasic" id="shoppingCartKit">
                    <img src="img/wrench.svg" alt=""/>
                    <div class="cartItemName">Basic Kit</div>
                    <div class="cartItemPrice">5999.99€</div>
                    <button class="noshadow" value="changeKit"><i class="material-icons">swap_horiz</i></button>
                </div>
                <div id="clinic" class="cartItem optional">
                    <img src="img/pregnant.svg" alt=""/>
                    <div class="cartItemName">Klinikgeburt</div>
                    <div class="cartItemPrice">499.99€</div>
                    <button class="noshadow" value="removeItem"><i class="material-icons">clear</i></button>
                </div>
                <div id="drone" class="cartItem optional">
                    <img src="img/drone-delivery.svg" alt=""/>
                    <div class="cartItemName">Lieferung per Drohne</div>
                    <div class="cartItemPrice">249.99€</div>
                    <button class="noshadow" value="removeItem"><i class="material-icons">clear</i></button>
                </div>
                <div id="heron" class="cartItem optional">
                    <img src="img/heron.svg" alt=""/>
                    <div class="cartItemName">Lieferung per Storch</div>
                    <div class="cartItemPrice">0.99€</div>
                    <button class="noshadow" value="removeItem"><i class="material-icons">clear</i></button>
                </div>
            </div>
            <div class="shoppingCartTotal">
                <span class="title">Gesamt:</span>
                <span class="value">5999.99€</span>
            </div>
HTML;
    }

    protected function generateSectionEnd() {
        echo<<<HTML
            <form action="phase5.html" method="post">
                <button type="submit" class="floatright" value="continue">Weiter <i class="material-icons">forward</i></button>
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
        $this->generatePageHeader('GenoChoice&trade; - Optionale Pakete buchen');
        $this->generateNavigationBar();
        $this->generatePageTitle();

        $this->generateSectionStart();
        $this->generateAvailableKits();
        $this->generateAvailableOptionals();
        $this->generateShoppingCart();
        $this->generateSectionEnd();

        $this->generatePageFooter('phase4.js');
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
            $page = new Phase4();
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
Phase4::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").
//? >

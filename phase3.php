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

/**
 * Container-Klasse für aus der DB abgerufene
 * verfügbare Kits
 */
class Kit
{
    /**
     * @var int ID des Kits in der Datenbank
     */
    public $kitid;

    /**
     * @var string Name des Kits
     */
    public $name;

    /**
     * @var int Preis des Kits
     */
    public $price;

    /**
     * @var string Erster Beschreibungsstickpunkt
     */
    public $desc1;

    /**
     * @var string Zweiter Beschreibungsstichpunkt
     */
    public $desc2;

    /**
     * @var string Dritter Beschreibungsstichpunkt
     */
    public $desc3;

    /**
     * @var resource Hintergrund-Bild für Kit als SVG (als Blob in DB hinterlegt)
     */
    public $bg;

    /**
     * @var string Zu verwendende CSS-Klasse für Kit
     */
    public $cssclass;

    /**
     * @var int Definiert, ob der Container im Browser alle verfügbaren Spalten nutzen soll
     */
    public $stretched;

    /**
     * Kit constructor
     */
    public function __construct()
    {
    }
}

/**
 * Klasse für Phase 3
 */
class Phase3 extends Page
{
    /**
     * @var array Liste der verfügbaren Kits in der DB
     */
    private $kitList = [];

    /**
     * Fetch all data that is necessary for later output.
     * Data is stored in an easily accessible way e.g. as associative array.
     *
     * Speichert den Bestellstatus des aktuell angemeldeten Nutzers
     * @return void
     */
    protected function getViewData()
    {
        if(!isset($_SESSION["userid"])){
            header('Location: phase0.php');
        }

        // Kits aus Datenbank holen
        $this->updateKitList();
    }

    /**
     * Rufe die verfügbaren Kits aus der Datenbank ab und speichere sie in &$this->kitList
     * @return void
     */
    protected function updateKitList() {
        $query = "SELECT * FROM kit ORDER BY kitid";
        $result = $this->_database->query($query);

        while ($row = $result->fetch_assoc()) {
            $kit = new Kit();
            $kit->kitid = htmlspecialchars($row["kitid"]);
            $kit->name = htmlspecialchars($row["name"]);
            $kit->price = htmlspecialchars($row["price"]);
            $kit->desc1 = htmlspecialchars($row["desc1"]);
            $kit->desc2 = htmlspecialchars($row["desc2"]);
            $kit->desc3 = htmlspecialchars($row["desc3"]);
            $kit->bg = $row["bg"];
            $kit->cssclass = htmlspecialchars($row["cssclass"]);
            $kit->stretched = $row["cssstretched"]; // nicht nötig, da nur für If benötigt
            array_push($this->kitList, $kit);
        }
    }

    /**
     * Generiere Anfang der Kit-section
     * @return void
     */
    protected function generateSectionStart() {
        echo<<<HTML
            <section>
                <div class="sectionHeader" id="chooseKitHeader">Ihr Kit wählen</div>
HTML;

    }

    /**
     * Generiere alle in der DB verfügbaren Kits aus &$this->kitList
     * @return void
     */
    protected function generateAvailableKits() {
        echo '<div id="kitContainer">';
        foreach ($this->kitList as $kit) {
            $stretched = $kit->stretched == 1 ? "stretched" : "";
            echo<<<HTML
                <div class="bookable {$stretched}">
                    <div class="bookableHeader {$kit->cssclass}"><img src="data:image/svg+xml;utf8,{$kit->bg}" alt=""/>{$kit->name}</div>
                    <ul>
HTML;
            if ($kit->desc1 != "") {
                echo '<li>'.$kit->desc1.'</li>';
            }
            if ($kit->desc2 != "") {
                echo '<li>'.$kit->desc2.'</li>';
            }
            if ($kit->desc3 != "") {
                echo '<li>'.$kit->desc3.'</li>';
            }
            echo<<<HTML
                    </ul>
                    <button class="noshadow" data-kitid="{$kit->kitid}" data-name="{$kit->name}" data-price="{$kit->price}" data-bg="{$kit->bg}" data-cssclass="{$kit->cssclass}">{$kit->price}€</button>
                </div>
HTML;

        }
        echo '</div>';
    }

    /**
     * Generiere alle verfügbaren Optionals.
     * Diese werden nicht aus der DB abgerufen
     * @return void
     */
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
                    <div class="bookableHeader optional"><img src="img/heart.svg" alt=""/>Organversicherung</div>
                    <ul>
                        <li>Auswahl an biologisch gezüchteten Ersatzorganen</li>
                        <li>Kompatibilitätsgarantie</li>
                        <li>Komfortabel im Kühlschrank lagern</li>
                    </ul>
                    <button class="noshadow" value="insurance">899.99€</button>
                </div>
            </div>
HTML;
    }

    /**
     * Generiere den Einkaufswagen mit dem Basic Kit als Default.
     * Alle Optionals sind bereits versteckt vorhanden und werden bei Bedarf gezeigt bzw. wieder versteckt
     * @return void
     */
    protected function generateShoppingCart() {
        echo <<<HTML
            <div class="shoppingCartHeader">Warenkorb</div>
            <div class="shoppingCart">
                <div class="cartItem kitBasic" id="shoppingCartKit">
                    <img src="img/wrench.svg" alt=""/>
                    <div class="cartItemName">Basic Kit</div>
                    <div class="cartItemPrice">5999.99€</div>
                    <button class="noshadow nobackground" value="changeKit"><i class="material-icons">swap_horiz</i></button>
                </div>
                <div id="clinic" class="cartItem optional">
                    <img src="img/pregnant.svg" alt=""/>
                    <div class="cartItemName">Klinikgeburt</div>
                    <div class="cartItemPrice">499.99€</div>
                    <button class="noshadow nobackground" value="removeItem"><i class="material-icons">clear</i></button>
                </div>
                <div id="drone" class="cartItem optional">
                    <img src="img/drone-delivery.svg" alt=""/>
                    <div class="cartItemName">Lieferung per Drohne</div>
                    <div class="cartItemPrice">249.99€</div>
                    <button class="noshadow nobackground" value="removeItem"><i class="material-icons">clear</i></button>
                </div>
                <div id="insurance" class="cartItem optional">
                    <img src="img/heart.svg" alt=""/>
                    <div class="cartItemName">Organversicherung</div>
                    <div class="cartItemPrice">899.99€</div>
                    <button class="noshadow nobackground" value="removeItem"><i class="material-icons">clear</i></button>
                </div>
            </div>
            <div class="shoppingCartTotal">
                <span class="title">Gesamt:</span>
                <span class="value">5999.99€</span>
                <button class="noshadow nobackground" value="removeAll" id="deleteCart"><i class="material-icons">delete</i></button>
            </div>
HTML;
    }

    /**
     * Generiere das Ende der Bestelluns-Section
     * @return void
     */
    protected function generateSectionEnd() {
        echo <<<HTML
            <button id="confirmGenoCheckOrder" class="floatright">Bestellen <i class="material-icons">forward</i></button>
        </section>
HTML;

    }

    /**
     * First the necessary data is fetched and then the HTML is
     * assembled for output. i.e. the header is generated, the content
     * of the page ("view") is inserted and -if avaialable- the content of
     * all views contained is generated.
     * Finally the footer is added.
     * @return void
     */
    protected function generateView()
    {
        $this->getViewData();
        $this->generatePageHeader('GenoChoice&trade; - Pakete buchen');
        $this->generateNavigationBarUser(3);
        $this->generatePageTitle();

        $this->generateCurrentUser(false);
        $this->generateSectionStart();
        $this->generateAvailableKits();
        $this->generateAvailableOptionals();
        $this->generateShoppingCart();
        $this->generateSectionEnd();

        $this->generatePageFooter('phase3.js');
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
     * @return void
     */
    protected function processReceivedData()
    {
        parent::processReceivedData();
        // prüfe, ob diese Seite legitim von Phase2 aus aufgerufen wurde
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["getShopMenu"])) {
            $_SESSION["phase"] = 3;
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
     */
    public static function main()
    {
        session_start();
        try {
            $page = new Phase3();
            $page->processReceivedData();
            $page->checkSessionPhase(3);
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

<?php	// UTF-8 marker äöüÄÖÜß€
/**
 * Class PageTemplate for the exercises of the EWA lecture
 * Demonstrates use of PHP including class and OO.
 * Implements Zend coding standards.
 * Generate documentation with Doxygen or phpdoc
 *
 * PHP Version 5
 *
 * @category File
 * @package  Pizzaservice
 * @author   Bernhard Kreling, <b.kreling@fbi.h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 * @license  http://www.h-da.de  none
 * @Release  1.2
 * @link     http://www.fbi.h-da.de
 */

// to do: change name 'PageTemplate' throughout this file
require_once './Page.php';

/**
 * This is a template for top level classes, which represent
 * a complete web page and which are called directly by the user.
 * Usually there will only be a single instance of such a class.
 * The name of the template is supposed
 * to be replaced by the name of the specific HTML page e.g. baker.
 * The order of methods might correspond to the order of thinking
 * during implementation.

 * @author   Bernhard Kreling, <b.kreling@fbi.h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 */
class Phase1ToPhase2 extends Page
{
    private $user_created = false;

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
        // POST wird angenommen
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
        if (!$valid) {
            // redirect zu phase1.php
            header('Location: phase1.php');
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
        $query = "SELECT id FROM user WHERE email='".$email."'";
        $result = $this->_database->query($query);
        if ($result->fetch_assoc() == null) {
            // User mit dieser Email noch nicht vorhanden
            $query = $this->getMySQLInsertString("user", array("email", "firstname", "lastname", "address1", "address2", "address3"),
                                                array($email, $firstname, $lastname, $address1, $address2, $address3));
            $this->_database->query($query);
            if ($this->_database->errno != 0) {
                exit("Fehler beim Erstellen des Nutzers: ".$this->_database->error);
            }
            $this->user_created = true;
        }
    }

    protected function generateNavigationBar() {
echo <<<HTML
        <div>
            <ul class="navlist">
                <li><a href="#">Phase 1</a></li>
                <li class="active"><a href="#">Phase 1-2</a></li>
                <li><a href="#">Phase 3</a></li>
            </ul>
        </div>
HTML;
    }

    protected function generateUserCreated() {
echo<<<HTML
        <section>
          <span class="sectionHeader">User erstellt!</span>
        </section>
HTML;
    }

    protected function generatePageDescription() {
echo<<<HTML
        <section>
          <span class="sectionHeader">GenoCheck&trade; - Sendungsverfolgung</span>
          <p>
            Hier können Sie den Fortschritt Ihres persönlichen GenoCheck&trade;-Tests verfolgen.<br>
            Bei abgeschlossener Analyse werden Sie zu Ihren Ergebnissen weitergeleitet.
          </p>
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
        $this->generatePageHeader('GenoChoice&trade; - GenoCheck&trade; Fortschritt');
        $this->generateNavigationBar();
        $this->generatePageTitle();

        if ($this->user_created == true) {
            $this->generateUserCreated();
        }
        $this->generatePageDescription();

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
            $page = new Phase1ToPhase2();
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
Phase1ToPhase2::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").
//? >

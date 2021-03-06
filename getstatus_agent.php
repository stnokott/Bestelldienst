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
class StatusHelperAgent extends Page
{
    /**
     * Gibt den Wert des status-Attributs der genocheckorder-Datenbank für den User mit der
     * angegebenen Email-Adresse aus
     * @param String $userid id des Nutzers
     * @return Int Bestellungsstatus (0=bestätigt, 1=gesendet, 2=im Labor, 3=fertig)
     */
    protected function getUserOrderStatus($userid)
    {
        if (!$this->checkUserExists($userid)) {
            return null;
        }

        $query = "SELECT status FROM genocheckorder WHERE userid='" . $userid . "'";
        $result = $this->_database->query($query);

        if ($row = $result->fetch_assoc()) {
            return $row["status"];
        } else {
            return null;
        }
    }

    /**
     * Prüft, ob der Nutzer mit der angegebenen Email-Adresse einen Eintrag
     * in der User-Datenbank hat
     * @param  String $userid ID des zu prüfenden Nutzers
     * @return Boolean Ob der User in der Datenbank besteht
     */
    protected function checkUserExists($userid) {
        $query = "SELECT userid FROM user WHERE userid='".$userid."'";
        $result = $this->_database->query($query);

        return !$result->fetch_assoc() == null;
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
    protected function getStatus()
    {
        if (!isset($_GET["userid"])) {
            die ("userid-GET-Parameter muss gesetzt sein!");
        }

        $status = $this->getUserOrderStatus($_GET["userid"]);
        echo json_encode($status);
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
     * @return void
     */
    protected function processReceivedData()
    {

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
        header("Content-Type: application/json; charset=UTF-8");
        try {
            $page = new StatusHelperAgent();
            $page->processReceivedData();
            $page->getStatus();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

// This call is starting the creation of the page.
// That is input is processed and output is created.
StatusHelperAgent::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").
//? >

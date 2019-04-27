<?php	// UTF-8 marker äöüÄÖÜß€
/**
 * Class Page for the exercises of the EWA lecture
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

/**
 * This abstract class is a common base class for all
 * HTML-pages to be created.
 * It manages access to the database and provides operations
 * for outputting header and footer of a page.
 * Specific pages have to inherit from that class.
 * Each inherited class can use these operations for accessing the db
 * and for creating the generic parts of a HTML-page.
 *
 * @author   Bernhard Kreling, <b.kreling@fbi.h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 */
abstract class Page
{
    // --- ATTRIBUTES ---

    /**
     * Reference to the MySQLi-Database that is
     * accessed by all operations of the class.
     */
    protected $_database = null;

    // --- OPERATIONS ---

    /**
     * Connects to DB and stores
     * the connection in member $_database.
     * Needs name of DB, user, password.
     *
     * @return none
     */
    protected function __construct()
    {
        $this->_database = new mysqli("localhost", "website", "ewa2019sose", "bestelldienst");;
        if ($this->_database->connect_error) {
            die("Connection failed: " . $this->_database->connect_error);
        }
        $this->_database->set_charset("utf8"); // stellt sicher, dass Sonderzeichen korrekt übertragen werden
    }

    /**
     * Closes the DB connection and cleans up
     *
     * @return none
     */
    protected function __destruct()
    {
        $this->_database->close();
<<<<<<< HEAD
=======
        // to do: close database
>>>>>>> d1c8d64b5f35d84ffe83bfe843024fcce6d16126
    }

    /**
     * Generates the header section of the page.
     * i.e. starting from the content type up to the body-tag.
     * Takes care that all strings passed from outside
     * are converted to safe HTML by htmlspecialchars.
     *
     * @return none
     */
    protected function generatePageHeader($title)
    {
        header("Content-type: text/html; charset=UTF-8");
echo <<<HTML
        <!DOCTYPE html>
        <html lang="de">
          <head>
            <meta charset="UTF-8">
            <title>$title</title>
            <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
            <link rel="stylesheet" href="main.css">
            <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
            <link href="https://fonts.googleapis.com/css?family=Raleway|Roboto" rel="stylesheet">
          </head>

          <body>
HTML;
    }

    protected function generatePageTitle() {
echo<<<HTML
      <header>
        <span class="headerTitle">Genochoice</span><br>
        <span class="headerSubtitle">Legacy by Design</span>
      </header>
HTML;

    }

    /**
     * Outputs the end of the HTML-file i.e. /body etc.
     *
     * @return none
     */
    protected function generatePageFooter()
    {
        echo <<<HTML
            </body>
        </html>
HTML;
    }

    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If every page is supposed to do something with submitted
     * data do it here. E.g. checking the settings of PHP that
     * influence passing the parameters (e.g. magic_quotes).
     *
     * @return none
     */
    protected function processReceivedData()
    {
        if (get_magic_quotes_gpc()) {
            throw new Exception
                ("Bitte schalten Sie magic_quotes_gpc in php.ini aus!");
        }
    }

    protected function getMySQLInsertString($table, $columns, $values) {
        $sql = "INSERT INTO user (email, firstname, lastname, address1, address2, address3)
         VALUES ('" .$_POST['inputEmail']. "', '" .$_POST['inputFirstName']. "', '" .$_POST['inputLastName']. "', '" .$_POST['inputStreet']. "', '" .$_POST['inputCity']. "', '" .$_POST['inputZipcode']. "')";

        $columns_string = join(", ", $columns);
        $values_string = join("', '", $values);
        $string = "INSERT INTO ".$table." (".$columns_string.") VALUES ('".$values_string."')";
        return $string;
    }
} // end of class

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").
?>

<?php


/**
 * Class User
 */
class User
{
    /**
     * @var int ID des Users in der Datenbank
     */
    private $userid;

    /**
     * @var string Vorname des Nutzers
     */
    private $firstname;

    /**
     * @var string Nachname des Nutzers
     */
    private $lastname;

    /**
     * @var string E-Mail des Nutzers
     */
    private $email;

    /**
     * User Konstruktor.
     * @param $userid int ID des Users in der Datenbank
     * @param $firstname string Vorname des Users
     * @param $lastname string Nachname des Nutzers
     * @param $email string E-Mail des Nutzers
     */
    public function __construct($userid, $firstname, $lastname, $email)
    {
        $this->userid = $userid;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
    }

    /**
     * Destruktor
     */
    public function __destruct()
    {

    }

    /**
     * Gibt User-ID zurück
     * @return int User-ID des Nutzers in der DB
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Gibt Vorname des Users zurück
     * @return string Vorname des Users
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Gibt Nachname des Users zurück
     * @return string Nachname des Users
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Gibt E-Mail des Users zurück
     * @return string E-Mail des Users
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Gibt Attribute als vereinigten String zurück
     * @return string Alle relevanten Attribute als String
     */
    public function toString()
    {
        return $this->firstname . " " . $this->lastname . " - " . $this->email;
    }
}
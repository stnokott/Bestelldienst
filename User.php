<?php


class User
{
    private $userid;
    private $firstname;
    private $lastname;
    private $email;

    public function __construct($userid, $firstname, $lastname, $email)
    {
        $this->userid = $userid;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
    }

    public function __destruct()
    {

    }

    public function getUserid()
    {
        return $this->userid;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function toString()
    {
        return $this->firstname . " " . $this->lastname . " - " . $this->email;
    }
}
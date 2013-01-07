<?php

class Database
{
    private $host = '_HOST_';
    private $user = '_USERNAME_';
    private $password = '_PASSWORD_';
    private $dbName = '_DbNAME_';

    private $mysqli = null;
    
    public function Connect()
    {
        $this->mysqli = new mysqli( $this->host, $this->user, $this->password, $this->dbName );
        
        if ( mysqli_connect_error() != 0 )
        {
            return false;
        }
        return true;
    }
    
    public function Close()
    {
        $this->mysqli->Close();
        $this->mysqli = null;
    }
    
    public function Prepare( $stmt )
    {
        return $this->mysqli->prepare( $stmt );
    }
    
    public function GetErrors()
    {
        return $this->mysqli->error;
    }
}

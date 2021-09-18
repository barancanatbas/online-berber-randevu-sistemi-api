<?php


class model
{
    protected $host = "localhost";
    protected $dbname = "dbberber";
    protected $pass = "";
    protected $username = "root";
    public $con;
    protected $resimdir = "view/resimler/";

    public function __construct()
    {
        try {
            $this->con = new PDO("mysql:host=".$this->host.";dbname=".$this->dbname.";charset=utf8",$this->username,$this->pass);
        }catch (PDOException $e)
        {
            die($e->getMessage());
        }

    }

    public function __destruct()
    {
        $this->con = null;
    }
}
<?php
include_once 'model.php';

class Dbilce extends model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

    }

    public function GetByilno($ilno)
    {

        $sorgu =  $this->con->prepare("select * from ilceler where il_no = ?");
        $sorgu->execute([$ilno]);
        $veri = $sorgu->fetchAll(PDO::FETCH_ASSOC);
        if ($veri > 0)
        {
            return $veri;
        }
        else{
            return ["hata" => "bir hata var"];
        }
    }
}
<?php
include_once 'model.php';

class Dbtarifeler extends model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert($berberid,$tarifeAd,$tarifeUcret)
    {
        $sorgu = $this->con->prepare("insert into tarife (islemAdi,islemUcret,berberFk) values (?,?,?)");
        $sorgu->execute([$tarifeAd,$tarifeUcret,$berberid]);
        if ($sorgu)
        {
            return true;
        }
        else return false;
    }

    public function delete(int $silinecekid)
    {
        $sorgu = $this->con->prepare("delete from tarife where id = ?");
        $sorgu->execute([$silinecekid]);
        if ($sorgu)
        {
            return true;
        }
        else return false;
    }

    public function gets(int $berberid)
    {
        $sorgu = $this->con->prepare("select * from tarife where berberFk = ?");
        $sorgu->execute([$berberid]);
        if ($sorgu)
        {
            $veriler = $sorgu->fetchAll(PDO::FETCH_ASSOC);
            return $veriler;
        }
        else return false;
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}
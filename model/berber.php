<?php
include_once 'model.php';
include_once 'resimler.php';
include_once 'adres.php';

class Dbberber extends model
{
    private $resimlerDb;
    private $adresDb;
    public function __construct()
    {
        parent::__construct();
        $this->resimlerDb = new Dbresimler();
        $this->adresDb = new Dbadres();
    }

    public function gets()
    {
        $sorgu = $this->con->prepare("select * from berber");
        $sorgu->execute();
        $kayitsay = $sorgu->rowCount();
        if ($kayitsay > 0)
        {
            $veri = $sorgu->fetchAll(PDO::FETCH_ASSOC);
            return $veri;
        }
        else return false;
    }
    public function getbykulid($kulid)
    {
        $sorgu = $this->con->prepare("select * from berber where kullaniciFK = ?");
        $sorgu->execute(array($kulid));
        if ($sorgu)
        {
            $berber = $sorgu->fetch(PDO::FETCH_ASSOC);
            return $berber;
        }
    }

    public function get($id)
    {
        $sorgu = $this->con->prepare("select * from berber where id = ?");
        $sorgu->execute(array($id));
        if ($sorgu)
        {
            $berber = $sorgu->fetch(PDO::FETCH_ASSOC);
            return $berber;
        }
    }

    public function insertweb($berberad,$berbertel,$acilis,$kapanis,$periot,$adresFk,$kullaniciFk,$resimler)
    {

        $sorgu = $this->con->prepare("insert into berber (berberAd,berberTel,acilis,kapanis,periot,berberAdresFk,kullaniciFk) values (?,?,?,?,?,?,?)");
        $sorgu->execute(array($berberad,$berbertel,$acilis,$kapanis,$periot,$adresFk,$kullaniciFk));
        if ($sorgu) {
            $berberid = $this->con->lastInsertId();
            $result = $this->resimlerDb->uploadweb($resimler,$berberid);
            if ($result)
            {
                $sorgu2 = $this->con->prepare("update kullanicilar set berbermi = ? where id = ?");
                $sorgu2->execute([1,$kullaniciFk]);
                return true;
            }
            else return false;
        }
        else return false;
    }


    public function insert($berberad,$berbertel,$acilis,$kapanis,$periot,$adresFk,$kullaniciFk)
    {
        $sorgu = $this->con->prepare("insert into berber (berberAd,berberTel,acilis,kapanis,periot,berberAdresFk,kullaniciFk) values (?,?,?,?,?,?,?)");
        $sorgu->execute(array($berberad,$berbertel,$acilis,$kapanis,$periot,$adresFk,$kullaniciFk));
        if ($sorgu) {
            $sorgu2 = $this->con->prepare("update kullanicilar set berbermi = ? where id = ?");
            $sorgu2->execute([1,$kullaniciFk]);
            if ($sorgu2)
            {
                return true;
            }
            else{
                die("burada bir hata var ");
            }
        }
        else{
            return false;
        }
    }

    public function update($berberad,$berbertel,$acilis,$kapanis,$periot,$adresId,$id)
    {
        $sorgu = $this->con->prepare("update berber set berberAd = ? , berberTel = ? , acilis = ?, kapanis = ? , periot = ? , berberAdresFk = ? where kullaniciFK = ?");
        $sorgu->execute([$berberad,$berbertel,$acilis,$kapanis,$periot,$adresId,$id]);
        if ($sorgu)
        {
            return true;
        }
        else{
            return false;
        }
    }

    public function diasble(int $id)
    {
        $sorgu = $this->con->prepare("update berber set durum = ? where id = ?");
        $sorgu->execute([0,$id]);
        if ($sorgu)
            return true;
        else
            return false;
    }

}

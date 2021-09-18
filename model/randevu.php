<?php
include_once 'model.php';

class Dbrandevu extends model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getByKey($randevuKey)
    {
        $sorgu = $this->con->prepare("select * from randevu where randevuKey = ?");
        $sorgu->execute([$randevuKey]);
        if ($sorgu->rowCount() > 0)
        {
            $veriler = $sorgu->fetch(PDO::FETCH_ASSOC);
            return $veriler;
        }
        else return false;
    }

    public function insert($berberFk,$tarihsaat,$kulid)
    {
        $result = [];
        $randevuKey = uniqid();
        $sorgu = $this->con->prepare("insert into randevu (berberFk,tarihSaat,kullaniciFk,randevuKey) values (?,?,?,?)");
        $sorgu->execute(array($berberFk,$tarihsaat,$kulid,$randevuKey));
        if ($sorgu) {
            $result["mesaj"] = "başarılı";
            $result["randevuKey"] = $randevuKey;
            return $result;
        }
        else
            return false;

    }

    public function iptal($randevuKey)
    {
        $sorgu = $this->con->prepare("update randevu set iptal = ? where randevuKey = ?");
        $sorgu->execute([1,$randevuKey]);
        if ($sorgu)
            return true;
        else return false;
    }

    public function getByBerberId($berberid)
    {
        $sorgu = $this->con->prepare("select * from randevu where berberFk = ?");
        $sorgu->execute([$berberid]);
        if ($sorgu->rowCount() > 0)
        {
            $result = $sorgu->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        else
            return false;
    }

    public function getByKulFk($kulid)
    {
        $sorgu = $this->con->prepare("select r.*,b.berberAd,b.berberTel,b.berberAdresFk from randevu r inner join berber b on r.berberFk = b.id where r.kullaniciFk = ?");
        $sorgu->execute([$kulid]);
        if ($sorgu->rowCount() > 0)
        {
            $result = $sorgu->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        else
            return false;
    }
}
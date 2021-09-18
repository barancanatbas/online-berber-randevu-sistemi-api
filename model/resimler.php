<?php
include_once 'model.php';

class Dbresimler extends model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function gets($berberid)
    {
        $sorgu = $this->con->prepare("select * from resimler where berberFk = ? ");
        $sorgu->execute([$berberid]);
        if ($sorgu->rowCount() > 0)
        {
            $veriler = $sorgu->fetchAll(PDO::FETCH_ASSOC);
            return $veriler;
        }
        else return false;
    }

    public function uploadweb($resimler,$berberid)
    {
        for($i=0; $i < sizeof($resimler["name"]);$i++)
        {
            $resimTmp = $resimler["tmp_name"][$i];
            $uri = $this->resimdir.uniqid().".png";
            if (!move_uploaded_file($resimTmp,$uri))
            {
                return false;
            }
            $sorgu = $this->con->prepare("insert into resimler (resimsrc,berberFk) values (?,?)");
            $sorgu->execute([$uri,$berberid]);
        }
        return true;
    }

    public function uploadMobil($resimler,$berberid)
    {
        for ($i = 0; $i < count($resimler);$i++) {
            $uri = $this->resimdir . uniqid() . ".png";
            $resimdecode = base64_decode($resimler["resim".$i]);
            if (file_put_contents($uri, $resimdecode))
            {
                $sorgu = $this->con->prepare("insert into resimler (resimsrc,berberFk) values (?,?)");
                $sorgu->execute([$uri,$berberid]);
                if (!$sorgu)
                    return false;
            }
        }
        return true;
    }

    public function deleteById($idArray)
    {
        for ($i =0;$i < sizeof($idArray); $i++)
        {
            $sorgu = $this->con->prepare("delete from resimler where id = ?");
            $sorgu->execute([$idArray[$i]]);
        }
    }
}
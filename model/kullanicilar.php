<?php
include_once "model.php";

class Dbkullanicilar extends model
{
    public function __construct()
    {
        parent::__construct();
        $this->resimdir = "view/resimler/";
    }

    public function login($kuladi,$sifre)
    {
        $sifre = md5($sifre);
        $sorgu = $this->con->prepare("select * from kullanicilar where kuladi = ? and sifre = ?");
        $sorgu->execute([$kuladi,$sifre]);
        $veri = $sorgu->fetch(PDO::FETCH_ASSOC);
        if ($veri > 0)
        {
            return $veri;
        }
        else{
            return false;
        }
    }

    public function registerweb($kuladi,$ad,$soyad,$mail,$sifre,$telefon,$resim)
    {

        $resimTmp = $resim["tmp_name"];
        $uri = $this->resimdir.uniqid().".png";
        if (move_uploaded_file($resimTmp,$uri)) {
            $sorgu = $this->con->prepare("insert into kullanicilar (kuladi,ad,soyad,mail,sifre,telefon,lastsession,durum,berbermi,profilResim) values (?,?,?,?,?,?,?,?,?,?)");
            $sorgu->execute([$kuladi, $ad, $soyad, $mail, $sifre, $telefon, time(), 1, 0, $uri]);
            if ($sorgu) {
                $lastid = $this->con->lastInsertId();
                $mesaj = "başarılı";
                $result = array(
                    "id" => $lastid,
                    "mesaj" => $mesaj
                );
                return $result;
            } else {
                $mesaj = "hata";
                $result = array(
                    "mesaj" => $mesaj
                );
                return $result;
            }
        }
        else{
            $mesaj = "resim yüklenmedi";
            $result = array(
                "mesaj" => $mesaj
            );
            return $result;
        }
    }

    public function registermobil($kuladi,$ad,$soyad,$mail,$sifre,$telefon,$resim)
    {

        $pattern = '/data:image\/(.+);base64,(.*)/';
        preg_match($pattern, $resim, $matches);
        
        // image file extension
        $imageExtension = $matches[1];
        
        // base64-encoded image data
        $encodedImageData = $matches[2];
        

        $uri = $this->resimdir.uniqid().".png";
        $resimdecode = base64_decode($encodedImageData);
        if (file_put_contents($uri,$resimdecode)){
            $sorgu = $this->con->prepare("insert into kullanicilar (kuladi,ad,soyad,mail,sifre,telefon,lastsession,durum,berbermi,profilResim) values (?,?,?,?,?,?,?,?,?,?)");
            $sorgu->execute([$kuladi, $ad, $soyad, $mail, $sifre, $telefon, time(), 1, 0, $uri]);
            if ($sorgu) {
                $lastid = $this->con->lastInsertId();
                $mesaj = "başarılı";
                $result = array(
                    "id" => $lastid,
                    "mesaj" => $mesaj
                );
                return $result;
            } else {
                $mesaj = "hata";
                $result = array(
                    "mesaj" => $mesaj
                );
                return $result;
            }
        }
        else{
            $mesaj = "resim yüklenmedi";
            $result = array(
                "mesaj" => $mesaj
            );
            return $result;
        }
    }

    public function updatePass($sifre,$id)
    {
        $sorgu = $this->con->prepare("update kullanicilar set sifre = ? where id = ?");
        $sorgu->execute([$sifre,$id]);
        if ($sorgu)
        {
            $mesaj = "güncelleme işlemi başarılı";
            return ["mesaj" => $mesaj];
        }
    }

    public function updateGenel($yenikuladi,$ad,$soyad,$mail,$telefon,$id)
    {
        $sorgu = $this->con->prepare("update kullanicilar set kuladi = ? , ad = ?, soyad = ? , mail = ? ,telefon = ? where id = ?");
        $sorgu->execute([$yenikuladi,$ad,$soyad,$mail,$telefon,$id]);
        if ($sorgu)
            return true;
        else
            return false;

    }

   

    public function updateResim($id,$resim)
    {
        $user = $this->get($id);
        $resimyol = $user["profilResim"];

        $pattern = '/data:image\/(.+);base64,(.*)/';
        preg_match($pattern, $resim, $matches);
        // image file extension
        $imageExtension = $matches[1];
        // base64-encoded image data
        $encodedImageData = $matches[2];
        
        $uri = $this->resimdir.uniqid().".png";
        $resimdecode = base64_decode($encodedImageData);

        if (file_put_contents($uri,$resimdecode))
        {
            $sorgu = $this->con->prepare("update kullanicilar set profilResim = ? where id = ?");
            $sorgu->execute([$uri,$id]);
            if ($sorgu)
            {
                unlink($resimyol);
                return ["mesaj" => "Resim güncellendi","uri" => $uri];
            }
            else{
                return ["mesaj" => "Resim güncellenmedi"];
            }
        }
    }

    public function get($id)
    {
        $sorgu = $this->con->prepare("select * from kullanicilar where id = ?");
        $sorgu->execute([$id]);
        $veri = $sorgu->fetch(PDO::FETCH_ASSOC);
        return $veri;
    }

    // buraya pek gerek yok sanki ya bunun yerine berber bilgilerini getirirsek orda gösteririrz
    public function gets()
    {
        $sorgu = $this->con->prepare("select id,kuladi,ad,soyad,mail,profilResim from dbberber.kullanicilar");
        $sorgu->execute();
        $veri = $sorgu->fetchAll(PDO::FETCH_ASSOC);
        if ($veri > 0)
        {
            $json = json_encode($veri);
            return json_encode(json_decode($json),JSON_PRETTY_PRINT);
        }
    }
}
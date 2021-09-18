<?php
include_once 'controller.php';
include_once 'model/kullanicilar.php';
include_once 'model/berber.php';
include_once 'model/randevu.php';

class Kullanicilar extends MainController
{

    private $db;
    private $berberDb;
    private $randevuDb;
    
    public function __construct()
    {

        parent::__construct();
        $this->db = new Dbkullanicilar();
        $this->berberDb = new Dbberber();
        $this->randevuDb = new Dbrandevu();
    }

    public function register()
    {
        if (isset($_POST) and !empty($_POST))
        {
            $ad = $this->filtre($_POST["ad"]);
            $soyad = $this->filtre($_POST["soyad"]);
            $kuladi = $this->filtre($_POST["kuladi"]);
            $mail = $this->filtre($_POST["mail"]);
            $sifre = md5($_POST["sifre"]);
            $telefon = $this->filtre($_POST["telefon"]);
            $device = $this->filtre($_POST["device"]);
            $resim = $_POST["resim"];
            $result = $this->db->registermobil($kuladi, $ad, $soyad, $mail, $sifre, $telefon, $resim);
            
            print_r(json_encode($result));
        }
        else{
            $result = array(
                "mesaj" => "lütfen bir değer gönderin"
            );
            print_r(json_encode($result));
        }
    }

    public function login()
    {
        if (isset($_POST) and !empty($_POST))
        {
            $kuladi = $this->filtre($_POST["kuladi"]);
            $sifre = $this->filtre($_POST["sifre"]);
            $result = $this->db->login($kuladi,$sifre);
            print_r(json_encode($result));
        }
    }

    public function index()
    {
        $result = ["mesaj" => "bir veri yok "];
        print_r(json_encode($result));
    }

    public function update($id = 0)
    {
        $id = (int) $id;
        if ($id == 0)
            echo json_encode(["mesaj" => "geçerli bir id değeri giriniz"]);
        elseif ($id < 0)
            echo json_encode(["mesaj" => "geçerli bir id değeri giriniz2"]);
        elseif($id > 0){
            $kuladi = $this->filtre($_POST["kuladi"]);
            $sifre = $_POST["sifre"];
            $kullanici =  $this->checkauth($kuladi,$sifre);
            if($kullanici != false and $kullanici["id"] == $id) {
                if (isset($_POST) and !empty($_POST)) {
                    $formname = $this->filtre($_POST["islem"]);
                    if ($formname == "resim") {
                        $resim = $_POST["resim"];
                        $result = json_encode($this->db->updateResim($id,$resim));
                        echo $result;
                    }

                    if ($formname == "genel") {
                        $yenikuladi = $this->filtre($_POST["yenikuladi"]);
                        $ad = $this->filtre($_POST["ad"]);
                        $soyad = $this->filtre($_POST["soyad"]);
                        $mail = $this->filtre($_POST["mail"]);
                        $telefon = $this->filtre($_POST["telefon"]);

                        $result = json_encode($this->db->updateGenel($yenikuladi,$ad,$soyad,$mail,$telefon,$id));
                        echo $result;
                    }

                    if ($formname == "sifre") {
                        $sifre = md5($this->filtre($_POST["yenisifre"]));
                        $result = json_encode($this->db->updatePass($sifre, $id));
                        print_r($result);
                    }
                    if ($formname == "deneme")
                    {
                        echo json_encode(["mesaj" => "geçerli bir id değeri giriniz3"]);
                    }
                }
                else {
                    echo json_encode(["mesaj" => "bir veri gönderin"]);
                }
            }
            else{
                echo json_encode(["mesaj" => "lütfen kendi hesabınızı giriniz"]);
            }

        }
    }

    public function get($id = 0)
    {
        if (isset($_POST) and !empty($_POST))
        {
            $kuladi = $this->filtre($_POST["kuladi"]);
            $sifre = $_POST["sifre"];
            $oturum =  $this->checkauth($kuladi,$sifre);
            if (!$oturum)
            {
                $result["mesaj"] = "Lütfen bilgilerinizi kontrol ediniz.";
                print_r(json_encode($result));
                die();
            }
            elseif($oturum["id"] == $id)
            {
                $id =(int)$id;
                if ($id == 0)
                {
                    $result["mesaj"] ="Geçerli bir id giriniz";
                    print_r(json_encode($result));
                }
                else{
                    $result =  $this->db->get($id);
                    $result["randevular"] = $this->randevuDb->getByKulFk($id);
                    print_r(json_encode($result));
                }
            }
            else{
                $result["mesaj"] = "Lütfen kendi hesabınızı giriniz";
                print_r(json_encode($result));
                die();
            }

        }
    }
}
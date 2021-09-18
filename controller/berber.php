<?php
include_once 'controller.php';
include_once 'model/berber.php';
include_once 'model/adres.php';
include_once 'model/resimler.php';
include_once 'model/randevu.php';
include_once 'model/tarifeler.php';

class Berber extends MainController
{
    private $db;
    protected $adresDb;
    private $resimlerDb;
    private $randevuDb;
    private $tarifeDb;
    public function __construct()
    {
        parent::__construct();
        $this->tarifeDb = new Dbtarifeler();
        $this->randevuDb = new Dbrandevu();
        $this->db = new Dbberber();
        $this->adresDb = new Dbadres();
        $this->resimlerDb = new Dbresimler();
    }

    public function get($id = 0)
    {
        if ($id == 0 or $id < 0)
        {
            print_r(json_encode(["hata" => "berber bulunamadı"]));
            die();
        }
        $veriler = $this->db->get($id);
        if($veriler){
            $id = $veriler["id"];
            $veriler["resimler"] = $this->resimlerDb->gets($id);
            $veriler["tarifeler"] = $this->tarifeDb->gets($veriler["id"]);
            $veriler["adres"] = $this->adresDb->get($veriler["berberAdresFk"]);
            echo json_encode($veriler);
        }
        else print_r(json_encode(["hata" => "berber bulunamadı"]));
    }

    public function getbykulid($id = 0)
    {
        if ($id == 0 or $id < 0)
        {
            print_r(json_encode(["hata" => "berber bulunamadı"]));
            die();
        }
        $veriler = $this->db->getbykulid($id);
        if($veriler){
            $id = $veriler["id"];
            $veriler["resimler"] = $this->resimlerDb->gets($id);
            $veriler["tarifeler"] = $this->tarifeDb->gets($veriler["id"]);
            $veriler["adres"] = $this->adresDb->get($veriler["berberAdresFk"]);
            echo json_encode($veriler);
        }
        else print_r(json_encode(["hata" => "berber bulunamadı 2"]));
    }

    public function gets()
    {
        $berberler = $this->db->gets();
        if($berberler){
            foreach($berberler as $key => $berber)
            {
                $resimler = $this->resimlerDb->gets($berber["id"]);
                if($resimler)
                    $berberler[$key]["resimler"] = $resimler;
                $adres = $this->adresDb->get($berber["berberAdresFk"]);
                if($adres)
                    $berberler[$key]["adres"] = $adres;
            }
            echo json_encode($berberler);
        }
        else{
            echo json_encode(array("mesaj" => "berber yok","durum"=>"0"));
        }
        
    }

    public function getByBerber(int $id =0)
    {
        if ($id !=0 and $id > 0)
        {
            if (isset($_POST) and !empty($_POST))
            {
                $kuladi = $this->filtre($_POST["kuladi"]);
                $sifre = $_POST["sifre"];
                $user = $this->checkauth($kuladi,$sifre);
                $berber = $this->db->get($user["id"]);
                if ($user["id"] == $id and $berber["kullaniciFK"] ==$id)
                {
                    $veriler = $this->db->get($id);
                    if($veriler) {
                        $id = $veriler["id"];
                        $veriler["resimler"] = $this->resimlerDb->gets($id);
                    }
                    $randevular = $this->randevuDb->getByBerberId($berber["id"]);
                    if ($randevular)
                    {
                        $veriler["randevular"] = $randevular;
                    }
                    $tarifeler = $this->tarifeDb->gets($berber["id"]);
                    if ($tarifeler)
                    {
                        $veriler["tarifeler"] = $tarifeler;
                    }
                    echo json_encode($veriler);
                }
            }
        }
    }

    public function disable($id = 0)
    {
        $id = (int)$id;
        if ($id == 0 or $id < 0)
        {
            print_r(json_encode(["mesaj" => "lütfen geçerli bir id giriniz"]));
        }
        else{
            if (isset($_POST) and !empty($_POST))
            {
                $kuladi = $this->filtre($_POST["kuladi"]);
                $sifre = $_POST["sifre"];
                $user = $this->checkauth($kuladi,$sifre);
                if ($user and $user["id"] == $id)
                {
                    $result = $this->db->diasble($id);
                    if ($result)
                        print_r(json_encode(["mesaj" => "hesabınız başarı ile dondurulmuştur"]));
                    else
                        print_r(json_encode(["mesaj" => "hesabınız başarı ile dondurulmuştur"]));
                }
            }
            else{
                print_r(json_encode(["mesaj" => "lütfen geçerli bir veri gönderin "]));
            }
        }
    }

    public function insert()
    {
        
        $kuladi = $this->filtre($_POST["kuladi"]);
        $sifre = $_POST["sifre"];
        $kullaniciFk = $this->filtre($_POST["kullaniciFk"]);
        $user = $this->user->login($kuladi,$sifre);
        if ($user)
        {
            if ($user["berbermi"] == 0)
            {
                $berberad = $this->filtre($_POST["berberad"]);
                $berbertel = $this->filtre($_POST["berbertel"]);
                $acilis = $this->filtre($_POST["acilis"]);
                $kapanis = $this->filtre($_POST["kapanis"]);
                $periot = $this->filtre($_POST["periot"]);

                 // adres işlemleri
                $ilceFk = (int)$this->filtre($_POST["ilceFk"]);
                $adresDetay = $this->filtre($_POST["adresDetay"]);

                $adresFk =  $this->adresDb->insert($ilceFk,$adresDetay);

                /*$count = (int)$this->filtre($_POST["count"]);
                for ($i = 0; $i < $count; $i++)
                {
                    $resimler["resim".$i] = $_POST["resim".$i]; 
                }
                */
                $result = $this->db->insert($berberad,$berbertel,$acilis,$kapanis,$periot,$adresFk,$kullaniciFk);
                echo $adresFk;
                if ($result)
                {
                    print_r(json_encode(["mesaj" => "Berber başarılı bir şekilde eklendi"]));
                }
                else print_r(json_encode(["hata" => "yeni bir berber eklenmedi"]));
            }
            else{
                print_r(json_encode(["hata" => "Lütfen kendi hesabınız ile işlemler yapınız"]));
            }
        }
    }

    public function update($id =0)
    {
        $id = (int)$id;
        if ($id == 0 or $id < 0)
        {
            echo json_encode(["mesaj" => "lütfen geçerli bir id değeri giriniz"]);
        }
        else{
            if(isset($_POST) and !empty($_POST))
            {
                $islem = $this->filtre($_POST["islem"]);
                $kuladi = $this->filtre($_POST["kuladi"]);
                $sifre = $_POST["sifre"];
                $user = $this->checkauth($kuladi,$sifre);

                if ($user and $user["id"] == $id)
                {
                    $berber = $this->db->get($id);
                    if ($user["id"] == $id and $user["berbermi"] == 1) {

                        if ($islem == "genel")
                        {
                            $berberad = $this->filtre($_POST["berberad"]);
                            $berbertel = $this->filtre($_POST["berbertel"]);
                            $acilis = $this->filtre($_POST["acilis"]);
                            $kapanis = $this->filtre($_POST["kapanis"]);
                            $periot = $this->filtre($_POST["periot"]);

                            // adres işlemleri
                            $ilceFk = $this->filtre($_POST["ilceFk"]);
                            $adresdetay = $this->filtre($_POST["adresDetay"]);

                            // eski adres bilgisini aldık
                            $berberTMP = $this->db->get($id);
                            $berberAdresTMP = (int)$berberTMP["berberAdresFk"];

                            // yeni adres bilgisini veri tabanı tablosuna kayıt ettik
                            $adresId = $this->adresDb->insert($ilceFk,$adresdetay);

                            // berberin yeni bilgilerini güncelledik
                            $result = $this->db->update($berberad,$berbertel,$acilis,$kapanis,$periot,$adresId,$id);
                            if ($result)
                            {
                                $this->adresDb->delete($berberAdresTMP);
                                $result = ["mesaj" => "işlem başarı ile gerçekleşti"];
                                print_r(json_encode($result));
                            }
                            else{
                                $result = ["mesaj" => "işlem yapılamadı"];
                                print_r(json_encode($result));
                            }
                        }

                        elseif($islem == "resim")
                        {
                            $device = $this->filtre($_POST["device"]);

                            if ($device == "web") {
                                $resimler = $_FILES["resimler"];
                                $result = $this->resimlerDb->uploadweb($resimler,$berber["id"]);
                                if ($result)
                                {
                                    print_r(json_encode(["mesaj"=>"resim başarılı bir şekilde eklendi"]));
                                }
                                else{
                                    print_r(json_encode(["mesaj"=>"resim eklenmedi"]));
                                }
                            }

                            elseif($device == "mobil")
                            {
                                $count = $this->filtre($_POST["count"]);
                                for ($i = 0;$i < $count;$i++)
                                {
                                    $resimler["resim".$i] = $_POST["resim".$i];
                                }
                                $result = $this->resimlerDb->uploadMobil($resimler,$berber["id"]);
                                die();
                            }
                        }
                    }
                }
                else echo "login ol ";
            }
            else{
                echo json_encode(["mesaj" => "lütfen bir veri gönderin"]);
            }
        }
    }
}



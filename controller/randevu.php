<?php
include_once 'controller.php';
include_once 'model/randevu.php';
require_once 'model/berber.php';
require_once 'model/kullanicilar.php';

class Randevu extends MainController
{

    protected $db;
    protected $berberdb;
    public function __construct()
    {
        parent::__construct();
        $this->db = new Dbrandevu();
    }

    public function getByKulid(int $id = 0)
    {
        if ($id != 0 and $id > 0)
        {
            if (isset($_POST) and !empty($_POST))
            {
                $kuladi = $this->filtre($_POST["kuladi"]);
                $sifre = $_POST["sifre"];
                $user = $this->checkauth($kuladi,$sifre);
                if (@$user["id"] == $id)
                {
                    $result = $this->db->getByKulFk($id);
                }
                else{
                    $result["mesaj"] = "lütfen kendi hesabınız ile giriş yapınız";
                }
                echo json_encode($result);
            }
        }
    }

    public function insert(int $id = 0)
    {

        if ($id != 0 and $id > 0)
        {
            if (isset($_POST) and !empty($_POST))
            {
                $kuladi = $this->filtre($_POST["kuladi"]);
                $sifre = $_POST["sifre"];

                $user = $this->checkauth($kuladi,$sifre);
                if (@$user["id"] == $id)
                {
                    $berberFk = $this->filtre($_POST["berberFk"]);
                    $tarihSaat = $this->filtre($_POST["tarihSaat"]);

                    $result = $this->db->insert($berberFk,$tarihSaat,$id);
                    if ($result){
                        echo json_encode($result);
                    }
                    else
                        echo json_encode(["mesaj" => "randevu alınamadı"]);
                }
                else echo json_encode(["mesaj" => "kullanici adı veya şifreyi tekrar giriniz"]);
            }
        }
    }

    public function iptal(int $id = 0)
    {
        if ($id != 0 and $id > 0)
        {
            if (isset($_POST) and !empty($_POST))
            {
                $kuladi = $this->filtre($_POST["kuladi"]);
                $sifre = $_POST["sifre"];

                $user = $this->checkauth($kuladi,$sifre);
                if (@$user["id"] == $id)
                {
                    $randevukey = $_POST["randevuKey"];

                    $randevu = $this->db->getByKey($randevukey);
                    if (@$randevu["kullaniciFk"] == $id)
                    {
                        $result = $this->db->iptal($randevukey);
                        if ($result)
                        {
                            echo json_encode(["mesaj" => "Randevu başarı ile iptal edildi"]);
                        }
                        else echo json_encode(["mesaj" => "Randevu iptal edilemedi"]);
                    }
                    else echo json_encode(["mesaj" => "lütfen geçerli bir id değeri giriniz"]);

                }
                else echo json_encode(["mesaj" => "kullanici adı veya şifreyi tekrar giriniz"]);
            }
        }
    }

    // todo: randevu sistemini berberlerin görebileceği bir şekilde listelet
    // todo : her kullanıcı kendi aldığı randevuları görebilecek
}